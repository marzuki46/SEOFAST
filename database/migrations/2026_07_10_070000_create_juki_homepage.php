<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Page::where('is_homepage', true)->update(['is_homepage' => false]);

        Page::create([
            'title' => 'Juki Digital Marketing — SEO & AI Content Agency Indonesia',
            'slug' => 'home',
            'template' => 'hero-centered',
            'is_homepage' => true,
            'is_published' => true,
            'meta_title' => 'Juki Digital Marketing — Raih Peringkat #1 di Google Tanpa Iklan Mahal',
            'meta_description' => 'Juki Digital Marketing solusi SEO & AI content untuk UMKM Indonesia. Naikkan traffic organik, perbaiki peringkat Google, dan dapatkan pelanggan baru tanpa biaya iklan.',
            'hero_headline' => 'Naikkan Omzet Bisnismu Lewat Google — Tanpa Jasa Iklan Mahal',
            'hero_subheadline' => 'Juki Digital Marketing bantu UMKM & agency di Indonesia naik peringkat di Google dengan strategi SEO terbukti, konten AI berkualitas, dan pipeline otomatis yang udah teruji.',
            'hero_cta_text' => 'Konsultasi Gratis',
            'hero_cta_url' => '#konsultasi',
            'hero_cta_text_2' => 'Lihat Portofolio',
            'hero_cta_url_2' => '#portofolio',
            'hero_features' => [
                'SEO Optimization',
                'AI Content',
                'Web Development',
                'Digital Strategy',
                'Google Analytics',
                'Social Media',
            ],
            'html_content' => '
<!-- Stats Bar -->
<section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl mb-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
            <div>
                <div class="font-outfit font-extrabold text-4xl md:text-5xl mb-2">50+</div>
                <div class="text-white/80 text-sm font-medium">Klien Puas</div>
            </div>
            <div>
                <div class="font-outfit font-extrabold text-4xl md:text-5xl mb-2">300%</div>
                <div class="text-white/80 text-sm font-medium">Rata-rata Kenaikan Traffic</div>
            </div>
            <div>
                <div class="font-outfit font-extrabold text-4xl md:text-5xl mb-2">#1</div>
                <div class="text-white/80 text-sm font-medium">Peringkat Google untuk 90% Keyword</div>
            </div>
            <div>
                <div class="font-outfit font-extrabold text-4xl md:text-5xl mb-2">1000+</div>
                <div class="text-white/80 text-sm font-medium">Konten Terindeks</div>
            </div>
        </div>
    </div>
</section>

<!-- Layanan Section -->
<section id="layanan" class="mb-20">
    <div class="text-center max-w-3xl mx-auto mb-14">
        <p class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3">LAYANAN</p>
        <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">Yang Bisa Saya Bantu</h2>
        <p class="text-slate-600 text-lg">Setiap layanan dirancang untuk kasih hasil nyata — bukan cuma janji manis.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-14 h-14 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">SEO Terpadu</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Technical audit, riset keyword, optimasi on-page, & silo structure. Website kamu bakal ramah Google dari pondasi.</p>
        </div>
        <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-14 h-14 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">AI Content Automation</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Konten blog berkualitas tinggi yang nulis sendiri — otomatis teroptimasi SEO, terstruktur silo, dan siap indeks dalam hitungan jam.</p>
        </div>
        <div class="p-8 rounded-2xl border border-slate-200 bg-white hover:shadow-lg hover:border-indigo-200 transition-all group">
            <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>
            </div>
            <h3 class="font-outfit font-bold text-xl text-slate-900 mb-3">Web Development</h3>
            <p class="text-slate-600 text-sm leading-relaxed">Bikin website Laravel yang cepet, SEO-friendly, dan gampang diurus. Cocok buat landing page, company profile, sampai marketplace.</p>
        </div>
    </div>
</section>

<!-- Kenapa Pilih Juki -->
<section class="mb-20 p-10 md:p-16 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 text-white">
    <div class="max-w-4xl mx-auto text-center">
        <p class="text-sm font-bold text-indigo-400 uppercase tracking-wider mb-3">KENAPA PILIH JUKI?</p>
        <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-6">Bukan Cuma Jasa SEO Biasa</h2>
        <p class="text-slate-300 text-lg mb-10">Saya beda dari yang lain. Ini alasannya:</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
            <div class="flex gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm">
                <span class="text-indigo-400 text-2xl font-bold shrink-0">01</span>
                <div>
                    <h4 class="font-bold text-white mb-1">Silo Architecture</h4>
                    <p class="text-sm text-slate-400">Setiap konten terstruktur dalam Topical Silo — bukan artikel acakan. Google liat kamu sebagai authority, bukan blog biasa.</p>
                </div>
            </div>
            <div class="flex gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm">
                <span class="text-indigo-400 text-2xl font-bold shrink-0">02</span>
                <div>
                    <h4 class="font-bold text-white mb-1">AI Pipeline Bukan Copy-Paste</h4>
                    <p class="text-sm text-slate-400">Pakai multi-agent AI (Drafter, Inquirer, Expander, Editor) yang saling review — hasilnya konten dalem, bukan generik.</p>
                </div>
            </div>
            <div class="flex gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm">
                <span class="text-indigo-400 text-2xl font-bold shrink-0">03</span>
                <div>
                    <h4 class="font-bold text-white mb-1">Closed-Loop GSC Sync</h4>
                    <p class="text-sm text-slate-400">Terintegrasi langsung sama Google Search Console. Konten yang turun peringkat otomatis masuk antrian re-optimasi.</p>
                </div>
            </div>
            <div class="flex gap-4 bg-white/5 rounded-xl p-6 backdrop-blur-sm">
                <span class="text-indigo-400 text-2xl font-bold shrink-0">04</span>
                <div>
                    <h4 class="font-bold text-white mb-1">Transparan & Terukur</h4>
                    <p class="text-sm text-slate-400">Kamu bisa liat real-time progress: peringkat keyword, jumlah konten terindeks, skor kualitas konten — semua dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonial -->
<section class="mb-20">
    <div class="text-center max-w-3xl mx-auto mb-14">
        <p class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3">TESTIMONIAL</p>
        <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">Apa Kata Klien?</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex gap-1 mb-4 text-amber-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed mb-4">"Traffic website naik 250% dalam 3 bulan setelah pake jasa SEO dari Juki. Yang paling saya suka, progress-nya real-time, gak perlu ngejar-ngejar laporan."</p>
            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">A</div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Andi Pratama</p>
                    <p class="text-xs text-slate-500">Owner, Toko Online Jakarta</p>
                </div>
            </div>
        </div>
        <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex gap-1 mb-4 text-amber-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed mb-4">"Dulu saya pake jasa SEO lain, hasilnya gak jelas. Begitu pake Juki, dalam 2 bulan keyword utama udah di halaman 1 Google. Recommended!"</p>
            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-sm">S</div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Sari Dewi</p>
                    <p class="text-xs text-slate-500">Founder, Beauty Brand Bandung</p>
                </div>
            </div>
        </div>
        <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex gap-1 mb-4 text-amber-400">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed mb-4">"Web development sekaligus SEO dalam satu paket. Gak perlu repot ngurus teknis. Tinggal fokus bisnis, urusan Google biar Juki yang urus."</p>
            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-sm">R</div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Rudi Hartono</p>
                    <p class="text-xs text-slate-500">CEO, PT Digital Nusantara</p>
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
            <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-2">Insight & Tutorial Terbaru</h2>
            <p class="text-slate-600">Tips SEO, AI content, dan digital marketing yang langsung bisa dipraktekin.</p>
        </div>
        <a href="/blog" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Lihat Semua Artikel <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg></a>
    </div>
    <div class="seofast-posts-grid" data-columns="3" data-limit="3"></div>
</section>

<!-- FAQ -->
<section id="faq" class="mb-20">
    <div class="text-center max-w-3xl mx-auto mb-14">
        <p class="text-sm font-bold text-indigo-600 uppercase tracking-wider mb-3">FAQ</p>
        <h2 class="font-outfit font-bold text-3xl md:text-5xl text-slate-900 mb-4">Pertanyaan yang Sering Diajukan</h2>
    </div>
    <div class="max-w-3xl mx-auto space-y-4">
        <details class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all" open>
            <summary class="font-outfit font-bold text-lg text-slate-900 cursor-pointer list-none flex items-center justify-between gap-4">Berapa lama hasil SEO bisa keliatan? <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary>
            <p class="mt-4 text-slate-600 text-sm leading-relaxed">Hasil awal biasanya keliatan di bulan ke-2 sampai ke-4. Tergantung kompetisi keyword dan kondisi website kamu. Yang penting, strategi saya udah teruji bisa naikin traffic organik secara signifikan.</p>
        </details>
        <details class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all">
            <summary class="font-outfit font-bold text-lg text-slate-900 cursor-pointer list-none flex items-center justify-between gap-4">Apakah cocok untuk UMKM dengan budget terbatas? <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary>
            <p class="mt-4 text-slate-600 text-sm leading-relaxed">Sangat cocok. Saya punya paket yang bisa disesuaikan dengan budget UMKM. Justru SEO adalah strategi jangka panjang paling hemat dibanding iklan berbayar yang tiap bulan keluar duit terus.</p>
        </details>
        <details class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all">
            <summary class="font-outfit font-bold text-lg text-slate-900 cursor-pointer list-none flex items-center justify-between gap-4">Apakah pakai konten AI? Apakah aman buat SEO? <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary>
            <p class="mt-4 text-slate-600 text-sm leading-relaxed">Iya, saya pakai AI buat produksi konten — tapi bukan asal generate. Saya punya sistem multi-agent yang nulis, review, expand, dan edit biar hasilnya dalem dan memenuhi standar E-E-A-T Google. Konten saya juga lolos CQI (Content Quality Index) minimal 80 sebelum dipublish.</p>
        </details>
        <details class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm open:border-indigo-200 open:ring-1 open:ring-indigo-100 transition-all">
            <summary class="font-outfit font-bold text-lg text-slate-900 cursor-pointer list-none flex items-center justify-between gap-4">Gimana cara mulai? <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg></summary>
            <p class="mt-4 text-slate-600 text-sm leading-relaxed">Klik tombol <strong>Konsultasi Gratis</strong> di atas. Kita diskusi dulu tentang kondisi website kamu, target keyword, dan goals bisnis. Gak ada kewajiban, kok.</p>
        </details>
    </div>
</section>

<!-- Final CTA -->
<section class="text-center py-16 px-4 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 text-white mb-8">
    <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-6">Siap Naikkan Peringkat Google-mu?</h2>
    <p class="text-lg text-white/80 max-w-2xl mx-auto mb-10">Jangan biarin website kamu tenggelam di halaman 2. Konsultasi gratis — gak ada kewajiban, gak ada harga palsu.</p>
    <a href="#konsultasi" class="inline-flex items-center gap-2 px-10 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-xl hover:scale-105 transition-all">Konsultasi Gratis Sekarang <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg></a>
</section>
',
        ]);
    }

    public function down(): void
    {
        Page::where('slug', 'home')->forceDelete();
    }
};
