@extends('layouts.admin')

@section('page_title', 'Edit Page Settings')

@section('styles')
<style>
.cke { border-radius: 12px !important; border: 1px solid #cbd5e1 !important; }
.cke_top { border-radius: 12px 12px 0 0 !important; }
.cke_bottom { border-radius: 0 0 12px 12px !important; }
.cke_editable {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
    font-size: 15px !important;
    line-height: 1.7 !important;
    color: #1e293b !important;
    padding: 12px 16px !important;
}
.cke_editable h2 { font-size: 1.5rem; font-weight: 700; margin: 1rem 0 0.5rem; font-family: 'Outfit', sans-serif; }
.cke_editable h3 { font-size: 1.25rem; font-weight: 700; margin: 0.75rem 0 0.5rem; font-family: 'Outfit', sans-serif; }
.cke_editable h4 { font-size: 1.1rem; font-weight: 700; margin: 0.5rem 0 0.25rem; font-family: 'Outfit', sans-serif; }
.cke_editable p { margin-bottom: 0.75rem; }
.cke_editable blockquote {
    border-left: 4px solid #6366f1;
    padding-left: 1rem;
    margin-left: 0;
    color: #64748b;
    font-style: italic;
}
.cke_editable pre {
    background: #f1f5f9;
    border-radius: 8px;
    padding: 1rem;
    font-size: 13px;
    overflow-x: auto;
}
.cke_editable table { border-collapse: collapse; width: 100%; }
.cke_editable th, .cke_editable td { border: 1px solid #e2e8f0; padding: 0.5rem 0.75rem; }
.cke_editable th { background: #f8fafc; font-weight: 600; }
</style>
@endsection

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold font-outfit">Edit Page Settings</h2>
        <p class="text-sm text-gray-500">Update meta information and featured image for {{ $page->title }}.</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="text-sm text-indigo-600 font-semibold hover:underline">&larr; Back to Pages</a>
</div>

<div class="bg-white rounded-xl shadow-sm border p-6 max-w-5xl">
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
        </div>

        <!-- ─── Template Selector ─── -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Landing Page Template</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach(\App\Models\Page::templates() as $key => $label)
                @php
                    $selected = old('template', $page->template ?? 'default') === $key;
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
                    <input type="radio" name="template" value="{{ $key }}" {{ $selected ? 'checked' : '' }} class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="flex flex-col items-center gap-2 p-4 border-2 rounded-xl transition-all pointer-events-none
                        {{ $selected ? 'border-indigo-500 bg-indigo-50 shadow-sm' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                        <svg class="w-6 h-6 {{ $selected ? 'text-indigo-600' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            {!! $icons[$key] !!}
                        </svg>
                        <span class="text-xs font-semibold text-center {{ $selected ? 'text-indigo-700' : 'text-slate-600' }}">{{ $label }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- ─── Hero Fields (hidden by default, shown via JS) ─── -->
        <div id="hero-fields" class="border-t pt-6 space-y-6 @if(($page->template ?? 'default') === 'default') hidden @endif">
            <h3 class="text-lg font-bold text-slate-900">Hero Section Content</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hero Headline</label>
                    <input type="text" name="hero_headline" value="{{ old('hero_headline', $page->hero_headline) }}" placeholder="Leave empty to use page title" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hero Subheadline</label>
                    <textarea name="hero_subheadline" rows="2" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" placeholder="Supporting text below the headline">{{ old('hero_subheadline', $page->hero_subheadline) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">CTA Button Text</label>
                    <input type="text" name="hero_cta_text" value="{{ old('hero_cta_text', $page->hero_cta_text) }}" placeholder="e.g. Get Started Free" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">CTA Button URL</label>
                    <input type="text" name="hero_cta_url" value="{{ old('hero_cta_url', $page->hero_cta_url) }}" placeholder="e.g. /contact or https://" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Secondary CTA Text</label>
                    <input type="text" name="hero_cta_text_2" value="{{ old('hero_cta_text_2', $page->hero_cta_text_2) }}" placeholder="e.g. Learn More" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Secondary CTA URL</label>
                    <input type="text" name="hero_cta_url_2" value="{{ old('hero_cta_url_2', $page->hero_cta_url_2) }}" placeholder="e.g. /features" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div id="hero-image-field" class="@if(($page->template ?? 'default') === 'hero-video') hidden @endif">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hero Image URL</label>
                    <input type="text" name="hero_image" value="{{ old('hero_image', $page->hero_image) }}" placeholder="https://example.com/image.jpg" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div id="hero-video-field" class="@if(($page->template ?? 'default') !== 'hero-video') hidden @endif">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hero Video URL (.mp4)</label>
                    <input type="text" name="hero_video_url" value="{{ old('hero_video_url', $page->hero_video_url) }}" placeholder="https://example.com/hero.mp4" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Hero Background Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="hero_bg_color" value="{{ old('hero_bg_color', $page->hero_bg_color ?? '#0f172a') }}" class="h-10 w-12 rounded border border-slate-300 cursor-pointer">
                        <span class="text-xs text-slate-400">Overrides gradient default</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Feature List (one per line)</label>
                <textarea name="hero_features" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" placeholder="Fast Performance&#10;SEO Optimized&#10;Easy to Use&#10;24/7 Support">{{ old('hero_features', is_array($page->hero_features) ? implode("\n", $page->hero_features) : $page->hero_features) }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Displayed as checkmark list in the hero section (templates: centered, split, cta).</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Page Content (HTML)</label>
                <textarea name="html_content" id="html_content" rows="20" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('html_content', $page->html_content) }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Edit dengan visual editor atau langsung HTML. Support paste dari Microsoft Word.</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Custom CSS</label>
                <textarea name="css_content" id="css_content" rows="6" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('css_content', $page->css_content) }}</textarea>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor4/4.25.0-lts/ckeditor.js"></script>
<script>
    let ckeditor = null;

    CKEDITOR.replace('html_content', {
        height: 500,
        entities: false,
        basicEntities: false,
        enterMode: CKEDITOR.ENTER_P,
        shiftEnterMode: CKEDITOR.ENTER_BR,
        allowedContent: true,
        extraAllowedContent: '*(*);*{*}',
        pasteFromWordRemoveStyles: false,
        pasteFromWordRemoveFontStyles: false,
        removePlugins: 'contextmenu,liststyle,tabletools',
        toolbar: [
            { name: 'document', items: ['Source', 'Preview'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            '/',
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] },
            '/',
            { name: 'styles', items: ['Format', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize'] }
        ],
        format_tags: 'p;h2;h3;h4;pre',
        contentsCss: [
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@400;700&display=swap'
        ],
        bodyClass: 'cke_editable',
        stylesSet: false,
    });

    CKEDITOR.on('instanceReady', function(e) {
        ckeditor = e.editor;
    });

    document.querySelector('form').addEventListener('submit', function() {
        if (ckeditor) {
            ckeditor.updateElement();
        }
    });

    function generateSlug() {
        const title = document.getElementById('title').value;
        const parent = document.getElementById('parent_slug').value;

        let slug = title.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');

        if (parent) {
            slug = parent + '/' + slug;
        }

        document.getElementById('slug').value = slug;
    }

    document.querySelectorAll('input[name="template"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const heroFields = document.getElementById('hero-fields');
            const isDefault = this.value === 'default';
            heroFields.classList.toggle('hidden', isDefault);

            document.getElementById('hero-image-field').classList.toggle('hidden', this.value === 'hero-video');
            document.getElementById('hero-video-field').classList.toggle('hidden', this.value !== 'hero-video');
        });
    });
</script>
@endpush
