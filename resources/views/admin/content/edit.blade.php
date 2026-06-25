@extends('layouts.admin')

@section('title', 'Edit Content: ' . $content->target_keyword . ' - SEOFAST')
@section('page_title', 'Edit Content Manually')

@section('admin_content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4 text-sm text-slate-500 mb-2">
        <a href="{{ route('admin.content.index') }}" class="hover:text-indigo-600 transition">All Content</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <a href="{{ route('admin.content.show', $content->id) }}" class="hover:text-indigo-600 transition">{{ $content->target_keyword }}</a>
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
        <span class="font-semibold text-slate-800">Edit</span>
    </div>

    <form action="{{ route('admin.content.update', $content->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="target_keyword" class="block text-sm font-semibold text-slate-700 mb-1">Target Keyword</label>
                <input type="text" name="target_keyword" id="target_keyword" value="{{ old('target_keyword', $content->target_keyword) }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label for="silo_blueprint_id" class="block text-sm font-semibold text-slate-700 mb-1">Silo / Topical Map</label>
                <select name="silo_blueprint_id" id="silo_blueprint_id" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                    @foreach($siloBlueprints as $silo)
                        <option value="{{ $silo->id }}" {{ $content->silo_blueprint_id == $silo->id ? 'selected' : '' }}>
                            {{ $silo->silo_name }} ({{ $silo->seed_keyword }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="hierarchy_level" class="block text-sm font-semibold text-slate-700 mb-1">Hierarchy Level</label>
                <select name="hierarchy_level" id="hierarchy_level" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                    <option value="pillar" {{ $content->hierarchy_level == 'pillar' ? 'selected' : '' }}>Pillar Page</option>
                    <option value="cluster" {{ $content->hierarchy_level == 'cluster' ? 'selected' : '' }}>Cluster Topic</option>
                    <option value="sub_cluster" {{ $content->hierarchy_level == 'sub_cluster' ? 'selected' : '' }}>Sub-Cluster</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
                <select name="status" id="status" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                    <option value="blueprint" {{ $content->status == 'blueprint' ? 'selected' : '' }}>Blueprint</option>
                    <option value="published" {{ $content->status == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="needs_reoptimize" {{ $content->status == 'needs_reoptimize' ? 'selected' : '' }}>Needs Reoptimize</option>
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label for="meta_title" class="block text-sm font-semibold text-slate-700 mb-1">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $content->meta_title) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div class="md:col-span-2">
                <label for="meta_description" class="block text-sm font-semibold text-slate-700 mb-1">Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="2"
                          class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('meta_description', $content->meta_description) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="featured_image_url" class="block text-sm font-semibold text-slate-700 mb-1">Featured Image URL (Sisipkan Gambar)</label>
                <input type="url" name="featured_image_url" id="featured_image_url" placeholder="https://example.com/image.jpg"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                <p class="text-xs text-slate-500 mt-1">URL gambar untuk disisipkan ke dalam konten sebagai gambar utama. Untuk menyisipkan gambar di dalam artikel, gunakan format Markdown: <code class="bg-slate-100 px-1 rounded">![Deskripsi Gambar](URL_Gambar)</code></p>
            </div>
        </div>

        <div class="space-y-4 pt-6 border-t border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Advanced SEO Meta</h3>
            <p class="text-xs text-slate-500 mb-4">Konfigurasi Robots, Canonical, dan Open Graph (Per Page SEO).</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="canonical" class="block text-sm font-semibold text-slate-700 mb-1">Canonical URL</label>
                    <input type="url" name="canonical" id="canonical" value="{{ old('canonical', $content->seoMeta?->canonical) }}" placeholder="https://domain.com/original-url"
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label for="robots" class="block text-sm font-semibold text-slate-700 mb-1">Robots / Indexation</label>
                    <select name="robots" id="robots" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                        <option value="index, follow" {{ old('robots', $content->seoMeta?->robots) == 'index, follow' ? 'selected' : '' }}>Index, Follow (Default)</option>
                        <option value="noindex, follow" {{ old('robots', $content->seoMeta?->robots) == 'noindex, follow' ? 'selected' : '' }}>Noindex, Follow</option>
                        <option value="index, nofollow" {{ old('robots', $content->seoMeta?->robots) == 'index, nofollow' ? 'selected' : '' }}>Index, Nofollow</option>
                        <option value="noindex, nofollow" {{ old('robots', $content->seoMeta?->robots) == 'noindex, nofollow' ? 'selected' : '' }}>Noindex, Nofollow</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="schema_type" class="block text-sm font-semibold text-slate-700 mb-1">Schema Markup Type</label>
                    <select name="schema_type" id="schema_type" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                        @php
                            $currentSchema = old('schema_type', $content->seoMeta?->schema['@'.'type'] ?? \App\Models\SystemSetting::get('seo_schema_default_type', 'Article'));
                        @endphp
                        <option value="Article" {{ $currentSchema == 'Article' ? 'selected' : '' }}>Article / BlogPosting</option>
                        <option value="LocalBusiness" {{ $currentSchema == 'LocalBusiness' ? 'selected' : '' }}>Local Business</option>
                        <option value="Organization" {{ $currentSchema == 'Organization' ? 'selected' : '' }}>Organization</option>
                        <option value="Product" {{ $currentSchema == 'Product' ? 'selected' : '' }}>Product</option>
                        <option value="None" {{ $currentSchema == 'None' ? 'selected' : '' }}>None (Disable Schema)</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="schema_custom_json" class="block text-sm font-semibold text-slate-700 mb-1">Custom Schema JSON (Opsional)</label>
                    <textarea name="schema_custom_json" id="schema_custom_json" rows="3" placeholder='{"@context": "https://schema.org", "@type": "LocalBusiness", ...}'
                              class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('schema_custom_json', isset($content->seoMeta?->schema) && is_array($content->seoMeta->schema) ? json_encode($content->seoMeta->schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea>
                    <p class="text-xs text-slate-500 mt-1">Kosongkan jika ingin menggunakan schema otomatis dari sistem. Jika diisi, ini akan digabungkan/menimpa schema otomatis.</p>
                </div>
                <div class="md:col-span-2">
                    <label for="og_title" class="block text-sm font-semibold text-slate-700 mb-1">Open Graph (Facebook/LinkedIn) Title</label>
                    <input type="text" name="og_title" id="og_title" value="{{ old('og_title', $content->seoMeta?->og_title) }}" placeholder="Custom title for social sharing..."
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div class="md:col-span-2">
                    <label for="og_description" class="block text-sm font-semibold text-slate-700 mb-1">Open Graph Description</label>
                    <textarea name="og_description" id="og_description" rows="2" placeholder="Custom description for social sharing..."
                              class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('og_description', $content->seoMeta?->og_description) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="og_image" class="block text-sm font-semibold text-slate-700 mb-1">Open Graph Image URL</label>
                    <input type="url" name="og_image" id="og_image" value="{{ old('og_image', $content->seoMeta?->og_image) }}" placeholder="https://domain.com/og-image.jpg"
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>
        </div>

        <div class="space-y-4 pt-4 border-t border-slate-100">
            <div>
                <label for="body_raw" class="block text-sm font-semibold text-slate-700 mb-1">Raw Markdown Body</label>
                <textarea name="body_raw" id="body_raw" rows="10"
                          class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('body_raw', $content->body_raw) }}</textarea>
            </div>
            <div>
                <label for="rendered_html_path" class="block text-sm font-semibold text-slate-700 mb-1">Final HTML Rendering</label>
                <textarea name="rendered_html_path" id="rendered_html_path" rows="10"
                          class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 font-mono focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('rendered_html_path', $content->rendered_html_path) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.content.show', $content->id) }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
