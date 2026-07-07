@extends('layouts.admin')

@section('title', 'Broken Link Checker - SEOFAST')
@section('page_title', 'Broken Link Checker')

@section('admin_content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <p class="text-slate-500 text-sm">Internal &amp; external links found in published content.</p>
        <div class="flex items-center gap-2 text-sm font-semibold">
            <span class="px-3 py-1 bg-slate-100 rounded-lg text-slate-600">Total: {{ $stats['total'] }}</span>
            <span class="px-3 py-1 bg-red-100 rounded-lg text-red-700">Broken: {{ $stats['broken'] }}</span>
            <span class="px-3 py-1 bg-amber-100 rounded-lg text-amber-700">{{ $stats['internal'] }} internal</span>
            <span class="px-3 py-1 bg-amber-100 rounded-lg text-amber-700">{{ $stats['external'] }} external</span>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.broken-links.scan') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-500 transition shadow-sm">Run Scan</button>
        </form>
        <form action="{{ route('admin.broken-links.clear_all') }}" method="POST" onsubmit="return confirm('Clear all link data?');">
            @csrf @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-50">Clear All</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
        {!! session('success') !!}
    </div>
@endif

<!-- Filter tabs -->
<div class="mb-4 flex items-center gap-2 text-sm">
    <a href="{{ route('admin.broken-links.index', ['filter' => 'broken']) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'broken' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Broken</a>
    <a href="{{ route('admin.broken-links.index', ['filter' => 'valid']) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'valid' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Valid</a>
    <a href="{{ route('admin.broken-links.index', ['filter' => 'all']) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ $filter === 'all' ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All</a>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">URL</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Anchor Text</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Content</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Checked</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($links as $link)
            <tr>
                <td class="px-6 py-4 text-sm font-mono text-gray-800 max-w-[250px] break-all">
                    <a href="{{ $link->url }}" target="_blank" rel="noopener" class="hover:text-indigo-600">{{ $link->url }}</a>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-[150px] truncate">{{ $link->anchor_text ?: '-' }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $link->link_type === 'internal' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ $link->link_type }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">
                    @if($link->is_broken)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">
                            {{ $link->status_code ?: 'ERR' }} {{ $link->error ? '— Error' : '' }}
                        </span>
                        @if($link->error)
                            <p class="text-xs text-red-500 mt-0.5 max-w-[200px] truncate" title="{{ $link->error }}">{{ $link->error }}</p>
                        @endif
                    @elseif($link->status_code)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">{{ $link->status_code }} OK</span>
                    @else
                        <span class="text-xs text-slate-400">Pending</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    @if($link->content)
                        <a href="{{ route('admin.content.show', $link->content_id) }}" class="font-medium text-indigo-600 hover:text-indigo-900">{{ $link->content->target_keyword ?: '#' . $link->content_id }}</a>
                    @else
                        <span class="text-slate-400">Deleted</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $link->checked_at ? $link->checked_at->diffForHumans() : '-' }}</td>
                <td class="px-6 py-4 text-right">
                    <form action="{{ route('admin.broken-links.destroy', $link) }}" method="POST" class="inline" onsubmit="return confirm('Remove this entry?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-900 font-semibold">Remove</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    No links found. Run a scan to check for broken links.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $links->links() }}
</div>
@endsection
