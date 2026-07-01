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
            return redirect()->back()->with('error', 'Silo does not have a Pillar.');
        }

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
            // Wajib ke Pillar
            $addLink($clusterA, $pillar);
            
            // Ke Sub-cluster miliknya
            $itsSubs = $subClusters->where('parent_id', $clusterA->id);
            foreach ($itsSubs as $sub) {
                $addLink($clusterA, $sub);
            }
        }

        // 3. SUB-CLUSTER -> Cluster Induk, Pillar, & Sesama Sub-cluster (1 Induk)
        foreach ($subClusters as $subA) {
            // Ke parent cluster
            $parentCluster = $clusters->where('id', $subA->parent_id)->first();
            if ($parentCluster) {
                $addLink($subA, $parentCluster);
            }
            
            // Ke Pillar (Grandparent)
            $addLink($subA, $pillar);
            
            // Ke sesama sub-cluster (siblings)
            $siblings = $subClusters->where('parent_id', $subA->parent_id)->where('id', '!=', $subA->id);
            foreach ($siblings as $sibling) {
                $addLink($subA, $sibling);
            }
        }

        // Wipe old links to cleanly replace
        $contentIds = $silo->contents()->pluck('id');
        DeterministicLink::whereIn('source_content_id', $contentIds)->delete();

        foreach ($plannedLinks as $idx => $link) {
            DeterministicLink::create([
                'source_content_id' => $link['source']->id,
                'target_content_id' => $link['target']->id,
                'mandatory_anchor_text' => '[PENDING_AI]', // empty state for AI
                'is_injected_successfully' => false
            ]);
        }

        return redirect()->route('admin.links.process_ai_view', ['silo_id' => $silo->id]);
    }

    public function processAiView(Request $request)
    {
        $siloId = $request->get('silo_id');
        $silo = SiloBlueprint::findOrFail($siloId);
        
        $contentIds = $silo->contents()->pluck('id');
        
        // Count how many links need processing
        $pendingCount = DeterministicLink::whereIn('source_content_id', $contentIds)
            ->where('mandatory_anchor_text', '[PENDING_AI]')
            ->count();
            
        $totalCount = DeterministicLink::whereIn('source_content_id', $contentIds)->count();

        if ($pendingCount === 0) {
            return redirect()->route('admin.links.index', ['silo_id' => $siloId])
                ->with('success', 'All AI anchors have been processed successfully!');
        }

        return view('admin.links.process_ai', compact('silo', 'pendingCount', 'totalCount'));
    }

    public function processAiChunk(Request $request)
    {
        $siloId = $request->input('silo_id');
        $silo = SiloBlueprint::findOrFail($siloId);
        $contentIds = $silo->contents()->pluck('id');
        
        // Get up to 5 links that need processing
        $links = DeterministicLink::with(['source', 'target'])
            ->whereIn('source_content_id', $contentIds)
            ->where('mandatory_anchor_text', '[PENDING_AI]')
            ->orderBy('target_content_id')
            ->limit(5)
            ->get();
            
        if ($links->isEmpty()) {
            return response()->json(['status' => 'done']);
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

        $userPrompt = "Generate SEO-optimized anchor texts for these internal links:\n";
        foreach ($links as $idx => $link) {
            $userPrompt .= ($idx + 1) . ". Source Article: '{$link->source->target_keyword}' ---> Target Article: '{$link->target->target_keyword}'\n";
        }
        
        $aiAnchors = $aiService->generateJson($systemPrompt, $userPrompt);
        
        if (is_array($aiAnchors) && count($aiAnchors) === $links->count()) {
            foreach ($links as $idx => $link) {
                $aiText = $aiAnchors[$idx];
                $anchorText = trim(strip_tags($aiText), "\"' ");
                
                $link->update([
                    'mandatory_anchor_text' => !empty($anchorText) ? $anchorText : $link->target->target_keyword
                ]);
            }
        } else {
            // If AI fails, use fallback to avoid getting stuck in a loop
            foreach ($links as $link) {
                $link->update([
                    'mandatory_anchor_text' => $link->target->target_keyword
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
