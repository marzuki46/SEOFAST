<?php

namespace App\Jobs\Ai;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SiloBlueprint;
use App\Models\DeterministicLink;
use App\Services\AIService;
use Illuminate\Support\Facades\Log;

class GenerateInternalLinkAnchorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $siloId;
    public $timeout = 600; // Allow 10 minutes for slow AI processing

    /**
     * Create a new job instance.
     */
    public function __construct($siloId)
    {
        $this->siloId = $siloId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $silo = SiloBlueprint::find($this->siloId);
        if (!$silo) return;

        // Fetch all links belonging to this silo that are not yet successfully injected or have a default placeholder
        // We will fetch ALL links for this silo for processing.
        $contentIds = $silo->contents()->pluck('id');
        $links = DeterministicLink::with(['source', 'target'])
            ->whereIn('source_content_id', $contentIds)
            ->where('is_injected_successfully', false)
            // We assume if it has the exact target keyword or matches placeholder, it needs processing
            ->get();

        if ($links->isEmpty()) {
            return;
        }

        // Ask AI in batches of 10
        $aiService = new AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "You are an expert SEO internal linking architect. Generate natural, contextually relevant, High-CTR anchor texts for internal links. DO NOT use the exact target keyword as the anchor text. Use compelling, click-worthy phrasing. Return a JSON array of strings corresponding to the given link pairs in the EXACT SAME ORDER. RETURN ONLY RAW VALID JSON ARRAY OF STRINGS.";

        $chunks = $links->chunk(10);

        foreach ($chunks as $chunk) {
            $userPrompt = "Generate High-CTR anchor texts for the following internal links:\n";
            foreach ($chunk->values() as $idx => $link) {
                $userPrompt .= ($idx + 1) . ". From article '{$link->source->target_keyword}' to article '{$link->target->target_keyword}'\n";
            }
            
            $aiAnchors = $aiService->generateJson($systemPrompt, $userPrompt);
            
            if (is_array($aiAnchors) && count($aiAnchors) === $chunk->count()) {
                foreach ($chunk->values() as $idx => $link) {
                    $aiText = $aiAnchors[$idx];
                    $anchorText = trim(strip_tags($aiText), "\"' ");
                    
                    if (!empty($anchorText)) {
                        $link->update([
                            'mandatory_anchor_text' => $anchorText
                        ]);
                    }
                }
            } else {
                Log::warning("AI failed to generate anchors for chunk in silo {$this->siloId}. API timeout or hallucination.");
            }
            
            // Sleep slightly to respect AI rate limits
            sleep(2);
        }
    }
}
