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

/* Feature tags in product section */
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

/* Changelog timeline */
.changelog-item {
    position: relative;
    padding-left: 2rem;
    padding-bottom: 2rem;
}
.changelog-item::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0.5rem;
    width: 2px;
    height: 100%;
    background: #e2e8f0;
}
.changelog-item:last-child::before { display: none; }
.changelog-item::after {
    content: '';
    position: absolute;
    left: 0;
    top: 0.5rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background: #6366f1;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #6366f1;
}
.changelog-item.amber::after { background: #d97706; box-shadow: 0 0 0 2px #d97706; }
.changelog-item.emerald::after { background: #059669; box-shadow: 0 0 0 2px #059669; }
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
                Lisensi dan paket produk digital siap pakai: <strong>SEOFAST Framework</strong> (Laravel SEO automation), tema/template Laravel, dan tools pendukung lainnya. Cocok untuk developer, agency, dan bisnis yang ingin manage SEO sendiri.
            </p>
            <div class="flex flex-wrap gap-2">
                <span class="feature-tag feature-tag-indigo">SEOFAST Framework</span>
                <span class="feature-tag feature-tag-purple">Laravel Template</span>
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
<!-- PRODUK DIGITAL: SEOFAST FRAMEWORK — DETAIL LENGKAP     -->
<!-- ════════════════════════════════════════════════════════ -->
<section class="mb-24 anim-scale-in" id="seofast-product">
    <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 text-white overflow-hidden relative">
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-indigo-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[20rem] h-[20rem] bg-purple-500/10 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>

        <div class="relative px-6 py-14 md:px-14 md:py-20">
            <!-- Header -->
            <div class="text-center max-w-4xl mx-auto mb-16">
                <span class="inline-block text-xs font-bold text-indigo-400 uppercase tracking-[0.2em] mb-4">PRODUK DIGITAL</span>
                <h2 class="font-outfit font-bold text-4xl md:text-6xl mb-4 leading-tight">
                    SEOFAST <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">Framework</span>
                </h2>
                <p class="text-slate-300 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
                    Platform SEO automation berbasis <strong class="text-white">Laravel</strong> untuk membangun, mengelola, dan mengoptimasi website secara terstruktur. Bukan CMS biasa — ini adalah SEO Operating System.
                </p>
                <div class="flex flex-wrap justify-center gap-3 mt-8">
                    <span class="feature-tag !bg-indigo-900/50 !text-indigo-300 !border !border-indigo-700/50">Laravel Native</span>
                    <span class="feature-tag !bg-emerald-900/50 !text-emerald-300 !border !border-emerald-700/50">Multi-Provider AI</span>
                    <span class="feature-tag !bg-purple-900/50 !text-purple-300 !border !border-purple-700/50">Silo Architecture</span>
                    <span class="feature-tag !bg-amber-900/50 !text-amber-300 !border !border-amber-700/50">GSC Integration</span>
                    <span class="feature-tag !bg-sky-900/50 !text-sky-300 !border !border-sky-700/50">Rank Tracker</span>
                    <span class="feature-tag !bg-rose-900/50 !text-rose-300 !border !border-rose-700/50">Multi-Tenant</span>
                </div>
            </div>

            <!-- Feature Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 max-w-6xl mx-auto anim-stagger">

                <!-- AI Content Engine -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">AI Content Engine</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• 4-phase multi-agent pipeline (Drafter → Inquirer → Expander → Editor)</li>
                        <li class="flex items-start gap-2">• Support OpenAI, Gemini, Claude, DeepSeek, custom API</li>
                        <li class="flex items-start gap-2">• Auto fallback antar provider jika salah satu down</li>
                        <li class="flex items-start gap-2">• Content frameworks: AIDA, PAS, How-To, Listicle</li>
                        <li class="flex items-start gap-2">• Tone options: Formal, Friendly, Persuasive, Authoritative, Conversational</li>
                        <li class="flex items-start gap-2">• CQI Scoring (Content Quality Index) threshold ≥ 80</li>
                        <li class="flex items-start gap-2">• Auto image selection + WebP conversion</li>
                    </ul>
                </div>

                <!-- Silo Architect -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">Topical Map / Silo Architect</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• Visual node graph dengan Drawflow UI</li>
                        <li class="flex items-start gap-2">• AI generates keyword clusters: Pillar → Cluster → Sub-cluster</li>
                        <li class="flex items-start gap-2">• KGR (Keyword Golden Ratio) scoring</li>
                        <li class="flex items-start gap-2">• Search volume tracking per keyword</li>
                        <li class="flex items-start gap-2">• Lock/unlock silo setelah content creation</li>
                        <li class="flex items-start gap-2">• Multi-language silo support</li>
                    </ul>
                </div>

                <!-- Internal Linking -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">Deterministic Internal Linking</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• AI-mapped link relationships Pillar ↔ Cluster ↔ Sub-cluster</li>
                        <li class="flex items-start gap-2">• Zero orphan page enforcement</li>
                        <li class="flex items-start gap-2">• Mandatory anchor text injection saat rendering</li>
                        <li class="flex items-start gap-2">• Visual link graph overlay</li>
                        <li class="flex items-start gap-2">• Max 5 links per source content</li>
                        <li class="flex items-start gap-2">• AI-generated anchor keywords with fallback</li>
                    </ul>
                </div>

                <!-- GSC Integration -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center text-sky-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">Google Search Console</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• OAuth2 authentication with auto token refresh</li>
                        <li class="flex items-start gap-2">• URL Inspection API (2000 req/day)</li>
                        <li class="flex items-start gap-2">• Search Analytics API (clicks, impressions, CTR, position)</li>
                        <li class="flex items-start gap-2">• Google Indexing API (submit URLs via Service Account)</li>
                        <li class="flex items-start gap-2">• Coverage state tracking (Submitted, Crawled-Not-Indexed, etc.)</li>
                        <li class="flex items-start gap-2">• Scheduled daily sync jobs</li>
                    </ul>
                </div>

                <!-- SEO Audit Tools -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">SEO Audit & Quality Tools</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• URL Audit — comprehensive per-URL SEO check</li>
                        <li class="flex items-start gap-2">• Broken Link Scanner — scan & manage broken links</li>
                        <li class="flex items-start gap-2">• Duplicate Content Detection — AI cosine similarity</li>
                        <li class="flex items-start gap-2">• Readability Scoring — compute scores for all content</li>
                        <li class="flex items-start gap-2">• 404 Error Tracker + Redirect Manager</li>
                        <li class="flex items-start gap-2">• Canonical Mapping Service</li>
                    </ul>
                </div>

                <!-- Rank Tracker -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">SERP Rank Tracker</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• Daily SERP position snapshots</li>
                        <li class="flex items-start gap-2">• Ranking trend analysis over time</li>
                        <li class="flex items-start gap-2">• SERP feature detection (Featured Snippet, PAA, AI Overview)</li>
                        <li class="flex items-start gap-2">• Multi-device (desktop/mobile) & multi-country tracking</li>
                        <li class="flex items-start gap-2">• Auto-reoptimization jika ranking drop &gt;5 posisi</li>
                        <li class="flex items-start gap-2">• Content Freshness Engine (weekly re-evaluation)</li>
                    </ul>
                </div>

                <!-- Schema + Rendering -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center text-cyan-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">Schema & Rendering Engine</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• JSON-LD injection per content/page</li>
                        <li class="flex items-start gap-2">• Schema types: Article, FAQPage, HowTo, Product, LocalBusiness</li>
                        <li class="flex items-start gap-2">• Dynamic sitemap.xml with priority scores</li>
                        <li class="flex items-start gap-2">• Dynamic robots.txt from crawl budget rules</li>
                        <li class="flex items-start gap-2">• Ghost publish with noindex placeholder</li>
                        <li class="flex items-start gap-2">• Multi-language hreflang (ID/EN)</li>
                    </ul>
                </div>

                <!-- Digital Marketplace -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">Digital Marketplace</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• Product catalog with SEO-friendly URLs</li>
                        <li class="flex items-start gap-2">• Pre-order system with launch management</li>
                        <li class="flex items-start gap-2">• Midtrans payment integration (Snap API)</li>
                        <li class="flex items-start gap-2">• Order management + transfer verification</li>
                        <li class="flex items-start gap-2">• Buyer portal with support tickets</li>
                        <li class="flex items-start gap-2">• Product access control (grant/revoke)</li>
                    </ul>
                </div>

                <!-- Page Builder -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">Page Builder & Templates</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• 6 template variants: centered, split, image, video, CTA, default</li>
                        <li class="flex items-start gap-2">• Visual Page Builder with folder organization</li>
                        <li class="flex items-start gap-2">• Custom meta title/description per page</li>
                        <li class="flex items-start gap-2">• Custom CSS injection per page</li>
                        <li class="flex items-start gap-2">• Homepage selection system</li>
                        <li class="flex items-start gap-2">• Tailwind CSS v4 + Alpine.js frontend</li>
                    </ul>
                </div>

                <!-- WordPress Tools -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center text-amber-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">WordPress Import/Export</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• Full WXR 1.2 import from WordPress</li>
                        <li class="flex items-start gap-2">• Category mapping to Silo Blueprints</li>
                        <li class="flex items-start gap-2">• Yoast SEO meta preservation</li>
                        <li class="flex items-start gap-2">• Media import with WebP conversion</li>
                        <li class="flex items-start gap-2">• Export to standard WXR format</li>
                    </ul>
                </div>

                <!-- Multi-Language -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-all">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/20 flex items-center justify-center text-sky-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.78.147 2.653.255M9 12l3 3m0 0l3-3m-3 3V3"/></svg>
                        </div>
                        <h3 class="font-outfit font-bold text-lg text-white">Multi-Language & SEO</h3>
                    </div>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li class="flex items-start gap-2">• Indonesian (default) + English (/en/ prefix)</li>
                        <li class="flex items-start gap-2">• Auto hreflang tags (ID, EN, x-default)</li>
                        <li class="flex items-start gap-2">• Multi-language content generation</li>
                        <li class="flex items-start gap-2">• Language-aware fallback templates</li>
                        <li class="flex items-start gap-2">• SEO meta bilingual support</li>
                    </ul>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-12">
                <a href="{$contactUrl}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-xl shadow-indigo-600/20 transition-all hover:scale-105">
                    Saya Tertarik — Konsultasi Dulu
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════════ -->
<!-- DOKUMENTASI & CHANGELOG                                 -->
<!-- ════════════════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up" id="dokumentasi">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Documentation -->
        <div class="lg:w-5/12">
            <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">DOKUMENTASI</span>
            <h2 class="font-outfit font-bold text-4xl md:text-5xl text-slate-900 mb-6 leading-tight">
                SEOFAST Framework<br>
                <span class="text-indigo-600">v3.1</span>
            </h2>

            <div class="space-y-6 text-slate-500 text-sm leading-relaxed">
                <p><strong class="text-slate-900">Tech Stack:</strong> Laravel 13 / PHP 8.4+ / MySQL 8.0+ / Redis</p>
                <p><strong class="text-slate-900">Frontend:</strong> Tailwind CSS v4 / Alpine.js v3 / Vite 8</p>
                <p><strong class="text-slate-900">AI Providers:</strong> OpenAI, Google Gemini, Anthropic Claude, DeepSeek, 9Router</p>
                <p><strong class="text-slate-900">Cache Layers:</strong> Cloudflare (L1) → Nginx fastcgi_cache (L2) → Redis (L3)</p>
                <p><strong class="text-slate-900">Lisensi:</strong> Lisensi per-domain / multi-tenant untuk agency</p>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{$blogUrl}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors group">
                    Dokumentasi Lengkap
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>

        <!-- Changelog Timeline -->
        <div class="lg:w-7/12">
            <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">CHANGELOG</span>
            <h3 class="font-outfit font-bold text-2xl text-slate-900 mb-8">Riwayat Versi</h3>

            <div class="space-y-0">
                <!-- v3.1 -->
                <div class="changelog-item">
                    <span class="inline-block px-2.5 py-0.5 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full mb-2">v3.1 — Current</span>
                    <h4 class="font-outfit font-bold text-base text-slate-900 mb-2">Content Framework, Tone Options & Stabilitas</h4>
                    <ul class="space-y-1.5 text-sm text-slate-500">
                        <li class="flex items-start gap-2">+ Content frameworks: AIDA, PAS, How-To, Listicle + E-E-A-T enforcement</li>
                        <li class="flex items-start gap-2">+ Tone options: Formal, Friendly, Persuasive, Authoritative, Conversational</li>
                        <li class="flex items-start gap-2">+ Author name & bio configurable via SEO settings</li>
                        <li class="flex items-start gap-2">+ Anchor keywords in Phase 1 + fallback anchors</li>
                        <li class="flex items-start gap-2">+ Separate Phase 7 untuk SEO Meta + Embeddings</li>
                        <li class="flex items-start gap-2">* Fix: Multiple H1 → single H1 per artikel</li>
                        <li class="flex items-start gap-2">* Fix: AI hallucination artifacts & code block stripping</li>
                        <li class="flex items-start gap-2">* Fix: 522 timeout — limit 50 + database indexes</li>
                        <li class="flex items-start gap-2">* Fix: Infinite loop phase transitions</li>
                        <li class="flex items-start gap-2">* Fix: Content style: 3-5 sentences per paragraph, no lists, tables for data</li>
                    </ul>
                </div>

                <!-- v3.0 -->
                <div class="changelog-item emerald">
                    <span class="inline-block px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full mb-2">v3.0 — Major Release</span>
                    <h4 class="font-outfit font-bold text-base text-slate-900 mb-2">SEO Operating System — Rilis Utama</h4>
                    <ul class="space-y-1.5 text-sm text-slate-500">
                        <li class="flex items-start gap-2">+ Topical Map / Silo Architect dengan visual node graph (Drawflow)</li>
                        <li class="flex items-start gap-2">+ 4-phase AI content pipeline (Drafter → Inquirer → Expander → Editor)</li>
                        <li class="flex items-start gap-2">+ Multi-provider AI support + smart fallback</li>
                        <li class="flex items-start gap-2">+ Deterministic internal linking engine — zero orphan pages</li>
                        <li class="flex items-start gap-2">+ Google Search Console integration (OAuth2, API, Indexing)</li>
                        <li class="flex items-start gap-2">+ SEO Audit tools: URL audit, broken links, duplicate content, readability</li>
                        <li class="flex items-start gap-2">+ SERP Rank Tracker — daily snapshots, trend analysis</li>
                        <li class="flex items-start gap-2">+ Auto-reoptimization loop — ranking drop triggers content refresh</li>
                        <li class="flex items-start gap-2">+ Schema markup engine (Article, FAQ, HowTo, Product, LocalBusiness)</li>
                        <li class="flex items-start gap-2">+ WordPress Import/Export (WXR 1.2, Yoast SEO meta)</li>
                        <li class="flex items-start gap-2">+ Digital marketplace with Midtrans payment</li>
                        <li class="flex items-start gap-2">+ Buyer portal with support ticket system</li>
                        <li class="flex items-start gap-2">+ Page builder with 6 template variants</li>
                        <li class="flex items-start gap-2">+ Installation wizard</li>
                        <li class="flex items-start gap-2">+ Multi-language (ID/EN) with auto hreflang</li>
                        <li class="flex items-start gap-2">+ CQI (Content Quality Index) scoring engine</li>
                    </ul>
                </div>

                <!-- v2.x -->
                <div class="changelog-item amber">
                    <span class="inline-block px-2.5 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full mb-2">v2.x — Series</span>
                    <h4 class="font-outfit font-bold text-base text-slate-900 mb-2">Fase Pengembangan Awal</h4>
                    <ul class="space-y-1.5 text-sm text-slate-500">
                        <li class="flex items-start gap-2">+ AI content generation engine (single-phase)</li>
                        <li class="flex items-start gap-2">+ Basic silo structure management</li>
                        <li class="flex items-start gap-2">+ Google Search Console legacy Webmasters API</li>
                        <li class="flex items-start gap-2">+ Basic SEO settings & meta management</li>
                        <li class="flex items-start gap-2">+ Media library with WebP conversion</li>
                        <li class="flex items-start gap-2">+ Admin dashboard & system settings</li>
                        <li class="flex items-start gap-2">+ Dynamic sitemap & robots.txt</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
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
<!-- BLOG                                                     -->
<!-- ════════════════════════════════════════════════════════ -->
<section class="mb-24 anim-fade-up">
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
    <div class="seofast-posts-grid anim-stagger" data-columns="3" data-limit="3"></div>
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
                Apa bedanya beli framework vs pakai jasa?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed">Beli framework: kamu dapat lisensi untuk install di server sendiri, dikelola tim internal. Pakai jasa: saya yang eksekusi SEO untuk website kamu — cocok kalau tidak punya tim teknis.</div>
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
                SEOFAST itu Laravel atau WordPress?
                <svg class="faq-chevron w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="faq-answer mt-4 text-slate-500 text-sm leading-relaxed"><strong class="text-slate-900">Laravel</strong> — bukan WordPress. SEOFAST adalah native Laravel application, bukan plugin atau theme WordPress. Dibangun di atas Laravel 13 dengan PHP 8.4.</div>
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

<!-- ════════════════════════════════════════════════════════ -->
<!-- FINAL CTA                                                -->
<!-- ════════════════════════════════════════════════════════ -->
<section class="anim-scale-in text-center py-16 px-6 md:py-20 md:px-12 rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white mb-8 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.1),transparent_60%)]"></div>
    <div class="relative">
        <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-6 leading-tight">Siap Mulai atau Masih Bingung?</h2>
        <p class="text-lg text-white/80 max-w-2xl mx-auto mb-10 leading-relaxed">
            Butuh SEO? Tertarik dengan SEOFAST Framework? Atau ada project AI custom? Ngobrol aja dulu, gratis dan tanpa kewajiban.
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
            'hero_subheadline' => 'Juki Digital Marketing — jual SEOFAST Framework (Laravel SEO automation), layanan SEO done-for-you, dan jasa AI content & otomasi. B2B oriented, transparan, dan terukur.',
            'meta_title' => 'Juki Digital Marketing — Produk Digital, Jasa SEO & AI untuk B2B',
            'meta_description' => 'Juki Digital Marketing: jual SEOFAST Framework Laravel, jasa SEO, dan jasa AI untuk bisnis. B2B. Dari Silo Architecture sampai AI content pipeline — semua terintegrasi.',
            'hero_cta_text' => 'Konsultasi Gratis',
            'hero_cta_url' => $contactUrl,
            'hero_cta_text_2' => 'Lihat Produk',
            'hero_cta_url_2' => '#seofast-product',
            'hero_features' => [
                'Digital Products',
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
