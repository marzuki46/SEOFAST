<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class SeedDigitalProductLanding extends Command
{
    protected $signature = 'seofast:seed-landing';
    protected $description = 'Create a premium digital product landing page';

    public function handle(): int
    {
        $page = Page::updateOrCreate(
            ['slug' => 'mastering-seo-ai-ebook'],
            [
                'title' => 'Mastering SEO di Era AI',
                'template' => 'hero-cta',
                'hero_headline' => 'Buku #1 SEO di Indonesia yang Menggabungkan Strategi Klasik & Kecerdasan Buatan',
                'hero_subheadline' => 'Pelajari cara menguasai Google dengan teknik SEO terkini + AI content strategy. Dari dasar hingga mahir. Cocok untuk blogger, pemilik bisnis, dan SEO specialist.',
                'hero_cta_text' => 'Beli Sekarang — Rp149.000',
                'hero_cta_url' => '#pricing',
                'hero_cta_text_2' => 'Baca Sample Gratis',
                'hero_cta_url_2' => '#sample',
                'hero_features' => [
                    '320+ halaman materi premium',
                    'Studi kasus real-world',
                    'Template & checklist siap pakai',
                    'Update konten gratis 1 tahun',
                ],
                'hero_bg_color' => '#0f172a',
                'meta_title' => 'Mastering SEO di Era AI — Buku Panduan SEO Lengkap dengan AI',
                'meta_description' => 'Buku SEO terlengkap di Indonesia: dari riset keyword hingga AI content generation. Bonus template + akses komunitas.',
                'is_published' => true,
            ]
        );

        $page->html_content = $this->html();
        $page->save();

        $this->info("Landing page created: /{$page->slug}");

        return Command::SUCCESS;
    }

    protected function html(): string
    {
        return <<<'HTML'
<div class="max-w-5xl mx-auto">

<!-- Social Proof Bar -->
<div class="bg-slate-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 border-y border-slate-200 mb-20">
    <div class="flex flex-wrap items-center justify-center gap-6 md:gap-12 text-sm text-slate-500">
        <span class="flex items-center gap-2"><svg class="w-4 h-4 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> <span class="font-semibold text-slate-700">4.9/5</span> rating</span>
        <span class="flex items-center gap-2"><svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg> <span class="font-semibold text-slate-700">2.847+</span> pembaca</span>
        <span class="flex items-center gap-2"><svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg> 320 halaman</span>
        <span class="flex items-center gap-2"><svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Update 2026</span>
    </div>
</div>

<!-- What You Will Learn -->
<section class="mb-20">
    <div class="text-center mb-12">
        <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Apa yang Anda Pelajari</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Dari Nol hingga Mahir SEO + AI</h2>
        <p class="mt-3 text-slate-500 max-w-2xl mx-auto">Buku ini dirancang sistematis — cocok untuk pemula maupun praktisi yang ingin menguasai AI content strategy.</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="flex items-start gap-4 p-5 bg-white rounded-xl border border-slate-200"><div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5"><svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg></div><div><h3 class="font-bold text-slate-900 text-sm">Riset Keyword Mendalam</h3><p class="text-xs text-slate-500 mt-1">Teknik finding high-volume low-competition keywords dengan AI clustering.</p></div></div>
        <div class="flex items-start gap-4 p-5 bg-white rounded-xl border border-slate-200"><div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5"><svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg></div><div><h3 class="font-bold text-slate-900 text-sm">On-Page SEO Lengkap</h3><p class="text-xs text-slate-500 mt-1">Optimasi title, heading, internal linking, dan content silo architecture.</p></div></div>
        <div class="flex items-start gap-4 p-5 bg-white rounded-xl border border-slate-200"><div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5"><svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg></div><div><h3 class="font-bold text-slate-900 text-sm">AI Content Strategy</h3><p class="text-xs text-slate-500 mt-1">Scale content production dengan ChatGPT & Gemini tanpa kehilangan kualitas.</p></div></div>
        <div class="flex items-start gap-4 p-5 bg-white rounded-xl border border-slate-200"><div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5"><svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/></svg></div><div><h3 class="font-bold text-slate-900 text-sm">Technical SEO</h3><p class="text-xs text-slate-500 mt-1">Schema markup, Core Web Vitals, sitemap, dan optimasi website performance.</p></div></div>
        <div class="flex items-start gap-4 p-5 bg-white rounded-xl border border-slate-200"><div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5"><svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg></div><div><h3 class="font-bold text-slate-900 text-sm">Link Building & E-E-A-T</h3><p class="text-xs text-slate-500 mt-1">Strategi white-hat link building dan optimasi authoritativeness.</p></div></div>
        <div class="flex items-start gap-4 p-5 bg-white rounded-xl border border-slate-200"><div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5"><svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg></div><div><h3 class="font-bold text-slate-900 text-sm">SEO Analytics & Reporting</h3><p class="text-xs text-slate-500 mt-1">GSC, GA4, rank tracking, dan data-driven content optimization.</p></div></div>
    </div>
</section>

<!-- Author -->
<section class="bg-slate-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-20 border-y border-slate-200 mb-20">
    <div class="max-w-5xl mx-auto grid md:grid-cols-5 gap-10 items-center">
        <div class="md:col-span-2 flex justify-center"><div class="w-48 h-48 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-6xl font-bold shadow-xl">M</div></div>
        <div class="md:col-span-3">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Tentang Penulis</span>
            <h2 class="text-2xl md:text-3xl font-bold font-outfit text-slate-900">Ditulis oleh Marzuki — Praktisi SEO & AI Content Specialist</h2>
            <p class="mt-4 text-slate-600 leading-relaxed">Dengan lebih dari 8 tahun pengalaman di industri SEO, Marzuki telah membantu 50+ brand dan agency meningkatkan traffic organik mereka dan merupakan founder SEOFAST.</p>
            <div class="mt-6 flex flex-wrap gap-6 text-sm"><div><span class="font-bold text-slate-900">8+</span> <span class="text-slate-500">Tahun</span></div><div><span class="font-bold text-slate-900">50+</span> <span class="text-slate-500">Client</span></div><div><span class="font-bold text-slate-900">200+</span> <span class="text-slate-500">Konten</span></div></div>
        </div>
    </div>
</section>

<!-- Chapters -->
<section class="mb-20">
    <div class="text-center mb-12">
        <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Ada Apa Saja di Dalamnya</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">12 Bab + 5 Lampiran</h2>
        <p class="mt-3 text-slate-500">Setiap bab dilengkapi template & checklist siap pakai.</p>
    </div>
    <div class="grid sm:grid-cols-2 gap-3">
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">1</span><div><h3 class="font-bold text-slate-900 text-sm">Fundamental SEO 2026</h3><p class="text-xs text-slate-500 mt-1">Cara kerja Google, algoritma terbaru, dan mindset SEO.</p></div></div>
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">2</span><div><h3 class="font-bold text-slate-900 text-sm">Riset Keyword Lanjutan</h3><p class="text-xs text-slate-500 mt-1">High-volume low-competition keywords dengan AI.</p></div></div>
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">3</span><div><h3 class="font-bold text-slate-900 text-sm">Content Silo & Topic Cluster</h3><p class="text-xs text-slate-500 mt-1">Struktur website pillar & cluster pages.</p></div></div>
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">4</span><div><h3 class="font-bold text-slate-900 text-sm">AI Content Generation</h3><p class="text-xs text-slate-500 mt-1">Prompt engineering & quality control dengan AI.</p></div></div>
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">5</span><div><h3 class="font-bold text-slate-900 text-sm">Technical SEO Deep Dive</h3><p class="text-xs text-slate-500 mt-1">Schema, Core Web Vitals, mobile-first indexing.</p></div></div>
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">6</span><div><h3 class="font-bold text-slate-900 text-sm">Link Building Strategis</h3><p class="text-xs text-slate-500 mt-1">White-hat link building, digital PR, E-E-A-T.</p></div></div>
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">7</span><div><h3 class="font-bold text-slate-900 text-sm">Local SEO & Multi-bahasa</h3><p class="text-xs text-slate-500 mt-1">Google Business Profile, hreflang, SEO Indonesia & global.</p></div></div>
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">8</span><div><h3 class="font-bold text-slate-900 text-sm">SEO Analytics & Reporting</h3><p class="text-xs text-slate-500 mt-1">GSC, GA4, Looker Studio, laporan untuk client.</p></div></div>
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">9</span><div><h3 class="font-bold text-slate-900 text-sm">SEO E-commerce</h3><p class="text-xs text-slate-500 mt-1">Optimasi product page, category page, review schema.</p></div></div>
        <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-slate-200"><span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">10</span><div><h3 class="font-bold text-slate-900 text-sm">SEO Automation</h3><p class="text-xs text-slate-500 mt-1">Scale up dengan rank tracker, content calendar, bulk generation.</p></div></div>
    </div>
    <div class="mt-6 bg-indigo-50 border border-indigo-200 rounded-2xl p-5 flex items-center justify-between flex-wrap gap-3">
        <div><p class="font-bold text-indigo-900">+ 5 Lampiran Eksklusif</p><p class="text-sm text-indigo-700">Template riset keyword, SEO audit checklist, content brief template, rank tracker, prompt cheat sheet.</p></div>
        <span class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg shrink-0">Bonus Rp200.000</span>
    </div>
</section>

<!-- Bonuses -->
<section class="bg-gradient-to-br from-amber-50 to-orange-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-20 border-y border-amber-200 mb-20">
    <div class="max-w-5xl mx-auto text-center mb-12">
        <span class="inline-block px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Bonus Terbatas</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Bonus Senilai Rp500.000 Gratis</h2>
        <p class="mt-3 text-slate-500">Khusus 100 pembeli pertama.</p>
    </div>
    <div class="max-w-5xl mx-auto grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-amber-200 text-center"><div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-3"><svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div><h3 class="font-bold text-slate-900 text-sm">Checklist SEO 60+ Poin</h3><p class="text-xs text-slate-500 mt-1">Spreadsheet audit website siap pakai.</p><span class="inline-block mt-2 text-xs font-bold text-amber-600">FREE</span></div>
        <div class="bg-white rounded-xl p-5 border border-amber-200 text-center"><div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-3"><svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg></div><h3 class="font-bold text-slate-900 text-sm">Cheat Sheet Prompts AI</h3><p class="text-xs text-slate-500 mt-1">50+ prompt ChatGPT & Gemini untuk SEO.</p><span class="inline-block mt-2 text-xs font-bold text-amber-600">FREE</span></div>
        <div class="bg-white rounded-xl p-5 border border-amber-200 text-center"><div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-3"><svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><h3 class="font-bold text-slate-900 text-sm">Akses Komunitas Premium</h3><p class="text-xs text-slate-500 mt-1">Diskusi exclusive dengan sesama pembaca.</p><span class="inline-block mt-2 text-xs font-bold text-amber-600">FREE</span></div>
        <div class="bg-white rounded-xl p-5 border border-amber-200 text-center"><div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-3"><svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div><h3 class="font-bold text-slate-900 text-sm">Update 1 Tahun</h3><p class="text-xs text-slate-500 mt-1">Revisi konten gratis selama 12 bulan.</p><span class="inline-block mt-2 text-xs font-bold text-amber-600">FREE</span></div>
    </div>
</section>

<!-- Testimonials -->
<section class="mb-20">
    <div class="text-center mb-12">
        <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Testimoni</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Apa Kata Pembaca?</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-1 mb-3 text-amber-400 text-sm">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="text-sm text-slate-600 mb-4 leading-relaxed">"Fokus ke AI content strategy bikin saya bisa scale produksi konten 5x lipat. Recommended!"</p>
            <div class="flex items-center gap-3"><div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm">RD</div><div><p class="text-sm font-bold text-slate-900">Rina D.</p><p class="text-xs text-slate-400">Content Manager</p></div></div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-1 mb-3 text-amber-400 text-sm">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="text-sm text-slate-600 mb-4 leading-relaxed">"Bab technical SEO & schema markup sangat membantu. Banyak insight baru meski udah 5 tahun di SEO."</p>
            <div class="flex items-center gap-3"><div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold text-sm">AF</div><div><p class="text-sm font-bold text-slate-900">Ahmad F.</p><p class="text-xs text-slate-400">SEO Specialist</p></div></div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-1 mb-3 text-amber-400 text-sm">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="text-sm text-slate-600 mb-4 leading-relaxed">"Template-nya langsung bisa dipakai. Traffic blog naik 3x dalam 2 bulan. Beneran practical!"</p>
            <div class="flex items-center gap-3"><div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-sm">SN</div><div><p class="text-sm font-bold text-slate-900">Sari N.</p><p class="text-xs text-slate-400">Blogger</p></div></div>
        </div>
    </div>
</section>

<!-- Pricing -->
<section id="pricing" class="bg-slate-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-20 border-y border-slate-200 mb-20">
    <div class="max-w-lg mx-auto text-center">
        <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Harga</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Investasi untuk Masa Depan SEO Anda</h2>
        <div class="mt-10 bg-white rounded-3xl border-2 border-indigo-500 p-8 md:p-10 shadow-xl">
            <p class="text-sm text-slate-500 line-through">Rp 349.000</p>
            <div class="mt-2"><span class="text-5xl font-bold font-outfit text-slate-900">Rp149.000</span> <span class="text-slate-500 ml-2">sekali bayar</span></div>
            <p class="text-xs text-emerald-600 font-semibold mt-2">Hemat Rp 200.000 — diskon launch 57%</p>
            <ul class="mt-8 space-y-3 text-sm text-left">
                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 320+ halaman PDF + EPUB</li>
                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 5 bonus senilai Rp500.000</li>
                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Update gratis 1 tahun</li>
                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Akses grup komunitas premium</li>
                <li class="flex items-center gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Garansi uang kembali 14 hari</li>
            </ul>
            <a href="#" class="mt-8 block w-full px-6 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-lg rounded-xl shadow-lg shadow-indigo-600/20 transition-all hover:scale-[1.02] text-center">Beli Sekarang — Rp149.000</a>
            <p class="mt-3 text-xs text-slate-400">BCA, Mandiri, GoPay, OVO, QRIS</p>
        </div>
        <div class="mt-4 flex items-center justify-center gap-6 text-xs text-slate-400">
            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Garansi 14 hari</span>
            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Akses seumur hidup</span>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="max-w-2xl mx-auto mb-20">
    <div class="text-center mb-12">
        <span class="inline-block px-4 py-1.5 bg-slate-100 text-slate-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">FAQ</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Pertanyaan Umum</h2>
    </div>
    <div class="space-y-3">
        <details class="bg-white rounded-xl p-5 border border-slate-200 group"><summary class="font-semibold text-slate-900 cursor-pointer flex items-center justify-between gap-4">Cocok untuk pemula?<svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary><p class="mt-3 text-sm text-slate-500 leading-relaxed">Sangat cocok! Bab 1-4 untuk pemula, bab 5-12 untuk intermediate hingga advanced.</p></details>
        <details class="bg-white rounded-xl p-5 border border-slate-200 group"><summary class="font-semibold text-slate-900 cursor-pointer flex items-center justify-between gap-4">Format apa saja?<svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary><p class="mt-3 text-sm text-slate-500 leading-relaxed">PDF, EPUB, dan MOBI. Bisa dibaca di semua device.</p></details>
        <details class="bg-white rounded-xl p-5 border border-slate-200 group"><summary class="font-semibold text-slate-900 cursor-pointer flex items-center justify-between gap-4">Ada garansi?<svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary><p class="mt-3 text-sm text-slate-500 leading-relaxed">14 hari garansi uang kembali jika tidak sesuai.</p></details>
        <details class="bg-white rounded-xl p-5 border border-slate-200 group"><summary class="font-semibold text-slate-900 cursor-pointer flex items-center justify-between gap-4">Di-update?<svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary><p class="mt-3 text-sm text-slate-500 leading-relaxed">Update gratis 1 tahun termasuk perubahan algoritma Google.</p></details>
    </div>
</section>

<!-- Final CTA -->
<section class="bg-gradient-to-br from-slate-900 to-indigo-950 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-20 text-center">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-white">Mulai Kuasai SEO Sekarang</h2>
        <p class="mt-4 text-slate-400">2.847+ orang sudah membuktikan. Giliran Anda.</p>
        <div class="mt-3 text-amber-400 text-sm">&#9733;&#9733;&#9733;&#9733;&#9733; <span class="text-slate-400 ml-1">(4.9 rating)</span></div>
        <a href="#pricing" class="mt-8 inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-lg rounded-xl shadow-lg transition-all hover:scale-105">
            Beli Sekarang — Rp149.000
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        <p class="mt-4 text-xs text-slate-500">Garansi 14 hari • Akses seumur hidup • Update gratis</p>
    </div>
</section>

</div>
HTML;
    }
}
