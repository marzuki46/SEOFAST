@extends('layouts.frontend')
@php
use App\Models\SystemSetting;
$homeTitle = SystemSetting::get('seo_global_meta_title') ?: SystemSetting::get('home_meta_title', 'SEOFAST V3 — The High-Performance SEO Operating System');
$homeDesc = SystemSetting::get('seo_global_meta_description') ?: SystemSetting::get('home_meta_description', 'Deploy high-performance, SEO-optimized AI content with automated keyword research, Topical Silo building, and real-time Google Search Console synchronization.');
@endphp
@section('title', $homeTitle)
@section('meta_description', $homeDesc)

@section('content')
{{-- HERO --}}
<section class="relative overflow-hidden bg-slate-950 pt-32 pb-20">
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[900px] h-[600px] rounded-full bg-indigo-600/20 blur-[120px]"></div>
    <div class="absolute bottom-0 right-0 w-[400px] h-[400px] rounded-full bg-purple-600/15 blur-[100px]"></div>
  </div>
  <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/25 text-indigo-400 text-xs font-bold uppercase tracking-widest mb-8">
      <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span> v3.1 Live — Production Ready
    </div>
    <h1 class="font-outfit font-extrabold text-5xl md:text-7xl text-white tracking-tight leading-[1.05] mb-6 max-w-4xl mx-auto">
      Bangun Otoritas Topikal.<br>
      <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400 bg-clip-text text-transparent">Dominasi SERP Google.</span>
    </h1>
    <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed mb-10">
      Platform SEO pertama dengan <strong class="text-white">closed-loop AI engine</strong>. Riset keyword, bangun silo topikal, generate konten berkualitas tinggi, dan sinkronisasi otomatis dengan Google Search Console.
    </p>
    <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
      <a href="{{ route('buyer.login') }}" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-400 hover:to-violet-500 text-white font-bold text-base shadow-xl shadow-indigo-500/30 transition-all hover:scale-105 active:scale-95">
        Mulai Gratis Sekarang →
      </a>
      <a href="#features" class="px-8 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-semibold hover:bg-white/10 transition-all">
        Pelajari Fitur
      </a>
    </div>
    {{-- Dashboard mockup --}}
    <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl shadow-indigo-900/40 max-w-5xl mx-auto">
      <div class="flex items-center gap-2 px-4 h-11 bg-slate-800/80 border-b border-white/10">
        <span class="w-3 h-3 rounded-full bg-red-500/80"></span><span class="w-3 h-3 rounded-full bg-yellow-500/80"></span><span class="w-3 h-3 rounded-full bg-green-500/80"></span>
        <span class="mx-auto text-xs text-slate-500 font-mono">seofast.test/admin/dashboard</span>
      </div>
      <div class="bg-slate-900 p-6 md:p-8">
        <div class="grid grid-cols-3 gap-4 mb-6">
          @foreach([['Google Index Rate','99.4%','▲ +4.2% bulan ini','indigo'],['Content Quality Index','92 / 100','● Optimal E-E-A-T','violet'],['Active Topical Silos','18 Active','Zero orphan pages','purple']] as $s)
          <div class="p-5 rounded-xl bg-white/5 border border-white/5">
            <div class="text-slate-500 text-xs font-semibold mb-1">{{ $s[0] }}</div>
            <div class="text-white font-bold text-2xl font-outfit">{{ $s[1] }}</div>
            <div class="text-xs mt-1 @if($s[3]==='indigo') text-indigo-400 @elseif($s[3]==='violet') text-violet-400 @else text-purple-400 @endif">{{ $s[2] }}</div>
          </div>
          @endforeach
        </div>
        <div class="h-36 bg-white/5 rounded-xl border border-white/5 p-5 flex flex-col justify-between">
          <div class="flex justify-between"><span class="text-sm font-semibold text-white">GSC Performance (Closed-Loop Sync)</span><span class="text-xs text-slate-500">Auto-sync 03:00 WIB</span></div>
          <div class="flex items-end gap-1.5 h-16">
            @foreach([25,40,55,70,85,100] as $h)
            <div class="flex-1 rounded-t" style="height:{{ $h }}%; background:linear-gradient(to top, #6366f1, #a78bfa{{ $loop->last ? '' : '44' }})"></div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- STATS --}}
