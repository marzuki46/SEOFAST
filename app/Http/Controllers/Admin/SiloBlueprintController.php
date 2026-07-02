<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiloBlueprint;
use App\Models\Content;
use App\Services\AIService;
use App\Services\Seo\InternalLinkingService;
use Illuminate\Http\Request;

class SiloBlueprintController extends Controller
{
    /**
     * Display a listing of the Silo Blueprints.
     */
    public function index()
    {
        $silos = SiloBlueprint::withoutGlobalScopes()
            ->withCount('contents')
            ->latest()
            ->get();

        return view('admin.silo.index', compact('silos'));
    }

    /**
     * Store a newly created Silo Blueprint.
     */
    public function store(Request $request)
    {
        $request->validate([
            'silo_name' => 'required|string|max:255',
            'seed_keyword' => 'required|string|max:255',
            'target_language' => 'required|string|size:2',
            'target_country' => 'required|string|size:2',
        ]);

        $silo = SiloBlueprint::create([
            'tenant_id' => \App\Models\Tenant::first()?->id ?? 1,
            'silo_name' => $request->silo_name,
            'seed_keyword' => $request->seed_keyword,
            'target_language' => $request->target_language,
            'target_country' => $request->target_country,
            'is_locked' => false,
            'total_contents' => 0,
            'published_contents' => 0,
        ]);

        return redirect()->route('admin.silo.show', $silo->id)
            ->with('success', 'Topical Silo created! Now you can start generating your silo structure step-by-step.');
    }

    /**
     * Display the specified Silo Blueprint and its mapped contents.
     */
    public function show(SiloBlueprint $siloBlueprint)
    {
        // Load contents ordered by hierarchy level and target keyword
        $contents = $siloBlueprint->contents()->latest()->get();

        return view('admin.silo.show', ['silo' => $siloBlueprint, 'contents' => $contents]);
    }

    /**
     * Step 1: Generate Pillar Page keyword.
     */
    public function generatePillar(SiloBlueprint $silo)
    {
        // Check if pillar already exists
        if ($silo->contents()->where('hierarchy_level', 'pillar')->exists()) {
            return redirect()->back()->with('error', 'Pillar page already exists for this Silo.');
        }

        $aiService = new AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "You are an SEO expert. Given a seed keyword, return a highly optimized broad target keyword suitable for a Pillar Page (broad, high traffic, informational/commercial hub). Keep it concise. Return ONLY the keyword string, nothing else.";
        $userPrompt = "Seed: " . $silo->seed_keyword . "\nLanguage: " . $silo->target_language . "\nCountry: " . $silo->target_country;

        $pillarKeyword = trim($aiService->generate($systemPrompt, $userPrompt) ?? $silo->seed_keyword);
        $pillarKeyword = trim(strip_tags($pillarKeyword), "\"' ");

        Content::create([
            'tenant_id'         => $silo->tenant_id ?? (\App\Models\Tenant::first()?->id ?? 1),
            'silo_blueprint_id' => $silo->id,
            'parent_id'         => null,
            'target_keyword'    => $pillarKeyword,
            'slug'              => 'idea-' . \Illuminate\Support\Str::uuid(), // Temp unique slug since column cannot be null
            'hierarchy_level'   => 'pillar',
            'search_volume'     => 1000,
            'kgr_score'         => 0.85,
            'status'            => 'idea'
        ]);

        // Update total
        $silo->update(['total_contents' => $silo->contents()->count()]);

        return redirect()->back()->with('success', 'Pillar Page keyword generated! Please approve it to continue.');
    }

