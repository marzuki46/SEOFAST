@extends('layouts.admin')

@section('title', '404 Error Tracker - SEOFAST')
@section('page_title', '404 Error Tracker')

@section('admin_content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <p class="text-slate-500 text-sm">URLs that returned 404 errors. Create redirects to recover lost traffic.</p>
    <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('admin.errors.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search URL..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 w-56">
            <button type="submit" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50">Search</button>
        </form>
        <form action="{{ route('admin.errors.clear_all') }}" method="POST" onsubmit="return confirm('Clear all 404 entries? This cannot be undone.');">
            @csrf
            <button type="submit" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-50">Clear All</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm font-semibold">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">URL</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Referer</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Hits</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">First Seen</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Last Seen</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pageErrors as $error)
            <tr>
                <td class="px-6 py-4 text-sm font-mono text-gray-800 max-w-xs break-all">
                    <a href="{{ $error->url }}" target="_blank" rel="noopener" class="hover:text-indigo-600">{{ $error->url }}</a>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 max-w-[200px] truncate">{{ $error->referer ?: '-' }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $error->count > 10 ? 'bg-red-100 text-red-800' : ($error->count > 3 ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ $error->count }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $error->first_seen->format('d M Y, H:i') }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $error->last_seen->diffForHumans() }}</td>
                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('modal-{{ $error->id }}').classList.remove('hidden');" class="text-sm text-emerald-600 hover:text-emerald-900 font-semibold">Redirect</a>
                    <span class="text-gray-300">|</span>
                    <form action="{{ route('admin.errors.destroy', $error) }}" method="POST" class="inline" onsubmit="return confirm('Clear this entry?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-900 font-semibold">Ignore</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    No 404 errors tracked yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $pageErrors->links() }}
</div>

<!-- Inline modals for "Redirect" action -->
@foreach($pageErrors as $error)
<div id="modal-{{ $error->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4 p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-1">Create Redirect</h3>
        <p class="text-sm text-slate-500 mb-4">From: <span class="font-mono text-slate-700">{{ $error->url }}</span></p>
        <form action="{{ route('admin.errors.create_redirect', $error) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">New URL <span class="text-red-500">*</span></label>
                <input type="text" name="new_url" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500" placeholder="https://example.com/new-page">
            </div>
            <div class="flex items-center gap-3">
                <label class="text-sm font-bold text-gray-700">Type</label>
                <select name="status_code" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="301">301 — Permanent</option>
                    <option value="302">302 — Temporary</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-500 transition">Create Redirect</button>
                <button type="button" onclick="document.getElementById('modal-{{ $error->id }}').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
