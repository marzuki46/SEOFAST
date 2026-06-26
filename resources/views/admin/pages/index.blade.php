@extends('layouts.admin')

@section('page_title', 'Static Pages')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold font-outfit">Pages</h2>
        <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
            <a href="{{ route('admin.pages.index') }}" class="hover:text-indigo-600 font-semibold">Root</a>
            @if($folder)
                @php
                    $parts = explode('/', $folder);
                    $currentPath = '';
                @endphp
                @foreach($parts as $index => $part)
                    @php
                        $currentPath .= ($index == 0 ? '' : '/') . $part;
                    @endphp
                    <span>/</span>
                    <a href="{{ route('admin.pages.index', ['folder' => $currentPath]) }}" class="hover:text-indigo-600 font-semibold">{{ ucwords(str_replace('-', ' ', $part)) }}</a>
                @endforeach
            @else
                <span>/</span> <span>All root pages</span>
            @endif
        </div>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded shadow font-bold text-sm hover:bg-indigo-500">
        + Create New Page
    </a>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pages as $page)
            <tr>
                <td class="px-6 py-4">
                    @php
                        // Check if this page has children (by querying the DB or just assuming based on a helper)
                        // Actually, we can just make the title a link to the folder. If they click it, it acts as a folder.
                        $hasChildren = \App\Models\Page::where('slug', 'like', $page->slug . '/%')->exists();
                    @endphp

                    @if($hasChildren)
                        <a href="{{ route('admin.pages.index', ['folder' => $page->slug]) }}" class="font-bold text-indigo-600 hover:text-indigo-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h4l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                            {{ $page->title }}
                        </a>
                    @else
                        <span class="font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            {{ $page->title }}
                        </span>
                    @endif

                    @if($page->is_homepage)
                        <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                            Homepage
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">/{{ $page->slug }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $page->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $page->is_published ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    @if(!$page->is_homepage)
                    <form action="{{ route('admin.pages.set_home', $page->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-blue-600 hover:text-blue-900 font-semibold" title="Set as Homepage">Set Home</button>
                    </form>
                    <span class="text-gray-300">|</span>
                    @endif
                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="text-sm text-amber-600 hover:text-amber-900 font-bold">Settings & Meta</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.pages.builder', $page->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-bold">Visual Builder</a>
                    <span class="text-gray-300">|</span>
                    <a href="/{{ $page->slug }}" target="_blank" class="text-sm text-gray-600 hover:text-gray-900">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                    No pages created yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
