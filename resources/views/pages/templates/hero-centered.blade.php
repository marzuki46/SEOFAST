<!-- Hero Centered -->
<section class="relative overflow-hidden @if($page->hero_bg_color) style=&quot;background-color: {{ $page->hero_bg_color }};&quot; @else bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 @endif">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(99,102,241,0.15),transparent_50%)]"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-outfit text-white leading-tight">
            {{ $page->hero_headline ?? $page->title }}
        </h1>
        @if($page->hero_subheadline)
        <p class="mt-6 text-lg md:text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
            {{ $page->hero_subheadline }}
        </p>
        @endif
        <div class="mt-10 flex items-center justify-center gap-4 flex-wrap">
            @if($page->hero_cta_text)
            <a href="{{ $page->hero_cta_url ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all hover:scale-105">
                {{ $page->hero_cta_text }}
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            @endif
            @if($page->hero_cta_text_2)
            <a href="{{ $page->hero_cta_url_2 ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-slate-500 text-slate-300 hover:border-indigo-500 hover:text-white font-bold rounded-xl transition-all">
                {{ $page->hero_cta_text_2 }}
            </a>
            @endif
        </div>
        @if($page->hero_features)
        <div class="mt-16 grid grid-cols-2 md:grid-cols-{{ min(count($page->hero_features), 4) }} gap-6 max-w-3xl mx-auto">
            @foreach($page->hero_features as $feature)
            <div class="flex items-center gap-3 text-slate-300">
                <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm">{{ $feature }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- Body content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    {!! $page->renderContent() !!}
</div>
