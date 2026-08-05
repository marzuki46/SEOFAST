@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;
    $siteName = SystemSetting::get('site_name', config('app.name'));
    $brandName = SystemSetting::get('logo_alt', $siteName);
    $page = request('page', 1);
    $titleSuffix = $page > 1 ? " - Halaman {$page}" : "";
    $canonicalUrl = url()->current() . ($page > 1 ? '?page=' . $page : '');
    $blogTitle = SystemSetting::get('blog_meta_title', 'Blog & Artikel ' . $brandName . ' — Tips SEO, Web Development & Digital Marketing');
    $blogDesc = SystemSetting::get('blog_meta_description', 'Jelajahi artikel, tips, dan tutorial dari ' . $brandName . ' — panduan optimasi SEO, konten AI, pembuatan website, dan strategi pemasaran digital untuk mempercepat pertumbuhan bisnis Anda.');
    $blogHeading = SystemSetting::get('blog_index_heading', 'Blog & Artikel ' . $brandName . ' — Tips SEO, Web Development & Digital Marketing');
    $blogTagline = SystemSetting::get('blog_index_tagline', 'Jelajahi artikel dan tips dari ' . $brandName . ' — panduan optimasi SEO, konten AI, pembuatan website, dan strategi pemasaran digital untuk mempercepat pertumbuhan bisnis Anda.');
@endphp
@section('title', $blogTitle . $titleSuffix)
@section('meta_description', $blogDesc)
@section('canonical_url', $canonicalUrl)

@section('head_extra')
@if($posts->hasMorePages())
    <link rel="next" href="{{ $posts->nextPageUrl() }}">
@endif
@if(!$posts->onFirstPage())
    <link rel="prev" href="{{ $posts->previousPageUrl() }}">
@endif
@endsection

@section('schema_markup')
@php
    $crumbs = [
        ['name' => 'Home',   'url' => route('home')],
        ['name' => 'Blog',   'url' => request()->url()],
    ];
    
    // Blog index schema
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        'name' => $blogHeading,
        'description' => $blogTagline,
        'url' => request()->url(),
        'publisher' => [
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => url('/'),
        ],
        'isPartOf' => [
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => url('/'),
        ],
    ];
@endphp
{!! \App\Services\SeoHelper::breadcrumbSchema($crumbs) !!}
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection

@section('content')
@if(request()->filled('q'))
    @section('robots_meta', 'noindex, follow')
@endif

<!-- Blog Hero -->
@include('partials.juki-hero', [
    'badge' => 'Blog & Artikel',
    'heading' => $blogHeading,
    'subheadline' => $blogTagline,
    'ctaPrimaryText' => 'Konsultasi Gratis',
    'ctaSecondaryText' => 'Kirim Inquiry',
])

<!-- Main Feed Layout -->
<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Articles Grid (Left 2 Columns) -->
            <div class="lg:col-span-2 space-y-8">
                @if(request()->filled('q'))
                    <div class="p-4 rounded-xl border border-brand-indigo/20 bg-brand-indigo/5 flex justify-between items-center mb-6">
                        <span class="text-sm text-slate-700">
                            Search results for: <strong class="text-slate-900">"{{ request('q') }}"</strong>
                        </span>
                        <a href="{{ route('blog.index') }}" class="text-xs font-semibold text-brand-indigo hover:text-brand-purple hover:underline">Clear Search</a>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($posts as $post)

                        @include('blog.partials.card')
                    @empty
                        <div class="col-span-2 text-center py-20 border border-slate-200 rounded-2xl bg-white shadow-sm">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="font-outfit font-semibold text-lg text-slate-900 mb-2">No Articles Found</h3>
                            <p class="text-slate-500 text-sm">We couldn't find any articles matching your search query. Try other keywords.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Custom styled pagination -->
                @if($posts->hasPages())
                    <div class="pt-8 border-t border-slate-200 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-slate-500">
                                Showing <span class="font-semibold text-slate-700">{{ $posts->firstItem() }}</span> to <span class="font-semibold text-slate-700">{{ $posts->lastItem() }}</span> of <span class="font-semibold text-slate-700">{{ $posts->total() }}</span> results
                            </p>
                        </div>
                        <div class="flex gap-2">
                            @if($posts->onFirstPage())
                                <span class="px-4 py-2 text-xs font-semibold text-slate-400 border border-slate-200 rounded-xl cursor-not-allowed bg-slate-50">Previous</span>
                            @else
                                <a href="{{ $posts->previousPageUrl() }}" class="px-4 py-2 text-xs font-semibold text-slate-700 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 hover:border-slate-300 transition-all">Previous</a>
                            @endif

                            @if($posts->hasMorePages())
                                <a href="{{ $posts->nextPageUrl() }}" class="px-4 py-2 text-xs font-semibold text-slate-700 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 hover:border-slate-300 transition-all">Next</a>
                            @else
                                <span class="px-4 py-2 text-xs font-semibold text-slate-400 border border-slate-200 rounded-xl cursor-not-allowed bg-slate-50">Next</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            @include('blog.partials.sidebar')

        </div>
    </div>
</section>
@endsection
