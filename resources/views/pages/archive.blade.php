@extends('layouts.frontend')

@php
    $folderName = ucwords(str_replace('-', ' ', basename($slug)));
@endphp

@section('title', $folderName . ' Archive')
@section('meta_description', 'View all entries under ' . $folderName)

@section('content')
<!-- Archive Header -->
<section class="relative pt-24 pb-12 border-b border-slate-200 bg-slate-100/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-indigo/10 border border-brand-indigo/20 text-brand-indigo text-xs font-semibold uppercase tracking-wider mb-6">
            Page Archive
        </div>
        <h1 class="font-outfit font-extrabold text-4xl md:text-6xl text-slate-900 mb-4">
            {{ $folderName }}
        </h1>
        <p class="text-slate-600 text-sm md:text-base max-w-xl mx-auto">
            Explore all pages and sub-directories within the <code class="text-brand-indigo font-mono bg-slate-100 px-2 py-0.5 rounded border border-slate-200">/{{ $slug }}</code> folder.
        </p>
    </div>
</section>

<!-- Main Feed Layout -->
<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($childPages as $page)
                <article class="flex flex-col bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-slate-300 hover:shadow-md transition-all group">
                    <div class="p-6 flex flex-col flex-1">
                        <!-- Meta -->
                        <div class="flex items-center gap-3 text-xs text-slate-500 mb-4">
                            <span class="px-2 py-0.5 rounded-md bg-brand-indigo/10 text-brand-indigo font-semibold uppercase text-[10px]">
                                {{ $folderName }}
                            </span>
                            <span>&bull;</span>
                            <span>{{ $page->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <!-- Title -->
                        <h2 class="font-outfit font-bold text-xl text-slate-900 mb-3 line-clamp-2 group-hover:text-brand-indigo transition-colors">
                            <a href="{{ url($page->slug) }}">
                                {{ $page->title ?: ucwords(str_replace('-', ' ', basename($page->slug))) }}
                            </a>
                        </h2>
                        
                        <!-- Snippet -->
                        <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3 flex-1">
                            {{ $page->meta_description ?: 'Explore this page for more detailed information and project specifications.' }}
                        </p>
                        
                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Published
                            </div>
                            <a href="{{ url($page->slug) }}" class="text-xs font-bold text-slate-800 group-hover:text-brand-indigo transition-colors flex items-center gap-1">
                                View Details
                                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Custom styled pagination -->
        @if($childPages->hasPages())
            <div class="mt-12 pt-8 border-t border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500">
                        Showing <span class="font-semibold text-slate-700">{{ $childPages->firstItem() }}</span> to <span class="font-semibold text-slate-700">{{ $childPages->lastItem() }}</span> of <span class="font-semibold text-slate-700">{{ $childPages->total() }}</span> results
                    </p>
                </div>
                <div class="flex gap-2">
                    @if($childPages->onFirstPage())
                        <span class="px-4 py-2 text-xs font-semibold text-slate-400 border border-slate-200 rounded-xl cursor-not-allowed bg-slate-50">Previous</span>
                    @else
                        <a href="{{ $childPages->previousPageUrl() }}" class="px-4 py-2 text-xs font-semibold text-slate-700 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 hover:border-slate-300 transition-all">Previous</a>
                    @endif

                    @if($childPages->hasMorePages())
                        <a href="{{ $childPages->nextPageUrl() }}" class="px-4 py-2 text-xs font-semibold text-slate-700 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 hover:border-slate-300 transition-all">Next</a>
                    @else
                        <span class="px-4 py-2 text-xs font-semibold text-slate-400 border border-slate-200 rounded-xl cursor-not-allowed bg-slate-50">Next</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
