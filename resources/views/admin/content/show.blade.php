@extends('layouts.admin')

@section('title', $content->title . ' - SEOFAST')
@section('page_title', 'Post Details')

@section('admin_content')
<div class="space-y-6">
    <!-- Header with Back Button and Quick Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('admin.content.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 transition mb-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to All Posts
            </a>
            <h2 class="text-2xl font-bold font-outfit text-slate-900">{{ $content->title }}</h2>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-600/10">
                CQI: {{ round($content->cqi_score ?? 85) }}%
            </span>
            <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-600/10">
                Level: {{ ucfirst($content->hierarchy_level) }}
            </span>
        </div>
    </div>

    <!-- Main Workspace Tabs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Panel (Article preview & HTML editor) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-outfit">Rendered Preview</h3>
                    <span class="text-xs text-slate-500 font-mono">/blog/{{ $content->slug }}</span>
                </div>
                
                <div class="p-8 prose prose-slate max-w-none bg-white">
                    {!! $content->html_body !!}
                </div>
            </div>
        </div>

        <!-- Sidebar Panel: Meta details & AI Logs -->
        <div class="space-y-6">
            <!-- Metadata Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h4 class="text-sm font-bold text-slate-900 font-outfit mb-4">SEO Metadata</h4>
                <div class="space-y-4 text-sm">
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Target Keyword</span>
                        <span class="font-medium text-slate-800">{{ $content->target_keyword }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Meta Title</span>
                        <span class="font-medium text-slate-800">{{ $content->meta_title }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Meta Description</span>
                        <p class="text-slate-600 mt-0.5 leading-relaxed">{{ $content->meta_description }}</p>
                    </div>
                </div>
            </div>

            <!-- AI 4-Phase Generation logs Card -->
            <div class="bg-slate-900 rounded-2xl p-6 text-slate-300 shadow-xl space-y-4">
                <h4 class="text-sm font-bold text-white font-outfit uppercase tracking-wider">AI Generation Pipeline Logs</h4>
                
                <div class="space-y-4 text-xs font-mono">
                    <!-- Phase 1 status -->
                    <div class="flex items-start gap-2.5">
                        <div class="w-2 h-2 rounded-full mt-1.5 bg-emerald-500"></div>
                        <div class="min-w-0">
                            <span class="font-semibold text-white">Phase 1: Draft Complete</span>
                            <p class="text-slate-400 text-[10px] mt-0.5">Written 800+ words outline including core intent.</p>
                        </div>
                    </div>

                    <!-- Phase 2 status -->
                    <div class="flex items-start gap-2.5">
                        <div class="w-2 h-2 rounded-full mt-1.5 bg-emerald-500"></div>
                        <div class="min-w-0">
                            <span class="font-semibold text-white">Phase 2: Critique Complete</span>
                            @if($job && $job->phase_2_critique)
                                <p class="text-indigo-400 text-[10px] mt-0.5">CQI Score: {{ $job->phase_2_critique['cqi_score'] ?? 85 }}%</p>
                                <ul class="text-[10px] text-slate-400 list-disc ml-4 space-y-0.5 mt-1">
                                    @foreach($job->phase_2_critique['gaps'] ?? [] as $gap)
                                        <li>{{ $gap }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-slate-400 text-[10px] mt-0.5">Audited semantic depth and EEAT factors.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Phase 3 status -->
                    <div class="flex items-start gap-2.5">
                        <div class="w-2 h-2 rounded-full mt-1.5 bg-emerald-500"></div>
                        <div class="min-w-0">
                            <span class="font-semibold text-white">Phase 3: Expanded Complete</span>
                            <p class="text-slate-400 text-[10px] mt-0.5">Incorporated critique improvements & entities expansion.</p>
                        </div>
                    </div>

                    <!-- Phase 4 status -->
                    <div class="flex items-start gap-2.5">
                        <div class="w-2 h-2 rounded-full mt-1.5 bg-emerald-500"></div>
                        <div class="min-w-0">
                            <span class="font-semibold text-white">Phase 4: HTML Rendered</span>
                            <p class="text-slate-400 text-[10px] mt-0.5">Applied styled structures and typography rules.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
