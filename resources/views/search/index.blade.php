@extends('layouts.frontend')

@section('title', 'Search: ' . e($search) . ' — ' . \App\Models\SystemSetting::get('site_name', config('app.name')))
@section('meta_description', 'Search results for "' . e($search) . '" — ' . $totalResults . ' result' . ($totalResults !== 1 ? 's' : '') . ' found.')
@section('robots', 'noindex, follow')
@section('canonical_url', route('search', ['q' => $q]))
@section('og_title', 'Search: ' . e($search))
@section('og_description', 'Search results for "' . e($search) . '" — ' . $totalResults . ' result' . ($totalResults !== 1 ? 's' : '') . ' found.')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 font-outfit mb-2">
            Search results for &ldquo;{{ e($search) }}&rdquo;
        </h1>
        <p class="text-slate-500">{{ $totalResults }} result{{ $totalResults !== 1 ? 's' : '' }} found</p>
    </div>

    <form method="GET" action="{{ route('search') }}" class="mb-10">
        <div class="flex gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ e($q) }}" placeholder="Search again..."
                       class="w-full rounded-xl border border-slate-300 pl-11 pr-4 py-3 text-sm focus:border-brand-indigo focus:ring-1 focus:ring-brand-indigo outline-none">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">Search</button>
        </div>
    </form>

    @if($totalResults === 0)
    <div class="text-center py-20 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <h3 class="text-lg font-bold text-slate-700 font-outfit">No results found</h3>
        <p class="text-slate-500 text-sm mt-2">Try different keywords or browse our pages.</p>
    </div>
    @else
    <div x-data="{ tab: '{{ $posts->isNotEmpty() ? 'posts' : ($products->isNotEmpty() ? 'products' : 'pages') }}' }">
        <!-- Tab Buttons -->
        <div class="flex gap-1 mb-8 border-b border-slate-200">
            @if($posts->isNotEmpty())
            <button @click="tab = 'posts'" :class="{ 'border-brand-indigo text-brand-indigo': tab === 'posts', 'border-transparent text-slate-500 hover:text-slate-700': tab !== 'posts' }" class="flex items-center gap-2 px-5 py-3 text-sm font-semibold border-b-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                Blog Posts
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $posts->count() }}</span>
            </button>
            @endif
            @if($products->isNotEmpty())
            <button @click="tab = 'products'" :class="{ 'border-brand-indigo text-brand-indigo': tab === 'products', 'border-transparent text-slate-500 hover:text-slate-700': tab !== 'products' }" class="flex items-center gap-2 px-5 py-3 text-sm font-semibold border-b-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Products
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $products->count() }}</span>
            </button>
            @endif
            @if($pages->isNotEmpty())
            <button @click="tab = 'pages'" :class="{ 'border-brand-indigo text-brand-indigo': tab === 'pages', 'border-transparent text-slate-500 hover:text-slate-700': tab !== 'pages' }" class="flex items-center gap-2 px-5 py-3 text-sm font-semibold border-b-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Pages
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $pages->count() }}</span>
            </button>
            @endif
        </div>

        <!-- Blog Posts Tab -->
        @if($posts->isNotEmpty())
        <div x-show="tab === 'posts'" x-transition>
            <div class="space-y-4">
                @foreach($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="block bg-white border border-slate-200 rounded-2xl p-5 hover:border-indigo-200 hover:shadow-sm transition-all">
                    <h3 class="font-bold text-slate-900 mb-1">{{ $post->title }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2">{{ $post->excerpt }}</p>
                    <span class="text-xs text-indigo-600 font-medium mt-2 inline-block">{{ $post->published_at?->diffForHumans() ?? '' }}</span>
                </a>
                @endforeach
            </div>
            @if($posts->count() >= 5)
            <a href="{{ route('blog.index', ['q' => $search]) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 mt-4 inline-block">View all blog posts &rarr;</a>
            @endif
        </div>
        @endif

        <!-- Products Tab -->
        @if($products->isNotEmpty())
        <div x-show="tab === 'products'" x-transition>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($products as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="block bg-white border border-slate-200 rounded-2xl p-5 hover:border-emerald-200 hover:shadow-sm transition-all">
                    <h3 class="font-bold text-slate-900 mb-1">{{ $product->name }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2">{{ $product->description ?? '' }}</p>
                    <p class="text-sm font-bold text-emerald-600 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </a>
                @endforeach
            </div>
            @if($products->count() >= 5)
            <a href="{{ route('products.catalog', ['q' => $search]) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-800 mt-4 inline-block">View all products &rarr;</a>
            @endif
        </div>
        @endif

        <!-- Pages Tab -->
        @if($pages->isNotEmpty())
        <div x-show="tab === 'pages'" x-transition>
            <div class="space-y-4">
                @foreach($pages as $page)
                <a href="{{ $locale === 'en' ? route('en.page.show', $page->slug) : route('page.show', $page->slug) }}" class="block bg-white border border-slate-200 rounded-2xl p-5 hover:border-amber-200 hover:shadow-sm transition-all">
                    <h3 class="font-bold text-slate-900 mb-1">{{ $page->title }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2">{{ $page->meta_description ?? '' }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
