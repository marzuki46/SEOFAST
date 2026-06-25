@extends('layouts.admin')

@section('page_title', 'Static Pages')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold font-outfit">Pages</h2>
        <p class="text-sm text-gray-500">Manage your static pages, about us, contact us, and homepage.</p>
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
                <td class="px-6 py-4 font-bold text-gray-800">
                    {{ $page->title }}
                    @if($page->is_homepage)
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
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
