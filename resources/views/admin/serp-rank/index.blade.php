@extends('layouts.admin')

@section('title', 'SERP Rank Tracker - ' . config('app.name'))
@section('page_title', 'SERP Rank Tracker')

@section('admin_content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <p class="text-slate-500 text-sm">Keyword rank positions sourced from Google Search Console analytics data.</p>
        <div class="flex items-center gap-3 flex-wrap mt-2">
            <span class="px-3 py-1 bg-slate-100 rounded-lg text-sm font-semibold text-slate-600">Keywords: <strong>{{ $stats['total_keywords'] }}</strong></span>
            <span class="px-3 py-1 bg-emerald-100 rounded-lg text-sm font-semibold text-emerald-700">Ranked: <strong>{{ $stats['ranked_keywords'] }}</strong></span>
            <span class="px-3 py-1 bg-indigo-100 rounded-lg text-sm font-semibold text-indigo-700">Avg Position: <strong>{{ $stats['avg_position'] }}</strong></span>
            @if($stats['no_data'] > 0)
            <span class="px-3 py-1 bg-amber-100 rounded-lg text-sm font-semibold text-amber-700">No GSC Data: <strong>{{ $stats['no_data'] }}</strong></span>
            @endif
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.gsc.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">GSC Management</a>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">{{ session('success') }}</div>
@endif

@if($stats['ranked_keywords'] == 0 && $stats['no_data'] > 0)
<div class="mb-6 px-5 py-4 bg-amber-50 border border-amber-200 rounded-xl">
    <div class="flex items-start gap-3">
        <svg class="h-5 w-5 text-amber-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        <div>
            <p class="font-semibold text-amber-800">Belum ada data GSC</p>
            <p class="text-sm text-amber-700 mt-1">Gunakan sample data untuk melihat demo fitur rank tracker, atau hubungkan GSC via halaman GSC Management.</p>
            <div class="flex items-center gap-3 mt-3">
                <form action="{{ route('admin.serp-rank.sample') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-bold hover:bg-amber-500 transition shadow-sm">Generate Sample Data</button>
                </form>
                <span class="text-xs text-amber-600">atau</span>
                <a href="{{ route('admin.gsc.index') }}" class="text-sm text-indigo-600 font-semibold hover:underline">Hubungkan GSC →</a>
            </div>
        </div>
    </div>
</div>
@endif

@if($stats['no_data'] > 0 && $stats['ranked_keywords'] > 0)
<div class="mb-4 px-4 py-3 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg text-sm">
    <strong>{{ $stats['no_data'] }} content(s)</strong> have no GSC analytics data in the last 28 days.
    <a href="{{ route('admin.gsc.index') }}" class="underline font-semibold">Sync GSC data</a>
    or
    <form action="{{ route('admin.serp-rank.sample') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="underline font-semibold text-amber-800">generate sample data</button>
    </form>.
</div>
@endif

<!-- ─── Recommendations ─── -->
@if(!empty($recommendations))
<div class="mb-8 grid grid-cols-1 lg:grid-cols-2 gap-4">
    @if(!empty($recommendations['ctr_optimize']))
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3 bg-gradient-to-r from-amber-50 to-amber-100/50 border-b border-slate-200 flex items-center gap-2">
            <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
            <span class="text-sm font-bold text-amber-800">CTR Optimization Needed</span>
            <span class="ml-auto text-xs text-amber-600 bg-amber-100 px-2 py-0.5 rounded-full font-semibold">{{ count($recommendations['ctr_optimize']) }} items</span>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($recommendations['ctr_optimize'] as $rec)
            <div class="px-5 py-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 truncate block">{{ $rec['title'] }}</a>
                    <p class="text-xs text-slate-500 mt-0.5">"{{ $rec['query'] }}" — {{ $rec['impressions'] }} impressions, CTR {{ $rec['ctr'] }}% @ Pos {{ $rec['position'] }}</p>
                    <p class="text-xs text-amber-700 mt-1">{{ $rec['action'] }}</p>
                </div>
                <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="shrink-0 text-xs text-amber-600 font-semibold hover:underline">Edit</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($recommendations['dropping']))
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3 bg-gradient-to-r from-red-50 to-red-100/50 border-b border-slate-200 flex items-center gap-2">
            <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94 2.28m5.94 2.28l-2.28 5.941"/></svg>
            <span class="text-sm font-bold text-red-800">Dropping Rankings</span>
            <span class="ml-auto text-xs text-red-600 bg-red-100 px-2 py-0.5 rounded-full font-semibold">{{ count($recommendations['dropping']) }} items</span>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($recommendations['dropping'] as $rec)
            <div class="px-5 py-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 truncate block">{{ $rec['title'] }}</a>
                    <p class="text-xs text-slate-500 mt-0.5">"{{ $rec['query'] }}" — Dropped from #{{ $rec['previous'] }} → #{{ $rec['current'] }} ({{ $rec['drop'] }} positions)</p>
                    <p class="text-xs text-red-700 mt-1">{{ $rec['action'] }}</p>
                </div>
                <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="shrink-0 text-xs text-amber-600 font-semibold hover:underline">Edit</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($recommendations['quick_wins']))
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3 bg-gradient-to-r from-emerald-50 to-emerald-100/50 border-b border-slate-200 flex items-center gap-2">
            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75a2.25 2.25 0 01-2.25 2.25h-1.5M13.5 10.5H9m4.5 0h3M4.5 21h15a1.5 1.5 0 001.5-1.5v-3a1.5 1.5 0 00-1.5-1.5h-15A1.5 1.5 0 003 16.5v3A1.5 1.5 0 004.5 21z"/></svg>
            <span class="text-sm font-bold text-emerald-800">Quick Wins — Near Top 3</span>
            <span class="ml-auto text-xs text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full font-semibold">{{ count($recommendations['quick_wins']) }} items</span>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($recommendations['quick_wins'] as $rec)
            <div class="px-5 py-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 truncate block">{{ $rec['title'] }}</a>
                    <p class="text-xs text-slate-500 mt-0.5">"{{ $rec['query'] }}" — Current Pos #{{ $rec['position'] }} ({{ $rec['impressions'] }} impressions)</p>
                    <p class="text-xs text-emerald-700 mt-1">{{ $rec['action'] }}</p>
                </div>
                <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="shrink-0 text-xs text-amber-600 font-semibold hover:underline">Edit</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($recommendations['high_potential']))
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3 bg-gradient-to-r from-blue-50 to-blue-100/50 border-b border-slate-200 flex items-center gap-2">
            <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
            <span class="text-sm font-bold text-blue-800">High Click Potential</span>
            <span class="ml-auto text-xs text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full font-semibold">{{ count($recommendations['high_potential']) }} items</span>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($recommendations['high_potential'] as $rec)
            <div class="px-5 py-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 truncate block">{{ $rec['title'] }}</a>
                    <p class="text-xs text-slate-500 mt-0.5">"{{ $rec['query'] }}" — Pos #{{ $rec['position'] }}, {{ $rec['impressions'] }} impressions</p>
                    <p class="text-xs text-blue-700 mt-1">{{ $rec['action'] }}</p>
                </div>
                <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="shrink-0 text-xs text-amber-600 font-semibold hover:underline">Edit</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($recommendations['content_gap']))
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden lg:col-span-2">
        <div class="px-5 py-3 bg-gradient-to-r from-purple-50 to-purple-100/50 border-b border-slate-200 flex items-center gap-2">
            <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
            <span class="text-sm font-bold text-purple-800">Content Gap — No GSC Data</span>
            <span class="ml-auto text-xs text-purple-600 bg-purple-100 px-2 py-0.5 rounded-full font-semibold">{{ count($recommendations['content_gap']) }} items</span>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($recommendations['content_gap'] as $rec)
            <div class="px-5 py-3 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 truncate block">{{ $rec['title'] }}</a>
                    <p class="text-xs text-slate-500 mt-0.5">"{{ $rec['query'] }}" — No search analytics data found</p>
                    <p class="text-xs text-purple-700 mt-1">{{ $rec['action'] }}</p>
                </div>
                <a href="{{ route('admin.content.edit', $rec['content_id']) }}" class="shrink-0 text-xs text-amber-600 font-semibold hover:underline">Edit</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endif

