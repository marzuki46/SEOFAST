<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $page = Page::where('slug', 'home')->first();
        if (!$page) return;

        $contactUrl = url('/contact');
        $blogUrl = url('/blog');

        // ── CSS with minimalist scroll animations ──
        $cssContent = <<<'CSS'
/* ── Scroll-triggered animations ── */
.anim-fade-up {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}
.anim-fade-up.is-visible {
    opacity: 1;
    transform: translateY(0);
}

.anim-scale-in {
    opacity: 0;
    transform: scale(0.92);
    transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.anim-scale-in.is-visible {
    opacity: 1;
    transform: scale(1);
}

.anim-stagger > * {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.anim-stagger.is-visible > * {
    opacity: 1;
    transform: translateY(0);
}
.anim-stagger.is-visible > *:nth-child(1) { transition-delay: 0.05s; }
.anim-stagger.is-visible > *:nth-child(2) { transition-delay: 0.12s; }
.anim-stagger.is-visible > *:nth-child(3) { transition-delay: 0.19s; }
.anim-stagger.is-visible > *:nth-child(4) { transition-delay: 0.26s; }

/* Card hover lift */
.card-lift {
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.card-lift:hover {
    transform: translateY(-6px);
}

/* Gradient border accent */
.gradient-border {
    position: relative;
}
.gradient-border::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1, #a78bfa, #6366f1);
    background-size: 200% 100%;
    animation: shimmer 3s ease-in-out infinite;
    border-radius: 3px 3px 0 0;
}
@keyframes shimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

/* Step connector line */
.step-line {
    position: absolute;
    top: 2.5rem;
    left: 1.5rem;
    bottom: -2.5rem;
    width: 2px;
    background: linear-gradient(to bottom, #6366f1, #c4b5fd);
}
.step-line::after {
    content: '';
    position: absolute;
    top: 0;
    left: -3px;
    width: 8px;
    height: 8px;
    background: #6366f1;
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot {
    0%, 100% { box-shadow: 0 0 0 0 rgba(99,102,241,0.4); }
    50% { box-shadow: 0 0 0 8px rgba(99,102,241,0); }
}

/* Smooth details accordion */
details.smooth details {
    transition: all 0.3s ease;
}
details.smooth summary::-webkit-details-marker {
    display: none;
}
details.smooth .faq-chevron {
    transition: transform 0.3s ease;
}
details.smooth[open] .faq-chevron {
    transform: rotate(180deg);
}
details.smooth[open] .faq-answer {
    animation: fade-slide-in 0.35s ease;
}
@keyframes fade-slide-in {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Floating orb for hero accent (applied by template) */
.glow-orb-hero {
    animation: float-orb 8s ease-in-out infinite;
}
@keyframes float-orb {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(20px, -20px) scale(1.05); }
    66% { transform: translate(-10px, 10px) scale(0.95); }
}
CSS;

        // ── HTML content with animations ──
        $htmlContent = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function() {
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.anim-fade-up, .anim-scale-in, .anim-stagger').forEach(function(el) {
        observer.observe(el);
    });
});
</script>

<!-- ════════════════════════════════════════════ -->
<!-- LAYANAN                                      -->
<!-- ════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up" id="layanan">
    <div class="text-center max-w-3xl mx-auto mb-14">
        <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">LAYANAN</span>
        <h2 class="font-outfit font-bold text-4xl md:text-5xl text-slate-900 mb-4 leading-tight">Apa yang Saya Kerjakan</h2>
        <p class="text-slate-500 text-lg leading-relaxed">Setiap project ditangani langsung dengan pendekatan teknis yang jujur, bukan sekadar janji.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 anim-stagger">
        <!-- Card 1 -->
        <div class="gradient-border bg-white rounded-2xl border border-slate-200 p-8 card-lift shadow-sm hover:shadow-xl">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-6">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">SEO Website</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Audit teknis menyeluruh, riset keyword realistis, on-page optimization, dan struktur silo. Bikin website kamu ramah Google dari pondasi.</p>
        </div>

        <!-- Card 2 -->
        <div class="gradient-border bg-white rounded-2xl border border-slate-200 p-8 card-lift shadow-sm hover:shadow-xl">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-6">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">Website Laravel</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Pembuatan website pakai Laravel — cepat, SEO-friendly, dan gampang dikelola. Landing page, company profile, toko online, sampai platform custom.</p>
        </div>

        <!-- Card 3 -->
        <div class="gradient-border bg-white rounded-2xl border border-slate-200 p-8 card-lift shadow-sm hover:shadow-xl">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 mb-6">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">SEOFAST & AI Projects</h3>
            <p class="text-slate-500 text-sm leading-relaxed">SEOFAST adalah framework Laravel untuk SEO automation yang saya bangun. Juga menerima project AI custom dan integrasi sistem sesuai kebutuhan.</p>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════ -->
<!-- PROSES KERJA                                 -->
<!-- ════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up">
    <div class="text-center max-w-3xl mx-auto mb-14">
        <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">BAGAIMANA SAYA BEKERJA</span>
        <h2 class="font-outfit font-bold text-4xl md:text-5xl text-slate-900 mb-4 leading-tight">Proses yang Jelas & Terukur</h2>
        <p class="text-slate-500 text-lg">Bukan asal jalan. Setiap project punya alur yang terstruktur dari awal sampai selesai.</p>
    </div>

    <div class="relative max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
        <!-- Step 1 -->
        <div class="relative flex flex-col items-center text-center md:block md:text-left">
            <div class="hidden md:block absolute top-0 left-8 w-px h-full bg-gradient-to-b from-indigo-300 to-transparent -z-10"></div>
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-outfit font-bold text-2xl mb-5 mx-auto md:mx-0">01</div>
            <h3 class="font-outfit font-bold text-lg text-slate-900 mb-2">Diskusi & Riset</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Ngobrol dulu soal goals, target audiens, dan kondisi website kamu. Saya lakukan riset keyword dan audit awal.</p>
        </div>
        <!-- Step 2 -->
        <div class="relative flex flex-col items-center text-center md:block md:text-left">
            <div class="hidden md:block absolute top-0 left-8 w-px h-full bg-gradient-to-b from-purple-300 to-transparent -z-10"></div>
            <div class="w-16 h-16 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 font-outfit font-bold text-2xl mb-5 mx-auto md:mx-0">02</div>
            <h3 class="font-outfit font-bold text-lg text-slate-900 mb-2">Eksekusi & Implementasi</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Mulai dari optimasi teknis, pembuatan konten, atau development website. Semua dikerjakan dengan standar kualitas yang terukur.</p>
        </div>
        <!-- Step 3 -->
        <div class="relative flex flex-col items-center text-center md:block md:text-left">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 font-outfit font-bold text-2xl mb-5 mx-auto md:mx-0">03</div>
            <h3 class="font-outfit font-bold text-lg text-slate-900 mb-2">Monitoring & Optimasi</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Pantau progress via dashboard. Tracking peringkat keyword, traffic, dan indeksasi. Optimasi berkelanjutan berdasarkan data real.</p>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════ -->
<!-- TENTANG SEOFAST                              -->
<!-- ════════════════════════════════════════════ -->
<section class="mb-24 anim-scale-in p-10 md:p-16 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white overflow-hidden relative">
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
    <div class="relative max-w-4xl mx-auto">
        <span class="inline-block text-xs font-bold text-indigo-400 uppercase tracking-[0.2em] mb-4">TENTANG SEOFAST</span>
        <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-6 leading-tight">SEOFAST Itu Apa?</h2>
        <p class="text-slate-300 text-lg leading-relaxed max-w-3xl mb-8">
            <strong class="text-white">SEOFAST</strong> adalah ekosistem tools SEO berbasis Laravel yang saya bangun sendiri. Bukan plugin WordPress — tapi framework untuk mengelola SEO secara terstruktur: dari silo builder, AI content pipeline, monitoring ranking, sampai integrasi Google Search Console.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-center gap-4 bg-white/5 rounded-xl p-5 backdrop-blur-sm border border-white/10">
                <svg class="w-6 h-6 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm text-slate-300">Built with <strong class="text-white">Laravel</strong>, not WordPress</span>
            </div>
            <div class="flex items-center gap-4 bg-white/5 rounded-xl p-5 backdrop-blur-sm border border-white/10">
                <svg class="w-6 h-6 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm text-slate-300">SEO automation & <strong class="text-white">Silo Structure</strong></span>
            </div>
            <div class="flex items-center gap-4 bg-white/5 rounded-xl p-5 backdrop-blur-sm border border-white/10">
                <svg class="w-6 h-6 text-indigo-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm text-slate-300"><strong class="text-white">AI-assisted</strong> content pipeline multi-agent</span>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════ -->
<!-- KENAPA JUKI                                  -->
<!-- ════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up">
    <div class="flex flex-col lg:flex-row gap-12 items-center">
        <div class="lg:w-5/12">
            <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">KENAPA JUKI?</span>
            <h2 class="font-outfit font-bold text-4xl md:text-5xl text-slate-900 mb-6 leading-tight">Bukan Sekadar Jasa SEO Biasa</h2>
            <p class="text-slate-500 text-lg leading-relaxed mb-8">Saya kerja dengan sistem, bukan asal-asalan. Setiap project pakai pendekatan teknis yang terstruktur dan transparan.</p>
            <a href="{$contactUrl}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors group">
                Diskusikan Project Kamu
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
        <div class="lg:w-7/12 grid grid-cols-1 sm:grid-cols-2 gap-5 anim-stagger">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 card-lift shadow-sm hover:shadow-md">
                <span class="text-indigo-600 font-outfit font-bold text-3xl block mb-3">01</span>
                <h4 class="font-outfit font-bold text-slate-900 mb-1">Silo Structure</h4>
                <p class="text-slate-500 text-sm">Konten diatur dalam topik terstruktur, bukan acak. Google liat kamu sebagai authority.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 card-lift shadow-sm hover:shadow-md">
                <span class="text-purple-600 font-outfit font-bold text-3xl block mb-3">02</span>
                <h4 class="font-outfit font-bold text-slate-900 mb-1">Transparan</h4>
                <p class="text-slate-500 text-sm">Kamu bisa pantau progress real-time. Gak perlu ngejar-ngejar laporan.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 card-lift shadow-sm hover:shadow-md">
                <span class="text-emerald-600 font-outfit font-bold text-3xl block mb-3">03</span>
                <h4 class="font-outfit font-bold text-slate-900 mb-1">AI Sebagai Alat</h4>
                <p class="text-slate-500 text-sm">Saya pakai AI untuk bantu produksi — dipantau, diedit, dan dipastikan kualitasnya.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 card-lift shadow-sm hover:shadow-md">
                <span class="text-amber-600 font-outfit font-bold text-3xl block mb-3">04</span>
                <h4 class="font-outfit font-bold text-slate-900 mb-1">Fokus ke Kamu</h4>
                <p class="text-slate-500 text-sm">Saya dengerin dulu kebutuhan kamu, baru ngasih solusi. Bukan jual paket kaku.</p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════ -->
<!-- BLOG                                         -->
<!-- ════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
        <div>
            <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">DARI BLOG</span>
            <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-2 leading-tight">Tips & Tutorial Terbaru</h2>
            <p class="text-slate-500">Catatan seputar SEO, Laravel, dan digital marketing dari pengalaman sehari-hari.</p>
        </div>
        <a href="{$blogUrl}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors group shrink-0">
            Lihat Semua Artikel
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>
    <div class="seofast-posts-grid anim-stagger" data-columns="3" data-limit="3"></div>
</section>

<!-- ════════════════════════════════════════════ -->
<!-- PRODUK & PROJECT                             -->
<!-- ════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up">
    <div class="text-center max-w-3xl mx-auto mb-14">
        <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">PRODUK</span>
        <h2 class="font-outfit font-bold text-4xl md:text-5xl text-slate-900 mb-4 leading-tight">Yang Saya Kembangkan</h2>
        <p class="text-slate-500 text-lg">Produk dan layanan yang sedang dalam pengembangan dan siap membantu bisnis kamu.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 anim-stagger">
        <div class="bg-white rounded-2xl p-8 border border-slate-200 card-lift shadow-sm hover:shadow-xl">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-indigo-200">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-2">SEOFAST Framework</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Laravel package untuk SEO automation: silo builder, AI content pipeline, rank tracker, dan GSC integration. Cocok untuk developer dan agency.</p>
        </div>
        <div class="bg-white rounded-2xl p-8 border border-slate-200 card-lift shadow-sm hover:shadow-xl">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-emerald-200">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V12m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-2">Footer Pages / Tema</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Tema dan template Laravel yang saya buat untuk project website. Bisa dipakai langsung atau dikustom sesuai kebutuhan.</p>
        </div>
        <div class="bg-white rounded-2xl p-8 border border-slate-200 card-lift shadow-sm hover:shadow-xl">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-purple-200">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-2">AI Project Custom</h3>
            <p class="text-slate-500 text-sm leading-relaxed">Ada kebutuhan AI, automation, atau sistem custom? Diskusiin aja — saya usahakan sesuai request dengan teknologi yang tepat.</p>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════ -->
<!-- FAQ                                          -->
<!-- ════════════════════════════════════════════ -->
<section id="faq" class="mb-24 max-w-3xl mx-auto anim-fade-up">
    <div class="text-center mb-14">
        <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">FAQ</span>
        <h2 class="font-outfit font-bold text-4xl md:text-5xl text-slate-900 mb-4 leading-tight">Pertanyaan Umum</h2>
    </div>
    <div class="space-y-3">
        <details class="smooth p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all cursor-pointer" open>
            <summary class="font-outfit font-bold text-lg text-slate-900 list-none flex items-center justify-between gap-4 select-none">
                SEO-nya gimana cara kerja?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed">Saya liat kondisi website kamu dulu, audit teknis, riset keyword yang realistis, terus optimasi on-page dan konten. Semua dipantau lewat dashboard.</div>
        </details>
        <details class="smooth p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all cursor-pointer">
            <summary class="font-outfit font-bold text-lg text-slate-900 list-none flex items-center justify-between gap-4 select-none">
                Berapa lama hasilnya?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed">SEO butuh waktu. Umumnya 2-4 bulan mulai keliatan pergerakan. Tergantung kompetisi keyword, usia domain, dan kondisi awal website.</div>
        </details>
        <details class="smooth p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all cursor-pointer">
            <summary class="font-outfit font-bold text-lg text-slate-900 list-none flex items-center justify-between gap-4 select-none">
                Kalau cuma butuh bikin website aja?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed">Bisa. Saya terima project pembuatan website aja tanpa SEO. Laravel, responsive, SEO-friendly basic sudah include.</div>
        </details>
        <details class="smooth p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all cursor-pointer">
            <summary class="font-outfit font-bold text-lg text-slate-900 list-none flex items-center justify-between gap-4 select-none">
                SEOFAST itu plugin WordPress?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed">Bukan. SEOFAST adalah framework Laravel untuk SEO automation. Dibangun di atas Laravel, bukan WordPress. Cocok untuk yang serius ngurus SEO secara teknis dan terstruktur.</div>
        </details>
        <details class="smooth p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all cursor-pointer">
            <summary class="font-outfit font-bold text-lg text-slate-900 list-none flex items-center justify-between gap-4 select-none">
                Gimana cara mulai?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed">Klik tombol Konsultasi Gratis di atas. Kita ngobrol dulu, gak ada kewajiban. Saya dengerin dulu kebutuhan kamu, baru bahas solusinya.</div>
        </details>
    </div>
</section>

<!-- ════════════════════════════════════════════ -->
<!-- FINAL CTA                                    -->
<!-- ════════════════════════════════════════════ -->
<section class="anim-scale-in text-center py-16 px-6 md:py-20 md:px-12 rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white mb-8 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.1),transparent_60%)]"></div>
    <div class="relative">
        <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-6 leading-tight">Ada yang Mau Didiskusikan?</h2>
        <p class="text-lg text-white/80 max-w-2xl mx-auto mb-10 leading-relaxed">Butuh website? Pengen naik peringkat di Google? Atau ada project custom? Ngobrol aja dulu, gratis dan tanpa kewajiban.</p>
        <a href="{$contactUrl}" class="inline-flex items-center gap-2 px-10 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-xl hover:scale-105 transition-all duration-300">
            Hubungi Saya
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>
</section>
HTML;

        $page->update([
            'title' => 'Juki Digital Marketing — Jasa SEO & Pembuatan Website Laravel',
            'hero_headline' => 'Jasa SEO & Pembuatan Website Laravel untuk Bisnis Anda',
            'hero_subheadline' => 'Juki Digital Marketing — pendekatan teknis yang jujur & terstruktur untuk naik peringkat di Google. Dari audit hingga eksekusi, semuanya transparan.',
            'meta_title' => 'Juki Digital Marketing — Jasa SEO Website & Pembuatan Website Laravel',
            'meta_description' => 'Jasa SEO website & pembuatan website Laravel oleh Juki Digital Marketing. Juga mengembangkan SEOFAST, framework SEO automation berbasis Laravel. Konsultasi gratis.',
            'hero_cta_text' => 'Konsultasi Gratis',
            'hero_cta_url' => $contactUrl,
            'hero_cta_text_2' => 'Jelajahi Blog',
            'hero_cta_url_2' => $blogUrl,
            'hero_features' => [
                'SEO Optimization',
                'Web Development',
                'AI Automation',
                'Laravel Expert',
                'Silo Structure',
                'Rank Tracker',
            ],
            'css_content' => $cssContent,
            'html_content' => $htmlContent,
        ]);
    }

    public function down(): void
    {
        // no rollback — previous migration handles restore
    }
};
