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

        foreach ($clusters as $cluster) {
            DeterministicLink::firstOrCreate([
                'source_content_id' => $pillar->id,
                'target_content_id' => $cluster->id,
                'mandatory_anchor_text' => $cluster->target_keyword,
            ]);

            DeterministicLink::firstOrCreate([
                'source_content_id' => $cluster->id,
                'target_content_id' => $pillar->id,
                'mandatory_anchor_text' => $pillar->target_keyword . ' panduan',
            ]);
        }

        foreach ($subClusters as $subCluster) {
            DeterministicLink::firstOrCreate([
                'source_content_id' => $subCluster->id,
                'target_content_id' => $pillar->id,
                'mandatory_anchor_text' => $pillar->target_keyword,
            ]);
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
