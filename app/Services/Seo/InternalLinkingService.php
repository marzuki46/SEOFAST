<?php

namespace App\Services\Seo;

use App\Models\Content;
use App\Models\DeterministicLink;
use App\Models\SiloBlueprint;

class InternalLinkingService
{
    /**
     * Map internal links for a newly created silo blueprint.
     */
    public function mapLinksForSilo(SiloBlueprint $silo): void
    {
        $pillar = $silo->contents()->where('hierarchy_level', 'pillar')->first();
        if (!$pillar) return;

        $aiService = new \App\Services\AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "You are an expert SEO internal linking architect. Generate natural, contextually relevant, and conversational anchor texts for internal links. DO NOT use the exact target keyword as the anchor text. Vary the phrasing (e.g., use synonyms, call-to-actions, or descriptive phrases). Return a JSON array of strings corresponding to the given link pairs in the EXACT SAME ORDER. Example output: [\"baca panduan lengkap SEO\", \"pelajari teknik optimasi on-page\", \"strategi link building terbaru\"]. RETURN ONLY RAW VALID JSON ARRAY OF STRINGS.";

        // 1. Process cluster by cluster to avoid massive AI payloads
        $clusters = $silo->contents()->where('hierarchy_level', 'cluster')->get();
        foreach ($clusters as $cluster) {
            $plannedLinks = [];
            
            // Pillar <-> Cluster
            $plannedLinks[] = ['source' => $pillar, 'target' => $cluster];
            $plannedLinks[] = ['source' => $cluster, 'target' => $pillar];

            // Sub-clusters within this cluster chamber
            $subClusters = $silo->contents()
                ->where('hierarchy_level', 'sub_cluster')
                ->where('parent_id', $cluster->id)
                ->get();

            foreach ($subClusters as $sub) {
                $plannedLinks[] = ['source' => $sub, 'target' => $cluster];
                $plannedLinks[] = ['source' => $cluster, 'target' => $sub];
                $plannedLinks[] = ['source' => $sub, 'target' => $pillar];
            }

            // Peer-to-Peer within the same chamber
            foreach ($subClusters as $subA) {
                foreach ($subClusters as $subB) {
                    if ($subA->id !== $subB->id) {
                        $plannedLinks[] = ['source' => $subA, 'target' => $subB];
                    }
                }
            }

            // Ask AI to generate anchor texts
            $userPrompt = "Generate anchor texts for the following links:\n";
            foreach ($plannedLinks as $idx => $link) {
                $userPrompt .= ($idx + 1) . ". From: '{$link['source']->target_keyword}' TO: '{$link['target']->target_keyword}'\n";
            }

            $aiAnchors = $aiService->generateJson($systemPrompt, $userPrompt);
            $useAi = (is_array($aiAnchors) && count($aiAnchors) === count($plannedLinks));

            foreach ($plannedLinks as $idx => $link) {
                $anchorText = $useAi ? $aiAnchors[$idx] : $link['target']->target_keyword . ' (' . uniqid() . ')';
                $anchorText = trim(strip_tags($anchorText), "\"' ");

                // We only want ONE deterministic link between source and target for silo structures
                // If it exists, update its anchor text with the new AI generated one
                $existingLink = DeterministicLink::where('source_content_id', $link['source']->id)
                    ->where('target_content_id', $link['target']->id)
                    ->first();

                if ($existingLink) {
                    $existingLink->update([
                        'mandatory_anchor_text' => $anchorText
                    ]);
                } else {
                    DeterministicLink::create([
                        'source_content_id' => $link['source']->id,
                        'target_content_id' => $link['target']->id,
                        'mandatory_anchor_text' => $anchorText,
                        'is_injected_successfully' => false
                    ]);
                }
            }
        }
    }

    /**
     * Inject links into the generated HTML content if missing.
     */
    public function injectLinks(Content $content, string $html): string
    {
        $outboundLinks = $content->outboundLinks()->where('is_injected_successfully', false)->get();
        
        foreach ($outboundLinks as $link) {
            $target = Content::find($link->target_content_id);
            if (!$target) continue;

            $anchor = htmlspecialchars($link->mandatory_anchor_text);
            $url = '/' . ltrim($target->slug, '/');
            $aTag = sprintf('<a href="%s">%s</a>', $url, $anchor);
            
            if (strpos($html, $url) === false && strpos($html, $anchor) === false) {
                $html .= "<p>Baca juga: {$aTag}</p>";
            }

            $link->update([
                'is_injected_successfully' => true,
                'injected_at' => now(),
            ]);
        }

        return $html;
    }
}
