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

        $htmlContent = <<<HTML
<!-- Layanan -->
<section id="layanan" class="mb-20">
    <div class="text-center max-w-3xl mx-auto mb-14">
        <p class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3">LAYANAN</p>
        <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">Apa yang Saya Tawarkan</h2>
        <p class="text-slate-600 text-lg">Fokus ke hasil, bukan janji. Setiap project ditangani langsung dan dikomunikasikan secara transparan.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-14 h-14 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">SEO Website</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Optimasi website biar ramah Google. Technical audit, riset keyword, on-page SEO, dan struktur konten. Cocok buat website yang udah jalan tapi pengen naik peringkat.</p>
        </div>
        <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">Pembuatan Website</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Bikin website pakai Laravel dari nol. Landing page, company profile, toko online, atau platform custom — dibikin cepat, SEO-friendly, dan gampang diurus.</p>
        </div>
        <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-14 h-14 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">Produk & Project Lain</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Plugin WordPress, tema SEOFAST, dan project AI sesuai request. Punya kebutuhan custom? Diskusiin aja, saya usahakan.</p>
        </div>
    </div>
</section>

<!-- Kenapa Pilih Juki -->
<section class="mb-20 p-10 md:p-16 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 text-white">
    <div class="max-w-4xl mx-auto">
        <p class="text-sm font-bold text-indigo-400 uppercase tracking-wider mb-3 text-center">KENAPA PILIH JUKI?</p>
        <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-6 text-center">Pendekatan yang Jujur & Terstruktur</h2>
        <p class="text-slate-300 text-lg mb-10 text-center">Saya kerja dengan sistem, bukan asal-asalan.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
            <div class="flex gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm">
                <span class="text-indigo-400 text-2xl font-bold shrink-0">01</span>
                <div>
                    <h4 class="font-bold text-white mb-1">Silo Structure</h4>
                    <p class="text-sm text-slate-400">Konten diatur dalam topik yang terstruktur, bukan artikel acak. Biar Google paham kamu expert di bidangmu.</p>
                </div>
            </div>
            <div class="flex gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm">
                <span class="text-indigo-400 text-2xl font-bold shrink-0">02</span>
                <div>
                    <h4 class="font-bold text-white mb-1">Transparan</h4>
                    <p class="text-sm text-slate-400">Kamu bisa liat progress real-time. Gak perlu ngejar-ngejar laporan. Semua terpantau dari dashboard.</p>
                </div>
            </div>
            <div class="flex gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm">
                <span class="text-indigo-400 text-2xl font-bold shrink-0">03</span>
                <div>
                    <h4 class="font-bold text-white mb-1">AI Sebagai Alat Bantu</h4>
                    <p class="text-sm text-slate-400">Saya pakai AI buat bantu produksi konten — dipantau, diedit, dan dipastiin kualitasnya. Bukan asal generate.</p>
                </div>
            </div>
            <div class="flex gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm">
                <span class="text-indigo-400 text-2xl font-bold shrink-0">04</span>
                <div>
                    <h4 class="font-bold text-white mb-1">Fokus ke Bisnis Kamu</h4>
                    <p class="text-sm text-slate-400">Saya ngobrol dulu, ngerti dulu bisnis kamu, baru eksekusi. Bukan jualan paket kaku yang gak sesuai kebutuhan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts Grid -->
<section class="mb-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
        <div>
            <p class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3">DARI BLOG</p>
            <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-2">Tips & Tutorial</h2>
            <p class="text-slate-600">Catatan seputar SEO, Laravel, dan digital marketing dari pengalaman sehari-hari.</p>
        </div>
        <a href="{$blogUrl}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Lihat Semua Artikel <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg></a>
    </div>
    <div class="seofast-posts-grid" data-columns="3" data-limit="3"></div>
</section>

