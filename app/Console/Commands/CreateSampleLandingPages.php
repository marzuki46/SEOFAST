<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\SystemSetting;
use Illuminate\Console\Command;

class CreateSampleLandingPages extends Command
{
    protected $signature = 'seofast:sample-landing';
    protected $description = 'Create sample landing pages (project + product) with rich designs';

    public function handle(): int
    {
        $this->info('Creating sample landing pages...');

        $siteName = SystemSetting::get('site_name', 'SEOFAST');

        // ─── 1. Project Page: SEOFAST Framework ───
        $projectPage = Page::firstOrCreate(
            ['slug' => 'projects/seofast-laravel-framework'],
            [
                'title' => 'SEOFAST — Laravel SEO Automation Framework',
                'template' => 'hero-split',
                'hero_headline' => 'SEOFAST — SEO Automation Framework Berbasis Laravel',
                'hero_subheadline' => 'Framework Laravel untuk SEO automation: silo builder, AI content pipeline multi-agent, rank tracker terintegrasi GSC, dan audit tools — semua dalam satu ekosistem.',
                'hero_cta_text' => 'Pelajari Lebih Lanjut',
                'hero_cta_url' => '#learn',
                'hero_cta_text_2' => 'Lihat Dokumentasi',
                'hero_cta_url_2' => '#docs',
                'hero_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&q=80',
                'hero_features' => [
                    'Laravel Native',
                    'AI Content Pipeline',
                    'Silo Builder',
                    'GSC Integration',
                    'Rank Tracker',
                ],
                'hero_bg_color' => '#0f172a',
                'meta_title' => 'SEOFAST — Laravel SEO Automation Framework',
                'meta_description' => 'SEOFAST adalah framework Laravel untuk SEO automation: AI content pipeline, silo builder, rank tracker, dan audit tools. Cocok untuk developer dan agency.',
                'is_published' => true,
            ]
        );

        $projectPage->html_content = $this->projectHtml();
        $projectPage->save();

        $this->info("  ✓ Project page: /{$projectPage->slug}");

        // ─── 2. Product Page ───
        $productPage = Page::firstOrCreate(
            ['slug' => 'products/ai-content-writer'],
            [
                'title' => 'AI Content Writer Pro',
                'template' => 'hero-centered',
                'hero_headline' => 'AI Content Writer Pro — 100 Artikel SEO dalam Sekejap',
                'hero_subheadline' => 'Tinggalkan writer block. Hasilkan konten SEO berkualitas tinggi dengan 1 klik. Didukung ChatGPT-4, Gemini, dan analisis kompetitor real-time.',
                'hero_cta_text' => 'Coba Gratis 7 Hari',
                'hero_cta_url' => '#trial',
                'hero_cta_text_2' => 'Lihat Pricing',
                'hero_cta_url_2' => '#pricing',
                'hero_image' => null,
                'hero_features' => [
                    'Generate 100 artikel/hari',
                    'Optimasi CQI otomatis',
                    'Multi-bahasa',
                    'Integrasi WordPress & Blogger',
                    'Analisis kompetitor',
                    'Ranking tracker',
                ],
                'hero_bg_color' => '#0f172a',
                'meta_title' => 'AI Content Writer Pro - Tools Menulis Konten SEO dengan AI',
                'meta_description' => 'AI Content Writer Pro: buat artikel SEO berkualitas tinggi dengan AI. Fitur auto-optimasi, multi-bahasa, integrasi CMS, dan ranking tracker.',
                'is_published' => true,
            ]
        );

        $productPage->html_content = $this->productHtml();
        $productPage->save();

        $this->info("  ✓ Product page: /{$productPage->slug}");

        $this->newLine();
        $this->info('Done! Visit the pages above to see the designs.');
        $this->warn('Pages use template system — go to Edit Page to switch templates.');

        return Command::SUCCESS;
    }

