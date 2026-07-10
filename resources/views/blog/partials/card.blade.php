<article class="flex flex-col bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-slate-300 hover:shadow-md transition-all group">
    <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden bg-slate-100">
        <img src="{{ $post->featured_image_url ?: asset('images/seofast-placeholder.svg') }}" alt="{{ $post->featured_image_alt ?? $post->title }}" loading="lazy" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
    </a>
    <div class="p-6 flex flex-col flex-1">
        <div class="flex items-center gap-2 text-xs text-slate-500 mb-4 flex-wrap">
            <span class="px-2 py-0.5 rounded-md bg-brand-indigo/10 text-brand-indigo font-semibold uppercase text-[10px]">
                {{ $post->siloBlueprint->silo_name ?? 'SEO' }}
            </span>
            @if(method_exists($post, 'tags') && $post->relationLoaded('tags') && $post->tags->count() > 0)
                @foreach($post->tags->take(2) as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}" class="hover:text-brand-indigo transition">#{{ $tag->name }}</a>
                @endforeach
            @endif
            @if($post->published_at)
            <span class="hidden sm:inline">&bull;</span>
            <span class="hidden sm:inline">{{ $post->published_at->format('M d, Y') }}</span>
            @endif
        </div>
        <h2 class="font-outfit font-bold text-xl text-slate-900 mb-3 line-clamp-2 group-hover:text-brand-indigo transition-colors">
            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
        </h2>
        <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3 flex-1">
            {{ $post->excerpt }}
        </p>
        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Indexed
            </div>
            <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-slate-800 group-hover:text-brand-indigo transition-colors flex items-center gap-1">
                Read More
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</article>
