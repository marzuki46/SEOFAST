@extends('layouts.admin')

@section('page_title', 'Create Page')

@section('admin_content')
<div class="max-w-4xl">
    <div class="bg-white border rounded-xl shadow-sm p-6 mb-8">
        <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <h3 class="text-lg font-bold text-slate-900 mb-6 border-b pb-2">Page Structure</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Parent Page Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Parent Page (Folder)</label>
                    <select id="parent_slug" class="w-full border-gray-300 rounded-lg shadow-sm text-sm" onchange="generateSlug()">
                        <option value="">-- No Parent (Root Level) --</option>
                        @foreach($pages as $p)
                            <option value="{{ $p->slug }}">{{ $p->title }} ({{ $p->slug }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Select a parent folder to nest this page inside.</p>
                </div>

                <!-- Page Title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Page Title</label>
                    <input type="text" id="title" name="title" class="w-full border-gray-300 rounded-lg shadow-sm text-sm" required placeholder="e.g. System POS Klinik" onkeyup="generateSlug()">
                </div>
            </div>

            <!-- URL Slug (Auto-generated) -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">URL Slug</label>
                <div class="flex items-center">
                    <span class="bg-slate-50 border border-r-0 border-gray-300 rounded-l-lg px-3 py-2 text-sm text-gray-500 font-mono select-none flex-shrink-0">
                        {{ url('/') }}/
                    </span>
                    <input type="text" id="slug" name="slug" class="w-full border-gray-300 rounded-r-lg shadow-sm text-blue-600 font-mono text-sm" required placeholder="e.g. portfolio/system-pos-klinik">
                </div>
            </div>

            <h3 class="text-lg font-bold text-slate-900 mb-6 border-b pb-2">SEO & Display Settings</h3>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Title (SEO)</label>
                <input type="text" name="meta_title" class="w-full border-gray-300 rounded-lg shadow-sm text-sm" placeholder="Optional SEO title">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description (SEO)</label>
                <textarea name="meta_description" class="w-full border-gray-300 rounded-lg shadow-sm text-sm" rows="3" placeholder="Optional SEO description"></textarea>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail / OpenGraph Image</label>
                <input type="file" name="featured_image_upload" class="w-full border border-gray-300 rounded-lg shadow-sm p-2 text-sm" accept="image/*">
                <p class="text-xs text-gray-500 mt-1">Used for archive grids and social media sharing (Facebook, Twitter).</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 text-gray-600 font-semibold hover:text-gray-900">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow font-bold hover:bg-indigo-500 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Create & Start Building
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function generateSlug() {
        const title = document.getElementById('title').value;
        const parent = document.getElementById('parent_slug').value;
        
        let slug = title.toLowerCase()
            .replace(/[^\w\s-]/g, '') // remove special chars
            .replace(/\s+/g, '-')     // replace spaces with dashes
            .replace(/-+/g, '-');     // remove consecutive dashes

        if (parent) {
            slug = parent + '/' + slug;
        }

        document.getElementById('slug').value = slug;
    }
</script>
@endpush