<!-- Produk -->
<section class="mb-20">
    <div class="text-center max-w-3xl mx-auto mb-14">
        <p class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3">PRODUK</p>
        <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">Yang Lagi Saya Kerjakan</h2>
        <p class="text-slate-600 text-lg">Beberapa produk yang sedang dalam pengembangan dan siap digunakan.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:shadow-md transition-all">
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-2">Plugin WordPress</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Plugin untuk berbagai keperluan SEO, performa, dan integrasi. Rilis menyusul.</p>
        </div>
        <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:shadow-md transition-all">
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-2">Tema SEOFAST</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Theme WordPress yang dioptimasi untuk kecepatan dan SEO. Cocok buat blog dan company profile.</p>
        </div>
        <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:shadow-md transition-all">
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-2">Project AI Custom</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Ada kebutuhan project AI, automation, atau sistem custom? Diskusiin, saya usahakan sesuai request.</p>
        </div>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="mb-20">
    <div class="text-center max-w-3xl mx-auto mb-14">
        <p class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3">FAQ</p>
        <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">Pertanyaan yang Sering Diajukan</h2>
    </div>
    <div class="max-w-3xl mx-auto space-y-4">
        <details class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all" open>
            <summary class="font-outfit font-bold text-lg text-slate-900 cursor-pointer list-none flex items-center justify-between gap-4">SEO-nya gimana cara kerja? <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary>
            <p class="mt-4 text-slate-600 text-sm leading-relaxed">Saya liat kondisi website kamu dulu, audit teknis, riset keyword yang realistis, terus optimasi on-page dan konten. Semua dipantau lewat dashboard Google Search Console.</p>
        </details>
        <details class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all">
            <summary class="font-outfit font-bold text-lg text-slate-900 cursor-pointer list-none flex items-center justify-between gap-4">Berapa lama hasilnya? <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary>
            <p class="mt-4 text-slate-600 text-sm leading-relaxed">SEO butuh waktu. Umumnya 2-4 bulan mulai keliatan pergerakan. Tergantung kompetisi keyword, usia domain, dan kondisi awal website. Sabar dan konsisten adalah kuncinya.</p>
        </details>
        <details class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all">
            <summary class="font-outfit font-bold text-lg text-slate-900 cursor-pointer list-none flex items-center justify-between gap-4">Kalau cuma butuh bikin website aja? <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary>
            <p class="mt-4 text-slate-600 text-sm leading-relaxed">Bisa. Saya terima project pembuatan website aja tanpa SEO. Laravel, responsive, SEO-friendly basic udah include.</p>
        </details>
        <details class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all">
            <summary class="font-outfit font-bold text-lg text-slate-900 cursor-pointer list-none flex items-center justify-between gap-4">Gimana cara mulai? <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary>
            <p class="mt-4 text-slate-600 text-sm leading-relaxed">Klik tombol <strong>Konsultasi Gratis</strong> di atas. Kita ngobrol dulu, gak ada kewajiban. Saya dengerin dulu kebutuhan kamu, baru bahas solusinya.</p>
        </details>
    </div>
</section>

<!-- Final CTA -->
<section class="text-center py-16 px-4 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 text-white mb-8">
    <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-6">Ada yang Mau Didiskusikan?</h2>
    <p class="text-lg text-white/80 max-w-2xl mx-auto mb-10">Butuh website? Pengen naik peringkat di Google? Atau ada project custom? Ngobrol aja dulu, gratis.</p>
    <a href="{$contactUrl}" class="inline-flex items-center gap-2 px-10 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-xl hover:scale-105 transition-all">Hubungi Saya <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg></a>
</section>
HTML;

        $page->update([
            'hero_headline' => 'Butuh Website Profesional & SEO? Saya Bantu dari Nol sampai Online',
            'hero_subheadline' => 'Juki Digital Marketing — layanan SEO website & pembuatan website Laravel untuk UMKM, agency, dan siapapun yang mau serius di Google.',
            'meta_description' => 'Jasa SEO website & pembuatan website Laravel. Juga bikin plugin, tema SEOFAST, dan project AI sesuai request. Juki Digital Marketing, siap bantu.',
            'meta_title' => 'Juki Digital Marketing — Jasa SEO Website & Pembuatan Website',
            'hero_cta_url' => $contactUrl,
            'hero_cta_text_2' => 'Lihat Blog',
            'hero_cta_url_2' => $blogUrl,
            'html_content' => $htmlContent,
        ]);
    }

    public function down(): void
    {
        // no rollback needed
    }
};
