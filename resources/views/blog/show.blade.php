@extends('layouts.frontend')

@php
    use App\Services\SeoHelper;
    $postMeta    = SeoHelper::getModelMeta($post);
    $resolvedTitle = SeoHelper::postTitle($post->title, $postMeta['title']);
    // Gating hreflang EN: hanya jika post ini punya body bahasa Inggris terisi.
    $hasEnContent = $post->hasEnglishContent();
@endphp

@section('title', $resolvedTitle)
@section('meta_description', $postMeta['description'] ?? '')
@section('canonical_url', $postMeta['canonical'])
@if(isset($isPreview) && $isPreview)
    @section('robots_meta', 'noindex, nofollow')
@else
    @section('robots_meta', $postMeta['robots'])
@endif
@if(app()->getLocale() === 'en' && !$hasEnContent)
    {{-- Halaman EN tanpa konten EN → noindex agar tidak jadi duplikat tipis --}}
    @section('robots_meta', 'noindex, follow')
@endif
@section('og_image', $postMeta['og_image'])
@section('og_title', $postMeta['og_title'] ?? $resolvedTitle)
@section('og_description', $postMeta['og_desc'] ?? ($postMeta['description'] ?? ''))

@section('styles')
<style>
    /* Styling elements inside the dynamic HTML content */
    .prose h1, .prose h2, .prose h3, .prose h4 {
        font-family: 'Outfit', sans-serif;
        color: #0f172a;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .prose h2 {
        font-size: 1.75rem;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 0.5rem;
    }
    .prose h3 {
        font-size: 1.4rem;
    }
    .prose p {
        color: #475569;
        line-height: 1.8;
        margin-top: 1.25rem;
        margin-bottom: 1.25rem;
        font-size: 1.125rem;
    }
    .prose strong {
        color: #0f172a;
        font-weight: 600;
    }
    .prose a {
        color: #4f46e5;
        text-decoration: underline;
        font-weight: 500;
    }
    .prose a:hover {
        color: #4338ca;
    }
    .prose ul, .prose ol {
        margin-top: 1rem;
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }
    .prose li {
        color: #475569;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 1.05rem;
        list-style-type: disc;
    }
    .prose code {
        background-color: rgba(15, 23, 42, 0.05);
        color: #db2777;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        font-family: monospace;
        font-size: 0.9em;
    }
    .prose pre {
        background-color: #0f172a;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 0.75rem;
        padding: 1.25rem;
        overflow-x: auto;
        margin: 1.5rem 0;
    }
    .prose pre code {
        background-color: transparent;
        color: #f8fafc;
        padding: 0;
        border-radius: 0;
        font-size: 0.9rem;
    }
    .prose table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.95rem;
    }
    .prose thead {
        background-color: #f1f5f9;
    }
    .prose th {
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 600;
        color: #0f172a;
        border-bottom: 2px solid #e2e8f0;
    }
    .prose td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
        vertical-align: top;
    }
    .prose tr:last-child td {
        border-bottom: none;
    }
    .prose tr:hover td {
        background-color: #f8fafc;
    }

    /* Dynamic article HTML must never widen the viewport on small screens. */
    .article-main-grid,
    .article-column,
    .article-column .prose,
    .article-column header {
        min-width: 0;
        max-width: 100%;
    }
    .article-column .prose {
        overflow-wrap: anywhere;
        word-break: break-word;
    }
    .article-column .prose img,
    .article-column .prose video,
    .article-column .prose iframe {
        max-width: 100%;
        height: auto;
    }
    .article-column .prose table {
        display: block;
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .article-column .prose pre {
        max-width: 100%;
        overflow-x: auto;
    }
    @media (max-width: 767px) {
        .article-main-grid {
            display: block;
        }
        .article-column {
            width: 100%;
        }
        .article-column h1 {
            font-size: clamp(1.8rem, 8vw, 2.5rem);
            line-height: 1.15;
            overflow-wrap: anywhere;
        }
        .article-column .prose h2 {
            font-size: 1.45rem;
            line-height: 1.25;
        }
        .article-column .prose h3 {
            font-size: 1.2rem;
            line-height: 1.3;
        }
        .article-column .prose p,
        .article-column .prose li {
            font-size: 1rem;
            line-height: 1.75;
        }
        .article-column .prose table {
            font-size: 0.875rem;
        }
        .article-column .prose th,
        .article-column .prose td {
            min-width: 9rem;
            padding: 0.65rem 0.75rem;
        }
    }
</style>

@endsection

@section('schema_markup')
    {!! \App\Services\SeoHelper::renderSchema($post) !!}

{{-- BreadcrumbList JSON-LD (SSR) --}}
@php
    $crumbs = array_filter([
        ['name' => 'Home',   'url' => route('home')],
        ['name' => 'Blog',   'url' => route('blog.index')],
        $category ? ['name' => $category->silo_name, 'url' => route('blog.category', $category->slug)] : null,
        ['name' => $post->title, 'url' => request()->url()],
    ]);
@endphp
{!! \App\Services\SeoHelper::breadcrumbSchema($crumbs) !!}
@endsection

@section('content')
<!-- Breadcrumbs -->
<div class="border-b border-slate-200 py-4 bg-slate-100/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
            <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('blog.index') }}" class="hover:text-slate-900 transition-colors">Blog</a>
            @if($category)
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('blog.category', $category->slug) }}" class="hover:text-slate-900 transition-colors">{{ $category->silo_name }}</a>
            @endif
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-800 truncate max-w-[200px] md:max-w-xs font-semibold">{{ $post->title }}</span>
        </nav>
    </div>
