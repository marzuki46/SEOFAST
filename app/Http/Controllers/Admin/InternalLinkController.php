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
        $selectedCluster = $request->get('cluster_id');
        
        $contents = collect();
        $links = collect();

        if ($selectedSilo) {
            $query = Content::withoutGlobalScopes()->where('silo_blueprint_id', $selectedSilo);
            
            if ($selectedCluster) {
                // Filter specifically for this Cluster and its Sub-clusters
                $clusterContents = Content::withoutGlobalScopes()
                    ->where('id', $selectedCluster) // The Cluster
                    ->orWhere('parent_id', $selectedCluster) // The Sub-clusters
                    ->pluck('id');
                    
                $query->whereIn('id', $clusterContents);
            }
            
            $contents = $query->get();
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
            return redirect()->back()->with('error', 'Silo does not have a Pillar.');
        }

        $clusterId = $request->cluster_id;
        
        $clustersQuery = $silo->contents()->where('hierarchy_level', 'cluster');
        $subClustersQuery = $silo->contents()->where('hierarchy_level', 'sub_cluster');
        
        if ($clusterId) {
            $clustersQuery->where('id', $clusterId);
            $subClustersQuery->where('parent_id', $clusterId);
        }
        
        $clusters = $clustersQuery->get();
        $subClusters = $subClustersQuery->get();
        
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

        // 2. CLUSTER -> Pillar (Wajib) & Sub-Cluster miliknya (Max 2)
        foreach ($clusters as $clusterA) {
            // Wajib ke Pillar
            $addLink($clusterA, $pillar);
            
            // Ke Sub-cluster miliknya (maksimal 2 secara random/urutan)
            $itsSubs = $subClusters->where('parent_id', $clusterA->id)->take(2);
            foreach ($itsSubs as $sub) {
                $addLink($clusterA, $sub);
            }
        }

        // 3. SUB-CLUSTER -> Cluster Induk, Pillar, & Sesama Sub-cluster (Max 2)
        foreach ($subClusters as $subA) {
            // Ke parent cluster
            $parentCluster = $clusters->where('id', $subA->parent_id)->first();
            if ($parentCluster) {
                $addLink($subA, $parentCluster);
            }
            
            // Ke Pillar (Grandparent)
            $addLink($subA, $pillar);
            
            // Ke sesama sub-cluster (siblings) dalam 1 cluster (maksimal 2)
            $siblings = $subClusters->where('parent_id', $subA->parent_id)
                                    ->where('id', '!=', $subA->id)
                                    ->take(2);
            foreach ($siblings as $sibling) {
                $addLink($subA, $sibling);
            }
        }

        // We DO NOT wipe old links! We only create [PENDING_AI] for links that DO NOT exist yet.
        // This protects manually edited anchors or previously generated ones.
        foreach ($plannedLinks as $idx => $link) {
            DeterministicLink::firstOrCreate(
                [
                    'source_content_id' => $link['source']->id,
                    'target_content_id' => $link['target']->id,
                ],
                [
                    'mandatory_anchor_text' => '[PENDING_AI]',
                    'is_injected_successfully' => false
                ]
            );
        }

        return redirect()->route('admin.links.process_ai_view', [
            'silo_id' => $silo->id,
            'cluster_id' => $request->cluster_id
        ]);
    }

    public function processAiView(Request $request)
    {
        $siloId = $request->get('silo_id');
        $clusterId = $request->get('cluster_id');
        $silo = SiloBlueprint::findOrFail($siloId);
        
        $query = $silo->contents();
        if ($clusterId) {
            $query->where(function($q) use ($clusterId) {
                $q->where('id', $clusterId)
                  ->orWhere('parent_id', $clusterId);
            });
        }
        $contentIds = $query->pluck('id');
        
        // Count how many links need processing
        $pendingCount = DeterministicLink::whereIn('source_content_id', $contentIds)
            ->where('mandatory_anchor_text', '[PENDING_AI]')
            ->count();
            
        $totalCount = DeterministicLink::whereIn('source_content_id', $contentIds)->count();

        if ($pendingCount === 0) {
            return redirect()->route('admin.links.index', [
                'silo_id' => $siloId,
                'cluster_id' => $clusterId
            ])->with('success', 'All AI anchors have been processed successfully!');
        }

        return view('admin.links.process_ai', compact('silo', 'pendingCount', 'totalCount', 'clusterId'));
    }

    public function processAiChunk(Request $request)
    {
        $siloId = $request->input('silo_id');
        $clusterId = $request->input('cluster_id');
        $silo = SiloBlueprint::findOrFail($siloId);
        
        $query = $silo->contents();
        if ($clusterId) {
            $query->where(function($q) use ($clusterId) {
                $q->where('id', $clusterId)
                  ->orWhere('parent_id', $clusterId);
            });
        }
        $contentIds = $query->pluck('id');
        
        // Ambil SATU target_content_id yang masih punya status PENDING_AI
        $firstPendingLink = DeterministicLink::whereIn('source_content_id', $contentIds)
            ->where('mandatory_anchor_text', '[PENDING_AI]')
            ->orderBy('target_content_id')
            ->first();
            
        if (!$firstPendingLink) {
            return response()->json(['status' => 'done']);
        }
        
        $targetId = $firstPendingLink->target_content_id;
        
        // Ambil SEMUA link yang mengarah ke target_content_id ini dan statusnya PENDING_AI
        $links = DeterministicLink::with(['source', 'target'])
            ->whereIn('source_content_id', $contentIds)
            ->where('target_content_id', $targetId)
            ->where('mandatory_anchor_text', '[PENDING_AI]')
            ->get();
        
        $aiService = new \App\Services\AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "Anda adalah Pakar SEO Internal Linking. Tugas Anda adalah mencari anchor text yang bervariasi untuk SEBUAH artikel target agar tidak terindikasi spam (keyword cannibalization).
ATURAN MUTLAK:
1. Jika diminta 3 anchor, berikan EXACTLY 3 anchor.
2. Kombinasikan: 1 Exact Match (judul target), sisanya Partial Match (frasa turunan/sinonim) atau Long Tail (kalimat panjang natural).
3. Return ONLY a JSON array of strings, e.g. [\"Anchor 1\", \"Anchor 2\", \"Anchor 3\"]. NO MARKDOWN, NO EXTRA TEXT.";

        $targetKeyword = $links->first()->target->target_keyword;
        $count = $links->count();
        
        $userPrompt = "Target Article Keyword: '{$targetKeyword}'\n";
        $userPrompt .= "Tolong berikan {$count} variasi anchor text yang unik dan berbeda satu sama lain untuk artikel target di atas.";
        
        $aiAnchors = $aiService->generateJson($systemPrompt, $userPrompt);
        
        if (is_array($aiAnchors) && count($aiAnchors) >= $count) {
            foreach ($links as $idx => $link) {
                $aiText = $aiAnchors[$idx];
                $anchorText = trim(strip_tags($aiText), "\"' ");
                
                $link->update([
                    'mandatory_anchor_text' => !empty($anchorText) ? $anchorText : $link->target->target_keyword
                ]);
            }
        } else {
            // If AI fails, use fallback
            foreach ($links as $idx => $link) {
                $link->update([
                    'mandatory_anchor_text' => $link->target->target_keyword . ($idx > 0 ? ' bagian ' . ($idx + 1) : '')
                ]);
            }
            return response()->json(['status' => 'error_fallback']);
        }
        
        $remaining = DeterministicLink::whereIn('source_content_id', $contentIds)
            ->where('mandatory_anchor_text', '[PENDING_AI]')
            ->count();
            
            return response()->json([
            'status' => 'continue',
            'remaining' => $remaining
        ]);
    }

    public function update(Request $request, DeterministicLink $link)
    {
        $request->validate([
            'mandatory_anchor_text' => 'required|string|max:255',
        ]);

        $link->update([
            'mandatory_anchor_text' => $request->mandatory_anchor_text
        ]);

        return response()->json(['success' => true]);
    }
}
