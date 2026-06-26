@extends('layouts.frontend')

@section('title', 'SEOFAST Blog — Latest Insights in AI Content & SEO Automation')
@section('meta_description', 'Discover advanced technical SEO workflows, AI-driven content generation, and closed-loop Google Search Console sync strategies.')

@section('content')
<!-- Blog Header -->
<section class="relative pt-24 pb-12 border-b border-slate-200 bg-slate-100/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="font-outfit font-extrabold text-4xl md:text-6xl text-slate-900 mb-6">
            The SEOFAST <span class="bg-gradient-to-r from-brand-indigo to-brand-purple bg-clip-text text-transparent">Blog</span>
        </h1>
        <p class="text-slate-600 text-base md:text-lg max-w-2xl mx-auto">
            Practical strategies, technical tutorials, and case studies on how to scale organic search traffic using advanced automated pipelines.
        </p>
    </div>
</section>

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
                        <article class="flex flex-col bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-slate-300 hover:shadow-md transition-all group">
                            <div class="p-6 flex flex-col flex-1">
                                <!-- Meta -->
                                <div class="flex items-center gap-3 text-xs text-slate-500 mb-4">
                                    <span class="px-2 py-0.5 rounded-md bg-brand-indigo/10 text-brand-indigo font-semibold uppercase text-[10px]">
                                        {{ $post->siloBlueprint->silo_name ?? 'SEO' }}
                                    </span>
                                    <span>•</span>
                                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                                </div>
                                
                                <!-- Title -->
                                <h2 class="font-outfit font-bold text-xl text-slate-900 mb-3 line-clamp-2 group-hover:text-brand-indigo transition-colors">
                                    <a href="{{ route('blog.show', $post->slug ?: $post->getTranslation('slug', 'id', false)) }}">{{ $post->title }}</a>
                                </h2>
                                
                                <!-- Snippet -->
                                <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3 flex-1">
                                    {{ $post->meta_description }}
                                </p>
                                
                                <!-- Footer -->
                                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Indexed
                                    </div>
                                    <a href="{{ route('blog.show', $post->slug ?: $post->getTranslation('slug', 'id', false)) }}" class="text-xs font-bold text-slate-800 group-hover:text-brand-indigo transition-colors flex items-center gap-1">
                                        Read More
                                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
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

            <!-- Sidebar (Right Column) -->
            <div class="space-y-10">
                <!-- Search widget -->
                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">Search articles</h3>
                    <form action="{{ route('blog.index') }}" method="GET" class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search topics, keywords..."
                            class="w-full bg-slate-50 border border-slate-200 focus:border-brand-indigo rounded-xl px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-indigo transition-all">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Categories widget -->
                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">Categories</h3>
                    <div class="space-y-2">
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.category', $cat->slug) }}" class="flex justify-between items-center text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 px-3 py-2 rounded-xl transition-all">
                                <span>{{ $cat->silo_name }}</span>
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-xs font-mono">
                                    {{ $cat->contents_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Posts widget -->
                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">Recent posts</h3>
                    <div class="space-y-4">
                        @foreach($recentPosts as $recent)
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-slate-500 font-mono uppercase">{{ $recent->published_at ? $recent->published_at->format('M d, Y') : '' }}</span>
                                <a href="{{ route('blog.show', $recent->slug ?: $recent->getTranslation('slug', 'id', false)) }}" class="text-sm text-slate-700 hover:text-brand-indigo font-medium line-clamp-2 transition-colors">
                                    {{ $recent->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
