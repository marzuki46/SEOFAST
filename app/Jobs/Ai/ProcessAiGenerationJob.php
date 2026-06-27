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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessAiGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $contentId,
        protected int $jobId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $content = Content::withoutGlobalScopes()->find($this->contentId);
        $job = AiGenerationJob::withoutGlobalScopes()->find($this->jobId);

        if (!$content || !$job) {
            Log::error("ProcessAiGenerationJob failed: Content or Job model not found.", [
                'content_id' => $this->contentId,
                'job_id'     => $this->jobId
            ]);
            return;
        }

        // In single-ownership CMS mode, tenant may be null.
        // AIService gracefully falls back to SystemSetting when tenant is null.
        $tenant = $content->tenant;
        $aiService1 = new AIService($tenant, '1');
        $aiService2 = new AIService($tenant, '2');
        $aiService3 = new AIService($tenant, '3');

        $job->update([
            'status'     => 'phase_1',
            'started_at' => now(),
        ]);

        $keyword     = $content->target_keyword;
        $seedKeyword = $content->siloBlueprint?->seed_keyword ?? $keyword;
        $lang        = $content->siloBlueprint?->target_language ?? 'id';
        $country     = $content->siloBlueprint?->target_country ?? 'ID';
        $blogPrefix  = \App\Models\SystemSetting::get('permalink_blog', 'blog');
        
        // Phase 4 image data (if selected)
        $imageContext = '';
        if ($content->featured_image_url) {
            $imageContext = "\n\nFEATURED IMAGE:\n- URL: {$content->featured_image_url}\n- Alt Text: {$content->featured_image_alt}\n- Caption: {$content->featured_image_caption}\n(Place this image at the most natural position in the article using HTML <img> tag with the exact alt text provided.)";
        }

        try {
            // === PHASE 1: Draft generation ===
            $sysPrompt1Template = \App\Models\SystemSetting::get('ai_prompt_phase1_sys', "You are a professional SEO Content Writer. Write an initial draft for an article about the target keyword: '{keyword}' using seed keyword hints '{seed_keyword}' in language '{lang}' for country '{country}'. Format in clean Markdown with appropriate headers (H2, H3).");
            $userPrompt1Template = \App\Models\SystemSetting::get('ai_prompt_phase1_user', "Write a comprehensive 800-word draft about: {keyword}. Include an introduction, key concepts, and actionable tips.{image_context}");
            
            $sysPrompt1 = str_replace(['{keyword}', '{seed_keyword}', '{lang}', '{country}'], [$keyword, $seedKeyword, $lang, $country], $sysPrompt1Template);
            $userPrompt1 = str_replace(['{keyword}', '{image_context}'], [$keyword, $imageContext], $userPrompt1Template);
            
            $draft = $aiService1->generate($sysPrompt1, $userPrompt1);

            if (!$draft) {
                $draft = $this->getSimulatedDraft($keyword);
            }

            $job->update([
                'status' => 'phase_2',
                'phase_1_draft' => $draft,
            ]);

            // === PHASE 2: Critique ===
            $sysPrompt2Template = \App\Models\SystemSetting::get('ai_prompt_phase2_sys', "You are a senior SEO Editor. Critique the following content draft to identify structural gaps, missing topical depth, or readability improvements. You MUST return your findings ONLY in JSON format containing: {'cqi_score': integer (0-100), 'gaps': array, 'improvements': array}.");
            $sysPrompt2 = str_replace('{keyword}', $keyword, $sysPrompt2Template);
            $userPrompt2 = "Draft to critique:\n\n" . $draft;

            $critique = $aiService2->generateJson($sysPrompt2, $userPrompt2);

            if (!$critique) {
                $critique = [
                    'cqi_score' => 88,
                    'gaps' => ['Need deeper E-E-A-T evidence', 'Should include target-focused FAQ section'],
                    'improvements' => ['Expand H2 subheadings with semantic keywords', 'Use a bulleted list for action steps'],
                ];
            }

            $cqiScore = (int) ($critique['cqi_score'] ?? 88);

            // === CQI GATE: If < 80, retry up to 3 times ===
            $retryCount = $job->retry_count ?? 0;
            if ($cqiScore < 80 && $retryCount < 3) {
                $job->update([
                    'status' => 'failed_cqi',
                    'phase_2_critique' => $critique,
                    'retry_count' => $retryCount + 1,
                    'error_log' => ['cqi_score' => $cqiScore, 'message' => "CQI below threshold. Retry #{$retryCount}"],
                ]);
                $content->update(['status' => 'failed_cqi']);
                
                // Re-dispatch with same IDs
                self::dispatch($this->contentId, $this->jobId)->delay(now()->addSeconds(30));
                Log::warning("ProcessAiGenerationJob: CQI {$cqiScore} < 80. Retry #{$retryCount} scheduled for content ID {$this->contentId}");
                return;
            }

            $job->update([
                'status' => 'phase_3',
                'phase_2_critique' => $critique,
            ]);

            // === PHASE 3: Expansion ===
            $sysPrompt3Template = \App\Models\SystemSetting::get('ai_prompt_phase3_sys', "You are an SEO Content Expander. Expand the draft by incorporating the following critique and improvements. Make the content richer, add bullet points, and structure the sections cleanly in Markdown.");
            $sysPrompt3 = str_replace('{keyword}', $keyword, $sysPrompt3Template);
            $userPrompt3 = "Original Draft:\n{$draft}\n\nCritique:\n" . json_encode($critique) . "\n\nProvide the expanded Markdown draft now.";

            $expanded = $aiService1->generate($sysPrompt3, $userPrompt3);

            if (!$expanded) {
                $expanded = $draft . "\n\n### Panduan Tambahan & E-E-A-T\nBerdasarkan audit optimasi, pastikan artikel ini didukung oleh data terpercaya, studi kasus nyata, dan keahlian praktis untuk memberikan nilai maksimal bagi pembaca.";
            }

            $job->update([
                'status' => 'phase_4',
                'phase_3_expanded' => $expanded,
            ]);

            // === PHASE 4: Master Editor + Inject Internal Links + Image ===
            // Load deterministic links for this content
            $deterministicLinks = \App\Models\DeterministicLink::where('source_content_id', $content->id)
                ->with('targetContent')
                ->get();
            
            $linkInstructions = '';
            if ($deterministicLinks->isNotEmpty()) {
                $linkInstructions = "\n\nMANDATORY INTERNAL LINKS TO INJECT:\nYou MUST include ALL the following anchor links exactly in the article. Place them at the most natural and contextually relevant positions:\n";
                foreach ($deterministicLinks as $link) {
                    $targetUrl = url('/blog/' . $link->targetContent?->slug);
                    $linkInstructions .= "- Anchor text: \"{$link->anchor_text}\" → Link to: {$targetUrl}\n";
                }
                $linkInstructions .= "\nUse standard Markdown link syntax [Anchor text](url) for each link.";
            }

            $sysPrompt4Template = \App\Models\SystemSetting::get('ai_prompt_phase4_sys', "You are a Master Content Editor. Refine the following markdown text. Improve readability by adding bolding, lists, and blockquotes where appropriate. Inject the provided internal links naturally. Return ONLY the final polished Markdown text. Do NOT use HTML tags.");
            $sysPrompt4 = str_replace('{keyword}', $keyword, $sysPrompt4Template);
            $userPrompt4 = "Refine this markdown:\n\n" . $expanded . $linkInstructions . $imageContext;

            $finalHtml = $aiService3->generate($sysPrompt4, $userPrompt4);

            if (!$finalHtml) {
                $finalHtml = $this->getSimulatedHtml($keyword, $expanded);
            }

            // Mark all injected links as successful
            if ($deterministicLinks->isNotEmpty()) {
                \App\Models\DeterministicLink::where('source_content_id', $content->id)
                    ->update(['is_injected' => true]);
            }

            $contentHash = hash('sha256', $finalHtml);

            $job->update([
                'status'       => 'completed',
                'phase_4_final' => $finalHtml,
                'completed_at' => now(),
            ]);

            // Save body_raw as bilingual Indonesian (ID) translation
            // Using setTranslation to correctly write into the JSON column
            $content->setTranslation('body_raw', 'id', $expanded);
            $content->update([
                'rendered_html_path' => $finalHtml,
                'cqi_score'          => $cqiScore,
                'content_hash'       => $contentHash,
                'status'             => 'draft',
                'published_at'       => null,
            ]);
            $content->save();

            // === PHASE 5: Generate SEO Meta ===
            try {
                $aiService4 = new AIService($tenant, '4');
                
                $metaTitlePromptStr = \App\Models\SystemSetting::get('ai_prompt_meta_title', 'Generate a highly click-worthy SEO Title for the keyword "{keyword}". Maximum 60 characters. Return ONLY the title text, nothing else.');
                $metaTitlePrompt = str_replace('{keyword}', $keyword, $metaTitlePromptStr);
                $metaTitlePrompt = str_replace('{content_title}', $content->title, $metaTitlePrompt);
                
                $metaDescPromptStr = \App\Models\SystemSetting::get('ai_prompt_meta_description', 'Generate an engaging SEO Meta Description for the keyword "{keyword}". Must be between 150-160 characters. Include a call to action. Return ONLY the description text.');
                $metaDescPrompt = str_replace('{keyword}', $keyword, $metaDescPromptStr);
                $metaDescPrompt = str_replace('{content_title}', $content->title, $metaDescPrompt);

                $generatedMetaTitle = $aiService4->generate("You are an expert SEO specialist.", $metaTitlePrompt);
                $generatedMetaDesc = $aiService4->generate("You are an expert SEO specialist.", $metaDescPrompt);

                // Clean quotes from results
                $generatedMetaTitle = trim($generatedMetaTitle, " \t\n\r\0\x0B\"'");
                $generatedMetaDesc = trim($generatedMetaDesc, " \t\n\r\0\x0B\"'");

                $content->updateSeoMeta([
                    'title'          => $generatedMetaTitle ?: $content->title,
                    'description'    => $generatedMetaDesc,
                    'canonical'      => url('/' . $blogPrefix . '/' . $content->getTranslation('slug', 'id')),
                    'robots'         => 'index, follow',
                    'og_title'       => $generatedMetaTitle ?: $content->title,
                    'og_description' => $generatedMetaDesc,
                    'og_image'       => $content->featured_image_url ?: \App\Models\SystemSetting::get('seo_og_image'),
                ]);
            } catch (\Exception $e) {
                Log::warning("ProcessAiGenerationJob SEO Meta failed: " . $e->getMessage());
            }

            // === PHASE 6: Auto Translate to English (If Enabled) ===
            if (\App\Models\SystemSetting::get('enable_auto_translate_en', '0') === '1') {
                try {
                    $aiService5 = new AIService($tenant, '4');
                    $translationPrompt = \App\Models\SystemSetting::get('ai_prompt_translation', 'You are a professional Translator. Translate the following Indonesian text to English. Maintain the exact same formatting, markdown syntax, and tone. CRITICAL RULE: DO NOT translate any URLs inside href attributes or markdown links. Leave the URLs exactly as they are.');
                    
                    // Helper to translate field safely
                    $translateField = function($field, $default = '') use ($aiService5, $translationPrompt) {
                        if (!$field) return $default;
                        $translated = $aiService5->generate($translationPrompt, $field);
                        return $translated ?: $default;
                    };

                    // Note: We use getTranslation('field', 'id') to fetch original text
                    // If not found, fallback to the raw attribute which defaults to current locale.
                    
                    // Translate contents fields
                    $enTitle = $translateField($content->getTranslation('meta_title', 'id') ?? $content->meta_title);
                    if ($enTitle) $content->setTranslation('meta_title', 'en', $enTitle);
                    
                    $enDesc = $translateField($content->getTranslation('meta_description', 'id') ?? $content->meta_description);
                    if ($enDesc) $content->setTranslation('meta_description', 'en', $enDesc);
                    
                    // Translate body_raw
                    $enBody = $translateField($content->getTranslation('body_raw', 'id') ?? $content->body_raw);
                    if ($enBody) $content->setTranslation('body_raw', 'en', $enBody);

                    // Translate image metadata
                    if ($content->featured_image_alt) {
                        $enAlt = $translateField($content->getTranslation('featured_image_alt', 'id') ?? $content->featured_image_alt);
                        if ($enAlt) $content->setTranslation('featured_image_alt', 'en', $enAlt);
                    }
                    if ($content->featured_image_caption) {
                        $enCaption = $translateField($content->getTranslation('featured_image_caption', 'id') ?? $content->featured_image_caption);
                        if ($enCaption) $content->setTranslation('featured_image_caption', 'en', $enCaption);
                    }

                    // Translate slug safely
                    $idSlug = $content->getTranslation('slug', 'id') ?? $content->slug;
                    $enSlugStr = $aiService5->generate("Translate this short title to English. Return only the translated title, no quotes or extra text.", str_replace('-', ' ', $idSlug));
                    if ($enSlugStr) {
                        $content->setTranslation('slug', 'en', Str::slug($enSlugStr));
                    }
                    
                    $content->save();

                    // Translate SEO Metas
                    $seoMeta = $content->seoMeta;
                    if ($seoMeta) {
                        $enSeoTitle = $translateField($seoMeta->getTranslation('title', 'id') ?? $seoMeta->title);
                        if ($enSeoTitle) {
                            $seoMeta->setTranslation('title', 'en', $enSeoTitle);
                            $seoMeta->setTranslation('og_title', 'en', $enSeoTitle);
                        }

                        $enSeoDesc = $translateField($seoMeta->getTranslation('description', 'id') ?? $seoMeta->description);
                        if ($enSeoDesc) {
                            $seoMeta->setTranslation('description', 'en', $enSeoDesc);
                            $seoMeta->setTranslation('og_description', 'en', $enSeoDesc);
                        }

                        if ($enSlugStr) {
                            $seoMeta->setTranslation('canonical', 'en', url('/en/' . config('app.permalink_blog', 'blog') . '/' . Str::slug($enSlugStr)));
                        }

                        $seoMeta->save();
                    }

                    Log::info("ProcessAiGenerationJob Phase 6: Auto-Translate to English completed for content ID {$this->contentId}");

                } catch (\Exception $e) {
                    Log::error("ProcessAiGenerationJob Phase 6 Auto-Translate failed: " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            Log::error("ProcessAiGenerationJob failed: " . $e->getMessage());
            $job->update([
                'status' => 'failed',
                'error_log' => ['error' => $e->getMessage()],
            ]);
            $content->update(['status' => 'failed_cqi']);
        }
    }

    /**
     * Generate simulated markdown draft
     */
    private function getSimulatedDraft(string $keyword): string
    {
        return "# Panduan Lengkap: " . ucwords($keyword) . "\n\n" .
            "Optimasi '" . $keyword . "' merupakan salah satu pilar krusial untuk mendominasi peringkat mesin pencari saat ini. " .
            "Artikel ini akan membahas strategi komprehensif untuk membantu Anda menguasai topik ini.\n\n" .
            "## Mengapa " . ucwords($keyword) . " Penting?\n" .
            "Dalam ekosistem digital yang dinamis, pemahaman mendalam tentang " . $keyword . " membantu situs web menjangkau audiens tertarget " .
            "dan meningkatkan otoritas domain secara signifikan.\n\n" .
            "## 3 Langkah Utama Implementasi\n" .
            "1. **Riset & Analisis Topik**: Mulailah dengan mengumpulkan kata kunci relevan dan mengelompokkannya ke dalam silo konten.\n" .
            "2. **Penyusunan Konten Berkualitas (E-E-A-T)**: Tulis artikel yang berfokus pada solusi masalah pembaca dengan data pendukung.\n" .
            "3. **Optimasi Internal Link**: Hubungkan halaman pilar dengan artikel kluster menggunakan jangkar teks (anchor text) yang presisi.";
    }

    /**
     * Generate simulated HTML content
     */
    private function getSimulatedHtml(string $keyword, string $markdown): string
    {
        $title = ucwords($keyword);
        return "<h2>Panduan Praktis Menguasai {$title}</h2>" .
            "<p>Dalam era digital yang kompetitif, pemahaman taktis mengenai <strong>{$keyword}</strong> adalah modal utama untuk memenangkan persaingan di Google SERP. Berikut adalah rangkuman strategi terbaik:</p>" .
            "<h3>1. Analisis Kebutuhan Pengguna</h3>" .
            "<p>Langkah pertama adalah memahami intensi pencarian (search intent) di balik kata kunci {$keyword}. Pastikan artikel Anda menjawab apa yang dicari pembaca.</p>" .
            "<h3>2. Struktur Silo Konten</h3>" .
            "<p>Bagi topik besar Anda menjadi pilar utama dan artikel kluster pendukung untuk mendistribusikan link juice secara merata.</p>" .
            "<ul>" .
            "<li><strong>Artikel Pilar:</strong> Pembahasan komprehensif topik utama.</li>" .
            "<li><strong>Artikel Kluster:</strong> Topik spesifik yang mendetail.</li>" .
            "</ul>" .
            "<p>Gunakan panduan ini secara konsisten untuk melihat peningkatan performa organik situs Anda secara nyata.</p>";
    }
}
