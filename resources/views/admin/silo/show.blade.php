@extends('layouts.admin')

@section('title', $silo->silo_name . ' - Silo Map - SEOFAST')
@section('page_title', 'Topical Map: ' . $silo->silo_name)

@section('admin_content')
<div class="space-y-6">
    <div class="flex items-center gap-4 text-sm text-slate-500">
        <a href="{{ route('admin.silo.index') }}" class="hover:text-indigo-600 transition">Silos & Keywords</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="font-semibold text-slate-800">{{ $silo->silo_name }}</span>
    </div>

    <!-- Silo Meta Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-0.5">Seed Keyword</p>
                <p class="text-lg font-bold text-slate-900">{{ $silo->seed_keyword }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-0.5">Total Content</p>
                <p class="text-lg font-bold text-slate-900">{{ $silo->contents()->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-0.5">Locale</p>
                <p class="text-lg font-bold text-slate-900">{{ strtoupper($silo->target_language) }}-{{ strtoupper($silo->target_country) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-0.5">Structure Status</p>
                @if($silo->is_locked)
                    <p class="text-lg font-bold text-slate-900">Locked</p>
                @else
                    <p class="text-lg font-bold text-slate-900">Editable</p>
                @endif
            </div>
            <a href="{{ route('admin.content.create') }}" class="px-4 py-2 bg-indigo-50 text-indigo-700 font-semibold text-sm rounded-lg hover:bg-indigo-100 transition">
                + Write
            </a>
        </div>
    </div>

    <!-- Mapped Content Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-lg font-bold text-slate-900 font-outfit">Keyword Mappings & Content</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white border-b border-slate-200 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Keyword / Content Title</th>
                        <th class="px-6 py-4 font-semibold">Level</th>
                        <th class="px-6 py-4 font-semibold">Search Vol</th>
                        <th class="px-6 py-4 font-semibold">KGR Score</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($contents as $content)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <a href="{{ route('admin.content.show', $content->id) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition">{{ $content->target_keyword }}</a>
                                <span class="text-xs text-slate-500 mt-1">{{ Str::limit($content->meta_title, 40) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($content->hierarchy_level === 'pillar')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">Pillar Page</span>
                            @elseif($content->hierarchy_level === 'cluster')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Cluster Topic</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">Sub-Cluster</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono font-medium">
                            {{ number_format($content->search_volume) }}
                        </td>
                        <td class="px-6 py-4">
                            @if($content->kgr_score === null)
                                <span class="text-slate-400 italic">Pending</span>
                            @elseif($content->kgr_score < 0.25)
                                <span class="text-emerald-600 font-bold">{{ $content->kgr_score }} (Great)</span>
                            @else
                                <span class="text-slate-600 font-medium">{{ $content->kgr_score }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($content->status === 'published')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Published
                                </span>
                            @elseif($content->status === 'ai_processing')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span> Processing AI
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> {{ Str::title(str_replace('_', ' ', $content->status)) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <p class="text-slate-500 font-medium mb-3">No mapped content yet.</p>
                            <a href="{{ route('admin.content.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-100 transition">
                                Map New Keyword &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
