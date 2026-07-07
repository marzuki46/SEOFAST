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

            <h3 class="text-lg font-bold text-slate-900 mb-6 border-b pb-2">Landing Page Template</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-8">
                @foreach(\App\Models\Page::templates() as $key => $label)
                @php
                    $icons = [
                        'default' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>',
                        'hero-centered' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 6.553l-10.5-3m4.995 7.5L3.75 12M3.75 6.75h16.5"/>',
                        'hero-split' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v16.5h16.5V3.75H3.75zM12 3.75v16.5"/>',
                        'hero-image' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/>',
                        'hero-video' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h12.75a2.25 2.25 0 002.25-2.25V7.5a2.25 2.25 0 00-2.25-2.25H4.5A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/>',
                        'hero-cta' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>',
                    ];
                @endphp
                <label class="relative cursor-pointer block">
                    <input type="radio" name="template" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="flex flex-col items-center gap-2 p-4 border-2 rounded-xl transition-all pointer-events-none {{ $loop->first ? 'border-indigo-500 bg-indigo-50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                        <svg class="w-6 h-6 {{ $loop->first ? 'text-indigo-600' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            {!! $icons[$key] !!}
                        </svg>
                        <span class="text-xs font-semibold text-center">{{ $label }}</span>
                    </div>
                </label>
                @endforeach
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
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow font-bold hover:bg-indigo-500 flex items-center gap-2">
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