<section class="bg-slate-900 border-y border-white/5 py-14">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
    @foreach([['10x','Kecepatan Indexing'],['90+','Skor Kualitas Konten'],['0','Orphan Pages'],['24/7','Audit SEO Otomatis']] as $s)
    <div>
      <div class="font-outfit font-extrabold text-4xl md:text-5xl bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent mb-2">{{ $s[0] }}</div>
      <div class="text-slate-400 text-sm">{{ $s[1] }}</div>
    </div>
    @endforeach
  </div>
</section>

{{-- FEATURES --}}
<section id="features" class="py-24 bg-slate-950">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-16">
      <h2 class="font-outfit font-bold text-3xl md:text-5xl text-white mb-4">Dirancang untuk Mengalahkan Kompetitor</h2>
      <p class="text-slate-400 text-lg">Satu platform untuk riset, produksi, distribusi, dan optimasi konten SEO secara otomatis.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach([
        ['Topical Silo Blueprint','Peta hierarki konten otomatis: Pillar → Cluster → Sub-cluster. Tidak ada orphan page, setiap URL saling terhubung kuat.','indigo','M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2'],
        ['7-Phase AI Content Engine','Pipeline multi-agen: Keyword → Drafter → Inquirer → Expander → Editor → HTML → SEO Meta. CQI score di atas 80 terjamin.','violet','M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z'],
        ['Closed-Loop GSC Sync','Sinkronisasi harian dengan Google Search Console. URL yang drop ranking atau belum terindeks otomatis masuk antrean re-optimasi.','purple','M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
        ['Deterministic Internal Linking','AI menghasilkan variasi anchor text (Exact, Partial, Long Tail) untuk setiap internal link. Anti-cannibalization 100%.','blue','M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244'],
        ['Google Indexing API','Submit URL langsung ke Google Indexing API. Bukan crawl biasa — langsung diprioritaskan Google dalam hitungan menit.','emerald','M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418'],
        ['Content Quality Index (CQI)','Scoring engine internal yang mengaudit keyword density, entity coverage, readability, dan struktur sebelum publish.','rose','M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.745 3.745 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.745 3.745 0 011.043 3.296A3.745 3.745 0 0121 12z'],
      ] as $f)
      <div class="p-7 rounded-2xl border border-white/8 bg-white/3 hover:bg-white/6 hover:border-{{ $f[2] }}-500/30 transition-all group">
        <div class="w-12 h-12 rounded-xl bg-{{ $f[2] }}-500/15 flex items-center justify-center text-{{ $f[2] }}-400 mb-5 group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f[3] }}"/></svg>
        </div>
        <h3 class="font-outfit font-bold text-white text-lg mb-2">{{ $f[0] }}</h3>
        <p class="text-slate-400 text-sm leading-relaxed">{{ $f[1] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- PRODUCTS --}}
@if(isset($products) && $products->count() > 0)
<section id="products" class="py-24 bg-gradient-to-b from-slate-950 to-slate-900">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
      <div>
        <div class="text-indigo-400 text-xs font-bold uppercase tracking-widest mb-3">Semua Produk Digital</div>
        <h2 class="font-outfit font-bold text-3xl md:text-4xl text-white">Pilih Paket yang Tepat</h2>
      </div>
      <a href="{{ route('products.catalog') }}" class="text-sm font-semibold text-indigo-400 hover:text-violet-300 flex items-center gap-2 transition-colors">
        Lihat Semua Produk <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
      </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($products as $product)
      <a href="{{ route('products.show', $product->slug) }}" class="group flex flex-col p-6 rounded-2xl bg-slate-800/60 border border-white/8 hover:border-indigo-500/40 hover:bg-slate-800 transition-all hover:-translate-y-1">
        <div class="flex items-start justify-between mb-4">
          <div class="w-11 h-11 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 2.25v2.25m-16.5 0V8.625m16.5 2.25v2.25m-16.5 0v-2.25m16.5 4.5v2.25m-16.5 0v-2.25"/></svg>
          </div>
          @if($product->is_featured)
          <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-full bg-amber-400/15 text-amber-400 border border-amber-500/25">Unggulan</span>
          @endif
        </div>
        <h3 class="font-outfit font-bold text-white text-lg mb-2 group-hover:text-indigo-300 transition-colors">{{ $product->name }}</h3>
        <p class="text-slate-400 text-sm leading-relaxed mb-5 flex-1 line-clamp-2">{{ $product->description }}</p>
        <div class="pt-4 border-t border-white/5 flex items-center justify-between">
          <span class="font-bold text-white">{{ $product->price > 0 ? 'Rp '.number_format($product->price,0,',','.') : 'Hubungi Kami' }}</span>
          <span class="text-xs text-indigo-400 font-semibold group-hover:translate-x-1 transition-transform inline-block">Detail →</span>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- RECENT POSTS --}}