    protected function projectHtml(): string
    {
        return <<<'HTML'
<div class="max-w-5xl mx-auto">

    <!-- Features Section -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Features</span>
            <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Semua yang Anda Butuhkan untuk SEO</h2>
            <p class="mt-3 text-slate-500 max-w-2xl mx-auto">Dari content generation hingga ranking tracker — satu framework untuk menguasai SEO.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">AI Content Generation</h3>
                <p class="text-sm text-slate-500">Integrasi ChatGPT-4 & Google Gemini. Generate artikel SEO lengkap dengan struktur yang optimal dalam hitungan detik.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Auto CQI Optimization</h3>
                <p class="text-sm text-slate-500">Setiap konten otomatis di-score dengan Content Quality Index (CQI) dan dioptimasi untuk mencapai skor &gt;80.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">GSC Rank Tracker</h3>
                <p class="text-sm text-slate-500">Pantau peringkat keyword langsung dari dashboard WordPress. Terintegrasi dengan Google Search Console untuk data real-time.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.78.147 2.653.255M9 12l3 3m0 0l3-3m-3 3V3"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Multi-bahasa</h3>
                <p class="text-sm text-slate-500">Dukung Bahasa Indonesia dan Inggris. Auto-generate hreflang, schema markup, dan konten bilingual tanpa ribet.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
                <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">SEO Audit Otomatis</h3>
                <p class="text-sm text-slate-500">Scan otomatis broken links, duplicate content, readability score, dan URL structure audit — semua dari satu dashboard.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-md transition">
                <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Multi-user & Agency</h3>
                <p class="text-sm text-slate-500">Support multi-tenant, role management, dan white-label. Cocok untuk agency SEO yang handle banyak client.</p>
            </div>
        </div>
    </section>

    <!-- Results / Stats Section -->
    <section class="mb-20 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-8 md:p-12 text-white">
        <div class="grid md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold font-outfit">50rb+</div>
                <div class="text-sm text-indigo-200 mt-1">Konten Ter-generate</div>
            </div>
            <div>
                <div class="text-4xl font-bold font-outfit">3.2x</div>
                <div class="text-sm text-indigo-200 mt-1">Rata-rata Traffic Increase</div>
            </div>
            <div>
                <div class="text-4xl font-bold font-outfit">#1</div>
                <div class="text-sm text-indigo-200 mt-1">Ranking Rata-rata di Google</div>
            </div>
            <div>
                <div class="text-4xl font-bold font-outfit">98%</div>
                <div class="text-sm text-indigo-200 mt-1">User Satisfaction</div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Testimonials</span>
            <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Apa Kata Pengguna?</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-amber-400">★</span><span class="text-amber-400">★</span><span class="text-amber-400">★</span><span class="text-amber-400">★</span><span class="text-amber-400">★</span>
                </div>
                <p class="text-sm text-slate-600 mb-4">"Sejak pake SEOFAST, traffic blog saya naik 5x lipat dalam 3 bulan. Fitur AI content generation-nya luar biasa!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">A</div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Ahmad R.</p>
                        <p class="text-xs text-slate-400">Blogger, Indonesia</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-amber-400">★</span><span class="text-amber-400">★</span><span class="text-amber-400">★</span><span class="text-amber-400">★</span><span class="text-amber-400">★</span>
                </div>
                <p class="text-sm text-slate-600 mb-4">"Ranking tracker integration dengan GSC sangat membantu. Saya bisa monitor 500+ keywords dari satu dashboard."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold">S</div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Sarah M.</p>
                        <p class="text-xs text-slate-400">SEO Specialist, Singapore</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center gap-1 mb-3">
                    <span class="text-amber-400">★</span><span class="text-amber-400">★</span><span class="text-amber-400">★</span><span class="text-amber-400">★</span><span class="text-amber-400">★</span>
                </div>
                <p class="text-sm text-slate-600 mb-4">"Agency saya handle 20+ client. SEOFAST bikin workflow content production jauh lebih efisien. Highly recommended!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold">D</div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">David K.</p>
                        <p class="text-xs text-slate-400">Agency Owner, Malaysia</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Changelog -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Changelog</span>
            <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Riwayat Pembaruan</h2>
        </div>
        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-start gap-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="text-indigo-600 font-bold text-sm">v2.1</span>
                </div>
                <div>
                    <p class="text-sm text-slate-400 font-mono">15 Juni 2026</p>
                    <h3 class="text-lg font-bold text-slate-900 mt-1">Integrasi Google Gemini & Peningkatan CQI</h3>
                    <ul class="mt-2 space-y-1 text-sm text-slate-600">
                        <li class="flex items-start gap-2">+ Support Gemini Pro untuk content generation</li>
                        <li class="flex items-start gap-2">+ CQI Score engine update — akurasi optimal 40% lebih baik</li>
                        <li class="flex items-start gap-2">+ Broken link checker sekarang support internal links</li>
                        <li class="flex items-start gap-2">* Fix: hreflang tag untuk homepage bilingual</li>
                        <li class="flex items-start gap-2">* Fix: compatibility dengan Laravel 12</li>
                    </ul>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-start gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="text-emerald-600 font-bold text-sm">v2.0</span>
                </div>
                <div>
                    <p class="text-sm text-slate-400 font-mono">1 Mei 2026</p>
                    <h3 class="text-lg font-bold text-slate-900 mt-1">Major Update — Content Calendar & Social Preview</h3>
                    <ul class="mt-2 space-y-1 text-sm text-slate-600">
                        <li class="flex items-start gap-2">+ Content Calendar view (bulanan/mingguan)</li>
                        <li class="flex items-start gap-2">+ Social Preview Simulator (Facebook, Twitter, LinkedIn)</li>
                        <li class="flex items-start gap-2">+ Readability Dashboard dengan filter level</li>
                        <li class="flex items-start gap-2">+ URL Structure Audit tool</li>
                        <li class="flex items-start gap-2">* Redesign dashboard dengan SEO widgets</li>
                    </ul>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-start gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                    <span class="text-amber-600 font-bold text-sm">v1.0</span>
                </div>
                <div>
                    <p class="text-sm text-slate-400 font-mono">1 Maret 2026</p>
                    <h3 class="text-lg font-bold text-slate-900 mt-1">Initial Release</h3>
                    <ul class="mt-2 space-y-1 text-sm text-slate-600">
                        <li class="flex items-start gap-2">+ AI Content Generation (ChatGPT Integration)</li>
                        <li class="flex items-start gap-2">+ CQI Quality Scoring System</li>
                        <li class="flex items-start gap-2">+ GSC Integration & Rank Tracking</li>
                        <li class="flex items-start gap-2">+ Redirect Manager & 404 Error Tracker</li>
                        <li class="flex items-start gap-2">+ Multi-language support (ID/EN)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="mb-20 bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 md:p-12 text-center">
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-white">Siap Menguasai SEO?</h2>
            <p class="mt-3 text-slate-400 max-w-xl mx-auto">Mulai pakai SEOFAST sekarang dan dapatkan 30 hari trial gratis. Tanpa kartu kredit.</p>
        <div class="mt-8 flex items-center justify-center gap-4">
            <a href="#download" class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg transition">
                Download Gratis
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            </a>
            <a href="#docs" class="inline-flex items-center gap-2 px-8 py-4 border border-slate-500 text-slate-300 hover:border-indigo-500 hover:text-white font-bold rounded-xl transition">
                Dokumentasi
            </a>
        </div>
    </section>

</div>
HTML;
    }

