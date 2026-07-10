@extends('layouts.admin')

@section('title', 'Content Duplication Detector - ' . config('app.name'))
@section('page_title', 'Content Duplication Detector')

@section('admin_content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <p class="text-slate-500 text-sm">Detects near-duplicate content using word overlap similarity.</p>
        <div class="flex items-center gap-2 text-sm font-semibold">
            <span class="px-3 py-1 bg-slate-100 rounded-lg text-slate-600">Total: {{ $stats['total'] }}</span>
            <span class="px-3 py-1 bg-amber-100 rounded-lg text-amber-700">Unresolved: {{ $stats['unresolved'] }}</span>
            <span class="px-3 py-1 bg-red-100 rounded-lg text-red-700">High: {{ $stats['high'] }}</span>
            <span class="px-3 py-1 bg-blue-100 rounded-lg text-blue-700">Moderate: {{ $stats['moderate'] }}</span>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.duplicates.detect') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-500 transition shadow-sm">Run Detection</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Filter -->
<div class="mb-4 flex items-center gap-2 text-sm flex-wrap">
    <a href="{{ route('admin.duplicates.index', ['filter' => 'unresolved']) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'unresolved' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Unresolved</a>
    <a href="{{ route('admin.duplicates.index', ['filter' => 'resolved']) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'resolved' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Resolved</a>
    <a href="{{ route('admin.duplicates.index', ['filter' => 'all']) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'all' ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All</a>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Source Content</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Similar To</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Score</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Reason</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($mappings as $mapping)
            <tr>
                <td class="px-6 py-4 text-sm">
                    <a href="{{ route('admin.content.show', $mapping->content_id) }}" class="font-medium text-indigo-600 hover:text-indigo-900">{{ $mapping->content?->target_keyword ?: '#' . $mapping->content_id }}</a>
                    <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($mapping->content?->target_keyword, 60) }}</p>
                </td>
                <td class="px-6 py-4 text-sm">
                    <a href="{{ route('admin.content.show', $mapping->canonical_target_id) }}" class="font-medium text-indigo-600 hover:text-indigo-900">{{ $mapping->canonicalTarget?->target_keyword ?: '#' . $mapping->canonical_target_id }}</a>
                    <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($mapping->canonicalTarget?->target_keyword, 60) }}</p>
                </td>
                <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $mapping->similarity_score >= 0.8 ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ number_format($mapping->similarity_score * 100, 1) }}%
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $mapping->reason ?? '-' }}</td>
                <td class="px-6 py-4 text-sm">
                    @if($mapping->is_resolved)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Resolved</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Unresolved</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                    @if($mapping->is_resolved)
                        <form action="{{ route('admin.duplicates.unresolve', $mapping) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-amber-600 hover:text-amber-900 font-semibold">Reopen</button>
                        </form>
                    @else
                        <form action="{{ route('admin.duplicates.resolve', $mapping) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-emerald-600 hover:text-emerald-900 font-semibold">Resolve</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    No similar content detected. Run a detection scan to find duplicates.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $mappings->links() }}
</div>
@endsection
