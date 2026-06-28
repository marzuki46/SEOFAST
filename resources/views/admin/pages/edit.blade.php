@extends('layouts.admin')

@section('page_title', 'Edit Page Settings')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold font-outfit">Edit Page Settings</h2>
        <p class="text-sm text-gray-500">Update meta information and featured image for {{ $page->title }}.</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="text-sm text-indigo-600 font-semibold hover:underline">&larr; Back to Pages</a>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6 max-w-4xl">
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Parent Page (Folder)</label>
                <select id="parent_slug" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" onchange="generateSlug()">
                    <option value="">-- No Parent (Root Level) --</option>
                    @foreach($pages as $p)
                        <option value="{{ $p->slug }}" {{ str_starts_with($page->slug, $p->slug . '/') ? 'selected' : '' }}>
                            {{ $p->title }} ({{ $p->slug }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">Select a parent folder to nest this page inside.</p>
            </div>

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1">Page Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" onkeyup="generateSlug()">
            </div>

            <div class="md:col-span-2">
                <label for="slug" class="block text-sm font-semibold text-slate-700 mb-1">URL Slug</label>
                <div class="flex items-center">
                    <span class="bg-slate-50 border border-r-0 border-gray-300 rounded-l-xl px-4 py-2 text-sm text-gray-500 font-mono select-none flex-shrink-0">
                        {{ url('/') }}/
                    </span>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}" required
                           class="w-full rounded-r-xl border border-slate-300 px-4 py-2 text-sm text-blue-600 font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="meta_title" class="block text-sm font-semibold text-slate-700 mb-1">SEO Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div class="md:col-span-2">
                <label for="meta_description" class="block text-sm font-semibold text-slate-700 mb-1">SEO Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="3"
                          class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('meta_description', $page->meta_description) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Featured Image (Upload)</label>
                <input type="file" name="featured_image_upload" id="featured_image_upload" accept="image/*"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                
                @if($page->seoMeta && $page->seoMeta->og_image)
                    <div class="mt-3">
                        <p class="text-xs text-slate-500 mb-1">Gambar Saat Ini:</p>
                        <img src="{{ $page->seoMeta->og_image }}" alt="Featured Image" class="h-24 w-auto rounded border border-slate-200 object-cover">
                    </div>
                @endif
            </div>

            <div class="md:col-span-2 flex items-center">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_published" class="ml-2 block text-sm font-medium text-gray-700">
                    Publish this page
                </label>
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-sm transition">
                Save Page Settings
            </button>
        </div>
    </form>
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