    /**
     * Step 2: Generate Cluster Keywords from Pillar.
     */
    public function generateClusters(SiloBlueprint $silo, Content $content)
    {
        if ($content->hierarchy_level !== 'pillar') {
            return redirect()->back()->with('error', 'Invalid content level. Clusters can only be generated from a Pillar Page.');
        }

        $existingClusters = $silo->contents()->where('hierarchy_level', 'cluster')->where('parent_id', $content->id)->pluck('target_keyword')->toArray();
        $existingText = count($existingClusters) > 0 ? "Daftar cluster yang sudah ada: [" . implode(', ', $existingClusters) . "]. Tolong buatkan ide cluster BARU yang tidak tumpang tindih dengan daftar tersebut." : "";

        $aiService = new AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "You are an expert SEO architect. Given a Pillar Page target keyword, generate exactly 3 to 5 highly relevant cluster topics (supporting sub-topics) that fit a SILO architecture. {$existingText} Return the results as a raw JSON array of strings, e.g. [\"Topic 1\", \"Topic 2\", \"Topic 3\"]. Return ONLY valid JSON.";
        $userPrompt = "Pillar: " . $content->target_keyword . "\nLanguage: " . $silo->target_language . "\nCountry: " . $silo->target_country;

        $clusters = $aiService->generateJson($systemPrompt, $userPrompt);

        if (!$clusters || !is_array($clusters)) {
            $errorMsg = 'Failed to generate cluster keywords. Please try again.';
            if (session('ai_error')) {
                $errorMsg .= ' Detail error: ' . session('ai_error');
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        foreach ($clusters as $clusterText) {
            $clusterText = trim(strip_tags($clusterText), "\"' ");
            if (empty($clusterText)) continue;
            
            // double check to prevent duplicate insertion
            if (in_array(strtolower($clusterText), array_map('strtolower', $existingClusters))) continue;

            Content::create([
                'tenant_id'         => $silo->tenant_id ?? (\App\Models\Tenant::first()?->id ?? 1),
                'silo_blueprint_id' => $silo->id,
                'parent_id'         => $content->id,
                'target_keyword'    => $clusterText,
                'slug'              => 'idea-' . \Illuminate\Support\Str::uuid(), // Temp unique slug since column cannot be null
                'hierarchy_level'   => 'cluster',
                'search_volume'     => 450,
                'kgr_score'         => 0.35,
                'status'            => 'idea'
            ]);
        }

        $silo->update(['total_contents' => $silo->contents()->count()]);

        return redirect()->back()->with('success', 'Cluster keywords generated successfully! Please approve them.');
    }

    /**
     * Step 3: Generate Sub-cluster Keywords from Cluster.
     */
    public function generateSubClusters(SiloBlueprint $silo, Content $content)
    {
        if ($content->hierarchy_level !== 'cluster') {
            return redirect()->back()->with('error', 'Invalid content level. Sub-clusters can only be generated from a Cluster.');
        }

        $existingSubs = $silo->contents()->where('hierarchy_level', 'sub_cluster')->where('parent_id', $content->id)->pluck('target_keyword')->toArray();
        $existingText = count($existingSubs) > 0 ? "Daftar sub-cluster yang sudah ada: [" . implode(', ', $existingSubs) . "]. Tolong buatkan ide sub-cluster BARU yang tidak tumpang tindih dengan daftar tersebut." : "";


        $aiService = new AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "You are an expert SEO architect. Given a Cluster Topic keyword, generate exactly 3 to 5 highly specific long-tail keywords or sub-cluster topics (low competition, high search intent) supporting it. {$existingText} Return the results as a raw JSON array of strings, e.g. [\"Subtopic 1\", \"Subtopic 2\", \"Subtopic 3\"]. Return ONLY valid JSON.";
        $userPrompt = "Cluster: " . $content->target_keyword . "\nLanguage: " . $silo->target_language . "\nCountry: " . $silo->target_country;

        $subClusters = $aiService->generateJson($systemPrompt, $userPrompt);

        if (!$subClusters || !is_array($subClusters)) {
            $errorMsg = 'Failed to generate sub-cluster keywords. Please try again.';
            if (session('ai_error')) {
                $errorMsg .= ' Detail error: ' . session('ai_error');
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        foreach ($subClusters as $subText) {
            $subText = trim(strip_tags($subText), "\"' ");
            if (empty($subText)) continue;
            
            if (in_array(strtolower($subText), array_map('strtolower', $existingSubs))) continue;

            Content::create([
                'tenant_id'         => $silo->tenant_id ?? (\App\Models\Tenant::first()?->id ?? 1),
                'silo_blueprint_id' => $silo->id,
                'parent_id'         => $content->id,
                'target_keyword'    => $subText,
                'slug'              => 'idea-' . \Illuminate\Support\Str::uuid(), // Temp unique slug since column cannot be null
                'hierarchy_level'   => 'sub_cluster',
                'search_volume'     => 120,
                'kgr_score'         => 0.12,
                'status'            => 'idea'
            ]);
        }

        $silo->update(['total_contents' => $silo->contents()->count()]);

        return redirect()->back()->with('success', 'Sub-cluster keywords generated successfully! Please approve them.');
    }

    /**
     * Map Silo Internal Links.
     */
    public function mapInternalLinks(SiloBlueprint $silo)
    {
        $linker = new InternalLinkingService();
        $linker->mapLinksForSilo($silo);

        return redirect()->back()->with('success', 'Silo Internal Links mapped successfully! Your contents will now automatically cross-link inside their chambers.');
    }

    public function approveContent(SiloBlueprint $silo, Content $content)
    {
        if ($content->silo_blueprint_id !== $silo->id || $content->status !== 'idea') {
            return back()->with('error', 'Invalid content for approval.');
        }
        $slug = \Illuminate\Support\Str::slug($content->target_keyword);
        
        // Ensure unique slug
        $baseSlug = $slug;
        $counter = 1;
        while (\App\Models\Content::withoutGlobalScopes()->where(function($q) use ($slug) {
            $q->where('slug', $slug)->orWhere('slug', 'LIKE', '%"id":"'.$slug.'"%');
        })->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }
        
        $content->update([
            'slug' => $slug,
            'status' => 'blueprint'
        ]);
        
        return back()->with('success', 'Keyword approved! URL has been generated.');
    }
    
    public function deleteContent(SiloBlueprint $silo, Content $content)
    {
        if ($content->silo_blueprint_id !== $silo->id) {
            return back()->with('error', 'Invalid content.');
        }
        $content->delete();
        $silo->update(['total_contents' => $silo->contents()->count()]);
        return back()->with('success', 'Keyword removed.');
    }

    public function regenerateContent(SiloBlueprint $silo, Content $content)
    {
        if ($content->silo_blueprint_id !== $silo->id) {
            return back()->with('error', 'Invalid content.');
        }
        $content->update([
            'status' => 'blueprint',
            'body_raw' => null,
            'rendered_html_path' => null,
        ]);
        return back()->with('success', 'Status dikembalikan ke antrean (Blueprint). Silakan klik "Proses Cluster Ini" untuk mulai membuat ulang artikel beserta update link-nya.');
    }
    public function processCluster(SiloBlueprint $silo, Content $content)
    {
        if ($content->hierarchy_level !== 'cluster') {
            return back()->with('error', 'Can only process from cluster level.');
        }
        
        // Check if pillar is approved
        $pillar = $silo->contents()->where('hierarchy_level', 'pillar')->first();
        if (!$pillar || $pillar->status === 'idea') {
            return back()->with('error', 'Harap proses/approve artikel Pillar terlebih dahulu!');
        }
        
        $idsToProcess = [];
        $processableStatuses = ['blueprint', 'draft', 'failed_cqi', 'failed'];
        
        if (in_array($pillar->status, $processableStatuses)) {
            $idsToProcess[] = $pillar->id;
        }
        if (in_array($content->status, $processableStatuses)) {
            $idsToProcess[] = $content->id;
        }
        
        $subs = $silo->contents()->where('hierarchy_level', 'sub_cluster')->where('parent_id', $content->id)->get();
        foreach($subs as $sub) {
            if (in_array($sub->status, $processableStatuses)) {
                $idsToProcess[] = $sub->id;
            }
        }
        
        if (empty($idsToProcess)) {
            return redirect()->route('admin.links.index', ['silo_id' => $silo->id, 'cluster_id' => $content->id])
                ->with('info', 'Semua konten di cluster ini sudah masuk antrean aktif. Silakan kelola Internal Link.');
        }
        
        $tenantId = \App\Models\Tenant::first()?->id ?? 1;
        $queuedCount = 0;
        foreach($idsToProcess as $cId) {
            $c = \App\Models\Content::withoutGlobalScopes()->find($cId);
            $alreadyActive = \App\Models\AiGenerationJob::withoutGlobalScopes()
                ->where('content_id', $cId)
                ->whereIn('status', ['pending', 'processing', 'phase_1', 'phase_2', 'phase_3', 'phase_4', 'phase_5', 'phase_6', 'phase_7'])
                ->exists();
            if ($alreadyActive) continue;
            
            $job = \App\Models\AiGenerationJob::withoutGlobalScopes()
                ->where('content_id', $cId)
                ->whereIn('status', ['failed', 'failed_cqi', 'completed'])
                ->first();
            if ($job) {
                $job->update(['status' => 'pending', 'retry_count' => $job->retry_count + 1]);
            } else {
                \App\Models\AiGenerationJob::create([
                    'tenant_id' => $tenantId, 'content_id' => $cId, 'job_type' => 'initial_generation', 'status' => 'pending', 'retry_count' => 0
                ]);
            }
            $c->update(['status' => 'ai_processing']);
            $queuedCount++;
        }
        
        if ($queuedCount > 0) {
            return redirect()->route('admin.links.index', ['silo_id' => $silo->id, 'cluster_id' => $content->id])
                ->with('success', "Berhasil mendaftarkan {$queuedCount} artikel (Pillar + Cluster + Sub-cluster) ke antrean. Silakan tentukan Anchor Link internal untuk cluster ini terlebih dahulu.");
        }
        
        // Tetap redirect ke halaman links meskipun sudah masuk antrean, agar user bisa melanjutkan mapping link
        return redirect()->route('admin.links.index', ['silo_id' => $silo->id, 'cluster_id' => $content->id])
            ->with('info', 'Semua konten terkait sudah berada di dalam antrean aktif. Silakan lanjutkan pengaturan Anchor Link.');
    }

    /**
     * Remove the specified Silo Blueprint.
     */
    public function destroy(SiloBlueprint $silo)
    {
        $silo->delete();

        return redirect()->route('admin.silo.index')
            ->with('success', 'Silo Blueprint deleted successfully.');
    }
}
