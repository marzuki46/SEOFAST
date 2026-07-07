<!-- Hero Split (text left, image right) -->
<section class="relative overflow-hidden @if($page->hero_bg_color) style=&quot;background-color: {{ $page->hero_bg_color }};&quot; @else bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 @endif">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_left,rgba(99,102,241,0.12),transparent_50%)]"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="max-w-xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-outfit text-white leading-tight">
                    {{ $page->hero_headline ?? $page->title }}
                </h1>
                @if($page->hero_subheadline)
                <p class="mt-6 text-lg text-slate-300 leading-relaxed">
                    {{ $page->hero_subheadline }}
                </p>
                @endif
                <div class="mt-8 flex items-center gap-4 flex-wrap">
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
                <div class="mt-10 grid grid-cols-2 gap-4">
                    @foreach($page->hero_features as $feature)
                    <div class="flex items-center gap-3 text-slate-300">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm">{{ $feature }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="relative">
                @if($page->hero_image)
                <img src="{{ $page->hero_image }}" alt="{{ $page->hero_headline ?? $page->title }}" loading="lazy" class="w-full rounded-2xl shadow-2xl shadow-indigo-600/10 object-cover max-h-[500px]">
                @else
                <div class="w-full aspect-[4/3] rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 border border-slate-700/50 flex items-center justify-center">
                    <svg class="w-24 h-24 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Body content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    {!! $page->renderContent() !!}
</div>
