@extends('layouts.frontend')

@php
    $page = request('page', 1);
    $titleSuffix = $page > 1 ? " - Halaman {$page}" : "";
@endphp
@section('title', $tag->name . ' — Tag' . $titleSuffix)
@section('meta_description', 'Artikel dengan tag ' . $tag->name)
@section('robots_meta', 'index, follow')

@if($posts->hasPages())
@section('head_extra')
@if($posts->hasMorePages())
    <link rel="next" href="{{ $posts->nextPageUrl() }}">
@endif
@if(!$posts->onFirstPage())
    <link rel="prev" href="{{ $posts->previousPageUrl() }}">
@endif
@endsection
@endif

@section('content')
<section class="relative pt-24 pb-12 border-b border-slate-200 bg-slate-100/30">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <p class="text-sm font-semibold text-brand-indigo mb-2">Tag</p>
        <h1 class="font-outfit font-extrabold text-4xl md:text-5xl text-slate-900 mb-4">
            {{ $tag->name }}
        </h1>
        <p class="text-slate-600 text-base max-w-xl mx-auto">
            {{ $posts->total() }} artikel dengan tag ini
        </p>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 space-y-8">
                @forelse($posts as $post)
                    <article class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition group">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                                <span>{{ $post->published_at?->format('M d, Y') }}</span>
                                @if($post->siloBlueprint)
                                    <a href="{{ route('blog.category', \Illuminate\Support\Str::slug($post->siloBlueprint->silo_name)) }}" class="text-brand-indigo hover:underline font-medium">{{ $post->siloBlueprint->silo_name }}</a>
                                @endif
                            </div>
                            <h2 class="font-outfit font-bold text-xl text-slate-900 mb-3 group-hover:text-brand-indigo transition">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="text-slate-600 text-sm leading-relaxed line-clamp-3">{{ $post->excerpt }}</p>
                            @if($post->tags->count() > 0)
                            <div class="flex flex-wrap gap-1.5 mt-4">
                                @foreach($post->tags as $tagItem)
                                    <a href="{{ route('blog.tag', $tagItem->slug) }}" class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600 hover:bg-brand-indigo/10 hover:text-brand-indigo transition">{{ $tagItem->name }}</a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="text-center py-16 text-slate-400">
                        <p class="text-lg font-semibold">No articles found with this tag.</p>
                    </div>
                @endforelse

                @if($posts->hasPages())
                <div class="pt-6">
                    {{ $posts->links() }}
                </div>
                @endif
            </div>

            <aside class="space-y-8">
                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-xs tracking-wider uppercase">Categories</h3>
                    <div class="space-y-2">
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.category', \Illuminate\Support\Str::slug($cat->silo_name)) }}" class="flex items-center justify-between text-sm text-slate-600 hover:text-brand-indigo transition">
                                <span>{{ $cat->silo_name }}</span>
                                <span class="text-xs text-slate-400">{{ $cat->contents_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-xs tracking-wider uppercase">Recent Posts</h3>
                    <div class="space-y-4">
                        @foreach($recentPosts as $recent)
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-slate-500 font-mono uppercase">{{ $recent->published_at?->format('M d, Y') }}</span>
                                <a href="{{ route('blog.show', $recent->slug) }}" class="text-sm text-slate-700 hover:text-brand-indigo font-medium line-clamp-2 transition">{{ $recent->title }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
