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

        // 3. SUB-CLUSTER -> Cluster Induk, Pillar, & Sesama Sub-cluster (1 Induk)
        foreach ($subClusters as $subA) {
            $parentCluster = $clusters->where('id', $subA->parent_id)->first();
            if ($parentCluster) {
                $addLink($subA, $parentCluster);
            }
            
            $addLink($subA, $pillar);
            
            $siblings = $subClusters->where('parent_id', $subA->parent_id)->where('id', '!=', $subA->id);
            foreach ($siblings as $sibling) {
                $addLink($subA, $sibling);
            }
        }

        $aiService = new \App\Services\AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "Anda adalah Pakar SEO Internal Linking. Tugas Anda adalah mencari anchor text yang paling natural untuk menautkan link dari Source Article ke Target Article.
ATURAN MUTLAK KATEGORI ANCHOR:
Buatlah anchor text yang bervariasi dengan mematuhi distribusi ini secara acak namun cerdas:
1. Exact Match Anchor (Jarang digunakan): Gunakan judul target persis, atau sangat mirip (hanya boleh muncul 1 dari 5).
2. Partial Match Anchor (Paling Direkomendasikan): Ekstrak topik inti dan gunakan frasa turunan/sinonim. (contoh jika target 'Cara beternak sapi', anchor bisa: 'panduan beternak sapi', 'tips merawat sapi', 'cara memelihara sapi potong').
3. Long Tail Anchor: Kalimat/frasa agak panjang (contoh: 'cara membuat sapi sehat dan cepat gemuk', 'cara memilih bibit sapi yang bagus').
4. UBAH SEDIKIT STRUKTUR KALIMAT jika perlu agar anchor text menyatu dengan konteks sumbernya secara natural 100%. DILARANG spam keyword yang sama persis berkali-kali.
Return a JSON array of strings corresponding to the links in the EXACT SAME ORDER. NO MARKDOWN, NO EXTRA TEXT. ONLY A RAW JSON ARRAY OF STRINGS.";

        // Chunk links into batches of 10 to avoid AI payload getting too big
        $chunks = array_chunk($plannedLinks, 10);
        foreach ($chunks as $chunkLinks) {
            $userPrompt = "Generate SEO-optimized anchor texts for these internal links:\n";
            foreach ($chunkLinks as $idx => $link) {
                $userPrompt .= ($idx + 1) . ". Source Article: '{$link['source']->target_keyword}' ---> Target Article: '{$link['target']->target_keyword}'\n";
            }

            $aiAnchors = $aiService->generateJson($systemPrompt, $userPrompt);
            $useAi = (is_array($aiAnchors) && count($aiAnchors) === count($chunkLinks));

            foreach ($chunkLinks as $idx => $link) {
                $anchorText = $useAi ? $aiAnchors[$idx] : $link['target']->target_keyword;
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
