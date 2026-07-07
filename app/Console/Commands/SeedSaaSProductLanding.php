<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class SeedSaaSProductLanding extends Command
{
    protected $signature = 'seofast:seed-saas-landing';
    protected $description = 'Create a premium SaaS landing page';

    public function handle(): int
    {
        $page = Page::updateOrCreate(
            ['slug' => 'seofast-analytics-pro'],
            [
                'title' => 'SEOFAST Analytics Pro — Rank Tracker & SEO Reporting All-in-One',
                'template' => 'hero-split',
                'hero_headline' => 'Pantau Peringkat Website Anda Secara Real-Time & Buat Laporan SEO Otomatis',
                'hero_subheadline' => 'Rank tracker multi-mesin pencari, audit technical SEO, dan reporting client dalam satu platform. Hemat 10 jam per minggu — tanpa spreadsheet.',
                'hero_cta_text' => 'Mulai Uji Coba Gratis',
                'hero_cta_url' => '#pricing',
                'hero_cta_text_2' => 'Lihat Demo',
                'hero_cta_url_2' => '#features',
                'hero_image' => '',
                'hero_features' => [
                    'Tracking Google, Bing, Yahoo',
                    'Laporan otomatis PDF & Looker Studio',
                    'Audit 50+ parameter teknis',
                    'Multi-user & client access',
                ],
                'hero_bg_color' => '#0f172a',
                'meta_title' => 'SEOFAST Analytics Pro — Rank Tracker & SEO Audit Tool Indonesia',
                'meta_description' => 'Platform rank tracker & SEO reporting all-in-one buatan Indonesia. Pantau peringkat, audit teknis, dan buat laporan client otomatis. Gratis 14 hari.',
                'is_published' => true,
            ]
        );

        $page->html_content = $this->html();
        $page->save();

        $this->info("SaaS landing page created: /{$page->slug}");

        return Command::SUCCESS;
    }

    protected function html(): string
    {
        return <<<'HTML'
<div class="max-w-7xl mx-auto">

<!-- Social Proof Bar -->
<div class="bg-white -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-6 border-y border-slate-200 mb-20">
    <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16 text-sm">
        <span class="flex items-center gap-2 text-slate-500"><svg class="w-5 h-5 text-indigo-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> 4.8/5 rating dari 250+ pengguna</span>
        <span class="flex items-center gap-2 text-slate-500"><svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg> 2.500+ pengguna aktif</span>
        <span class="flex items-center gap-2 text-slate-500"><svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg> 150+ tim & agency</span>
    </div>
</div>

<!-- Features -->
<section id="features" class="mb-24">
    <div class="text-center mb-14">
        <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Fitur Unggulan</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Semua yang Anda Butuhkan untuk SEO</h2>
        <p class="mt-3 text-slate-500 max-w-2xl mx-auto">Dari rank tracking hingga reporting, semua terintegrasi dalam satu dashboard.</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-colors"><svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"/></svg></div>
            <h3 class="text-lg font-bold font-outfit text-slate-900 mb-2">Multi-Engine Rank Tracker</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Pantau peringkat website Anda di Google, Bing, dan Yahoo secara harian. Data akurat dengan geolocation targeting.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-200 transition-colors"><svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div>
            <h3 class="text-lg font-bold font-outfit text-slate-900 mb-2">Laporan Otomatis PDF</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Generate laporan SEO mingguan/bulanan dalam format PDF siap kirim ke client. Branded dengan logo Anda.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-200 transition-colors"><svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div>
            <h3 class="text-lg font-bold font-outfit text-slate-900 mb-2">Technical SEO Audit</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Scan 50+ parameter teknis: Core Web Vitals, schema markup, broken links, meta tags, dan sitemap.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors"><svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
            <h3 class="text-lg font-bold font-outfit text-slate-900 mb-2">Multi-User & Client Access</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Tambahkan tim dan client dengan akses terbatas. Setiap user bisa melihat dashboard dan laporan masing-masing.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-rose-200 transition-colors"><svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/></svg></div>
            <h3 class="text-lg font-bold font-outfit text-slate-900 mb-2">Looker Studio Integration</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Hubungkan data rank tracker ke Google Looker Studio untuk visualisasi kustom dan dashboard real-time.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-sky-200 transition-colors"><svg class="w-6 h-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z"/></svg></div>
            <h3 class="text-lg font-bold font-outfit text-slate-900 mb-2">API & Webhook</h3>
            <p class="text-sm text-slate-500 leading-relaxed">REST API untuk mengakses data rank tracking, audit, dan report. Integrasikan dengan tools favorit Anda.</p>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="bg-slate-50 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-20 border-y border-slate-200 mb-24">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Cara Kerja</span>
            <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Mulai dalam 3 Langkah</h2>
            <p class="mt-3 text-slate-500">Tidak perlu instalasi. Langsung pantau peringkat website Anda.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-indigo-600/20"><span class="text-white text-2xl font-bold font-outfit">1</span></div>
                <h3 class="text-lg font-bold font-outfit text-slate-900 mb-2">Daftar & Masukkan Domain</h3>
                <p class="text-sm text-slate-500">Buat akun gratis, masukkan URL website Anda, dan pilih mesin pencari yang ingin dipantau.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-indigo-600/20"><span class="text-white text-2xl font-bold font-outfit">2</span></div>
                <h3 class="text-lg font-bold font-outfit text-slate-900 mb-2">Pilih Keyword & Target</h3>
                <p class="text-sm text-slate-500">Tentukan keyword yang ingin dilacak, atur jadwal tracking, dan set target peringkat yang diinginkan.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-indigo-600/20"><span class="text-white text-2xl font-bold font-outfit">3</span></div>
                <h3 class="text-lg font-bold font-outfit text-slate-900 mb-2">Pantau & Laporkan</h3>
                <p class="text-sm text-slate-500">Lihat peringkat real-time, dapatkan notifikasi perubahan, dan buat laporan otomatis untuk client.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter -->
<section class="mb-24">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-8 border border-slate-200 text-center"><span class="text-4xl font-bold font-outfit text-indigo-600">2.500+</span><p class="text-sm text-slate-500 mt-2">Pengguna Aktif</p></div>
        <div class="bg-white rounded-2xl p-8 border border-slate-200 text-center"><span class="text-4xl font-bold font-outfit text-indigo-600">50K+</span><p class="text-sm text-slate-500 mt-2">Keyword Dilacak</p></div>
        <div class="bg-white rounded-2xl p-8 border border-slate-200 text-center"><span class="text-4xl font-bold font-outfit text-indigo-600">98.5%</span><p class="text-sm text-slate-500 mt-2">Uptime</p></div>
        <div class="bg-white rounded-2xl p-8 border border-slate-200 text-center"><span class="text-4xl font-bold font-outfit text-indigo-600">4.8/5</span><p class="text-sm text-slate-500 mt-2">Rating Pengguna</p></div>
    </div>
</section>

<!-- Pricing -->
<section id="pricing" class="mb-24" x-data="{ yearly: false }">
    <div class="text-center mb-14">
        <span class="inline-block px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Harga</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Pilih Paket yang Tepat</h2>
        <p class="mt-3 text-slate-500">Gratis 14 hari trial di semua paket. Tidak perlu kartu kredit.</p>
        <div class="mt-6 inline-flex items-center bg-slate-100 rounded-full p-1">
            <button @click="yearly = false" :class="{'bg-white shadow-sm text-slate-900 font-bold': !yearly, 'text-slate-500': yearly}" class="px-5 py-2 rounded-full text-sm transition-all">Bulanan</button>
            <button @click="yearly = true" :class="{'bg-white shadow-sm text-slate-900 font-bold': yearly, 'text-slate-500': !yearly}" class="px-5 py-2 rounded-full text-sm transition-all">Tahunan <span class="text-emerald-500 font-bold ml-1">Hemat 20%</span></button>
        </div>
    </div>
    <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
        <!-- Free -->
        <div class="bg-white rounded-2xl p-8 border border-slate-200 relative">
            <h3 class="text-lg font-bold font-outfit text-slate-900">Starter</h3>
            <p class="text-sm text-slate-500 mt-1">Untuk pemula yang baru mulai.</p>
            <div class="mt-6"><span class="text-4xl font-bold font-outfit text-slate-900">Rp0</span><span class="text-slate-400 ml-1 text-sm">/bulan</span></div>
            <ul class="mt-8 space-y-3 text-sm">
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 1 project</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 10 keyword</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Tracking Google only</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Update harian</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-slate-300 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> Laporan PDF</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-slate-300 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> Multi-user</li>
            </ul>
            <a href="#" class="mt-8 block w-full px-6 py-3 border-2 border-slate-200 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 font-bold rounded-xl transition-all text-center text-sm">Mulai Gratis</a>
        </div>
        <!-- Pro -->
        <div class="bg-white rounded-2xl p-8 border-2 border-indigo-500 relative shadow-xl shadow-indigo-600/10 scale-[1.02]">
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full">Paling Populer</span>
            <h3 class="text-lg font-bold font-outfit text-slate-900">Pro</h3>
            <p class="text-sm text-slate-500 mt-1">Untuk freelancer & agency kecil.</p>
            <div class="mt-6">
                <template x-if="!yearly">
                    <div><span class="text-4xl font-bold font-outfit text-slate-900">Rp149K</span><span class="text-slate-400 ml-1 text-sm">/bulan</span></div>
                </template>
                <template x-if="yearly">
                    <div><span class="text-4xl font-bold font-outfit text-slate-900">Rp119K</span><span class="text-slate-400 ml-1 text-sm">/bulan <span class="text-emerald-500 font-bold">(Rp1.428.000/thn)</span></span></div>
                </template>
            </div>
            <ul class="mt-8 space-y-3 text-sm">
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 10 project</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 500 keyword</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Google + Bing + Yahoo</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Laporan PDF otomatis</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Technical SEO audit</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 5 user tim</li>
            </ul>
            <a href="#" class="mt-8 block w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all hover:scale-[1.02] text-center text-sm">Mulai Trial Gratis</a>
        </div>
        <!-- Enterprise -->
        <div class="bg-white rounded-2xl p-8 border border-slate-200 relative">
            <h3 class="text-lg font-bold font-outfit text-slate-900">Enterprise</h3>
            <p class="text-sm text-slate-500 mt-1">Untuk agency & perusahaan besar.</p>
            <div class="mt-6">
                <template x-if="!yearly">
                    <div><span class="text-4xl font-bold font-outfit text-slate-900">Rp499K</span><span class="text-slate-400 ml-1 text-sm">/bulan</span></div>
                </template>
                <template x-if="yearly">
                    <div><span class="text-4xl font-bold font-outfit text-slate-900">Rp399K</span><span class="text-slate-400 ml-1 text-sm">/bulan <span class="text-emerald-500 font-bold">(Rp4.788.000/thn)</span></span></div>
                </template>
            </div>
            <ul class="mt-8 space-y-3 text-sm">
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Project unlimited</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Keyword unlimited</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Semua mesin pencari</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Laporan kustom + Looker Studio</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> SLA & priority support</li>
                <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> API & webhook priority</li>
            </ul>
            <a href="#" class="mt-8 block w-full px-6 py-3 border-2 border-slate-200 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 font-bold rounded-xl transition-all text-center text-sm">Hubungi Sales</a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="mb-24">
    <div class="text-center mb-14">
        <span class="inline-block px-4 py-1.5 bg-purple-100 text-purple-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Testimoni</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Apa Kata Pengguna?</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-1 mb-3 text-amber-400 text-sm">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="text-sm text-slate-600 mb-4 leading-relaxed">"Rank tracker ini membantu saya menghemat 10+ jam per minggu. Laporan PDF otomatis jadi andalan saya untuk klien."</p>
            <div class="flex items-center gap-3"><div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm">DS</div><div><p class="text-sm font-bold text-slate-900">Dian S.</p><p class="text-xs text-slate-400">SEO Consultant</p></div></div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-1 mb-3 text-amber-400 text-sm">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="text-sm text-slate-600 mb-4 leading-relaxed">"Fitur audit technical SEO sangat detail. Banyak issue yang sebelumnya tidak saya sadari sekarang terdeteksi otomatis."</p>
            <div class="flex items-center gap-3"><div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold text-sm">AP</div><div><p class="text-sm font-bold text-slate-900">Adi P.</p><p class="text-xs text-slate-400">Digital Agency Owner</p></div></div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center gap-1 mb-3 text-amber-400 text-sm">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="text-sm text-slate-600 mb-4 leading-relaxed">"Multi-user access memudahkan tim saya. Masing-masing bisa lihat dashboard sendiri tanpa ganggu data orang lain."</p>
            <div class="flex items-center gap-3"><div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-sm">RN</div><div><p class="text-sm font-bold text-slate-900">Rina N.</p><p class="text-xs text-slate-400">Head of SEO</p></div></div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="max-w-3xl mx-auto mb-24">
    <div class="text-center mb-14">
        <span class="inline-block px-4 py-1.5 bg-slate-100 text-slate-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">FAQ</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900">Pertanyaan Umum</h2>
    </div>
    <div class="space-y-3">
        <details class="bg-white rounded-xl p-5 border border-slate-200 group"><summary class="font-semibold text-slate-900 cursor-pointer flex items-center justify-between gap-4">Apakah ada uji coba gratis?<svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary><p class="mt-3 text-sm text-slate-500 leading-relaxed">Ya, kami menyediakan 14 hari uji coba gratis di semua paket. Tidak perlu kartu kredit. Anda bisa cancel kapan saja.</p></details>
        <details class="bg-white rounded-xl p-5 border border-slate-200 group"><summary class="font-semibold text-slate-900 cursor-pointer flex items-center justify-between gap-4">Bisa tracking keyword mobile?<svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary><p class="mt-3 text-sm text-slate-500 leading-relaxed">Ya, kami mendukung tracking untuk desktop dan mobile. Anda bisa memilih device target untuk setiap keyword.</p></details>
        <details class="bg-white rounded-xl p-5 border border-slate-200 group"><summary class="font-semibold text-slate-900 cursor-pointer flex items-center justify-between gap-4">Berapa lama data tersimpan?<svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary><p class="mt-3 text-sm text-slate-500 leading-relaxed">Data tersimpan selama 2 tahun di paket Pro dan Enterprise. Paket Starter menyimpan 3 bulan terakhir.</p></details>
        <details class="bg-white rounded-xl p-5 border border-slate-200 group"><summary class="font-semibold text-slate-900 cursor-pointer flex items-center justify-between gap-4">Bisa integrasi dengan Google Search Console?<svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary><p class="mt-3 text-sm text-slate-500 leading-relaxed">Ya, kami terintegrasi dengan Google Search Console, Google Analytics 4, dan Looker Studio untuk visualisasi data.</p></details>
        <details class="bg-white rounded-xl p-5 border border-slate-200 group"><summary class="font-semibold text-slate-900 cursor-pointer flex items-center justify-between gap-4">Apakah ada API untuk akses data?<svg class="w-5 h-5 text-slate-400 group-open:rotate-180 transition shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary><p class="mt-3 text-sm text-slate-500 leading-relaxed">Ya, paket Enterprise menyertakan akses REST API dengan rate limit tinggi. Paket Pro juga bisa menambahkan API add-on.</p></details>
    </div>
</section>

<!-- FAQ Schema -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [
    {"@@type":"Question","name":"Apakah ada uji coba gratis?","acceptedAnswer":{"@@type":"Answer","text":"Ya, kami menyediakan 14 hari uji coba gratis di semua paket. Tidak perlu kartu kredit. Anda bisa cancel kapan saja."}},
    {"@@type":"Question","name":"Bisa tracking keyword mobile?","acceptedAnswer":{"@@type":"Answer","text":"Ya, kami mendukung tracking untuk desktop dan mobile. Anda bisa memilih device target untuk setiap keyword."}},
    {"@@type":"Question","name":"Berapa lama data tersimpan?","acceptedAnswer":{"@@type":"Answer","text":"Data tersimpan selama 2 tahun di paket Pro dan Enterprise. Paket Starter menyimpan 3 bulan terakhir."}},
    {"@@type":"Question","name":"Bisa integrasi dengan Google Search Console?","acceptedAnswer":{"@@type":"Answer","text":"Ya, kami terintegrasi dengan Google Search Console, Google Analytics 4, dan Looker Studio untuk visualisasi data."}},
    {"@@type":"Question","name":"Apakah ada API untuk akses data?","acceptedAnswer":{"@@type":"Answer","text":"Ya, paket Enterprise menyertakan akses REST API dengan rate limit tinggi. Paket Pro juga bisa menambahkan API add-on."}}
  ]
}
</script>

<!-- Final CTA -->
<section class="bg-gradient-to-br from-slate-900 to-indigo-950 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-24 text-center">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-white">Siap Meningkatkan Rank Tracking & Reporting Anda?</h2>
        <p class="mt-4 text-slate-400">Gabung dengan 2.500+ pengguna. Gratis 14 hari, tidak perlu kartu kredit.</p>
        <div class="mt-3 text-amber-400 text-sm">&#9733;&#9733;&#9733;&#9733;&#9733; <span class="text-slate-400 ml-1">4.8 rating dari 250+ pengguna</span></div>
        <a href="#pricing" class="mt-8 inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-lg rounded-xl shadow-lg transition-all hover:scale-105">
            Mulai Uji Coba Gratis
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
        <p class="mt-4 text-xs text-slate-500">Trial 14 hari • Cancel kapan saja • Tanpa kartu kredit</p>
    </div>
</section>

</div>
HTML;
    }
}