<!-- Filters -->
<div class="mb-4 flex items-center gap-2 text-sm flex-wrap">
    <a href="{{ route('admin.serp-rank.index', ['filter' => 'top3', 'search' => $search]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'top3' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Top 3</a>
    <a href="{{ route('admin.serp-rank.index', ['filter' => 'top10', 'search' => $search]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'top10' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Top 10</a>
    <a href="{{ route('admin.serp-rank.index', ['filter' => 'poor', 'search' => $search]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'poor' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Poor (&gt;20)</a>
    <a href="{{ route('admin.serp-rank.index', ['filter' => 'not_ranking', 'search' => $search]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'not_ranking' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Not Ranking</a>
    <a href="{{ route('admin.serp-rank.index') }}" class="px-4 py-2 rounded-lg font-semibold transition {{ !$filter ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All</a>

    <form method="GET" action="{{ route('admin.serp-rank.index') }}" class="flex items-center gap-2 ml-auto">
        @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif
        <input type="text" name="search" value="{{ $search }}" placeholder="Search keyword or slug..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 w-56">
        <button type="submit" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50">Search</button>
    </form>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Content / Keyword</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">GSC Query</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-center">Position</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-center">Trend</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Clicks</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Impressions</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">CTR</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($paginated as $row)
            @php
                $pos = (float) $row->position;
                $posLabel = $pos <= 3 ? 'text-emerald-600' : ($pos <= 10 ? 'text-indigo-600' : ($pos <= 20 ? 'text-amber-600' : 'text-red-600'));
                $prevKey = $row->content_id . '_' . $row->gsc_query;
                $prevPos = isset($previousData[$prevKey]) ? (float) $previousData[$prevKey]->avg_pos : null;
                $trend = $prevPos !== null ? $prevPos - $pos : null;
            @endphp
            <tr>
                <td class="px-6 py-4 text-sm">
                    <a href="{{ route('admin.content.show', $row->content_id) }}" class="font-medium text-indigo-600 hover:text-indigo-900">{{ $row->target_keyword ?: 'No keyword' }}</a>
                    <p class="text-xs text-slate-400 mt-0.5">/{{ $row->slug }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $row->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $row->status }}</span>
                </td>
                <td class="px-6 py-4 text-sm font-mono text-slate-600 max-w-xs truncate">{{ $row->gsc_query }}</td>
                <td class="px-6 py-4 text-sm text-center">
                    <span class="inline-flex items-center justify-center w-10 h-8 rounded-lg font-bold {{ $posLabel }} bg-slate-50">{{ number_format($pos, 1) }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-center">
                    @if($trend !== null)
                        @if($trend > 2)
                            <span class="text-emerald-600 font-bold flex items-center justify-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                                +{{ number_format($trend, 1) }}
                            </span>
                        @elseif($trend < -2)
                            <span class="text-red-600 font-bold flex items-center justify-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94 2.28m5.94 2.28l-2.28 5.941"/></svg>
                                {{ number_format(abs($trend), 1) }}
                            </span>
                        @else
                            <span class="text-slate-400">~</span>
                        @endif
                    @else
                        <span class="text-slate-300">--</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-right font-semibold">{{ number_format($row->clicks) }}</td>
                <td class="px-6 py-4 text-sm text-right">{{ number_format($row->impressions) }}</td>
                <td class="px-6 py-4 text-sm text-right">{{ number_format($row->ctr * 100, 2) }}%</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.content.edit', $row->content_id) }}" class="text-sm text-amber-600 hover:text-amber-900 font-semibold">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    @if($filter)
                        No keyword rankings match the current filter.
                    @else
                        No GSC analytics data found. <a href="{{ route('admin.gsc.index') }}" class="underline text-indigo-600 font-semibold">Sync GSC data</a>
                        or
                        <form action="{{ route('admin.serp-rank.sample') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="underline text-indigo-600 font-semibold">generate sample data</button>
                        </form>.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $paginated->links() }}
</div>

@if(count($noDataContents) > 0 && !$filter)
<div class="mt-6">
    <h3 class="text-sm font-semibold text-slate-500 uppercase mb-2">Content Without GSC Data ({{ count($noDataContents) }})</h3>
    <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Keyword</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach(array_slice($noDataContents->toArray(), 0, 10) as $nc)
                <tr>
                    <td class="px-6 py-3 text-slate-600">{{ $nc['target_keyword'] ?: '--' }}</td>
                    <td class="px-6 py-3 text-slate-500 font-mono">/{{ $nc['slug'] }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $nc['status'] === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $nc['status'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