    protected function productHtml(): string
    {
        return <<<'HTML'
<div class="max-w-5xl mx-auto">

    <!-- USP Bar -->
    <div class="bg-gradient-to-r from-indigo-50 via-purple-50 to-sky-50 rounded-2xl p-4 md:p-6 mb-16 text-center border border-indigo-100">
        <p class="text-sm font-semibold text-indigo-700">🔥 <span class="text-indigo-900">Launch Offer:</span> Diskon 50% untuk 100 pendaftar pertama + Free Onboarding!</p>
    </div>

    <!-- Features Grid -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Fitur Lengkap</span>
            <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Tools Lengkap untuk Content Writer Modern</h2>
            <p class="mt-3 text-slate-500 max-w-2xl mx-auto">Dari riset keyword hingga publish — semua dalam satu platform terintegrasi.</p>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="flex items-start gap-4 p-6 bg-white rounded-2xl border border-slate-200">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">AI Content Writer</h3>
                    <p class="text-sm text-slate-500 mt-1">Generate artikel dengan 1 klik. Pilih tone, panjang, dan format. Didukung AI terbaik.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-6 bg-white rounded-2xl border border-slate-200">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">CQI Auto-Scoring</h3>
                    <p class="text-sm text-slate-500 mt-1">Setiap artikel di-score otomatis. Dapatkan rekomendasi perbaikan untuk capai skor maksimal.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-6 bg-white rounded-2xl border border-slate-200">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Bulk Generation</h3>
                    <p class="text-sm text-slate-500 mt-1">Generate hingga 100 artikel sekaligus. Tinggal upload keyword list, biarkan AI bekerja.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-6 bg-white rounded-2xl border border-slate-200">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Multi-platform Publish</h3>
                    <p class="text-sm text-slate-500 mt-1">Publish langsung ke WordPress, Blogger, atau export ke HTML/Markdown. Integasi REST API siap pakai.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-6 bg-white rounded-2xl border border-slate-200">
                <div class="w-10 h-10 bg-rose-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">SEO Analytics</h3>
                    <p class="text-sm text-slate-500 mt-1">GSC integration, rank tracker, broken link checker, readability score — semua tools SEO dalam satu tempat.</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-6 bg-white rounded-2xl border border-slate-200">
                <div class="w-10 h-10 bg-sky-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Team Collaboration</h3>
                    <p class="text-sm text-slate-500 mt-1">Undang tim, atur role, review & approval workflow. Cocok untuk content team dan agency.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section class="mb-20" id="pricing">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Pricing</span>
            <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Pilih Paket yang Tepat</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Starter</h3>
                <p class="text-sm text-slate-500 mt-1">Untuk blogger individu</p>
                <div class="mt-4">
                    <span class="text-3xl font-bold text-slate-900">$29</span>
                    <span class="text-sm text-slate-500">/bulan</span>
                </div>
                <ul class="mt-6 space-y-3 text-sm">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> 50 artikel/bulan</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Basic CQI Scoring</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> 1 User</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> WordPress Integration</li>
                </ul>
                <a href="#" class="mt-6 block text-center px-4 py-3 border border-indigo-600 text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition">Mulai Trial</a>
            </div>
            <div class="bg-white rounded-2xl p-6 border-2 border-indigo-500 shadow-lg relative">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-indigo-600 text-white text-xs font-bold px-4 py-1 rounded-full">POPULER</span>
                <h3 class="text-lg font-bold text-slate-900">Professional</h3>
                <p class="text-sm text-slate-500 mt-1">Untuk content team & agency kecil</p>
                <div class="mt-4">
                    <span class="text-3xl font-bold text-slate-900">$79</span>
                    <span class="text-sm text-slate-500">/bulan</span>
                </div>
                <ul class="mt-6 space-y-3 text-sm">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> 500 artikel/bulan</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Advanced CQI + Readability</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> 5 Users</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> GSC Rank Tracker</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> SEO Audit Tools</li>
                </ul>
                <a href="#" class="mt-6 block text-center px-4 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-500 transition shadow-sm">Mulai Trial</a>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Enterprise</h3>
                <p class="text-sm text-slate-500 mt-1">Untuk agency besar & korporasi</p>
                <div class="mt-4">
                    <span class="text-3xl font-bold text-slate-900">$199</span>
                    <span class="text-sm text-slate-500">/bulan</span>
                </div>
                <ul class="mt-6 space-y-3 text-sm">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Unlimited artikel</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> White-label</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Unlimited Users</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> API Access</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg> Priority Support 24/7</li>
                </ul>
                <a href="#" class="mt-6 block text-center px-4 py-3 border border-indigo-600 text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition">Hubungi Kami</a>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="mb-20">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-1.5 bg-slate-100 text-slate-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">FAQ</span>
            <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Pertanyaan Umum</h2>
        </div>
        <div class="max-w-2xl mx-auto space-y-4">
            <details class="bg-white rounded-2xl p-6 border border-slate-200 group">
                <summary class="font-bold text-slate-900 cursor-pointer flex items-center justify-between">
                    Apakah ada free trial?
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </summary>
                <p class="mt-3 text-sm text-slate-500">Ya! Semua paket termasuk 14 hari free trial tanpa kartu kredit. Anda bisa cancel kapan saja.</p>
            </details>
            <details class="bg-white rounded-2xl p-6 border border-slate-200 group">
                <summary class="font-bold text-slate-900 cursor-pointer flex items-center justify-between">
                    Bahasa apa saja yang didukung?
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </summary>
                <p class="mt-3 text-sm text-slate-500">Kami mendukung Bahasa Indonesia dan Inggris. Konten bilingual dengan hreflang otomatis.</p>
            </details>
            <details class="bg-white rounded-2xl p-6 border border-slate-200 group">
                <summary class="font-bold text-slate-900 cursor-pointer flex items-center justify-between">
                    Bisakah integrasi dengan CMS lain?
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </summary>
                <p class="mt-3 text-sm text-slate-500">Ya, kami menyediakan REST API yang bisa diintegrasikan dengan CMS apapun. Juga support export HTML/Markdown.</p>
            </details>
            <details class="bg-white rounded-2xl p-6 border border-slate-200 group">
                <summary class="font-bold text-slate-900 cursor-pointer flex items-center justify-between">
                    Bagaimana dengan kualitas konten?
                    <svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </summary>
                <p class="mt-3 text-sm text-slate-500">Setiap konten melewati CQI (Content Quality Index) scoring. Skor minimal 80 sebelum publish. Anda juga bisa edit manual.</p>
            </details>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="mb-20 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-8 md:p-12 text-center">
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-white">Mulai Trial Gratis Sekarang</h2>
        <p class="mt-3 text-indigo-200 max-w-xl mx-auto">14 hari gratis, tanpa kartu kredit. Cancel kapan saja.</p>
        <div class="mt-8 flex items-center justify-center gap-4">
            <a href="#trial" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-lg hover:bg-slate-100 transition">
                Coba Gratis 14 Hari
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            <a href="#schedule-demo" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/10 transition">
                Jadwalkan Demo
            </a>
        </div>
    </section>

</div>
HTML;
    }
}
