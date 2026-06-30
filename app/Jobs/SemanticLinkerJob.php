<?php

namespace App\Jobs;

use App\Models\Content;
use App\Models\ContentEmbedding;
use App\Models\SystemSetting;
use App\Services\AIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SemanticLinkerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contentId;

    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct($contentId)
    {
        $this->contentId = $contentId;
    }

    /**
     * Calculate cosine similarity between two vectors.
     */
    private function cosineSimilarity(array $vec1, array $vec2): float
    {
        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        
        $count = min(count($vec1), count($vec2));
        for ($i = 0; $i < $count; $i++) {
            $dotProduct += $vec1[$i] * $vec2[$i];
            $normA += pow($vec1[$i], 2);
            $normB += pow($vec2[$i], 2);
        }

        if ($normA == 0 || $normB == 0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $strategy = SystemSetting::get('internal_link_strategy', 'deterministic');
        if (!in_array($strategy, ['semantic', 'both'])) {
            return;
        }

        $threshold = (float) SystemSetting::get('semantic_link_threshold', 0.82);
        $maxLinks = (int) SystemSetting::get('semantic_max_links', 3);

        $content = Content::with('tenant')->find($this->contentId);
        if (!$content) return;

        $sourceEmbeddings = ContentEmbedding::where('content_id', $content->id)->get();
        if ($sourceEmbeddings->isEmpty()) return;

        // Fetch all other embeddings from published/draft contents
        // To save memory, we could chunk this, but we'll load them all for now assuming < 10k vectors
        // Or better, stream them.
        $matches = [];
        
        ContentEmbedding::where('content_id', '!=', $content->id)
            ->whereHas('content', function($q) {
                $q->whereIn('status', ['published', 'draft']);
            })
            ->chunk(1000, function ($targetEmbeddings) use (&$matches, $sourceEmbeddings, $threshold) {
                foreach ($sourceEmbeddings as $src) {
                    $srcVec = $src->vector_data;
                    if (!is_array($srcVec)) continue;

                    foreach ($targetEmbeddings as $tgt) {
                        $tgtVec = $tgt->vector_data;
                        if (!is_array($tgtVec)) continue;

                        $sim = $this->cosineSimilarity($srcVec, $tgtVec);
                        if ($sim >= $threshold) {
                            $matches[] = [
                                'source_chunk' => $src->chunk_text,
                                'target_content_id' => $tgt->content_id,
                                'target_title' => $tgt->content->title,
                                'target_slug' => $tgt->content->slug,
                                'similarity' => $sim
                            ];
                        }
                    }
                }
            });

        if (empty($matches)) {
            Log::info("SemanticLinker: No matches found above threshold {$threshold} for Content ID {$content->id}");
            return;
        }

        // Sort by similarity descending
        usort($matches, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        // Filter out duplicates to the same target content, keeping the highest similarity
        $uniqueTargets = [];
        $finalMatches = [];
        foreach ($matches as $match) {
            if (!in_array($match['target_content_id'], $uniqueTargets)) {
                $uniqueTargets[] = $match['target_content_id'];
                $finalMatches[] = $match;
                if (count($finalMatches) >= $maxLinks) {
                    break;
                }
            }
        }

        if (empty($finalMatches)) return;

        $aiService = new AIService($content->tenant, 'default');
        $bodyRaw = $content->body_raw;
        $linksInjected = 0;

        foreach ($finalMatches as $match) {
            $sourceText = $match['source_chunk'];
            // Check if source text is still in the body
            if (strpos($bodyRaw, $sourceText) === false) continue;

            $targetUrl = url('/blog/' . $match['target_slug']);
            $targetTitle = $match['target_title'];

            $sysPrompt = "You are a master SEO editor. Your task is to inject an internal link naturally into the provided paragraph.\n"
                       . "Do NOT change the core meaning of the paragraph. Just slightly weave the target topic and insert a Markdown link.\n"
                       . "Target Topic: '{$targetTitle}'\nTarget URL: '{$targetUrl}'\n"
                       . "Return ONLY the modified paragraph. No pleasantries, no markdown code blocks.";
            
            $userPrompt = "Original Paragraph:\n" . $sourceText;

            $rewritten = $aiService->generate($sysPrompt, $userPrompt, ['max_tokens' => 1000]);
            
            if ($rewritten && !empty(trim($rewritten))) {
                // Ensure no markdown block wrappers
                $rewritten = trim(str_replace(['```markdown', '```'], '', $rewritten));
                
                // Replace the exact chunk in the body with the rewritten chunk
                $bodyRaw = str_replace($sourceText, $rewritten, $bodyRaw);
                $linksInjected++;
            }
        }

        if ($linksInjected > 0) {
            $content->update(['body_raw' => $bodyRaw]);
            Log::info("SemanticLinker: Successfully injected {$linksInjected} semantic links into Content ID {$content->id}");
        }
    }
}