</div>

<!-- Article Main Section -->
<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="article-main-grid grid min-w-0 grid-cols-1 gap-12 lg:grid-cols-4">
            
            <!-- Article Body (Left 3 Columns) -->
            <div class="article-column min-w-0 space-y-8 lg:col-span-3">
                <!-- Header -->
                <header class="space-y-6">
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        @if($category)
                            <a href="{{ route('blog.category', $category->slug) }}" class="px-3 py-1 rounded-full bg-brand-indigo/10 border border-brand-indigo/20 text-brand-indigo font-bold text-xs uppercase">
                                {{ $category->silo_name }}
                            </a>
                        @endif
                        @if($post->relationLoaded('tags') && $post->tags->count() > 0)
                            @foreach($post->tags as $tag)
                                <a href="{{ route('blog.tag', $tag->slug) }}" class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200 transition">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        @endif
                        <span class="text-slate-300">•</span>
                        <span class="text-slate-600 font-medium">
                            @if($post->revised_at)
                                Last Updated: {{ $post->revised_at->format('F d, Y') }}
                            @elseif($post->published_at)
                                Published: {{ $post->published_at->format('F d, Y') }}
                            @else
                                {{ $post->updated_at ? $post->updated_at->format('F d, Y') : '' }}
                            @endif
                        </span>
                    </div>

                    <h1 class="font-outfit font-extrabold text-3xl md:text-5xl text-slate-900 tracking-tight leading-tight">
                        {{ $post->title }}
                    </h1>

                    @if($post->featured_image_url)
                        <figure class="mt-8 mb-2 overflow-hidden rounded-3xl border border-slate-200 shadow-sm bg-slate-100">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full h-auto max-h-[520px] object-cover" loading="eager" fetchpriority="high" decoding="async">
                        </figure>
                    @endif


                </header>

                @php
                    $articleChunks = preg_split('/(?<=<\/p>)/i', (string) $post->html_body, -1, PREG_SPLIT_NO_EMPTY);
                    $articleHasMiddleCards = $relatedPosts->count() > 0 && count($articleChunks) > 2;
                    $articleInsertAt = $articleHasMiddleCards ? max(1, (int) ceil(count($articleChunks) / 2)) : count($articleChunks);
                @endphp

                <!-- Article body with contextual related cards in the middle -->
                <div class="prose max-w-none border-t border-slate-200 pt-8">
                    {!! implode('', array_slice($articleChunks, 0, $articleInsertAt)) !!}
                </div>

                @if($articleHasMiddleCards)
                    <section class="my-12 rounded-3xl border border-brand-indigo/15 bg-gradient-to-br from-slate-50 via-white to-indigo-50/60 p-5 sm:p-8" aria-labelledby="related-middle-title">
                        <div class="mb-6 flex items-end justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-indigo">Bacaan lanjutan</p>
                                <h2 id="related-middle-title" class="mt-2 font-outfit text-2xl font-extrabold tracking-tight text-slate-900">Masih berkaitan dengan kebutuhan Anda</h2>
                            </div>
                            <a href="{{ route('blog.index') }}" class="hidden text-sm font-bold text-brand-indigo hover:text-brand-purple sm:inline-flex">Lihat semua artikel →</a>
                        </div>
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                            @foreach($relatedPosts as $rel)
                                <a href="{{ route('blog.show', $rel->slug) }}" class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-brand-indigo/30 hover:shadow-lg">
                                    <div class="aspect-[16/9] overflow-hidden bg-slate-100">
                                        <img src="{{ $rel->featured_image_url ?: asset('images/seofast-placeholder.svg') }}" alt="{{ $rel->featured_image_alt ?? $rel->title }}" loading="lazy" width="640" height="360" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                    </div>
                                    <div class="flex flex-1 flex-col p-5">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand-indigo">{{ $rel->siloBlueprint->silo_name ?? 'Artikel terkait' }}</span>
                                        <h3 class="mt-2 line-clamp-3 font-outfit text-lg font-bold leading-snug text-slate-900 group-hover:text-brand-indigo">{{ $rel->title }}</h3>
                                        <span class="mt-auto pt-5 text-sm font-bold text-slate-600 group-hover:text-brand-indigo">Baca artikel →</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                <div class="prose max-w-none">
                    {!! implode('', array_slice($articleChunks, $articleInsertAt)) !!}
                </div>

                <!-- Share Buttons -->
                <div class="pt-8 mt-12 border-t border-slate-200 flex items-center gap-4">
                    <span class="font-outfit font-bold text-slate-900 text-sm uppercase tracking-wider">Bagikan Artikel:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" aria-label="Share on Facebook" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-[#1877F2] hover:text-white transition-all">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&amp;text={{ urlencode($post->title) }}" aria-label="Share on Twitter" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-[#1DA1F2] hover:text-white transition-all">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . request()->url()) }}" aria-label="Share on WhatsApp" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-[#25D366] hover:text-white transition-all">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    </a>
                </div>

                <!-- Author Bio Card -->
                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm mt-12 flex gap-4 items-start">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-brand-indigo to-brand-purple flex items-center justify-center font-outfit font-extrabold text-white text-md shadow-lg shadow-brand-indigo/10">
                            {{ strtoupper(substr(\App\Models\SystemSetting::get('author_name', 'Tri Marzuki'), 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-outfit font-bold text-slate-900 text-base">Ditulis oleh {{ \App\Models\SystemSetting::get('author_name', 'Tri Marzuki') }} — Founder Juki Web Developer Solo</h4>
                        <p class="text-slate-600 text-sm mt-1 leading-relaxed">
                            {{ \App\Models\SystemSetting::get('author_bio', 'Tri Marzuki membantu UMKM dan perusahaan di Solo Raya membangun website, aplikasi web, SEO, dan automasi digital yang lebih aman, terarah, dan mudah dikembangkan.') }}
                        </p>
                    </div>
                </div>

            </div>

            <!-- Table of Contents & Categories Sidebar (Right 1 Column) -->
            <div class="space-y-8">
                <!-- Table of Contents widget -->
                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm sticky top-28">
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-xs tracking-wider uppercase">Table of Contents</h3>
                    <nav id="toc" class="space-y-2 text-sm text-slate-600">
                        <!-- Dynamic list generated via JS -->
                        <ul class="space-y-2 border-l border-slate-200 pl-4" id="toc-list">
                            @php $tocItems = $post->toc; @endphp
                            @if(count($tocItems) > 0)
                                @foreach($tocItems as $heading)
                                    <li class="{{ $heading['level'] === 3 ? 'pl-4 text-xs' : 'text-sm font-medium' }}">
                                        <a href="#{{ $heading['id'] }}" class="hover:text-brand-indigo transition-colors block">
                                            {{ $heading['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <li class="text-slate-400 italic">No headings found</li>
                            @endif
                        </ul>
                    </nav>
                </div>

                <!-- Recent Posts in this Silo -->
                @if($relatedPosts->count() > 0)
                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-xs tracking-wider uppercase">Also in {{ $category ? $category->silo_name : 'this category' }}</h3>
                    <div class="space-y-4">
                        @foreach($relatedPosts as $recent)
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-slate-500 font-mono uppercase">{{ $recent->published_at ? $recent->published_at->format('M d, Y') : '' }}</span>
                                <a href="{{ route('blog.show', $recent->slug) }}" class="text-sm text-slate-700 hover:text-brand-indigo font-medium line-clamp-2 transition-colors">
                                    {{ $recent->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection
