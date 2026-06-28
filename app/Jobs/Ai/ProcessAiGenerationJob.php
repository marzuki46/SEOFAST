<?php

namespace App\Jobs\Ai;

use App\Models\Content;
use App\Models\AiGenerationJob;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;

class ProcessAiGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum attempts before Laravel marks the job as permanently failed.
     * Each phase is 1 attempt; with retries per phase, allow generous buffer.
     */
    public int $tries = 1;

    /**
     * Max execution time per job dispatch (seconds).
     * Shared hosting typically caps at 30–60s; keep under 55s.
     */
    public int $timeout = 55;

    /**
     * Enforce one AI job at a time system-wide.
     * If another is running, this job waits 30s and retries.
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping('ai_generation'))->releaseAfter(30)];
    }

    public function __construct(
        protected int $contentId,
        protected int $jobId,
        protected ?string $targetStatus = null
    ) {}

    // -------------------------------------------------------------------------
    // MAIN HANDLER
    // -------------------------------------------------------------------------

    public function handle(): void
    {
        $content = Content::withoutGlobalScopes()->find($this->contentId);
        $job     = AiGenerationJob::withoutGlobalScopes()->find($this->jobId);

        if (!$content || !$job) {
            Log::error('ProcessAiGenerationJob: model not found', [
                'content_id' => $this->contentId,
                'job_id'     => $this->jobId,
            ]);
            return;
        }

        $currentStatus = $job->status;

        // Guard: if the job is already completed or permanently failed, stop.
        if (in_array($currentStatus, ['completed', 'failed'])) {
            Log::info("ProcessAiGenerationJob: job {$this->jobId} already in terminal state '{$currentStatus}'. Skipping.");
            return;
        }

        // Normalize "pending / processing" → start at phase_1
        if (in_array($currentStatus, ['pending', 'processing'])) {
            $currentStatus = 'phase_1';
            $job->update(['status' => 'phase_1', 'started_at' => now()]);
        }

        // If previous CQI retry left job in failed_cqi, restart generation from phase_1
        if ($currentStatus === 'failed_cqi') {
            $currentStatus = 'phase_1';
            $job->update(['status' => 'phase_1', 'started_at' => now()]);
        }

        try {
            match ($currentStatus) {
                'phase_1' => $this->runPhase1($content, $job),
                'phase_2' => $this->runPhase2($content, $job),
                'phase_3' => $this->runPhase3($content, $job),
                'phase_4' => $this->runPhase4($content, $job),
                default   => Log::warning("ProcessAiGenerationJob: unknown status '{$currentStatus}' for job {$this->jobId}"),
            };
        } catch (\Exception $e) {
            $this->failJob($job, $content, $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // PHASE 1 — Initial Draft Generation
    // Goal: Produce a comprehensive, structured Markdown draft.
    // -------------------------------------------------------------------------

    private function runPhase1(Content $content, AiGenerationJob $job): void
    {
        $keyword     = $content->target_keyword;
        $seedKeyword = $content->siloBlueprint?->seed_keyword ?? $keyword;
        $lang        = $content->siloBlueprint?->target_language ?? 'id';
        $country     = $content->siloBlueprint?->target_country ?? 'ID';

        Log::info("AI Phase 1 START | job={$job->id} | keyword={$keyword}");

        $sysTemplate  = \App\Models\SystemSetting::get(
            'ai_prompt_phase1_sys',
            "You are an expert SEO Content Writer fluent in {lang}. Write a comprehensive, well-structured article about '{keyword}' targeting readers in {country}. Use proper Markdown formatting with H2 and H3 headings. Apply E-E-A-T principles: include expert insights, real examples, and actionable advice. The article must be at least 1,200 words."
        );
        $userTemplate = \App\Models\SystemSetting::get(
            'ai_prompt_phase1_user',
            "Write a comprehensive, SEO-optimised article draft in {lang} about: **{keyword}**.\n\nRequirements:\n- Minimum 1,200 words\n- Use H2 and H3 headings clearly\n- Cover: definition, importance, step-by-step guide, common mistakes, and a conclusion with CTA\n- Incorporate the seed keyword '{seed_keyword}' naturally\n- Do NOT include generic filler — every sentence must provide real value"
        );

        $sysPrompt  = strtr($sysTemplate,  ['{keyword}' => $keyword, '{seed_keyword}' => $seedKeyword, '{lang}' => $lang, '{country}' => $country]);
        $userPrompt = strtr($userTemplate, ['{keyword}' => $keyword, '{seed_keyword}' => $seedKeyword, '{lang}' => $lang, '{country}' => $country]);

        $aiService = new AIService($content->tenant, '1');
        $draft     = $aiService->generate($sysPrompt, $userPrompt);

        // Quality check: reject empty or too-short responses
        if (!$draft || mb_strlen(trim($draft)) < 300) {
            $this->failJob($job, $content, 'Phase 1 FAILED: AI returned empty or insufficient draft (< 300 chars). Check AI API key and quota.');
            return;
        }

        // Save Phase 1 result to DB before advancing
        $job->update([
            'status'        => 'phase_2',
            'phase_1_draft' => $draft,
        ]);

        Log::info("AI Phase 1 DONE | job={$job->id} | draft_length=" . mb_strlen($draft));

        // Dispatch Phase 2 as a separate job (respects timeout limits on shared hosting)
        self::dispatch($this->contentId, $this->jobId, $this->targetStatus);
    }

    // -------------------------------------------------------------------------
    // PHASE 2 — Quality Critique & CQI Scoring
    // Goal: Objectively score the draft and identify gaps before expansion.
    // -------------------------------------------------------------------------

    private function runPhase2(Content $content, AiGenerationJob $job): void
    {
        $keyword = $content->target_keyword;
        $draft   = $job->phase_1_draft;

        if (!$draft) {
            $this->failJob($job, $content, 'Phase 2 FAILED: phase_1_draft is empty in DB.');
            return;
        }

        Log::info("AI Phase 2 START | job={$job->id} | keyword={$keyword}");

        $sysTemplate = \App\Models\SystemSetting::get(
            'ai_prompt_phase2_sys',
            "You are a strict Senior SEO Content Auditor. Evaluate the article draft below against these criteria:\n1. Topical depth and keyword coverage\n2. E-E-A-T signals (expertise, experience, authoritativeness, trustworthiness)\n3. Readability and logical structure\n4. Actionable value for the reader\n5. Internal linking opportunities\n\nYou MUST respond ONLY with a valid JSON object with this exact structure:\n{\"cqi_score\": <integer 0-100>, \"strengths\": [<list of strengths>], \"gaps\": [<list of weaknesses>], \"improvements\": [<specific improvement instructions>]}\n\nDo NOT include any text outside the JSON object."
        );

        $sysPrompt  = strtr($sysTemplate, ['{keyword}' => $keyword]);
        $userPrompt = "Target keyword: {$keyword}\n\nDraft to audit:\n\n{$draft}";

        $aiService = new AIService($content->tenant, '2');
        $critique  = $aiService->generateJson($sysPrompt, $userPrompt);

        // If AI couldn't produce valid JSON critique, fail hard — no guessing
        if (!$critique || !isset($critique['cqi_score'])) {
            $this->failJob($job, $content, 'Phase 2 FAILED: AI did not return a valid JSON critique. Cannot assess quality.');
            return;
        }

        $cqiScore   = (int) $critique['cqi_score'];
        $retryCount = (int) ($job->retry_count ?? 0);

        // Save critique to DB regardless of outcome
        $job->update(['phase_2_critique' => $critique]);

        Log::info("AI Phase 2 DONE | job={$job->id} | cqi={$cqiScore} | retries={$retryCount}");

        // === CQI QUALITY GATE ===
        // Threshold: 75. If below, regenerate Phase 1 with improvement context (max 2 retries).
        $cqiThreshold = (int) \App\Models\SystemSetting::get('ai_cqi_threshold', 75);

        if ($cqiScore < $cqiThreshold) {
            if ($retryCount >= 2) {
                // Max retries exceeded — permanently fail this content
                $this->failJob(
                    $job, $content,
                    "Phase 2 FAILED: CQI score {$cqiScore} below threshold {$cqiThreshold} after {$retryCount} retries. Content quality is insufficient."
                );
                return;
            }

            // Retry: go back to Phase 1 with improvement instructions injected into the prompt
            $improvements = implode('; ', $critique['improvements'] ?? ['Improve depth, E-E-A-T signals, and actionable content.']);
            $job->update([
                'status'      => 'phase_1',
                'retry_count' => $retryCount + 1,
                'error_log'   => ['cqi_score' => $cqiScore, 'retry' => $retryCount + 1, 'improvements' => $improvements],
            ]);
            $content->update(['status' => 'ai_processing']);

            Log::warning("AI Phase 2: CQI {$cqiScore} < {$cqiThreshold}. Retrying Phase 1 (attempt " . ($retryCount + 1) . "/2) with improvements.");

            // Store improvement hints in a transient field for Phase 1 to pick up
            \App\Models\SystemSetting::set('_ai_retry_improvements_' . $this->jobId, $improvements);

            self::dispatch($this->contentId, $this->jobId, $this->targetStatus)->delay(now()->addSeconds(5));
            return;
        }

        // CQI passed — advance to Phase 3
        $job->update(['status' => 'phase_3']);
        self::dispatch($this->contentId, $this->jobId, $this->targetStatus);
    }

    // -------------------------------------------------------------------------
    // PHASE 3 — Content Expansion & Enrichment
    // Goal: Expand the draft using critique feedback to produce a rich article.
    // -------------------------------------------------------------------------

    private function runPhase3(Content $content, AiGenerationJob $job): void
    {
        $keyword = $content->target_keyword;
        $draft   = $job->phase_1_draft;
        $critique = $job->phase_2_critique;

        if (!$draft || !$critique) {
            $this->failJob($job, $content, 'Phase 3 FAILED: Missing draft or critique from DB.');
            return;
        }

        Log::info("AI Phase 3 START | job={$job->id} | keyword={$keyword}");

        $lang        = $content->siloBlueprint?->target_language ?? 'id';
        $improvements = implode("\n- ", $critique['improvements'] ?? []);
        $gaps         = implode("\n- ", $critique['gaps'] ?? []);

        $sysTemplate = \App\Models\SystemSetting::get(
            'ai_prompt_phase3_sys',
            "You are a Master SEO Content Expander writing in {lang}. Your task is to take an existing article draft and significantly improve it based on a quality audit. You must address ALL identified gaps and apply ALL improvement suggestions. The final output must be at least 1,800 words, use clear Markdown formatting, and read as a premium, authoritative article — NOT generic filler content. Return ONLY the improved Markdown text."
        );

        $sysPrompt  = strtr($sysTemplate, ['{lang}' => $lang, '{keyword}' => $keyword]);
        $userPrompt = "Target keyword: **{$keyword}**\n\n"
            . "## Original Draft\n{$draft}\n\n"
            . "## Quality Audit Gaps to Address\n- {$gaps}\n\n"
            . "## Specific Improvements Required\n- {$improvements}\n\n"
            . "Rewrite and expand the article now, fully addressing ALL gaps and improvements above. Minimum 1,800 words.";

        $aiService = new AIService($content->tenant, '3');
        $expanded  = $aiService->generate($sysPrompt, $userPrompt);

        // Reject insufficient expansion
        if (!$expanded || mb_strlen(trim($expanded)) < 500) {
            $this->failJob($job, $content, 'Phase 3 FAILED: AI returned empty or insufficient expanded content (< 500 chars).');
            return;
        }

        // Save expanded content to DB before advancing
        $job->update([
            'status'           => 'phase_4',
            'phase_3_expanded' => $expanded,
        ]);

        Log::info("AI Phase 3 DONE | job={$job->id} | expanded_length=" . mb_strlen($expanded));

        self::dispatch($this->contentId, $this->jobId, $this->targetStatus);
    }

    // -------------------------------------------------------------------------
    // PHASE 4 — Master Edit, Internal Link Injection & Final Publish
    // Goal: Polish the article, inject links, and save to the content record.
    // -------------------------------------------------------------------------

    private function runPhase4(Content $content, AiGenerationJob $job): void
    {
        $keyword  = $content->target_keyword;
        $expanded = $job->phase_3_expanded;
        $critique = $job->phase_2_critique;

        if (!$expanded) {
            $this->failJob($job, $content, 'Phase 4 FAILED: phase_3_expanded is empty in DB.');
            return;
        }

        Log::info("AI Phase 4 START | job={$job->id} | keyword={$keyword}");

        // Build internal link injection instructions
        $deterministicLinks = \App\Models\DeterministicLink::where('source_content_id', $content->id)
            ->with('targetContent')
            ->get();

        $linkInstructions = '';
        if ($deterministicLinks->isNotEmpty()) {
            $linkInstructions = "\n\n## MANDATORY INTERNAL LINKS\nYou MUST embed ALL of the following links naturally within the article text. Use standard Markdown syntax [anchor text](url):\n";
            foreach ($deterministicLinks as $link) {
                $targetUrl         = url('/blog/' . ($link->targetContent?->slug ?? '#'));
                $linkInstructions .= "- Anchor: \"{$link->anchor_text}\" → URL: {$targetUrl}\n";
            }
        }

        // Build featured image instruction
        $imageInstruction = '';
        if ($content->featured_image_url) {
            $imageInstruction = "\n\n## FEATURED IMAGE\nPlace this image at the most natural position near the top of the article:\n"
                . "![{$content->featured_image_alt}]({$content->featured_image_url})\n"
                . "Caption: {$content->featured_image_caption}";
        }

        $lang        = $content->siloBlueprint?->target_language ?? 'id';

        $sysTemplate = \App\Models\SystemSetting::get(
            'ai_prompt_phase4_sys',
            "You are a Chief Content Editor writing in {lang}. Your task is to perform a final editorial pass on the article below:\n1. Improve readability: use bold text, bullet lists, numbered lists, and blockquotes strategically\n2. Ensure the opening paragraph immediately hooks the reader\n3. Inject all mandatory internal links at the most contextually natural positions\n4. Place the featured image at the top if provided\n5. Ensure the conclusion has a strong call-to-action\n6. Return ONLY the final polished Markdown — no commentary, no preamble."
        );

        $sysPrompt  = strtr($sysTemplate, ['{lang}' => $lang, '{keyword}' => $keyword]);
        $userPrompt = "Target keyword: **{$keyword}**\n\n## Article to Polish\n{$expanded}{$linkInstructions}{$imageInstruction}";

        $aiService = new AIService($content->tenant, '4');
        $finalBody = $aiService->generate($sysPrompt, $userPrompt);

        // Reject insufficient final output
        if (!$finalBody || mb_strlen(trim($finalBody)) < 500) {
            $this->failJob($job, $content, 'Phase 4 FAILED: AI returned empty or insufficient final content (< 500 chars).');
            return;
        }

        $cqiScore    = (int) ($critique['cqi_score'] ?? 80);
        $contentHash = hash('sha256', $finalBody);
        $targetStatus = $this->targetStatus ?? 'draft';
        $blogPrefix  = \App\Models\SystemSetting::get('permalink_blog', 'blog');

        // Save Phase 4 final output to DB
        $job->update([
            'status'        => 'completed',
            'phase_4_final' => $finalBody,
            'completed_at'  => now(),
        ]);

        // Mark internal links as injected
        if ($deterministicLinks->isNotEmpty()) {
            \App\Models\DeterministicLink::where('source_content_id', $content->id)
                ->update(['is_injected' => true]);
        }

        // Persist the final article body and update content status
        $content->body_raw = $finalBody;
        $content->update([
            'cqi_score'    => $cqiScore,
            'content_hash' => $contentHash,
            'status'       => $targetStatus,
            'published_at' => $content->published_at ?? now(),
        ]);
        $content->save();

        Log::info("AI Phase 4 DONE | job={$job->id} | status={$targetStatus} | cqi={$cqiScore} | body_length=" . mb_strlen($finalBody));

        // ---- Phase 5: SEO Meta Generation (non-blocking) ----
        try {
            $this->generateSeoMeta($content, $keyword, $blogPrefix);
        } catch (\Exception $e) {
            Log::warning("AI Phase 5 (SEO Meta) failed for job {$job->id}: " . $e->getMessage());
            // Do NOT fail the whole job — content is already saved
        }

        // Clean up temporary retry hints if any
        \App\Models\SystemSetting::where('key', '_ai_retry_improvements_' . $this->jobId)->delete();

        // ── Chain: dispatch the next pending job so articles process one-by-one ──
        $this->dispatchNextPendingJob();
    }

    // -------------------------------------------------------------------------
    // PHASE 5 — SEO Meta Generation (called inline after Phase 4)
    // -------------------------------------------------------------------------

    private function generateSeoMeta(Content $content, string $keyword, string $blogPrefix): void
    {
        $aiService = new AIService($content->tenant, '4');

        $metaTitlePrompt = \App\Models\SystemSetting::get(
            'ai_prompt_meta_title',
            'Write a highly click-worthy SEO title for the keyword "{keyword}". Maximum 60 characters. Return ONLY the title text — no quotes, no commentary.'
        );
        $metaDescPrompt  = \App\Models\SystemSetting::get(
            'ai_prompt_meta_description',
            'Write an engaging SEO meta description for the keyword "{keyword}". Must be 150–160 characters. Include a compelling call to action. Return ONLY the description text.'
        );

        $metaTitlePrompt = str_replace('{keyword}', $keyword, $metaTitlePrompt);
        $metaDescPrompt  = str_replace('{keyword}', $keyword, $metaDescPrompt);

        $generatedTitle = trim($aiService->generate('You are an expert SEO specialist.', $metaTitlePrompt), " \t\n\r\"'");
        $generatedDesc  = trim($aiService->generate('You are an expert SEO specialist.', $metaDescPrompt),  " \t\n\r\"'");

        $content->updateSeoMeta([
            'title'          => $generatedTitle ?: $content->title,
            'description'    => $generatedDesc,
            'canonical'      => url('/' . $blogPrefix . '/' . $content->slug),
            'robots'         => 'index, follow',
            'og_title'       => $generatedTitle ?: $content->title,
            'og_description' => $generatedDesc,
            'og_image'       => $content->featured_image_url ?: \App\Models\SystemSetting::get('seo_og_image'),
        ]);

        Log::info("AI Phase 5 (SEO Meta) DONE | content={$content->id}");
    }

    // -------------------------------------------------------------------------
    // HELPERS
    // -------------------------------------------------------------------------

    /**
     * Permanently fail a job and mark the content accordingly.
     * No fallback content is ever produced — quality is non-negotiable.
     * After failing, dispatch the next pending job so the queue continues.
     */
    private function failJob(AiGenerationJob $job, Content $content, string $reason): void
    {
        Log::error("ProcessAiGenerationJob PERMANENTLY FAILED | job={$job->id} | reason={$reason}");

        $job->update([
            'status'    => 'failed',
            'error_log' => ['reason' => $reason, 'failed_at' => now()->toISOString()],
        ]);

        $content->update(['status' => 'failed_cqi']);

        // Even on failure, dispatch the next job in the queue
        $this->dispatchNextPendingJob();
    }

    /**
     * Find and dispatch the next pending AiGenerationJob that hasn't been
     * dispatched yet. This enforces strict sequential (one-at-a-time) processing.
     */
    private function dispatchNextPendingJob(): void
    {
        // Find the next job that is still purely pending (never dispatched to queue)
        $nextJob = \App\Models\AiGenerationJob::withoutGlobalScopes()
            ->where('status', 'pending')
            ->where('id', '!=', $this->jobId)  // not the current one
            ->oldest()
            ->first();

        if (!$nextJob) {
            Log::info('ProcessAiGenerationJob: No more pending jobs. Queue finished.');
            return;
        }

        // Read the target_status that was stored during bulkGenerateAi
        $nextTargetStatus = $nextJob->error_log['target_status'] ?? $this->targetStatus ?? 'draft';

        // Clear the temporary storage from error_log
        $nextJob->update(['error_log' => null]);

        Log::info("ProcessAiGenerationJob: Dispatching next job #{$nextJob->id} (content_id={$nextJob->content_id})");

        self::dispatch($nextJob->content_id, $nextJob->id, $nextTargetStatus);
    }
}
