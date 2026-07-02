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

        $clusters = $silo->contents()->where('hierarchy_level', 'cluster')->get();
        $subClusters = $silo->contents()->where('hierarchy_level', 'sub_cluster')->get();

        $plannedLinks = [];
        $linksPerSource = [];

        $addLink = function($source, $target) use (&$plannedLinks, &$linksPerSource) {
            if (!$source || !$target) return;
            $sid = $source->id;
            if (!isset($linksPerSource[$sid])) {
                $linksPerSource[$sid] = 0;
            }
            if ($linksPerSource[$sid] < 5) {
                $plannedLinks[] = ['source' => $source, 'target' => $target];
                $linksPerSource[$sid]++;
            }
        };

        // 1. PILLAR -> Semua Cluster di bawahnya
        foreach ($clusters as $cluster) {
            $addLink($pillar, $cluster);
        }

        // 2. CLUSTER -> Pillar (Wajib) & Sub-Cluster miliknya
        foreach ($clusters as $clusterA) {
            $addLink($clusterA, $pillar);
            
            $itsSubs = $subClusters->where('parent_id', $clusterA->id);
            foreach ($itsSubs as $sub) {
                $addLink($clusterA, $sub);
            }
        }

        // 3. SUB-CLUSTER -> Cluster Induk & Pillar (Home is naturally handled via layout/nav, NO siblings)
        foreach ($subClusters as $subA) {
            $parentCluster = $clusters->where('id', $subA->parent_id)->first();
            if ($parentCluster) {
                $addLink($subA, $parentCluster);
            }
            
            $addLink($subA, $pillar);
        }

        $aiService = new \App\Services\AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "Anda adalah Pakar SEO Internal Linking. Tugas Anda adalah mencari anchor text yang bervariasi untuk SEBUAH artikel target agar tidak terindikasi spam (keyword cannibalization).
ATURAN MUTLAK:
1. Jika diminta 3 anchor, berikan EXACTLY 3 anchor.
2. Kombinasikan: 1 Exact Match (judul target), sisanya Partial Match (frasa turunan/sinonim) atau Long Tail (kalimat panjang natural).
3. Return ONLY a JSON array of strings, e.g. [\"Anchor 1\", \"Anchor 2\", \"Anchor 3\"]. NO MARKDOWN, NO EXTRA TEXT.";

        // Kumpulkan berdasarkan TARGET (Anchor Dictionary Deduplication Strategy)
        $linksByTarget = [];
        foreach ($plannedLinks as $link) {
            $linksByTarget[$link['target']->id][] = $link;
        }

        foreach ($linksByTarget as $targetId => $groupLinks) {
            $target = $groupLinks[0]['target'];
            $count = count($groupLinks);
            
            $userPrompt = "Target Article Keyword: '{$target->target_keyword}'\n";
            $userPrompt .= "Tolong berikan {$count} variasi anchor text yang unik dan berbeda satu sama lain untuk artikel target di atas.";

            $aiAnchors = $aiService->generateJson($systemPrompt, $userPrompt);
            $useAi = (is_array($aiAnchors) && count($aiAnchors) >= $count);

            foreach ($groupLinks as $idx => $link) {
                $anchorText = $useAi ? $aiAnchors[$idx] : $target->target_keyword . ' bagian ' . ($idx + 1);
                $anchorText = trim(strip_tags($anchorText), "\"' ");

                $existingLink = DeterministicLink::withoutGlobalScopes()
                    ->where('source_content_id', $link['source']->id)
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
