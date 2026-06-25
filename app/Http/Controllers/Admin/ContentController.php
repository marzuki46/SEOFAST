<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\SiloBlueprint;
use App\Models\AiGenerationJob;
use App\Jobs\Ai\ProcessAiGenerationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contents = Content::withoutGlobalScopes()
            ->with(['siloBlueprint', 'latestUrlInspection'])
            ->latest()
            ->get();

        return view('admin.content.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource (Write with AI).
     */
    public function create()
    {
        $siloBlueprints = SiloBlueprint::withoutGlobalScopes()->get();

        // Seed a sample silo blueprint if none exists so the user has data to pick from
        if ($siloBlueprints->isEmpty()) {
            $silo = SiloBlueprint::create([
                'tenant_id' => 0,
                'silo_name' => 'SEO Optimization Silo',
                'seed_keyword' => 'seo tips',
                'target_language' => 'id',
                'target_country' => 'ID',
                'is_locked' => false,
                'total_contents' => 0,
                'published_contents' => 0,
            ]);
            $siloBlueprints = collect([$silo]);
        }

        return view('admin.content.create', compact('siloBlueprints'));
    }

    /**
     * Store a newly created resource in storage (Dispatch AI Generator).
     */
    public function store(Request $request)
    {
        $request->validate([
            'target_keyword' => 'required|string|max:255',
            'hierarchy_level' => 'required|string|in:pillar,cluster,sub_cluster',
            'silo_blueprint_id' => 'required|exists:silo_blueprints,id',
        ]);

        $slug = Str::slug($request->target_keyword);

        // Check for duplicate slugs
        $exists = Content::withoutGlobalScopes()->where("slug->id", $slug)->exists();
        if ($exists) {
            $slug .= '-' . rand(100, 999);
        }

        $generateAi = $request->has('generate_ai');

        // Create Content model
        $content = Content::create([
            'tenant_id'        => 0,
            'silo_blueprint_id' => $request->silo_blueprint_id,
            'target_keyword'   => $request->target_keyword,
            'slug'             => json_encode(['id' => $slug]),
            'meta_title'       => ucwords($request->target_keyword) . ' - SEOFAST',
            'meta_description' => 'Panduan taktis terperinci mengenai ' . $request->target_keyword . ' untuk optimasi mesin pencari.',
            'hierarchy_level' => $request->hierarchy_level,
            'status' => $generateAi ? 'ai_processing' : 'blueprint',
        ]);

        // Calculate crawl priority score: pillar gets highest, cluster medium, sub_cluster lowest
        $hierarchyWeight = match($request->hierarchy_level) {
            'pillar' => 1.0,
            'cluster' => 0.6,
            'sub_cluster' => 0.3,
            default => 0.3,
        };
        $searchVolume = (int) $request->get('search_volume', 0);
        $searchVolumeWeight = min($searchVolume / 1000, 1.0) * 0.4;
        $crawlPriority = round(($searchVolumeWeight) + ($hierarchyWeight * 0.6), 2);
        $content->update(['crawl_priority_score' => $crawlPriority]);

        if ($generateAi) {
            // Create AI Generation Job
            $job = AiGenerationJob::create([
                'tenant_id' => 0,
                'content_id' => $content->id,
                'job_type' => 'initial_generation',
                'status' => 'pending',
                'llm_model_used' => \App\Models\SystemSetting::get('ai_model', 'gpt-4o'),
            ]);

            // Dispatch background processing job
            ProcessAiGenerationJob::dispatch($content->id, $job->id);

            return redirect()->route('admin.content.index')
                ->with('success', 'AI Generation Job has been queued. The article is being written in 4 distinct quality phases!');
        }

        return redirect()->route('admin.content.index')
            ->with('success', 'Keyword manually added as Blueprint!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Content $content)
    {
        $job = AiGenerationJob::where('content_id', $content->id)->latest()->first();

        return view('admin.content.show', compact('content', 'job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $content)
    {
        $siloBlueprints = SiloBlueprint::withoutGlobalScopes()->get();

        return view('admin.content.edit', compact('content', 'siloBlueprints'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $content)
    {

        $request->validate([
            'target_keyword' => 'required|string|max:255',
            'hierarchy_level' => 'required|string|in:pillar,cluster,sub_cluster',
            'silo_blueprint_id' => 'required|exists:silo_blueprints,id',
            'body_raw' => 'nullable|string',
            'rendered_html_path' => 'nullable|string',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'status' => 'required|string',
            'canonical' => 'nullable|url',
            'robots' => 'nullable|string',
            'og_title' => 'nullable|string',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|url',
            'schema_type' => 'nullable|string',
            'schema_custom_json' => 'nullable|string',
        ]);

        $content->update($request->except(['canonical', 'robots', 'og_title', 'og_description', 'og_image', 'schema_type']));

        // Build schema
        $schema = null;
        if ($request->schema_custom_json) {
            $parsed = json_decode($request->schema_custom_json, true);
            if (is_array($parsed)) {
                $schema = $parsed;
            }
        }
        if (!$schema && $request->schema_type && $request->schema_type !== 'None') {
            $schema = ['@type' => $request->schema_type];
        }

        // Update polymorphic SEO meta
        $content->updateSeoMeta([
            'title' => $request->meta_title,
            'description' => $request->meta_description,
            'canonical' => $request->canonical,
            'robots' => $request->robots ?? 'index, follow',
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
            'og_image' => $request->og_image,
            'schema' => $schema,
        ]);

        return redirect()->route('admin.content.show', $content->id)
            ->with('success', 'Content successfully updated manually.');
    }

    /**
     * Trigger AI Generation for a manual Blueprint.
     */
    public function generateAi(Content $content)
    {
        if ($content->status === 'published' || $content->status === 'ai_processing') {
            return redirect()->back()->withErrors(['error' => 'Content is already processing or published.']);
        }

        $content->update(['status' => 'ai_processing']);

        // Create AI Generation Job
        $job = AiGenerationJob::create([
            'tenant_id' => 0,
            'content_id' => $content->id,
            'job_type' => 'initial_generation',
            'status' => 'pending',
            'llm_model_used' => \App\Models\SystemSetting::get('ai_model', 'gpt-4o'),
        ]);

        // Dispatch background processing job
        ProcessAiGenerationJob::dispatch($content->id, $job->id);

        return redirect()->back()->with('success', 'AI Generation Job has been queued for ' . $content->target_keyword);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $content)
    {

        $content->delete();

        return redirect()->route('admin.content.index')
            ->with('success', 'Post successfully deleted.');
    }
}