<section class="py-24 bg-slate-900 border-t border-white/5">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
      <div>
        <div class="text-violet-400 text-xs font-bold uppercase tracking-widest mb-3">Artikel Terbaru</div>
        <h2 class="font-outfit font-bold text-3xl md:text-4xl text-white">Insight SEO Terkini</h2>
      </div>
      <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-violet-400 hover:text-violet-300 flex items-center gap-2 transition-colors">
        Ke Blog <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
      </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @forelse($recentPosts as $post)
      <article class="group flex flex-col bg-slate-800/60 border border-white/8 rounded-2xl overflow-hidden hover:border-violet-500/30 hover:-translate-y-1 transition-all">
        <div class="p-6 flex flex-col flex-1">
          <div class="flex items-center gap-2 text-xs text-slate-500 mb-4">
            <span class="px-2.5 py-1 rounded-full bg-violet-500/15 text-violet-400 font-semibold border border-violet-500/20">{{ $post->siloBlueprint->silo_name ?? 'SEO' }}</span>
            <span>{{ $post->published_at?->format('d M Y') ?? '-' }}</span>
          </div>
          <h3 class="font-outfit font-bold text-white text-lg mb-3 line-clamp-2 group-hover:text-violet-300 transition-colors">
            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title ?? $post->target_keyword }}</a>
          </h3>
          <p class="text-slate-400 text-sm leading-relaxed mb-5 flex-1 line-clamp-3">{{ $post->meta_description }}</p>
          <div class="pt-4 border-t border-white/5">
            <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-slate-300 group-hover:text-violet-400 transition-colors flex items-center gap-1">
              Baca Artikel <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>
      </article>
      @empty
      <div class="col-span-3 text-center py-16">
        <div class="w-16 h-16 rounded-2xl bg-slate-800 flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        </div>
        <p class="text-slate-500">Belum ada artikel yang dipublish.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="py-24 bg-slate-950 border-t border-white/5">
  <div class="mx-auto max-w-4xl px-4 text-center">
    <div class="rounded-3xl bg-gradient-to-br from-indigo-600/20 via-violet-600/10 to-purple-600/20 border border-indigo-500/20 p-12 md:p-16 relative overflow-hidden">
      <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(99,102,241,0.15)_0%,transparent_70%)]"></div>
      <div class="relative">
        <h2 class="font-outfit font-extrabold text-3xl md:text-5xl text-white mb-4">Siap Dominasi SERP Google?</h2>
        <p class="text-slate-400 text-lg mb-8 max-w-xl mx-auto">Mulai dengan satu silo topikal. Lihat bagaimana AI kami membangun otoritas konten yang tidak bisa ditandingi kompetitor.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="{{ route('buyer.login') }}" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-400 hover:to-violet-500 text-white font-bold shadow-xl shadow-indigo-500/30 transition-all hover:scale-105">
            Mulai Sekarang — Gratis
          </a>
          <a href="{{ route('contact.show') }}" class="px-8 py-4 rounded-2xl bg-white/5 border border-white/15 text-white font-semibold hover:bg-white/10 transition-all">
            Konsultasi Gratis
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
