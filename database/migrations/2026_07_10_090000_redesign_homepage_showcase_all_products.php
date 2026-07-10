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
        $productUrl = url('/produk');

        $cssContent = <<<'CSS'
/* ── Scroll animations ── */
.anim-fade-up {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}
.anim-fade-up.is-visible { opacity: 1; transform: translateY(0); }

.anim-scale-in {
    opacity: 0;
    transform: scale(0.92);
    transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.anim-scale-in.is-visible { opacity: 1; transform: scale(1); }

.anim-stagger > * {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.anim-stagger.is-visible > * { opacity: 1; transform: translateY(0); }
.anim-stagger.is-visible > *:nth-child(1) { transition-delay: 0.05s; }
.anim-stagger.is-visible > *:nth-child(2) { transition-delay: 0.12s; }
.anim-stagger.is-visible > *:nth-child(3) { transition-delay: 0.19s; }
.anim-stagger.is-visible > *:nth-child(4) { transition-delay: 0.26s; }
.anim-stagger.is-visible > *:nth-child(5) { transition-delay: 0.33s; }
.anim-stagger.is-visible > *:nth-child(6) { transition-delay: 0.40s; }

.card-lift {
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.card-lift:hover { transform: translateY(-6px); }

.gradient-top {
    position: relative;
}
.gradient-top::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
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

details.smooth summary::-webkit-details-marker { display: none; }
details.smooth .faq-chevron { transition: transform 0.3s ease; }
details.smooth[open] .faq-chevron { transform: rotate(180deg); }
details.smooth[open] .faq-answer { animation: fadeSlideIn 0.35s ease; }
@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

.feature-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
    background: #f1f5f9;
    color: #475569;
    transition: all 0.2s;
}
.feature-tag:hover { background: #e2e8f0; color: #1e293b; }
.feature-tag-indigo { background: #eef2ff; color: #4f46e5; }
.feature-tag-emerald { background: #ecfdf5; color: #059669; }
.feature-tag-amber { background: #fffbeb; color: #d97706; }
.feature-tag-purple { background: #faf5ff; color: #7c3aed; }
.feature-tag-rose { background: #fff1f2; color: #e11d48; }
.feature-tag-sky { background: #f0f9ff; color: #0284c7; }
CSS;

        $htmlContent = <<<HTML
<script>
document.addEventListener('DOMContentLoaded', function() {
    var obs = new IntersectionObserver(function(e) {
        e.forEach(function(e) { if (e.isIntersecting) { e.target.classList.add('is-visible'); obs.unobserve(e.target); } });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.anim-fade-up, .anim-scale-in, .anim-stagger').forEach(function(e) { obs.observe(e); });
});
</script>

<!-- ════════════════════════════════════════════════════════ -->
<!-- LAYANAN B2B                                             -->
<!-- ════════════════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up" id="layanan">
    <div class="text-center max-w-3xl mx-auto mb-12">
        <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">SOLUSI B2B</span>
        <h2 class="font-outfit font-bold text-4xl md:text-5xl text-slate-900 mb-4 leading-tight">
            Tiga Pilar Bisnis Saya
        </h2>
        <p class="text-slate-500 text-lg leading-relaxed">
            Dari produk digital hingga layanan penuh — semua dirancang untuk membantu bisnis kamu tumbuh di Google.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 anim-stagger">
        <!-- 1. Digital Products -->
        <div class="gradient-top bg-white rounded-2xl border border-slate-200 p-8 card-lift shadow-sm hover:shadow-xl">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-indigo-200">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">Produk Digital</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                Lisensi dan paket produk digital siap pakai: <strong>SEOFAST Framework</strong> (Laravel SEO automation), Pro SEO Tool, AI tools, dan template Laravel. Cocok untuk developer, agency, dan bisnis yang ingin manage SEO sendiri.
            </p>
            <div class="flex flex-wrap gap-2">
                <span class="feature-tag feature-tag-indigo">SEOFAST Framework</span>
                <span class="feature-tag feature-tag-purple">Pro SEO Tool</span>
                <span class="feature-tag feature-tag-amber">AI Tools</span>
            </div>
        </div>

        <!-- 2. SEO Services -->
        <div class="gradient-top bg-white rounded-2xl border border-slate-200 p-8 card-lift shadow-sm hover:shadow-xl">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-emerald-200">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">Jasa SEO</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                Done-for-you SEO service. Dari audit teknis, riset keyword, silo structure, optimasi on-page, sampai monitoring ranking. Cocok untuk bisnis yang ingin naik peringkat tanpa repot urusan teknis.
            </p>
            <div class="flex flex-wrap gap-2">
                <span class="feature-tag feature-tag-emerald">SEO Audit</span>
                <span class="feature-tag feature-tag-indigo">Silo Structure</span>
                <span class="feature-tag feature-tag-amber">Rank Tracker</span>
            </div>
        </div>

        <!-- 3. AI Services -->
        <div class="gradient-top bg-white rounded-2xl border border-slate-200 p-8 card-lift shadow-sm hover:shadow-xl">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-purple-200">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">Jasa AI & Otomasi</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                Layanan pembuatan konten AI berkualitas tinggi, pipeline automation, dan project AI custom. Multi-agent system (Drafter, Inquirer, Expander, Editor) — bukan asal generate.
            </p>
            <div class="flex flex-wrap gap-2">
                <span class="feature-tag feature-tag-purple">AI Content</span>
                <span class="feature-tag feature-tag-rose">Automation</span>
                <span class="feature-tag feature-tag-sky">Custom Project</span>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════════ -->
<!-- KATALOG PRODUK DIGITAL — Dynamic Product Grid           -->
<!-- ════════════════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up" id="produk">
    <div class="text-center max-w-3xl mx-auto mb-12">
        <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">KATALOG PRODUK</span>
        <h2 class="font-outfit font-bold text-4xl md:text-5xl text-slate-900 mb-4 leading-tight">
            Produk Digital
        </h2>
        <p class="text-slate-500 text-lg leading-relaxed">
            Framework, tools, dan template siap pakai untuk mempercepat dan mengoptimasi project digital kamu.
        </p>
    </div>

    <div class="seofast-products-grid anim-stagger" data-columns="2" data-limit="10"></div>
</section>

<!-- ════════════════════════════════════════════════════════ -->
<!-- KENAPA JUKI (B2B)                                       -->
<!-- ════════════════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up">
    <div class="rounded-3xl bg-gradient-to-br from-slate-900 to-slate-800 p-10 md:p-14 text-white relative overflow-hidden">
        <div class="absolute top-0 left-1/2 w-[40rem] h-[40rem] bg-indigo-500/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="relative">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block text-xs font-bold text-indigo-400 uppercase tracking-[0.2em] mb-4">B2B ADVANTAGE</span>
                <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-4 leading-tight">Kenapa Bekerja Sama dengan Saya?</h2>
                <p class="text-slate-300 text-lg">Saya membangun sistem, bukan sekadar jual jasa. Ini yang membedakan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-4xl mx-auto">
                <div class="flex items-start gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm border border-white/10">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-outfit font-bold text-white mb-1">Produk + Layanan Terintegrasi</h4>
                        <p class="text-sm text-slate-400">Beli framework-nya, atau sewa saya untuk eksekusi. Atau dua-duanya. Fleksibel sesuai kebutuhan bisnis kamu.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm border border-white/10">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-outfit font-bold text-white mb-1">Transparan & Terukur</h4>
                        <p class="text-sm text-slate-400">Semua progress bisa dipantau real-time via dashboard. Ranking, traffic, indeksasi — semuanya terbuka.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm border border-white/10">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                    </div>
                    <div>
                        <h4 class="font-outfit font-bold text-white mb-1">Teknis Bukan Omong Kosong</h4>
                        <p class="text-sm text-slate-400">Silo structure, CQI scoring, deterministic linking, render integrity — semua ada sistemnya. Bukan asal-asalan.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm border border-white/10">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-outfit font-bold text-white mb-1">Skalabel untuk Agency</h4>
                        <p class="text-sm text-slate-400">Dengan SEOFAST Framework, agency bisa handle banyak client dalam satu sistem. Multi-tenant, role management, white-label.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════════ -->
<!-- BLOG — Featured + Grid                                  -->
<!-- ════════════════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up" id="blog">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
        <div>
            <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">DARI BLOG</span>
            <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-2 leading-tight">Tips & Tutorial</h2>
            <p class="text-slate-500">Catatan seputar SEO, Laravel, AI, dan digital marketing dari pengalaman sehari-hari.</p>
        </div>
        <a href="{$blogUrl}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors group shrink-0">
            Lihat Semua Artikel
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>
    <div class="seofast-posts-grid anim-stagger" data-columns="2" data-limit="4"></div>
</section>

<!-- ════════════════════════════════════════════════════════ -->
<!-- FAQ                                                      -->
<!-- ════════════════════════════════════════════════════════ -->
<section id="faq" class="mb-24 max-w-3xl mx-auto anim-fade-up">
    <div class="text-center mb-12">
        <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">FAQ</span>
        <h2 class="font-outfit font-bold text-4xl md:text-5xl text-slate-900 mb-4 leading-tight">Pertanyaan Umum</h2>
    </div>
    <div class="space-y-3">
        <details class="smooth p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all cursor-pointer" open>
            <summary class="font-outfit font-bold text-lg text-slate-900 list-none flex items-center justify-between gap-4 select-none">
                SEOFAST Framework itu untuk siapa?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed">Untuk developer Laravel, agency SEO, dan bisnis yang ingin mengelola SEO secara terstruktur. Bisa dipakai untuk single domain atau multi-tenant.</div>
        </details>
        <details class="smooth p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all cursor-pointer">
            <summary class="font-outfit font-bold text-lg text-slate-900 list-none flex items-center justify-between gap-4 select-none">
                Apa bedanya beli produk vs pakai jasa?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed"><strong class="text-slate-900">Beli produk:</strong> kamu dapat lisensi untuk install di server sendiri, dikelola tim internal. <strong class="text-slate-900">Pakai jasa:</strong> saya yang eksekusi SEO untuk website kamu — cocok kalau tidak punya tim teknis.</div>
        </details>
        <details class="smooth p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all cursor-pointer">
            <summary class="font-outfit font-bold text-lg text-slate-900 list-none flex items-center justify-between gap-4 select-none">
                Berapa lama hasil SEO keliatan?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed">SEO butuh waktu. Umumnya 2-4 bulan mulai keliatan pergerakan. Tergantung kompetisi keyword, usia domain, dan kondisi awal website.</div>
        </details>
        <details class="smooth p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all cursor-pointer">
            <summary class="font-outfit font-bold text-lg text-slate-900 list-none flex items-center justify-between gap-4 select-none">
                Produk yang tersedia apa aja?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed">Saat ini tersedia <strong class="text-slate-900">SEOFAST Framework</strong> (SEO automation Laravel) dan <strong class="text-slate-900">Pro SEO Tool</strong>. Sedang dikerjakan: AI Content Assistant dan Laravel Starter Template. Cek halaman <a href="{$productUrl}" class="text-indigo-600 font-semibold hover:underline">/produk</a> untuk info lengkap.</div>
        </details>
    </div>
</section>

<!-- ════════════════════════════════════════════════════════ -->
<!-- FINAL CTA                                                -->
<!-- ════════════════════════════════════════════════════════ -->
<section class="anim-scale-in text-center py-16 px-6 md:py-20 md:px-12 rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white mb-8 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.1),transparent_60%)]"></div>
    <div class="relative">
        <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-6 leading-tight">Siap Mulai atau Masih Bingung?</h2>
        <p class="text-lg text-white/80 max-w-2xl mx-auto mb-10 leading-relaxed">
            Butuh SEO? Tertarik dengan produk digital? Atau ada project AI custom? Ngobrol aja dulu, gratis dan tanpa kewajiban.
        </p>
        <a href="{$contactUrl}" class="inline-flex items-center gap-2 px-10 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-xl hover:scale-105 transition-all duration-300">
            Konsultasi Gratis
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>
</section>
HTML;

        $page->update([
            'title' => 'Juki Digital Marketing — Jual Produk Digital, Jasa SEO & AI',
            'hero_headline' => 'Produk Digital, Jasa SEO & AI untuk Bisnis Anda',
            'hero_subheadline' => 'Juki Digital Marketing — jual SEOFAST Framework (Laravel SEO automation), Pro SEO Tool, layanan SEO done-for-you, dan jasa AI content & otomasi. B2B oriented, transparan, dan terukur.',
            'meta_title' => 'Juki Digital Marketing — Produk Digital, Jasa SEO & AI untuk B2B',
            'meta_description' => 'Juki Digital Marketing: jual SEOFAST Framework Laravel, Pro SEO Tool, jasa SEO, dan jasa AI untuk bisnis. B2B. Dari Silo Architecture sampai AI content pipeline — semua terintegrasi.',
            'hero_cta_text' => 'Konsultasi Gratis',
            'hero_cta_url' => $contactUrl,
            'hero_cta_text_2' => 'Lihat Produk',
            'hero_cta_url_2' => '#produk',
            'hero_features' => [
                'Produk Digital',
                'SEO Service',
                'AI Service',
                'Laravel Expert',
                'Silo Architecture',
                'Rank Tracker',
            ],
            'css_content' => $cssContent,
            'html_content' => $htmlContent,
        ]);
    }

    public function down(): void
    {
        // no rollback
    }
};
