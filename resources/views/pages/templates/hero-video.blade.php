<!-- Hero Video Background -->
<section class="relative overflow-hidden min-h-[75vh] flex items-center">
    @if($page->hero_video_url)
    <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
        <source src="{{ $page->hero_video_url }}" type="video/mp4">
    </video>
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/80 via-slate-900/50 to-slate-900/80"></div>
    @else
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-900">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(99,102,241,0.1),transparent_60%)]"></div>
    </div>
    @endif
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
            <a href="{{ $page->hero_cta_url ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-slate-900 font-bold rounded-xl shadow-lg hover:bg-slate-100 transition-all hover:scale-105">
                {{ $page->hero_cta_text }}
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            @endif
            @if($page->hero_cta_text_2)
            <a href="{{ $page->hero_cta_url_2 ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-white/30 text-white hover:bg-white/10 font-bold rounded-xl transition-all">
                {{ $page->hero_cta_text_2 }}
            </a>
            @endif
        </div>
    </div>
</section>

<!-- Body content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    {!! $page->renderContent() !!}
</div>
