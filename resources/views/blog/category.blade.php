@extends('layouts.frontend')

@php
    $page = request('page', 1);
    $titleSuffix = $page > 1 ? " - Halaman {$page}" : "";
    $canonicalUrl = url()->current() . ($page > 1 ? '?page=' . $page : '');
@endphp
@section('title', 'Category: ' . $category->silo_name . ' — SEOFAST Blog' . $titleSuffix)
@section('meta_description', 'Read high-performance articles about ' . $category->silo_name . ' optimized with the SEOFAST topical silo engine.')
@section('canonical_url', $canonicalUrl)

@section('schema_markup')
@php
    $crumbs = [
        ['name' => 'Home',   'url' => route('home')],
        ['name' => 'Blog',   'url' => route('blog.index')],
        ['name' => $category->silo_name, 'url' => request()->url()],
    ];
@endphp
{!! \App\Services\SeoHelper::breadcrumbSchema($crumbs) !!}
@endsection

@section('content')
<!-- Breadcrumb -->
<div class="border-b border-slate-200 py-4 bg-slate-100/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
            <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('blog.index') }}" class="hover:text-slate-900 transition-colors">Blog</a>
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-slate-800 font-semibold">{{ $category->silo_name }}</span>
        </nav>
    </div>
</div>

<!-- Blog Category Header -->
<section class="relative pt-24 pb-12 border-b border-slate-200 bg-slate-100/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-indigo/10 border border-brand-indigo/20 text-brand-indigo text-xs font-semibold uppercase tracking-wider mb-6">
            Category Archive
        </div>
        <h1 class="font-outfit font-extrabold text-4xl md:text-6xl text-slate-900 mb-4">
            {{ $category->silo_name }}
        </h1>
        <p class="text-slate-600 text-sm md:text-base max-w-xl mx-auto">
            Topical Silo Seed Keyword: <code class="text-brand-indigo font-mono bg-slate-100 px-2 py-0.5 rounded border border-slate-200">{{ $category->seed_keyword }}</code>
        </p>
    </div>
</section>

<!-- Main Feed Layout -->
<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Articles Grid (Left 2 Columns) -->
            <div class="lg:col-span-2 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($posts as $post)

                        @include('blog.partials.card')
                    @empty
                        <div class="col-span-2 text-center py-20 border border-slate-200 rounded-2xl bg-white shadow-sm">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="font-outfit font-semibold text-lg text-slate-900 mb-2">No Articles Found</h3>
                            <p class="text-slate-500 text-sm">There are no published articles in this category yet.</p>
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

            @include('blog.partials.sidebar', ['activeCategory' => $category])

        </div>
    </div>
</section>
@endsection
