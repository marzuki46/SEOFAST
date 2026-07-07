<!-- Hero Full Background Image -->
<section class="relative overflow-hidden min-h-[70vh] flex items-center"
    @if($page->hero_image)
    style="background-image: url('{{ $page->hero_image }}'); background-size: cover; background-position: center;"
    @else
    class="bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950"
    @endif
>
    @if($page->hero_image)
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/60 to-transparent"></div>
    @endif
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 w-full">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-outfit text-white leading-tight max-w-2xl">
            {{ $page->hero_headline ?? $page->title }}
        </h1>
        @if($page->hero_subheadline)
        <p class="mt-6 text-lg md:text-xl text-slate-200 max-w-xl leading-relaxed">
            {{ $page->hero_subheadline }}
        </p>
        @endif
        @if($page->hero_cta_text)
        <div class="mt-10">
            <a href="{{ $page->hero_cta_url ?? '#' }}" class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all hover:scale-105">
                {{ $page->hero_cta_text }}
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Body content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    {!! $page->renderContent() !!}
</div>
