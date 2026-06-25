<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiloBlueprint;
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
            'tenant_id' => 0, // Fallback as it is single ownership now
            'silo_name' => $request->silo_name,
            'seed_keyword' => $request->seed_keyword,
            'target_language' => $request->target_language,
            'target_country' => $request->target_country,
            'is_locked' => false,
            'total_contents' => 0,
            'published_contents' => 0,
        ]);

        \App\Jobs\Ai\GenerateKeywordClusterJob::dispatch($silo);

        return redirect()->route('admin.silo.index')
            ->with('success', 'Topical Silo created successfully! AI is now generating clusters in the background.');
    }

    /**
     * Display the specified Silo Blueprint and its mapped contents.
     */
    public function show(SiloBlueprint $siloBlueprint)
    {
        $contents = $siloBlueprint->contents()->latest()->get();

        return view('admin.silo.show', ['silo' => $siloBlueprint, 'contents' => $contents]);
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
