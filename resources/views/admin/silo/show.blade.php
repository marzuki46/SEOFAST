@extends('layouts.admin')

@section('title', $silo->silo_name . ' - Silo Map - SEOFAST')
@section('page_title', 'Topical Map: ' . $silo->silo_name)

@section('admin_content')
<div class="space-y-6">
    <!-- Breadcrumb & Top Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.silo.index') }}" class="hover:text-brand-indigo transition font-medium">Silos & Keywords</a>
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <span class="font-semibold text-slate-800">{{ $silo->silo_name }}</span>
        </div>
        
        @if($silo->contents()->exists())
            <form action="{{ route('admin.silo.map_internal_links', $silo->id) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-indigo hover:bg-brand-indigo/90 text-white font-bold text-sm rounded-xl shadow-sm transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    Hubungkan Internal Link Silo
                </button>
            </form>
        @endif
    </div>

    <!-- Silo Meta Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-0.5">Seed Keyword / Topik Utama</p>
                <p class="text-base font-bold text-slate-900">{{ $silo->seed_keyword }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-0.5">Total Konten Terpetakan</p>
                <p class="text-base font-bold text-slate-900">{{ $silo->contents()->count() }} Halaman</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-0.5">Target Wilayah / Negara</p>
                <p class="text-base font-bold text-slate-900">{{ strtoupper($silo->target_language) }} - {{ strtoupper($silo->target_country) }}</p>
            </div>
        </div>
    </div>

    <!-- SILO CHAMBERS VIEW (Kamar-kamar Silo) -->
    <div class="space-y-8">
        
        <!-- CHAMBER 1: PILLAR PAGE -->
        @php
            $pillar = $contents->where('hierarchy_level', 'pillar')->first();
        @endphp
        
        <div class="bg-white rounded-3xl border-2 border-indigo-600 shadow-md p-6 md:p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <div class="flex items-center gap-3">
                    <span class="px-3.5 py-1 text-xs font-bold bg-indigo-600 text-white rounded-full uppercase tracking-wider">Tingkat 1: Pillar Page</span>
                    <span class="text-xs text-slate-400 font-medium">Halaman Utama Silo</span>
                </div>
            </div>

            @if(!$pillar)
                <div class="text-center py-8">
                    <p class="text-slate-500 mb-4 font-medium">Belum ada Pillar Page yang dibuat untuk topik utama ini.</p>
                    <form action="{{ route('admin.silo.generate_pillar', $silo->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl transition duration-150 shadow-sm">
                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Generate Pillar Page dengan AI
                        </button>
                    </form>
                </div>
            @else
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-indigo-50/50 rounded-2xl p-5 border border-indigo-100">
                    <div class="space-y-2">
                        <h3 class="text-xl md:text-2xl font-extrabold text-slate-900 font-outfit">{{ $pillar->target_keyword }}</h3>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-600 font-medium">
                            <span>Vol Pencarian: <strong class="text-slate-900 font-mono">{{ number_format($pillar->search_volume) }}</strong></span>
                            <span>&bull;</span>
                            <span>KGR Score: 
                                @if($pillar->kgr_score < 0.25)
                                    <strong class="text-emerald-600">{{ $pillar->kgr_score }}</strong>
                                @else
                                    <strong class="text-slate-900">{{ $pillar->kgr_score }}</strong>
                                @endif
                            </span>
                            <span>&bull;</span>
                            <span>Status: 
                                @if($pillar->status === 'published')
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">Published</span>
                                @elseif($pillar->status === 'ai_processing')
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-md animate-pulse">AI Writing...</span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">Draft / Blueprint</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        @if($pillar->status === 'blueprint')
                            <form action="{{ route('admin.content.generate', $pillar->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 bg-brand-violet hover:bg-brand-violet/90 text-white font-bold text-sm rounded-xl shadow-sm transition">
                                    Tulis Artikel AI
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.content.show', $pillar->id) }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm rounded-xl transition">
                                Lihat Halaman
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Action to generate Level 2 Clusters -->
                @php
                    $clusters = $contents->where('hierarchy_level', 'cluster')->where('parent_id', $pillar->id);
                @endphp
                
                @if($clusters->isEmpty())
                    <div class="mt-6 border-t border-slate-100 pt-6 text-center">
                        <p class="text-sm text-slate-500 mb-3">Pillar page siap. Langkah berikutnya: Buat beberapa cluster topik pendukung.</p>
                        <form action="{{ route('admin.silo.generate_clusters', ['silo' => $silo->id, 'content' => $pillar->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Generate Keyword Cluster (Tingkat 2)
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        </div>

        <!-- CHAMBER 2 & 3: CLUSTER & SUB-CLUSTER ROOMS -->
        @if($pillar)
            @php
                $clusters = $contents->where('hierarchy_level', 'cluster')->where('parent_id', $pillar->id);
            @endphp

            @if($clusters->isNotEmpty())
                <div class="space-y-2">
                    <div class="flex items-center gap-2 px-2">
                        <span class="px-3.5 py-1 text-xs font-bold bg-blue-600 text-white rounded-full uppercase tracking-wider">Tingkat 2 & 3: Cluster Chambers</span>
                        <span class="text-xs text-slate-400 font-medium">Bilik Topik Pendukung & Long-tail Keywords</span>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-2">
                        @foreach($clusters as $cluster)
                            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition duration-200">
                                <div>
                                    <!-- Cluster Header -->
                                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-3 mb-4">
                                        <div class="space-y-1">
                                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wide">Cluster Room</span>
                                            <h4 class="text-lg font-bold text-slate-900 font-outfit">{{ $cluster->target_keyword }}</h4>
                                            <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
                                                <span>Vol: {{ number_format($cluster->search_volume) }}</span>
                                                <span>&bull;</span>
                                                <span>KGR: {{ $cluster->kgr_score }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            @if($cluster->status === 'blueprint')
                                                <form action="{{ route('admin.content.generate', $cluster->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-brand-violet hover:bg-brand-violet/90 text-white font-bold text-xs rounded-lg shadow-sm transition">
                                                        Tulis
                                                    </button>
                                                </form>
                                            @else
                                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-50 text-emerald-700">{{ Str::title($cluster->status) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Sub-clusters list (Level 3 Children) -->
                                    @php
                                        $subClusters = $contents->where('hierarchy_level', 'sub_cluster')->where('parent_id', $cluster->id);
                                    @endphp

                                    <div class="space-y-2 mt-4 pl-1">
                                        <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sub-Cluster Keywords (Tingkat 3)</h5>
                                        
                                        @if($subClusters->isEmpty())
                                            <div class="py-4 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 mt-2">
                                                <p class="text-xs text-slate-400 mb-2">Belum ada sub-cluster keyword.</p>
                                                <form action="{{ route('admin.silo.generate_subclusters', ['silo' => $silo->id, 'content' => $cluster->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-lg transition">
                                                        + Generate Sub-clusters
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="space-y-1.5 mt-2 max-h-60 overflow-y-auto pr-1">
                                                @foreach($subClusters as $sub)
                                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 transition text-xs">
                                                        <div class="min-w-0 pr-2">
                                                            <p class="font-bold text-slate-800 truncate" title="{{ $sub->target_keyword }}">{{ $sub->target_keyword }}</p>
                                                            <div class="flex items-center gap-2 mt-0.5 text-[10px] text-slate-400 font-medium">
                                                                <span>Vol: {{ $sub->search_volume }}</span>
                                                                <span>KGR: {{ $sub->kgr_score }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-1.5 flex-shrink-0">
                                                            @if($sub->status === 'blueprint')
                                                                <form action="{{ route('admin.content.generate', $sub->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="px-2 py-1 bg-brand-violet hover:bg-brand-violet/90 text-white font-extrabold rounded-md shadow-xs transition">
                                                                        AI Write
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="px-2 py-0.5 text-[9px] font-bold rounded bg-emerald-50 text-emerald-700">{{ Str::title($sub->status) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
        
    </div>
</div>
@endsection
