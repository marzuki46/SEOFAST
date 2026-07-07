<?php

namespace App\Jobs\Ai;

use App\Models\Content;
use App\Models\AiGenerationJob;
use App\Models\DeterministicLink;
use App\Services\AIService;
use App\Services\AiRecoveryManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAiGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public int $timeout = 300;

    public function __construct(
        protected int $contentId,
        protected int $jobId,
        protected ?string $targetStatus = null
    ) {
        $this->onQueue('ai');
    }

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

        if (in_array($currentStatus, ['completed', 'failed'])) {
            Log::info("ProcessAiGenerationJob: job {$this->jobId} already in terminal state '{$currentStatus}'. Skipping.");
            return;
        }

        if (in_array($currentStatus, ['pending', 'processing'])) {
            $currentStatus = 'phase_1';
            $job->update(['status' => 'phase_1', 'started_at' => now()]);
        }

        if ($currentStatus === 'failed_cqi') {
            $currentStatus = 'phase_1';
            $job->update(['status' => 'phase_1', 'started_at' => now()]);
        }

        $recovery = new AiRecoveryManager($content->tenant);
        if ($recovery->isCircuitOpen()) {
            $job->update(['status' => 'pending']);
            self::dispatch($this->contentId, $this->jobId, $this->targetStatus)
                ->delay(now()->addSeconds(30));
            return;
        }

        try {
            match ($currentStatus) {
                'phase_1' => $this->runPhase1($content, $job),
                'phase_2' => $this->runPhase2($content, $job),
                'phase_3' => $this->runPhase3($content, $job),
                'phase_4' => $this->runPhase4($content, $job),
                'phase_5' => $this->runPhase5($content, $job),
                'phase_6' => $this->runPhase6($content, $job),
                default   => Log::warning("ProcessAiGenerationJob: unknown status '{$currentStatus}' for job {$this->jobId}"),
            };
        } catch (\Exception $e) {
            $this->handleError($job, $content, $e, $currentStatus);
        }
    }

    // -------------------------------------------------------------------------
    // PHASE 1 — LSI / Entity Keywords
    // -------------------------------------------------------------------------

    private function runPhase1(Content $content, AiGenerationJob $job): void
    {
        $keyword     = $content->target_keyword;
        $seedKeyword = $content->siloBlueprint?->seed_keyword ?? $keyword;
        $lang        = $content->siloBlueprint?->target_language ?? 'id';
        $country     = $content->siloBlueprint?->target_country ?? 'ID';

        Log::info("AI Phase 1 (LSI + Entities) START | job={$job->id} | keyword={$keyword}");

        $aiService = new AIService($content->tenant, 'default');

        // METHOD 1: Generate structured JSON with separate LSI and Entities
        $sysTemplate = \App\Models\SystemSetting::get(
            'ai_prompt_phase1_sys',
            'You are an Expert SEO Strategist. Generate LSI keywords and semantic entities relevant to "{keyword}". Return ONLY a valid JSON object with exactly this structure: {"lsi": ["keyword1", "keyword2", "keyword3"], "entities": ["entity1", "entity2", "entity3"]}. NO MARKDOWN, NO EXTRA TEXT.'
        );
        $sysPrompt  = strtr($sysTemplate, ['{keyword}' => $keyword, '{lang}' => $lang, '{country}' => $country]);
        $userPrompt = "Topic: {$keyword}\nLanguage: {$lang}\nCountry: {$country}";

        $result = $aiService->generateJson($sysPrompt, $userPrompt);

        if (is_array($result) && !empty($result['lsi']) && !empty($result['entities'])) {
            $lsiList = is_array($result['lsi']) ? $result['lsi'] : [$result['lsi']];
            $entityList = is_array($result['entities']) ? $result['entities'] : [$result['entities']];
        } else {
            // METHOD 2 (fallback): Generate plain comma-separated string, parse manually
            Log::warning("Phase 1 JSON failed, trying comma-separated fallback.");
            $fallbackPrompt = "Generate LSI keywords and semantic entities for '{$keyword}'. Return as: LSI: kw1, kw2, kw3 | ENTITIES: ent1, ent2, ent3";
            $fallback = $aiService->generate($sysPrompt, $userPrompt);

            if (!$fallback || mb_strlen(trim($fallback)) < 10) {
                throw new \Exception('Phase 1 (LSI/Entities) returned empty or insufficient result.');
            }

            // Try to parse the fallback format
            $lsiList = [];
            $entityList = [];
            if (preg_match('/LSI:\s*(.+?)(?:\||$)/i', $fallback, $m)) {
                $lsiList = array_map('trim', explode(',', $m[1]));
            }
            if (preg_match('/ENTITIES?:\s*(.+?)$/i', $fallback, $m)) {
                $entityList = array_map('trim', explode(',', $m[1]));
            }
            if (empty($lsiList)) {
                // METHOD 3 (last resort): Treat entire output as LSI
                $lsiList = array_map('trim', explode(',', $fallback));
            }
        }

        $stored = json_encode([
            'lsi'      => $lsiList,
            'entities' => $entityList,
        ], JSON_UNESCAPED_UNICODE);

        $job->update([
            'status'      => 'phase_2',
            'phase_1_lsi' => $stored,
        ]);

        Log::info("AI Phase 1 (LSI + Entities) DONE | job={$job->id} | lsi=" . count($lsiList) . " | entities=" . count($entityList));

        self::dispatch($this->contentId, $this->jobId, $this->targetStatus);
    }

    // -------------------------------------------------------------------------
    // PHASE 2 — Initial Draft + Internal Links
    // -------------------------------------------------------------------------

    private function runPhase2(Content $content, AiGenerationJob $job): void
    {
        $keyword     = $content->target_keyword;
        $seedKeyword = $content->siloBlueprint?->seed_keyword ?? $keyword;
        $lang        = $content->siloBlueprint?->target_language ?? 'id';
        $country     = $content->siloBlueprint?->target_country ?? 'ID';
        $rawLsi     = $job->phase_1_lsi;

        if (!$rawLsi) {
            throw new \Exception('Phase 2 (Draft) FAILED: phase_1_lsi is empty in DB.');
        }

        // Parse structured JSON or fallback to plain text (backward compatibility)
        $lsiList = [];
        $entityList = [];
        $parsed = json_decode($rawLsi, true);
        if (is_array($parsed) && isset($parsed['lsi'])) {
            $lsiList = is_array($parsed['lsi']) ? $parsed['lsi'] : [$parsed['lsi']];
            $entityList = isset($parsed['entities']) && is_array($parsed['entities']) ? $parsed['entities'] : [];
        } else {
            // Old format: plain comma-separated string
            $lsiList = array_map('trim', explode(',', $rawLsi));
        }

        $lsiText      = implode(', ', $lsiList);
        $entityText   = !empty($entityList) ? implode(', ', $entityList) : '';

        Log::info("AI Phase 2 (Draft) START | job={$job->id} | keyword={$keyword}");

        $strategy = \App\Models\SystemSetting::get('internal_link_strategy', 'deterministic');
        $linkInstructions = '';

        if (in_array($strategy, ['deterministic', 'both'])) {
            $deterministicLinks = DeterministicLink::where('source_content_id', $content->id)
                ->with('targetContent')->get();

            if ($deterministicLinks->isNotEmpty()) {
                $linkInstructions = "\n\nMANDATORY INTERNAL LINKS (Embed organically in text using Markdown):\n";
                foreach ($deterministicLinks as $link) {
                    $url = url('/blog/' . ($link->targetContent?->slug ?? '#'));
                    $linkInstructions .= "- [{$link->mandatory_anchor_text}]({$url})\n";
                }
            }
        }

        if ($content->hierarchy_level === 'pillar') {
            $clusters = Content::withoutGlobalScopes()
                ->where('parent_id', $content->id)
                ->where('hierarchy_level', 'cluster')
                ->whereNotIn('status', ['idea'])
                ->get();
            if ($clusters->isNotEmpty()) {
                $linkInstructions .= "\n\nCRITICAL PILLAR REQUIREMENT: As a Pillar Page, you MUST extend the content by explicitly creating dedicated sections (H2/H3) for the following cluster topics. You MUST naturally insert their corresponding links within their respective sections:\n";
                foreach ($clusters as $cluster) {
                    $dLink = DeterministicLink::where('source_content_id', $content->id)
                        ->where('target_content_id', $cluster->id)
                        ->first();
                    $anchor = $dLink ? ($dLink->mandatory_anchor_text ?? $cluster->target_keyword) : $cluster->target_keyword;

                    $slugStr = is_string($cluster->slug) ? $cluster->slug : ($cluster->slug['id'] ?? '#');
                    $url = url('/blog/' . ltrim($slugStr, '/'));

                    $linkInstructions .= "- Cluster Topic: {$cluster->target_keyword} => Link Markdown: [{$anchor}]({$url})\n";
                }
            }
        }

        // ── Content Framework & E-E-A-T ──
        $framework = $content->content_framework ?? $content->siloBlueprint?->content_framework ?? 'default';
        $frameworkMap = [
            'aida' => [
                'label' => 'AIDA Framework',
                'outline' => "Struktur konten WAJIB mengikuti kerangka AIDA:\n"
                    . "1. **Attention (A)** — Buka dengan hook kuat: fakta mengejutkan, pertanyaan provokatif, atau statistik impactful yang relevan dengan keyword.\n"
                    . "2. **Interest (I)** — Bangkitkan minat: jelaskan mengapa topik ini penting, apa masalahnya, dan siapa yang terdampak. Gunakan storytelling atau data.\n"
                    . "3. **Desire (D)** — Tanamkan keinginan: paparkan solusi, manfaat, bukti sosial, studi kasus, atau perbandingan. Tunjukkan mengapa ini adalah pendekatan terbaik.\n"
                    . "4. **Action (A)** — Tutup dengan ajakan: langkah konkret yang harus dilakukan pembaca selanjutnya (CTA).",
            ],
            'pas' => [
                'label' => 'PAS Framework (Problem — Agitate — Solution)',
                'outline' => "Struktur konten WAJIB mengikuti kerangka PAS:\n"
                    . "1. **Problem (P)** — Identifikasi masalah spesifik yang dialami target audiens terkait keyword. Jelaskan dengan jelas dan relatable.\n"
                    . "2. **Agitate (A)** — Perkuat masalahnya: jelaskan konsekuensi, rasa sakit, dan urgensi jika masalah tidak segera diselesaikan.\n"
                    . "3. **Solution (S)** — Berikan solusi lengkap: langkah-langkah praktis, strategi, tools, dan panduan yang menyelesaikan masalah.",
            ],
            'how_to' => [
                'label' => 'How-To Guide Framework',
                'outline' => "Struktur konten WAJIB mengikuti kerangka How-To Guide:\n"
                    . "1. **Pendahuluan** — Jelaskan apa yang akan dicapai pembaca dan mengapa panduan ini penting.\n"
                    . "2. **Prasyarat / Persiapan** — Apa saja yang dibutuhkan sebelum memulai (tools, pengetahuan, akun, dll).\n"
                    . "3. **Langkah-langkah Detail** — Setiap langkah sebagai H2 terpisah, jelaskan dengan jelas. Sertakan tips, screenshot (deskripsikan), dan troubleshooting.\n"
                    . "4. **Kesimpulan** — Ringkas hasil akhir dan berikan langkah selanjutnya.",
            ],
            'listicle' => [
                'label' => 'Listicle Framework',
                'outline' => "Struktur konten WAJIB mengikuti kerangka Listicle:\n"
                    . "1. **Pendahuluan** — Konteks mengapa daftar ini relevan dan bermanfaat.\n"
                    . "2. **Daftar Item** — Setiap item sebagai H2. Format: [Nomor]. [Judul Item]. Jelaskan secara detail, berikan data/contoh untuk setiap item.\n"
                    . "3. **Kesimpulan** — Rekap dan rekomendasi item terbaik.",
            ],
        ];

        $frameworkInstructions = '';
        if (isset($frameworkMap[$framework])) {
            $fw = $frameworkMap[$framework];
            $frameworkInstructions = "\n\n### CONTENT FRAMEWORK: {$fw['label']}\n{$fw['outline']}";
        }

        $eeatInstructions = "\n\n### E-E-A-T REQUIREMENTS (Wajib dipenuhi):\n"
            . "- **Experience (Pengalaman)** — Tunjukkan pengalaman praktis. Gunakan contoh nyata, studi kasus, anekdot personal yang relevan.\n"
            . "- **Expertise (Keahlian)** — Tunjukkan otoritas. Gunakan terminologi yang tepat, referensi sumber kredibel, data/statistik terbaru.\n"
            . "- **Authoritativeness (Otoritas)** — Bangun kredibilitas. Sertakan kutipan ahli, link ke sumber resmi, sertifikasi, atau penghargaan.\n"
            . "- **Trustworthiness (Kepercayaan)** — Jaga akurasi. Cantumkan tanggal publikasi, author bio singkat, sumber data, dan hindari klaim berlebihan.";

        $requirements = "Requirements:\n- Minimum 1000 words\n- Use H2 and H3 headings\n- Bold LSI keywords in the text\n- Naturally integrate Entity keywords\n- Follow the framework structure exactly\n- Meet all E-E-A-T signals";

        $sysTemplate = \App\Models\SystemSetting::get(
            'ai_prompt_phase2_sys',
            "You are an Expert SEO Writer writing in {lang}. Write a comprehensive article draft (minimum 1000 words) using the provided LSI keywords. **Make the LSI keywords bold**. You must naturally inject the provided MANDATORY INTERNAL LINKS using Markdown. Also naturally integrate the Entity keywords throughout the content. Return ONLY the article draft."
        );
        $sysPrompt  = strtr($sysTemplate, ['{keyword}' => $keyword, '{lang}' => $lang, '{country}' => $country]);
        $userPrompt = "Keyword: **{$keyword}**\nSeed: {$seedKeyword}\nLSI Keywords: {$lsiText}\n";
        if ($entityText) {
            $userPrompt .= "Entity Keywords: {$entityText}\n";
        }
        $userPrompt .= "\n{$requirements}\n{$linkInstructions}{$frameworkInstructions}{$eeatInstructions}";

        // Inject retry improvements from failed CQI audit
        $improvementsHint = \App\Models\SystemSetting::get('_ai_retry_improvements_' . $this->jobId, '');
        if ($improvementsHint) {
            $userPrompt .= "\n\nPREVIOUS AUDIT FEEDBACK (address these issues in this revision):\n{$improvementsHint}";
        }

        $aiService = new AIService($content->tenant, 'default');
        $draft     = $aiService->generate($sysPrompt, $userPrompt);

        if (!$draft || mb_strlen(trim($draft)) < 300) {
            throw new \Exception('Phase 2 (Draft) returned empty or insufficient content (< 300 chars).');
        }

        $job->update([
            'status'       => 'phase_3',
            'phase_1_draft' => $draft,
        ]);

        Log::info("AI Phase 2 (Draft) DONE | job={$job->id} | draft_length=" . mb_strlen($draft));

        self::dispatch($this->contentId, $this->jobId, $this->targetStatus);
    }

    // -------------------------------------------------------------------------
    // PHASE 3 — Critical Questions (from draft auditor)
    // No CQI here — article is not yet finished.
    // -------------------------------------------------------------------------

    private function runPhase3(Content $content, AiGenerationJob $job): void
    {
        $keyword = $content->target_keyword;
        $draft   = $job->phase_1_draft;
        $lang    = $content->siloBlueprint?->target_language ?? 'id';
        $framework = $content->content_framework ?? $content->siloBlueprint?->content_framework ?? 'default';

        if (!$draft) {
            throw new \Exception('Phase 3 (Questions) FAILED: phase_1_draft is empty in DB.');
        }

        Log::info("AI Phase 3 (Questions) START | job={$job->id} | keyword={$keyword}");

        $langHint = $lang === 'id'
            ? 'Tulis pertanyaan dalam Bahasa Indonesia.'
            : "Write questions in {$lang}.";

        $frameworkHint = $framework !== 'default'
            ? " Also audit whether the draft follows the {$framework} content framework structure correctly."
            : '';

        $sysTemplate = \App\Models\SystemSetting::get(
            'ai_prompt_phase3_sys',
            "You are a strict Senior SEO Content Auditor. Read the draft below and generate a list of at least 10 'Critical Questions' that a human expert would ask, which this draft currently fails to answer adequately. Be thorough and cover depth, relevance, E-E-A-T signals (Experience, Expertise, Authoritativeness, Trustworthiness), and practical implementation. Respond ONLY with a valid JSON array of strings:\n[\"Question 1?\", \"Question 2?\"]"
        );
        $sysPrompt  = strtr($sysTemplate, ['{keyword}' => $keyword]);
        $userPrompt = "Target keyword: {$keyword}\n{$langHint}{$frameworkHint}\n\nDraft to audit:\n\n{$draft}";

        $aiService = new AIService($content->tenant, 'default');
        $critique  = $aiService->generateJson($sysPrompt, $userPrompt);

        if (!$critique || !is_array($critique) || count($critique) < 10) {
            $criticalQuestions = [
                "Apa strategi {$keyword} yang paling efektif?",
                "Bagaimana cara mengukur keberhasilan {$keyword}?",
                "Apa kesalahan umum dalam {$keyword} dan cara menghindarinya?",
                "Studi kasus sukses {$keyword} terbaru?",
                "Bagaimana {$keyword} berkembang dalam 5 tahun terakhir?",
                "Tools apa yang wajib digunakan untuk {$keyword}?",
                "Apa perbedaan {$keyword} dengan metode tradisional?",
                "Bagaimana cara memulai {$keyword} untuk pemula?",
                "Berapa biaya yang dibutuhkan untuk {$keyword}?",
                "Apa tren terbaru dalam {$keyword}?",
            ];
            $critique = is_array($critique) ? array_merge($critique, $criticalQuestions) : $criticalQuestions;
            $critique = array_slice($critique, 0, 10);
            Log::warning("Phase 3: Kurang dari 10 pertanyaan, padding defaults.");
        }

        $job->update(['phase_2_critique' => $critique]);

        Log::info("AI Phase 3 (Questions) DONE | job={$job->id} | questions=" . count($critique));

        $job->update(['status' => 'phase_4']);
        self::dispatch($this->contentId, $this->jobId, $this->targetStatus);
    }

    // -------------------------------------------------------------------------
    // PHASE 4 — Answer Critical Questions
    // -------------------------------------------------------------------------

    private function runPhase4(Content $content, AiGenerationJob $job): void
    {
        $keyword  = $content->target_keyword;
        $draft    = $job->phase_1_draft;
        $critique = $job->phase_2_critique;
        $lang     = $content->siloBlueprint?->target_language ?? 'id';

        if (!$draft || !$critique) {
            throw new \Exception('Phase 4 (Answers) FAILED: missing draft or critique from DB.');
        }

        $questions = is_array($critique) ? (isset($critique[0]) ? $critique : ($critique['questions'] ?? [])) : [];
        if (empty($questions)) {
            $questions = ['What advanced strategies are not yet covered?', 'How to avoid common mistakes?'];
        }

        Log::info("AI Phase 4 (Answers) START | job={$job->id} | keyword={$keyword} | questions=" . count($questions));

        $criticalQs = implode("\n- ", $questions);

        $sysTemplate = \App\Models\SystemSetting::get(
            'ai_prompt_phase4_sys',
            "You are a Subject Matter Expert in {lang}. Provide highly detailed, deeply researched answers to the following 'Critical Questions' in paragraph form. DO NOT generate code blocks, HTML, or technical implementations. Write in natural language only. Return ONLY the answers in Markdown formatting."
        );
        $sysPrompt  = strtr($sysTemplate, ['{lang}' => $lang, '{keyword}' => $keyword]);
        $userPrompt = "Topic: **{$keyword}**\n\nQuestions to Answer:\n- {$criticalQs}";

        $aiService = new AIService($content->tenant, 'default');
        $answers   = $aiService->generate($sysPrompt, $userPrompt);

        if (!$answers || mb_strlen(trim($answers)) < 100) {
            throw new \Exception('Phase 4 (Answers) returned empty or insufficient content (< 100 chars).');
        }

        $job->update([
            'status'          => 'phase_5',
            'phase_4_answers' => $answers,
        ]);

        Log::info("AI Phase 4 (Answers) DONE | job={$job->id} | answers_length=" . mb_strlen($answers));

        self::dispatch($this->contentId, $this->jobId, $this->targetStatus);
    }

    // -------------------------------------------------------------------------
    // PHASE 5 — Combine + HTML Conversion + CQI Quality Gate
    // Final article must be complete before CQI scoring.
    // -------------------------------------------------------------------------

    private function runPhase5(Content $content, AiGenerationJob $job): void
    {
        $keyword  = $content->target_keyword;
        $draft    = $job->phase_1_draft;
        $answers  = $job->phase_4_answers;
        $lang     = $content->siloBlueprint?->target_language ?? 'id';

        if (!$draft || !$answers) {
            throw new \Exception('Phase 5 (Combine+HTML) FAILED: missing draft or answers from DB.');
        }

        Log::info("AI Phase 5 (Combine) START | job={$job->id} | keyword={$keyword}");

        $brandNames       = \App\Models\SystemSetting::get('ai_prompt_brand_names', '');
        $brandPositioning = \App\Models\SystemSetting::get('ai_prompt_brand_positioning', '');

        // Step A: Combine draft + answers
        $framework = $content->content_framework ?? $content->siloBlueprint?->content_framework ?? 'default';
        $frameworkNote = $framework !== 'default'
            ? " CRITICAL: Preserve the {$framework} content framework structure exactly as in the original draft."
            : '';

        $sysP5 = \App\Models\SystemSetting::get(
            'ai_prompt_phase5_sys',
            "You are a Master SEO Content Editor writing in {lang}. Rewrite and drastically expand the original draft by seamlessly weaving in the provided 'Detailed Answers'. Preserve all existing Markdown links EXACTLY as they are. Do NOT add an FAQ section; weave the answers seamlessly into the body paragraphs with proper H2/H3 headings. Strengthen E-E-A-T signals (Experience, Expertise, Authoritativeness, Trustworthiness). Return ONLY the improved Markdown."
        );
        $sysP5 .= $frameworkNote;
        $sysP5 = strtr($sysP5, [
            '{lang}'              => $lang,
            '{keyword}'           => $keyword,
            '{brand_names}'       => $brandNames,
            '{brand_positioning}' => $brandPositioning,
        ]);
        $userP5 = "Keyword: **{$keyword}**\n\nOriginal Draft:\n{$draft}\n\nDetailed Answers to weave in:\n{$answers}";

        $aiService = new AIService($content->tenant, 'default');
        $combined  = $aiService->generate($sysP5, $userP5);

        $draftLen    = mb_strlen(trim($draft));
        $combinedLen = mb_strlen(trim($combined));

        if (!$combined || $combinedLen < $draftLen * 0.75) {
            Log::warning("Phase 5 Combine fell back to concatenation (combined={$combinedLen}, draft={$draftLen})");
            $combined = $draft . "\n\n## Pembahasan Lanjutan\n\n" . $answers;
        }

        // Step B: Convert to HTML
        Log::info("AI Phase 5 (HTML) START | job={$job->id}");

        $sysP6 = \App\Models\SystemSetting::get(
            'ai_prompt_phase6_sys',
            "You are a Chief Content Editor writing in {lang}. Do a final polish of the article. Preserve all existing Markdown links exactly as they are. Output the final result as clean HTML (using <h2>, <h3>, <p>, <strong>, <a>, etc.), NOT Markdown. Do not include ```html or <html> tags, just the inner HTML body."
        );
        $sysP6 = strtr($sysP6, [
            '{lang}'              => $lang,
            '{keyword}'           => $keyword,
            '{brand_names}'       => $brandNames,
            '{brand_positioning}' => $brandPositioning,
        ]);
        $sysP6 .= "\n\nCRITICAL: Wrap your ENTIRE final HTML output inside <article_body> and </article_body> tags. Do not include any text outside these tags.";
        $userP6 = "Keyword: **{$keyword}**\n\nArticle:\n{$combined}";

        $finalBody = $aiService->generate($sysP6, $userP6);

        if ($finalBody) {
            $finalBody = preg_replace('/^```html|```$/mi', '', trim($finalBody));

            if (preg_match('/<article_body>([\s\S]*?)<\/article_body>/i', $finalBody, $matches)) {
                $finalBody = trim($matches[1]);
            } elseif (preg_match('/<(?:h[1-3]|p|div|section)[\s\S]*>/i', $finalBody, $matches)) {
                $finalBody = $matches[0];
            }
        }

        // Cleanup: strip code blocks and AI hallucination artifacts
        if ($finalBody) {
            $finalBody = preg_replace('/```[\s\S]*?```/', '', $finalBody);
            $finalBody = preg_replace('/<pre>[\s\S]*?<\/pre>/i', '', $finalBody);
            $finalBody = preg_replace('/<code>[\s\S]*?<\/code>/i', '', $finalBody);
            $finalBody = preg_replace('/^.*ponytail:.*$/m', '', $finalBody);
            $finalBody = preg_replace('/^.*ponytail.*$/mi', '', $finalBody);
            $finalBody = preg_replace('/^\[.*skipped:.*$/m', '', $finalBody);
            $finalBody = preg_replace('/^(-->|->)\s.*$/m', '', $finalBody);
            $finalBody = trim($finalBody);
        }

        if (!$finalBody || mb_strlen(trim($finalBody)) < 300) {
            Log::warning("Phase 5 HTML AI failed. Using Markdown fallback.");
            $finalBody = \Illuminate\Support\Str::markdown($combined);
        }

        if (!$finalBody || mb_strlen(trim($finalBody)) < 100) {
            throw new \Exception('Phase 5 (HTML) failed to produce valid HTML even after fallback.');
        }

        Log::info("AI Phase 5 (HTML) DONE | job={$job->id} | html_length=" . mb_strlen($finalBody));

        // Step C: CQI Quality Gate on final article
        Log::info("AI Phase 5 (CQI Audit) START | job={$job->id}");
        $cqiThreshold = (int) \App\Models\SystemSetting::get('ai_cqi_threshold', 75);
        $retryCount   = (int) ($job->retry_count ?? 0);

        $frameworkAudit = $framework !== 'default'
            ? ", and whether the article follows the {$framework} content framework structure"
            : '';

        $cqiSys = "You are a strict Senior SEO Content Auditor. Score the following HTML article on depth, relevance, readability, E-E-A-T signals{$frameworkAudit}. Respond ONLY with valid JSON with this exact structure:\n{\"cqi_score\": <integer 0-100>, \"gaps\": [\"gap1\", \"gap2\"], \"improvements\": [\"improvement1\", \"improvement2\"]}";
        $cqiResult = $aiService->generateJson($cqiSys, "Target keyword: **{$keyword}**\n\nArticle HTML:\n{$finalBody}");

        if (!$cqiResult || !isset($cqiResult['cqi_score'])) {
            Log::warning("Phase 5 CQI audit failed, defaulting to score 80.");
            $cqiResult = ['cqi_score' => 80, 'gaps' => [], 'improvements' => []];
        }

        $cqiScore = (int) $cqiResult['cqi_score'];

        // Save combined, HTML, and CQI audit
        $job->update([
            'phase_5_combined' => $combined,
            'phase_6_html'     => $finalBody,
            'phase_2_critique' => $cqiResult,  // overwrite questions — Phase 4 already consumed them
        ]);

        if ($cqiScore < $cqiThreshold) {
            if ($retryCount >= 2) {
                $this->failJob(
                    $job, $content,
                    "CQI score {$cqiScore} below threshold {$cqiThreshold} after {$retryCount} retries."
                );
                return;
            }

            $improvements = implode('; ', $cqiResult['improvements'] ?? ['Improve depth and E-E-A-T signals.']);
            \App\Models\SystemSetting::set('_ai_retry_improvements_' . $this->jobId, $improvements);

            $job->update([
                'status'      => 'phase_2',
                'retry_count' => $retryCount + 1,
                'error_log'   => ['cqi_score' => $cqiScore, 'retry' => $retryCount + 1, 'improvements' => $improvements],
            ]);
            $content->update(['status' => 'ai_processing']);

            Log::warning("CQI {$cqiScore} < {$cqiThreshold}. Retrying Phase 2 (attempt " . ($retryCount + 1) . "/2).");

            self::dispatch($this->contentId, $this->jobId, $this->targetStatus)->delay(now()->addSeconds(5));
            return;
        }

        Log::info("AI Phase 5 (CQI Audit) PASSED | job={$job->id} | score={$cqiScore}");

        $job->update(['status' => 'phase_6']);
        self::dispatch($this->contentId, $this->jobId, $this->targetStatus);
    }

    // -------------------------------------------------------------------------
    // PHASE 6 — SEO Meta + Save + Embeddings
    // -------------------------------------------------------------------------

    private function runPhase6(Content $content, AiGenerationJob $job): void
    {
        $keyword  = $content->target_keyword;
        $finalBody = $job->phase_6_html;
        $critique  = $job->phase_2_critique;

        if (!$finalBody) {
            throw new \Exception('Phase 6 (Save) FAILED: phase_6_html is empty in DB.');
        }

        Log::info("AI Phase 6 (Save) START | job={$job->id} | keyword={$keyword}");

        // Capitalize headings
        $finalBody = preg_replace_callback('/(<h[1-6][^>]*>)(.*?)(<\/h[1-6]>)/i', function ($matches) {
            $capitalized = preg_replace_callback('/\b([a-z])/u', function ($m) {
                return mb_strtoupper($m[1], 'UTF-8');
            }, $matches[2]);
            return $matches[1] . $capitalized . $matches[3];
        }, $finalBody);

        $contentHash = hash('sha256', $finalBody);
        $cqiScore    = (int) ($critique['cqi_score'] ?? 80);
        $targetStatus = $this->targetStatus ?? 'draft';
        $blogPrefix  = \App\Models\SystemSetting::get('permalink_blog', 'blog');

        // Save final output to job
        $job->update([
            'status'       => 'completed',
            'phase_4_final'=> $finalBody,
            'completed_at' => now(),
        ]);

        // Mark deterministic links as injected
        try {
            DeterministicLink::where('source_content_id', $content->id)
                ->update(['is_injected' => true, 'injected_at' => now()]);
        } catch (\Exception $e) {
            Log::warning("DeterministicLink update failed for job {$job->id}: " . $e->getMessage());
        }

        // Capitalize title
        $newTitle = preg_replace_callback('/\b([a-z])/u', function ($m) {
            return mb_strtoupper($m[1], 'UTF-8');
        }, $content->title ?? '');

        // Persist content
        try {
            $content->body_raw = $finalBody;
            $content->update([
                'title'        => $newTitle,
                'cqi_score'    => $cqiScore,
                'content_hash' => $contentHash,
                'status'       => $targetStatus,
                'published_at' => $content->published_at ?? now(),
            ]);
            $content->save();

            Log::info("AI Phase 6 (Saved) DONE | job={$job->id} | status={$targetStatus} | cqi={$cqiScore} | body=" . mb_strlen($finalBody));
        } catch (\Exception $e) {
            Log::error("Content save failed for job {$job->id} but job marked completed: " . $e->getMessage());
        }

        // SEO Meta Generation (non-blocking)
        try {
            $this->generateSeoMeta($content, $keyword, $blogPrefix);
        } catch (\Exception $e) {
            Log::warning("SEO Meta failed for job {$job->id}: " . $e->getMessage());
        }

        // Embeddings (non-blocking)
        try {
            $this->generateEmbeddings($content, $finalBody);
        } catch (\Exception $e) {
            Log::warning("Embeddings failed for job {$job->id}: " . $e->getMessage());
        }

        // Clean up retry hints
        try {
            \App\Models\SystemSetting::where('key', '_ai_retry_improvements_' . $this->jobId)->delete();
        } catch (\Exception $e) {
            Log::warning("Cleanup retry hints failed for job {$job->id}: " . $e->getMessage());
        }

        // Chain next job
        $this->dispatchNextPendingJob();
    }

    // -------------------------------------------------------------------------
    // SEO Meta Generation (inline after Phase 6)
    // -------------------------------------------------------------------------

    private function generateSeoMeta(Content $content, string $keyword, string $blogPrefix): void
    {
        $aiService = new AIService($content->tenant, 'default');

        $metaTitlePrompt = \App\Models\SystemSetting::get(
            'ai_prompt_meta_title',
            'Write a highly click-worthy SEO title for "{keyword}". Max 60 characters. Return ONLY the title.'
        );
        $metaDescPrompt  = \App\Models\SystemSetting::get(
            'ai_prompt_meta_description',
            'Write an engaging SEO meta description for "{keyword}". Must be 150-160 characters with CTA. Return ONLY the description.'
        );

        $metaTitlePrompt = str_replace('{keyword}', $keyword, $metaTitlePrompt);
        $metaDescPrompt  = str_replace('{keyword}', $keyword, $metaDescPrompt);

        $generatedTitle = trim($aiService->generate('You are an expert SEO specialist.', $metaTitlePrompt), " \t\n\r\"'");
        $generatedDesc  = trim($aiService->generate('You are an expert SEO specialist.', $metaDescPrompt), " \t\n\r\"'");

        $generatedTitle = preg_replace_callback('/\b([a-z])/u', function ($m) {
            return mb_strtoupper($m[1], 'UTF-8');
        }, $generatedTitle);

        $content->updateSeoMeta([
            'title'          => $generatedTitle ?: $content->title,
            'description'    => $generatedDesc,
            'canonical'      => url('/' . $blogPrefix . '/' . $content->slug),
            'robots'         => 'index, follow',
            'og_title'       => $generatedTitle ?: $content->title,
            'og_description' => $generatedDesc,
            'og_image'       => $content->featured_image_url ?: \App\Models\SystemSetting::get('seo_og_image'),
        ]);

        Log::info("SEO Meta DONE | content={$content->id}");
    }

    // -------------------------------------------------------------------------
    // Embeddings Generation (inline after Phase 6)
    // -------------------------------------------------------------------------

    private function generateEmbeddings(Content $content, string $finalBody): void
    {
        $strategy = \App\Models\SystemSetting::get('internal_link_strategy', 'deterministic');
        if (!in_array($strategy, ['semantic', 'both'])) {
            return;
        }

        $aiService = new AIService($content->tenant, 'default');

        $plainText = strip_tags($finalBody);
        $plainText = preg_replace('/\s+/', ' ', $plainText);
        $chunks    = mb_str_split($plainText, 1000);

        \App\Models\ContentEmbedding::where('content_id', $content->id)->delete();

        $vectorCount = 0;
        foreach ($chunks as $chunk) {
            $chunk = trim($chunk);
            if (mb_strlen($chunk) < 50) continue;

            $vector = $aiService->generateEmbeddings($chunk);
            if ($vector && is_array($vector)) {
                \App\Models\ContentEmbedding::create([
                    'content_id'  => $content->id,
                    'chunk_text'  => $chunk,
                    'vector_data' => $vector,
                ]);
                $vectorCount++;
            }
        }

        Log::info("Embeddings DONE | content={$content->id} | vectors={$vectorCount}");
    }

    // -------------------------------------------------------------------------
    // ERROR HANDLING
    // -------------------------------------------------------------------------

    private function handleError(AiGenerationJob $job, Content $content, \Exception $e, string $currentPhase): void
    {
        Log::error("ProcessAiGenerationJob ERROR | job={$job->id} | phase={$currentPhase} | msg={$e->getMessage()}");

        $recovery = new AiRecoveryManager($content->tenant);
        $logs     = [];
        $result   = $recovery->handleFailure($job, $content, $e, $currentPhase, $logs);

        if (isset($result['status'])) {
            if ($result['status'] === 'wait') {
                $job->update(['status' => 'pending']);
                self::dispatch($this->contentId, $this->jobId, $this->targetStatus)
                    ->delay(now()->addSeconds($result['wait_time'] ?? 90));
                return;
            }

            if ($result['status'] === 'continue') {
                self::dispatch($this->contentId, $this->jobId, $this->targetStatus)
                    ->delay(now()->addSeconds(5));
                return;
            }
        }

        // Recovery failed — permanently fail
        $this->failJob($job, $content, $e->getMessage() . ' (unrecoverable after retries)');
    }

    // -------------------------------------------------------------------------
    // HELPERS
    // -------------------------------------------------------------------------

    private function failJob(AiGenerationJob $job, Content $content, string $reason): void
    {
        Log::error("ProcessAiGenerationJob FAILED | job={$job->id} | reason={$reason}");

        $job->update([
            'status'    => 'failed',
            'error_log' => ['reason' => $reason, 'failed_at' => now()->toISOString()],
        ]);

        $content->update(['status' => 'failed_cqi']);

        $this->dispatchNextPendingJob();
    }

    private function dispatchNextPendingJob(): void
    {
        $nextJob = AiGenerationJob::withoutGlobalScopes()
            ->where('status', 'pending')
            ->where('id', '!=', $this->jobId)
            ->oldest()
            ->first();

        if (!$nextJob) {
            Log::info('No more pending AI jobs.');
            return;
        }

        $nextTargetStatus = $nextJob->error_log['target_status'] ?? $this->targetStatus ?? 'draft';
        $nextJob->update(['error_log' => null]);

        Log::info("Dispatching next job #{$nextJob->id} (content_id={$nextJob->content_id})");

        self::dispatch($nextJob->content_id, $nextJob->id, $nextTargetStatus);
    }
}
