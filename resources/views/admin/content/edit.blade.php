@extends('layouts.admin')

@php
    $siteName = \App\Models\SystemSetting::get('site_name', 'SEOFAST');
@endphp

@section('title', 'Edit Content: ' . $content->target_keyword . ' - SEOFAST')
@section('page_title', 'Edit Content Manually')

@section('admin_content')
<div class="max-w-6xl mx-auto space-y-6">
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

    <form action="{{ route('admin.content.update', $content->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6"
          x-data="{
            socialPreviewOpen: false,
            get previewTitle() {
                const og = document.getElementById('og_title')?.value;
                const meta = document.getElementById('meta_title')?.value;
                return og || meta || '{{ $siteName }}';
            },
            get previewDesc() {
                const og = document.getElementById('og_description')?.value;
                const meta = document.getElementById('meta_description')?.value;
                return og || meta || '{{ $siteName }}';
            },
            get previewImage() {
                const og = document.getElementById('og_image')?.value;
                const feat = document.getElementById('featured_image_url')?.value;
                return og || feat || '';
            }
          }">
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
                    <option value="draft" {{ $content->status == 'draft' ? 'selected' : '' }}>Draft (Review)</option>
                    <option value="published" {{ $content->status == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="needs_reoptimize" {{ $content->status == 'needs_reoptimize' ? 'selected' : '' }}>Needs Reoptimize</option>
                </select>
            </div>

            <div>
                <label for="published_at" class="block text-sm font-semibold text-slate-700 mb-1">Schedule Publish (Opsional)</label>
                <input type="datetime-local" name="published_at" id="published_at" 
                       value="{{ $content->published_at ? $content->published_at->format('Y-m-d\TH:i') : '' }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                <p class="text-xs text-slate-500 mt-1">Isi untuk post di masa lalu (backdate) atau masa depan (schedule).</p>
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
                <label class="block text-sm font-semibold text-slate-700 mb-1">Featured Image (Upload atau URL)</label>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="featured_image_upload" class="block text-xs text-slate-500 mb-1">Upload Gambar Baru</label>
                        <input type="file" name="featured_image_upload" id="featured_image_upload" accept="image/*"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>
                    <div class="flex-1">
                        <label for="featured_image_url" class="block text-xs text-slate-500 mb-1">Atau Gunakan URL (Akan tertimpa jika Anda upload file)</label>
                        <input type="url" name="featured_image_url" id="featured_image_url" value="{{ old('featured_image_url', $content->featured_image_url) }}" placeholder="https://example.com/image.jpg"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                @if($content->featured_image_url)
                    <div class="mt-3">
                        <p class="text-xs text-slate-500 mb-1">Gambar Saat Ini:</p>
                        <img src="{{ $content->featured_image_url }}" alt="Featured" class="h-24 w-auto rounded border border-slate-200 object-cover">
                    </div>
                @endif
                <p class="text-xs text-slate-500 mt-2">Untuk menyisipkan gambar di dalam artikel (Markdown), gunakan format: <code class="bg-slate-100 px-1 rounded">![Deskripsi Gambar](URL_Gambar)</code></p>
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
                    <textarea name="schema_custom_json" id="schema_custom_json" rows="3" placeholder='{"@@context": "https://schema.org", "@@type": "LocalBusiness", ...}'
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
                <div class="md:col-span-2">
                    <label for="twitter_card" class="block text-sm font-semibold text-slate-700 mb-1">Twitter Card Type</label>
                    <select name="twitter_card" id="twitter_card" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                        <option value="summary_large_image" {{ old('twitter_card', $content->seoMeta?->twitter_card ?? 'summary_large_image') == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                        <option value="summary" {{ old('twitter_card', $content->seoMeta?->twitter_card) == 'summary' ? 'selected' : '' }}>Summary (Small Image)</option>
                    </select>
                </div>
            </div>

            <!-- Social Preview Button -->
            <div class="flex justify-end">
                <button type="button" @click="socialPreviewOpen = !socialPreviewOpen" class="px-5 py-2.5 bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-semibold rounded-xl hover:from-sky-600 hover:to-indigo-700 transition shadow-sm text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                    Preview Social
                </button>
            </div>
        </div>

        {{-- Social Preview Modal --}}
        <div x-show="socialPreviewOpen" x-cloak class="fixed inset-0 z-50 flex items-start justify-center pt-10 pb-10 overflow-y-auto bg-black/40"
             @click.self="socialPreviewOpen = false" x-transition>
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold font-outfit text-slate-900">Social Media Preview</h3>
                    <button @click="socialPreviewOpen = false" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                @php
                    $siteName = \App\Models\SystemSetting::get('site_name', config('app.name'));
                @endphp

                {{-- Facebook Preview --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </h4>
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white max-w-md">
                        <div class="bg-slate-100 h-48 flex items-center justify-center overflow-hidden" x-show="$el.nextElementSibling.querySelector('img').naturalWidth">
                            <img :src="previewImage" alt="" class="w-full h-full object-cover" x-on:error.once="$el.parentElement.classList.add('hidden')" onerror="this.parentElement.parentElement.style.display='none'">
                        </div>
                        <div class="px-4 py-3 bg-white">
                            <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold truncate">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</div>
                            <div class="text-sm font-bold text-slate-900 leading-snug mt-0.5 line-clamp-2" x-text="previewTitle || '{{ addslashes($siteName) }}'"></div>
                            <div class="text-sm text-slate-500 mt-1 line-clamp-2" x-text="previewDesc || '{{ addslashes($siteName) }}'"></div>
                        </div>
                    </div>
                </div>

                {{-- Twitter/X Preview --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        X (Twitter)
                    </h4>
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white max-w-md">
                        <div class="bg-slate-100 h-48 flex items-center justify-center overflow-hidden" x-show="$el.nextElementSibling.querySelector('img').naturalWidth">
                            <img :src="previewImage" alt="" class="w-full h-full object-cover" x-on:error.once="$el.parentElement.classList.add('hidden')" onerror="this.parentElement.parentElement.style.display='none'">
                        </div>
                        <div class="px-4 py-3 bg-white">
                            <div class="text-sm font-bold text-slate-900 leading-snug line-clamp-2" x-text="previewTitle || '{{ addslashes($siteName) }}'"></div>
                            <div class="text-sm text-slate-500 mt-0.5 line-clamp-2" x-text="previewDesc || ''"></div>
                            <div class="text-xs text-slate-400 mt-1">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</div>
                        </div>
                    </div>
                </div>

                {{-- LinkedIn Preview --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        LinkedIn
                    </h4>
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white max-w-md">
                        <div class="bg-slate-100 h-48 flex items-center justify-center overflow-hidden" x-show="$el.nextElementSibling.querySelector('img').naturalWidth">
                            <img :src="previewImage" alt="" class="w-full h-full object-cover" x-on:error.once="$el.parentElement.classList.add('hidden')" onerror="this.parentElement.parentElement.style.display='none'">
                        </div>
                        <div class="px-4 py-3 bg-white">
                            <div class="text-sm font-bold text-slate-900 leading-snug line-clamp-2" x-text="previewTitle || '{{ addslashes($siteName) }}'"></div>
                            <div class="text-sm text-slate-500 mt-0.5 line-clamp-3" x-text="previewDesc || '{{ addslashes($siteName) }}'"></div>
                            <div class="text-xs text-slate-400 mt-1">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4 pt-4 border-t border-slate-100">
            <div>
                <label for="body_raw" class="block text-sm font-semibold text-slate-700 mb-1">Article Content</label>
                <textarea name="body_raw" id="body_raw" rows="20"
                          class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('body_raw', $content->body_raw) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.content.show', $content->id) }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancel</a>
            <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#body_raw',
        height: 600,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code | fullscreen | help',
        menubar: 'file edit view insert format tools table help',
        branding: false,
        promotion: false,
        content_style: 'body { font-family: Inter, sans-serif; font-size: 15px; line-height: 1.8; color: #1e293b; }'
    });
});
</script>
@endpush
