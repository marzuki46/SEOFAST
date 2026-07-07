<!-- Hero Bold CTA -->
<section class="relative overflow-hidden @if($page->hero_bg_color) style=&quot;background-color: {{ $page->hero_bg_color }};&quot; @else bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 @endif">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,rgba(168,85,247,0.15),transparent_50%)]"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 rounded-full text-sm text-white/80 mb-8">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
            <span class="text-xs font-semibold">Featured</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-outfit text-white leading-tight">
            {{ $page->hero_headline ?? $page->title }}
        </h1>
        @if($page->hero_subheadline)
        <p class="mt-6 text-lg md:text-xl text-purple-200/80 max-w-2xl mx-auto leading-relaxed">
            {{ $page->hero_subheadline }}
        </p>
        @endif
        <div class="mt-12 flex items-center justify-center gap-4 flex-wrap">
            @if($page->hero_cta_text)
            <a href="{{ $page->hero_cta_url ?? '#' }}" class="inline-flex items-center gap-2 px-10 py-5 bg-white text-purple-900 font-bold text-lg rounded-xl shadow-2xl shadow-purple-600/20 hover:bg-slate-100 transition-all hover:scale-105">
                {{ $page->hero_cta_text }}
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            @endif
            @if($page->hero_cta_text_2)
            <a href="{{ $page->hero_cta_url_2 ?? '#' }}" class="inline-flex items-center gap-2 px-10 py-5 border-2 border-white/20 text-white font-bold text-lg rounded-xl hover:bg-white/10 transition-all">
                {{ $page->hero_cta_text_2 }}
            </a>
            @endif
        </div>
        @if($page->hero_features)
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
            @foreach($page->hero_features as $feature)
            <div class="bg-white/5 rounded-xl p-5 text-center backdrop-blur-sm border border-white/10">
                <p class="text-white font-semibold">{{ $feature }}</p>
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
