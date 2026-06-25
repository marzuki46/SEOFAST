@extends('layouts.frontend')

@php
    use App\Services\SeoHelper;
    $postMeta    = SeoHelper::getModelMeta($post);
    $resolvedTitle = SeoHelper::postTitle($post->title, $postMeta['title']);
@endphp

@section('title', $resolvedTitle)
@section('meta_description', $postMeta['description'] ?? '')
@section('canonical_url', $postMeta['canonical'])
@section('robots_meta', $postMeta['robots'])
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
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <!-- Article Body (Left 3 Columns) -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Header -->
                <header class="space-y-6">
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        @if($category)
                            <a href="{{ route('blog.category', $category->slug) }}" class="px-3 py-1 rounded-full bg-brand-indigo/10 border border-brand-indigo/20 text-brand-indigo font-bold text-xs uppercase">
                                {{ $category->silo_name }}
                            </a>
                        @endif
                        <span class="text-slate-300">•</span>
                        <span class="text-slate-600 font-medium">Published: {{ $post->published_at ? $post->published_at->format('F d, Y') : 'Draft' }}</span>
                        <span class="text-slate-300">•</span>
                        <span class="px-2.5 py-0.5 rounded bg-slate-100 text-xs text-slate-600 font-semibold border border-slate-200 uppercase">
                            {{ $post->hierarchy_level }} Post
                        </span>
                    </div>

                    <h1 class="font-outfit font-extrabold text-3xl md:text-5xl text-slate-900 tracking-tight leading-tight">
                        {{ $post->title }}
                    </h1>


                </header>

                <!-- Markdown Content rendered as HTML -->
                <div class="prose max-w-none border-t border-slate-200 pt-8">
                    {!! $post->html_body !!}
                </div>

                <!-- Share Buttons -->
                <div class="pt-8 mt-12 border-t border-slate-200 flex items-center gap-4">
                    <span class="font-outfit font-bold text-slate-900 text-sm uppercase tracking-wider">Bagikan Artikel:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" aria-label="Share on Facebook" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-[#1877F2] hover:text-white transition-all">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" aria-label="Share on Twitter" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-[#1DA1F2] hover:text-white transition-all">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . request()->url()) }}" aria-label="Share on WhatsApp" target="_blank" rel="noopener noreferrer" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-[#25D366] hover:text-white transition-all">
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    </a>
                </div>

                <!-- Author Bio Card -->
                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm mt-12 flex gap-4 items-start">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-brand-indigo to-brand-purple flex items-center justify-center font-outfit font-extrabold text-white text-md shadow-lg shadow-brand-indigo/10">
                        SF
                    </div>
                    <div>
                        <h4 class="font-outfit font-bold text-slate-900 text-base">Written by SEOFAST Intelligence Engine</h4>
                        <p class="text-slate-600 text-sm mt-1 leading-relaxed">
                            This article was generated and optimized using the SEOFAST content engine, utilizing semantic keyword planning, structural silo logic, and automated GSC inspection verification.
                        </p>
                    </div>
                </div>

                <!-- Related Cluster Posts inside the same Silo -->
                @if($relatedPosts->count() > 0)
                    <div class="pt-12 border-t border-slate-200">
                        <h3 class="font-outfit font-bold text-2xl text-slate-900 mb-6">Related Articles in this Silo</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedPosts as $rel)
                                <a href="{{ route('blog.show', $rel->slug ?: 'draft') }}" class="p-5 rounded-xl border border-slate-200 bg-white hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between group">
                                    <div>
                                        <span class="text-[10px] text-brand-indigo font-mono uppercase font-semibold mb-2 block">
                                            {{ $rel->hierarchy_level }}
                                        </span>
                                        <h4 class="font-outfit font-bold text-slate-900 text-sm group-hover:text-brand-indigo transition-colors line-clamp-2">
                                            {{ $rel->title }}
                                        </h4>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Table of Contents & Categories Sidebar (Right 1 Column) -->
            <div class="space-y-8">
                <!-- Table of Contents widget -->
                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm sticky top-28">
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-xs tracking-wider uppercase">Table of Contents</h3>
                    <nav id="toc" class="space-y-2 text-sm text-slate-600">
                        <!-- Dynamic list generated via JS -->
                        <ul class="space-y-2 border-l border-slate-200 pl-4" id="toc-list">
                            <li class="text-slate-400 italic">No headings found</li>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const article = document.querySelector('.prose');
        const tocList = document.getElementById('toc-list');
        
        if (article && tocList) {
            const headings = article.querySelectorAll('h2, h3');
            
            if (headings.length > 0) {
                tocList.innerHTML = '';
                
                headings.forEach((heading, index) => {
                    const text = heading.textContent;
                    const id = 'heading-' + index;
                    heading.id = id;
                    
                    const li = document.createElement('li');
                    const a = document.createElement('a');
                    a.href = '#' + id;
                    a.textContent = text;
                    a.className = 'hover:text-brand-indigo transition-colors block';
                    
                    if (heading.tagName.toLowerCase() === 'h3') {
                        li.className = 'pl-4 text-xs';
                    } else {
                        li.className = 'text-sm font-medium';
                    }
                    
                    li.appendChild(a);
                    tocList.appendChild(li);
                });
            }
        }
    });
</script>
@endpush
