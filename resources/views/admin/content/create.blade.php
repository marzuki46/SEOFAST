@extends('layouts.admin')

@section('title', 'Write with AI - SEOFAST')
@section('page_title', 'Write with AI')

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Generator Form -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 font-outfit mb-2">Configure AI Writing Request</h3>
            <p class="text-sm text-slate-500 mb-6">Enter your target keywords. The AI pipeline will research, draft, critique, and finalize the article layout automatically.</p>

            <form action="{{ route('admin.content.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Target Keyword -->
                <div>
                    <label for="target_keyword" class="block text-sm font-semibold text-slate-700 mb-1.5">Target Keyword</label>
                    <input type="text" name="target_keyword" id="target_keyword" required placeholder="e.g. cara membuat website dengan laravel"
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition outline-none">
                    <p class="text-xs text-slate-400 mt-1">The primary keyword you want the article to rank for in Google Search Console.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Silo Blueprint -->
                    <div>
                        <label for="silo_blueprint_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Silo Category</label>
                        <select name="silo_blueprint_id" id="silo_blueprint_id" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition outline-none bg-white">
                            @foreach($siloBlueprints as $silo)
                                <option value="{{ $silo->id }}">{{ $silo->silo_name }} ({{ $silo->seed_keyword }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-400 mt-1">Links the post structurally to a specific category silo map.</p>
                    </div>

                    <!-- Hierarchy Level -->
                    <div>
                        <label for="hierarchy_level" class="block text-sm font-semibold text-slate-700 mb-1.5">Hierarchy Level</label>
                        <select name="hierarchy_level" id="hierarchy_level" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition outline-none bg-white">
                            <option value="pillar">Pillar Content (Main Guide)</option>
                            <option value="cluster" selected>Cluster Content (Supporting Subtopic)</option>
                            <option value="sub_cluster">Sub-Cluster (Niche Article)</option>
                        </select>
                        <p class="text-xs text-slate-400 mt-1">Configures internal link mappings for link flow distribution.</p>
                    </div>
                </div>

                <!-- Options -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="generate_ai" id="generate_ai" value="1" checked
                           class="h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                    <div>
                        <label for="generate_ai" class="text-sm font-semibold text-slate-800">Generate Content via AI Pipeline</label>
                        <p class="text-xs text-slate-500">Uncheck to manually add this keyword to the silo as a blueprint without writing content.</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-tr from-brand-indigo to-brand-purple px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/10 hover:opacity-90 transition">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l8.904-4.452L18 18l4.5-9-9 4.5 1.314-.657z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707-.707" />
                        </svg>
                        Submit Keyword
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: 4-Phase Info -->
    <div class="space-y-6">
        <div class="bg-gradient-to-tr from-slate-900 to-indigo-950 rounded-2xl p-6 text-white shadow-xl">
            <h4 class="text-base font-bold font-outfit text-white mb-4 uppercase tracking-wider">The 4-Phase Generation Pipeline</h4>
            
            <div class="space-y-5 text-sm">
                <!-- Phase 1 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center font-bold text-indigo-300 border border-indigo-500/30">
                        1
                    </div>
                    <div>
                        <h5 class="font-semibold text-white">Topic Drafter</h5>
                        <p class="text-xs text-indigo-200/70 mt-0.5">Researches intent and writes the primary draft outlining key points and layout headings.</p>
                    </div>
                </div>

                <!-- Phase 2 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center font-bold text-indigo-300 border border-indigo-500/30">
                        2
                    </div>
                    <div>
                        <h5 class="font-semibold text-white">Critique & Quality Audit</h5>
                        <p class="text-xs text-indigo-200/70 mt-0.5">Audits readability, keyword density, and E-E-A-T gaps. Produces CQI score recommendation.</p>
                    </div>
                </div>

                <!-- Phase 3 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center font-bold text-indigo-300 border border-indigo-500/30">
                        3
                    </div>
                    <div>
                        <h5 class="font-semibold text-white">Content Expander</h5>
                        <p class="text-xs text-indigo-200/70 mt-0.5">Rewrites draft to fill structural gaps, add rich examples, and optimize semantics.</p>
                    </div>
                </div>

                <!-- Phase 4 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center font-bold text-indigo-300 border border-indigo-500/30">
                        4
                    </div>
                    <div>
                        <h5 class="font-semibold text-white">Master HTML Styling</h5>
                        <p class="text-xs text-indigo-200/70 mt-0.5">Formats final markdown into clean semantic HTML ready for publishing and indexing.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
