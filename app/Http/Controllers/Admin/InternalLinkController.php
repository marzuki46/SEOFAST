<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\DeterministicLink;
use App\Models\SiloBlueprint;
use Illuminate\Http\Request;

class InternalLinkController extends Controller
{
    public function index(Request $request)
    {
        $silos = SiloBlueprint::withoutGlobalScopes()->get();
        
        $selectedSilo = $request->get('silo_id', $silos->first()->id ?? null);
        
        $contents = collect();
        $links = collect();

        if ($selectedSilo) {
            $contents = Content::withoutGlobalScopes()->where('silo_blueprint_id', $selectedSilo)->get();
            $links = DeterministicLink::withoutGlobalScopes()
                ->whereIn('source_content_id', $contents->pluck('id'))
                ->with(['source', 'target'])
                ->get();
        }

        return view('admin.links.index', compact('silos', 'selectedSilo', 'contents', 'links'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'source_content_id' => 'required|exists:contents,id',
            'target_content_id' => 'required|exists:contents,id|different:source_content_id',
            'anchor_text' => 'required|string|max:255',
        ]);

        DeterministicLink::create([
            'source_content_id' => $request->source_content_id,
            'target_content_id' => $request->target_content_id,
            'mandatory_anchor_text' => $request->anchor_text,
            'is_injected_successfully' => false,
        ]);

        return redirect()->back()->with('success', 'Internal Link mapped successfully!');
    }

    public function generateAi(Request $request)
    {
        $request->validate(['silo_id' => 'required|exists:silo_blueprints,id']);
        
        $silo = SiloBlueprint::findOrFail($request->silo_id);
        $pillar = $silo->contents()->where('hierarchy_level', 'pillar')->first();
        
        if (!$pillar) {
            return redirect()->back()->with('error', 'Silo does not have a Pillar (Kategori Utama).');
        }

        $clusters = $silo->contents()->where('hierarchy_level', 'cluster')->get();
        $subClusters = $silo->contents()->where('hierarchy_level', 'sub_cluster')->get();
        
        $plannedLinks = [];
        $linksPerSource = [];

        $addLink = function($source, $target) use (&$plannedLinks, &$linksPerSource) {
            $sid = $source->id;
            if (!isset($linksPerSource[$sid])) {
                $linksPerSource[$sid] = 0;
            }
            if ($linksPerSource[$sid] < 3) {
                $plannedLinks[] = ['source' => $source, 'target' => $target];
                $linksPerSource[$sid]++;
            }
        };

        // 1. Kategori Utama (Pillar) -> links to Sub Cluster 
        $shuffledSubs = $subClusters->shuffle();
        foreach ($shuffledSubs as $sub) {
            $addLink($pillar, $sub);
        }

        // 2. Child (Cluster) -> sesama child (other clusters), sub_cluster
        foreach ($clusters as $clusterA) {
            $targets = collect();
            
            // to other clusters
            foreach ($clusters as $clusterB) {
                if ($clusterA->id !== $clusterB->id) {
                    $targets->push($clusterB);
                }
            }
            
            // to its sub_clusters
            $itsSubs = $subClusters->where('parent_id', $clusterA->id);
            foreach ($itsSubs as $sub) {
                $targets->push($sub);
            }
            
            $targets = $targets->shuffle();
            foreach ($targets as $tgt) {
                $addLink($clusterA, $tgt);
            }
        }

        // 3. Sub Cluster -> child (cluster_utama/parent), antar sub_cluster
        foreach ($subClusters as $subA) {
            $targets = collect();
            
            // to its parent cluster
            $parentCluster = $clusters->where('id', $subA->parent_id)->first();
            if ($parentCluster) {
                $targets->push($parentCluster);
            }
            
            // to other sub_clusters (siblings only)
            $siblings = $subClusters->where('parent_id', $subA->parent_id);
            foreach ($siblings as $subB) {
                if ($subA->id !== $subB->id) {
                    $targets->push($subB);
                }
            }
            
            $targets = $targets->shuffle();
            foreach ($targets as $tgt) {
                $addLink($subA, $tgt);
            }
        }

        if (empty($plannedLinks)) {
            return redirect()->back()->with('error', 'No internal links can be mapped with the current silo contents.');
        }

        // Ask AI
        $aiService = new \App\Services\AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "You are an expert SEO internal linking architect. Generate natural, contextually relevant, High-CTR anchor texts for internal links. DO NOT use the exact target keyword as the anchor text. Use compelling, click-worthy phrasing. Return a JSON array of strings corresponding to the given link pairs in the EXACT SAME ORDER. RETURN ONLY RAW VALID JSON ARRAY OF STRINGS.";

        $userPrompt = "Generate High-CTR anchor texts for the following internal links:\n";
        foreach ($plannedLinks as $idx => $link) {
            $userPrompt .= ($idx + 1) . ". From article '{$link['source']->target_keyword}' to article '{$link['target']->target_keyword}'\n";
        }

        $aiAnchors = $aiService->generateJson($systemPrompt, $userPrompt);
        $useAi = (is_array($aiAnchors) && count($aiAnchors) === count($plannedLinks));

        // Wipe old links to cleanly replace
        $contentIds = $silo->contents()->pluck('id');
        DeterministicLink::whereIn('source_content_id', $contentIds)->delete();

        foreach ($plannedLinks as $idx => $link) {
            // Fallback to exact target keyword if AI fails to return correct format
            $anchorText = $useAi ? $aiAnchors[$idx] : $link['target']->target_keyword;
            $anchorText = trim(strip_tags($anchorText), "\"' ");

            DeterministicLink::create([
                'source_content_id' => $link['source']->id,
                'target_content_id' => $link['target']->id,
                'mandatory_anchor_text' => $anchorText,
                'is_injected_successfully' => false
            ]);
        }

        if (!$useAi) {
            return redirect()->back()->with('error', 'AI failed to generate anchors properly. Fallback applied (max 3 links per article). Please try again or check AI configuration.');
        }

        return redirect()->back()->with('success', 'High-CTR Anchor Texts successfully generated by AI (max 3 links per article)!');
    }
}
