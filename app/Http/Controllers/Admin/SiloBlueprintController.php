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

        $pillarSlug = \Illuminate\Support\Str::slug($pillarKeyword);

        Content::create([
            'tenant_id'         => $silo->tenant_id ?? (\App\Models\Tenant::first()?->id ?? 1),
            'silo_blueprint_id' => $silo->id,
            'parent_id'         => null,
            'target_keyword'    => $pillarKeyword,
            'slug'              => json_encode(['id' => $pillarSlug]),
            'hierarchy_level'   => 'pillar',
            'search_volume'     => 1000,
            'kgr_score'         => 0.85,
            'status'            => 'blueprint'
        ]);

        // Update total
        $silo->update(['total_contents' => $silo->contents()->count()]);

        return redirect()->back()->with('success', 'Pillar Page keyword generated successfully!');
    }

    /**
     * Step 2: Generate Cluster Keywords from Pillar.
     */
    public function generateClusters(SiloBlueprint $silo, Content $content)
    {
        if ($content->hierarchy_level !== 'pillar') {
            return redirect()->back()->with('error', 'Invalid content level. Clusters can only be generated from a Pillar Page.');
        }

        // Check if clusters already generated
        if ($silo->contents()->where('hierarchy_level', 'cluster')->where('parent_id', $content->id)->exists()) {
            return redirect()->back()->with('error', 'Clusters already generated for this Pillar.');
        }

        $aiService = new AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "You are an expert SEO architect. Given a Pillar Page target keyword, generate exactly 3 to 5 highly relevant cluster topics (supporting sub-topics) that fit a SILO architecture. Return the results as a raw JSON array of strings, e.g. [\"Topic 1\", \"Topic 2\", \"Topic 3\"]. Return ONLY valid JSON.";
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

            $clusterSlug = \Illuminate\Support\Str::slug($clusterText);
            Content::create([
                'tenant_id'         => $silo->tenant_id ?? (\App\Models\Tenant::first()?->id ?? 1),
                'silo_blueprint_id' => $silo->id,
                'parent_id'         => $content->id,
                'target_keyword'    => $clusterText,
                'slug'              => json_encode(['id' => $clusterSlug]),
                'hierarchy_level'   => 'cluster',
                'search_volume'     => 450,
                'kgr_score'         => 0.35,
                'status'            => 'blueprint'
            ]);
        }

        $silo->update(['total_contents' => $silo->contents()->count()]);

        return redirect()->back()->with('success', 'Cluster keywords generated successfully!');
    }

    /**
     * Step 3: Generate Sub-cluster Keywords from Cluster.
     */
    public function generateSubClusters(SiloBlueprint $silo, Content $content)
    {
        if ($content->hierarchy_level !== 'cluster') {
            return redirect()->back()->with('error', 'Invalid content level. Sub-clusters can only be generated from a Cluster.');
        }

        // Check if sub-clusters already generated
        if ($silo->contents()->where('hierarchy_level', 'sub_cluster')->where('parent_id', $content->id)->exists()) {
            return redirect()->back()->with('error', 'Sub-clusters already generated for this Cluster.');
        }

        $aiService = new AIService($silo->tenant ?? \App\Models\Tenant::first(), 'keyword');
        $systemPrompt = "You are an expert SEO architect. Given a Cluster Topic keyword, generate exactly 3 to 5 highly specific long-tail keywords or sub-cluster topics (low competition, high search intent) supporting it. Return the results as a raw JSON array of strings, e.g. [\"Subtopic 1\", \"Subtopic 2\", \"Subtopic 3\"]. Return ONLY valid JSON.";
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

            $subSlug = \Illuminate\Support\Str::slug($subText);
            Content::create([
                'tenant_id'         => $silo->tenant_id ?? (\App\Models\Tenant::first()?->id ?? 1),
                'silo_blueprint_id' => $silo->id,
                'parent_id'         => $content->id,
                'target_keyword'    => $subText,
                'slug'              => json_encode(['id' => $subSlug]),
                'hierarchy_level'   => 'sub_cluster',
                'search_volume'     => 120,
                'kgr_score'         => 0.12,
                'status'            => 'blueprint'
            ]);
        }

        $silo->update(['total_contents' => $silo->contents()->count()]);

        return redirect()->back()->with('success', 'Sub-cluster keywords generated successfully!');
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
