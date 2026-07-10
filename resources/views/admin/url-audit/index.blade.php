@extends('layouts.admin')

@section('title', 'URL Structure Audit - ' . config('app.name'))
@section('page_title', 'URL Structure Audit')

@section('admin_content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <p class="text-slate-500 text-sm">Analyze URL depth, slug quality, and keyword presence across your content.</p>
        @if($summary)
        <div class="flex items-center gap-2 text-sm font-semibold">
            <span class="px-3 py-1 bg-slate-100 rounded-lg text-slate-600">Avg: <strong>{{ $summary['avg_score'] }}</strong></span>
            <span class="px-3 py-1 bg-emerald-100 rounded-lg text-emerald-700">Good: {{ $summary['good'] }}</span>
            <span class="px-3 py-1 bg-amber-100 rounded-lg text-amber-700">Needs Work: {{ $summary['needs_work'] }}</span>
            <span class="px-3 py-1 bg-red-100 rounded-lg text-red-700">Poor: {{ $summary['poor'] }}</span>
            <span class="px-3 py-1 bg-slate-200 rounded-lg text-slate-500">Issues: {{ $summary['total_issues'] }}</span>
        </div>
        @endif
    </div>
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.url-audit.run') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-500 transition shadow-sm">Run Audit</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

@if(!$summary)
<div class="bg-white border rounded-xl shadow-sm p-12 text-center text-slate-500">
    <p class="text-lg font-semibold mb-1">No audit data yet</p>
    <p class="text-sm">Click "Run Audit" to analyze your content URLs.</p>
</div>
@else
<!-- Filters -->
<div class="mb-4 flex items-center gap-2 text-sm flex-wrap">
    <a href="{{ route('admin.url-audit.index', ['filter' => 'good', 'search' => $search]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'good' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Good</a>
    <a href="{{ route('admin.url-audit.index', ['filter' => 'needs_work', 'search' => $search]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'needs_work' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Needs Work</a>
    <a href="{{ route('admin.url-audit.index', ['filter' => 'poor', 'search' => $search]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'poor' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Poor</a>
    <a href="{{ route('admin.url-audit.index') }}" class="px-4 py-2 rounded-lg font-semibold transition {{ !$filter ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All</a>

    <div class="text-sm text-slate-400 ml-2">{{ $total }} URLs</div>

    <form method="GET" action="{{ route('admin.url-audit.index') }}" class="flex items-center gap-2 ml-auto">
        @if($filter) <input type="hidden" name="filter" value="{{ $filter }}"> @endif
        <input type="text" name="search" value="{{ $search }}" placeholder="Search title or slug..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 w-56">
        <button type="submit" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50">Search</button>
    </form>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Content</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Depth</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Length</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">KW in URL</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Issues</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Score</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($paginated as $r)
            @php
                $scoreClass = $r['score'] >= 80 ? 'text-emerald-600' : ($r['score'] >= 50 ? 'text-amber-600' : 'text-red-600');
                $barColor = $r['score'] >= 80 ? 'bg-emerald-500' : ($r['score'] >= 50 ? 'bg-amber-500' : 'bg-red-500');
            @endphp
            <tr>
                <td class="px-6 py-4 text-sm">
                    <a href="{{ route('admin.content.show', $r['content_id']) }}" class="font-medium text-indigo-600 hover:text-indigo-900">{{ Str::limit($r['title'], 50) }}</a>
                    @if($r['target_keyword'])
                        <p class="text-xs text-slate-400 mt-0.5">KW: {{ $r['target_keyword'] }}</p>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm font-mono text-slate-600 max-w-xs truncate">{{ $r['slug'] }}</td>
                <td class="px-6 py-4 text-sm text-center {{ $r['depth'] > 3 ? 'text-red-500 font-bold' : 'text-slate-600' }}">{{ $r['depth'] }}</td>
                <td class="px-6 py-4 text-sm text-center {{ $r['slug_length'] > 60 ? 'text-red-500 font-bold' : 'text-slate-600' }}">{{ $r['slug_length'] }}</td>
                <td class="px-6 py-4 text-sm text-center">
                    @if($r['keyword_in_url'] === true)
                        <span class="text-emerald-500 font-bold">Yes</span>
                    @elseif($r['keyword_in_url'] === false)
                        <span class="text-red-500 font-bold">No</span>
                    @else
                        <span class="text-slate-400">N/A</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    @if(count($r['issues']) > 0)
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($r['issues'], 0, 3) as $issue)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">{{ $issue }}</span>
                            @endforeach
                            @if(count($r['issues']) > 3)
                                <span class="text-xs text-slate-400">+{{ count($r['issues']) - 3 }} more</span>
                            @endif
                        </div>
                    @else
                        <span class="text-emerald-500 text-xs font-semibold">Clean</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="font-bold {{ $scoreClass }}">{{ $r['score'] }}</span>
                        <div class="w-12 h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $r['score'] }}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.content.edit', $r['content_id']) }}" class="text-sm text-amber-600 hover:text-amber-900 font-semibold">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">No URLs match the current filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4 flex items-center justify-between text-sm text-slate-500">
    <div>Showing {{ count($paginated) }} of {{ $total }} URLs (Page {{ $page }})</div>
    <div class="flex gap-2">
        @if($page > 1)
        <a href="{{ route('admin.url-audit.index', ['page' => $page - 1, 'filter' => $filter, 'search' => $search]) }}" class="px-3 py-1.5 bg-white border rounded-lg hover:bg-slate-50 font-semibold">&larr; Previous</a>
        @endif
        @if(($page * $perPage) < $total)
        <a href="{{ route('admin.url-audit.index', ['page' => $page + 1, 'filter' => $filter, 'search' => $search]) }}" class="px-3 py-1.5 bg-white border rounded-lg hover:bg-slate-50 font-semibold">Next &rarr;</a>
        @endif
    </div>
</div>
@endif
@endsection
