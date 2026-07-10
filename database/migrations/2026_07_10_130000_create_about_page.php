<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $page = Page::where('slug', 'about')->first();
        if ($page) return;

        $contactUrl = url('/contact');

        $cssContent = <<<'CSS'
.about-hero-icon {
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}
.about-hero-icon:hover { transform: scale(1.1) rotate(-3deg); }

.timeline-item {
    position: relative;
    padding-left: 2rem;
    padding-bottom: 2rem;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.5rem;
    width: 2px;
    height: 100%;
    background: linear-gradient(to bottom, #6366f1, #a78bfa);
}
.timeline-item::after {
    content: '';
    position: absolute;
    left: -4px;
    top: 0.5rem;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #6366f1;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #6366f1;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
}
.float-anim { animation: float 5s ease-in-out infinite; }

.stat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(99, 102, 241, 0.12);
}
CSS;

        $htmlContent = <<<HTML
<!-- ══════════════════════════════════════════ -->
<!-- HERO                                       -->
<!-- ══════════════════════════════════════════ -->
<section class="mb-24">
    <div class="max-w-4xl mx-auto text-center">
        <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">TENTANG SAYA</span>
        <h1 class="font-outfit font-bold text-4xl md:text-6xl text-slate-900 mb-6 leading-tight">
            Tri Marzuki
        </h1>
        <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed mb-8">
            Developer, SEO Specialist, dan Founder <strong class="text-slate-900">Juki Digital Marketing</strong>. 
            Membangun solusi digital berbasis AI untuk membantu bisis naik peringkat di Google.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{$contactUrl}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all hover:scale-105">
                Hubungi Saya
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            <a href="#story" class="inline-flex items-center gap-2 px-8 py-4 border-2 border-slate-300 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 font-bold rounded-xl transition-all">
                Cerita Saya
            </a>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════ -->
<!-- STATS                                      -->
<!-- ══════════════════════════════════════════ -->
<section class="mb-24">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 max-w-4xl mx-auto">
        <div class="stat-card bg-white border border-slate-200 rounded-2xl p-6 md:p-8 text-center shadow-sm">
            <div class="font-outfit font-extrabold text-3xl md:text-4xl bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent mb-2">10+</div>
            <div class="text-slate-500 text-sm font-medium">Tahun Pengalaman</div>
        </div>
        <div class="stat-card bg-white border border-slate-200 rounded-2xl p-6 md:p-8 text-center shadow-sm">
            <div class="font-outfit font-extrabold text-3xl md:text-4xl bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent mb-2">50+</div>
            <div class="text-slate-500 text-sm font-medium">Project SEO</div>
        </div>
        <div class="stat-card bg-white border border-slate-200 rounded-2xl p-6 md:p-8 text-center shadow-sm">
            <div class="font-outfit font-extrabold text-3xl md:text-4xl bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent mb-2">1000+</div>
            <div class="text-slate-500 text-sm font-medium">Konten Teroptimasi</div>
        </div>
        <div class="stat-card bg-white border border-slate-200 rounded-2xl p-6 md:p-8 text-center shadow-sm">
            <div class="font-outfit font-extrabold text-3xl md:text-4xl bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent mb-2">3</div>
            <div class="text-slate-500 text-sm font-medium">Produk Digital</div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════ -->
<!-- STORY                                      -->
<!-- ══════════════════════════════════════════ -->
<section class="mb-24" id="story">
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">CERITA SINGKAT</span>
                <h2 class="font-outfit font-bold text-3xl md:text-4xl text-slate-900 mb-6 leading-tight">
                    Dari Ngoprek Code<br>Jadi SEO <span class="text-indigo-600">Automation</span>
                </h2>
                <div class="prose prose-slate max-w-none">
                    <p class="text-slate-600 leading-relaxed">
                        Sejak 2014 saya berkecimpung di dunia web development — dari HTML static sampai Laravel enterprise. 
                        Bertahun-tahun menangani project digital, saya sadar satu hal: <strong class="text-slate-900">SEO masih manual, 
        repetitive, dan rentan error</strong>.
                    </p>
                    <p class="text-slate-600 leading-relaxed mt-4">
                        Tahun 2025, saya memutuskan membangun solusinya sendiri: <strong class="text-slate-900">SEOFAST Framework</strong> — 
                        sebuah SEO Operating System untuk Laravel yang mengotomatisasi semua aspek teknis SEO. 
                        Bukan plugin, bukan add-on. Tapi <strong class="text-slate-900">platform end-to-end</strong>.
                    </p>
                    <p class="text-slate-600 leading-relaxed mt-4">
                        Sekarang saya membantu bisnis dan agency memanfaatkan SEO automation untuk 
                        <strong class="text-slate-900">tumbuh lebih cepat di Google</strong>. Dari pembuatan konten AI, 
                        silo architecture, sampai Google Search Console sync — semuanya terintegrasi dalam satu sistem.
                    </p>
                </div>
            </div>
            <div class="relative">
                <div class="w-64 h-64 md:w-80 md:h-80 mx-auto rounded-3xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center float-anim shadow-xl shadow-indigo-200/50">
                    <svg class="w-32 h-32 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════ -->
<!-- TIMELINE / JOURNEY                          -->
<!-- ══════════════════════════════════════════ -->
<section class="mb-24">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-12">
            <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">PERJALANAN</span>
            <h2 class="font-outfit font-bold text-3xl md:text-4xl text-slate-900 mb-4 leading-tight">Bagaimana Sampai di Sini</h2>
        </div>
        <div class="space-y-0">
            <div class="timeline-item">
                <h3 class="font-outfit font-bold text-lg text-slate-900 mb-1">2014 — Mulai Ngoding</h3>
                <p class="text-slate-500 text-sm">Belajar HTML, CSS, PHP dari nol. Mulai bikin website sederhana untuk teman dan UKM lokal.</p>
            </div>
            <div class="timeline-item">
                <h3 class="font-outfit font-bold text-lg text-slate-900 mb-1">2017 — Masuk Laravel</h3>
                <p class="text-slate-500 text-sm">Beralih ke Laravel untuk project yang lebih kompleks. Mulai serius di web development dan REST API.</p>
            </div>
            <div class="timeline-item">
                <h3 class="font-outfit font-bold text-lg text-slate-900 mb-1">2020 — SEO Specialist</h3>
                <p class="text-slate-500 text-sm">Mulai fokus di SEO setelah melihat banyak project bagus tapi tidak muncul di Google. Belajar silo structure, topical authority, technical SEO.</p>
            </div>
            <div class="timeline-item">
                <h3 class="font-outfit font-bold text-lg text-slate-900 mb-1">2025 — Lahirnya SEOFAST</h3>
                <p class="text-slate-500 text-sm">Membangun SEOFAST Framework — solusi SEO automation untuk Laravel. Bukan plugin, platform end-to-end.</p>
            </div>
            <div class="timeline-item">
                <h3 class="font-outfit font-bold text-lg text-slate-900 mb-1">2026 — Juki Digital Marketing</h3>
                <p class="text-slate-500 text-sm">Meluncurkan Juki Digital Marketing: produk digital, jasa SEO, dan AI automation dalam satu brand.</p>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════ -->
<!-- WHAT I DO                                   -->
<!-- ══════════════════════════════════════════ -->
<section class="mb-24">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <span class="inline-block text-xs font-bold text-indigo-600 uppercase tracking-[0.2em] mb-4">KEAHLIAN</span>
            <h2 class="font-outfit font-bold text-3xl md:text-4xl text-slate-900 mb-4 leading-tight">Apa yang Saya Kerjakan</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white mb-4 shadow-md shadow-indigo-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>
                </div>
                <h3 class="font-outfit font-bold text-slate-900 mb-2">Web Development</h3>
                <p class="text-sm text-slate-500">Laravel, REST API, database architecture, deployment. Bikin sistem yang scalable dan maintainable.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white mb-4 shadow-md shadow-emerald-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                </div>
                <h3 class="font-outfit font-bold text-slate-900 mb-2">SEO & Optimization</h3>
                <p class="text-sm text-slate-500">Technical SEO, silo architecture, keyword research, content optimization, Google Search Console.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white mb-4 shadow-md shadow-purple-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                </div>
                <h3 class="font-outfit font-bold text-slate-900 mb-2">AI Automation</h3>
                <p class="text-sm text-slate-500">AI content pipeline, multi-agent system, custom automation. Memanfaatkan AI untuk hasil nyata.</p>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════ -->
<!-- CTA                                        -->
<!-- ══════════════════════════════════════════ -->
<section class="anim-fade-up text-center py-16 px-6 md:py-20 md:px-12 rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.1),transparent_60%)]"></div>
    <div class="relative">
        <h2 class="font-outfit font-bold text-3xl md:text-5xl mb-6 leading-tight">Ada Project? Mari Diskusikan</h2>
        <p class="text-lg text-white/80 max-w-2xl mx-auto mb-10 leading-relaxed">
            Butuh website? Mau optimasi SEO? Atau tertarik dengan SEOFAST Framework? 
            Ngobrol aja dulu, gratis.
        </p>
        <a href="{$contactUrl}" class="inline-flex items-center gap-2 px-10 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-xl hover:scale-105 transition-all duration-300">
            Konsultasi Gratis
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>
</section>
HTML;

        Page::create([
            'title' => 'Tentang Saya — Tri Marzuki | Juki Digital Marketing',
            'slug' => 'about',
            'template' => 'hero-centered',
            'is_published' => true,
            'meta_title' => 'Tentang Tri Marzuki — Founder Juki Digital Marketing & SEOFAST Framework',
            'meta_description' => 'Kenalan dengan Tri Marzuki: developer Laravel, SEO specialist, dan founder Juki Digital Marketing. Dari ngoprek code jadi bikin SEO automation platform.',
            'hero_headline' => 'Tri Marzuki',
            'hero_subheadline' => 'Developer Laravel | SEO Specialist | Founder Juki Digital Marketing. Membangun SEOFAST Framework — SEO Operating System untuk Laravel.',
            'hero_cta_text' => 'Hubungi Saya',
            'hero_cta_url' => $contactUrl,
            'hero_cta_text_2' => null,
            'hero_cta_url_2' => null,
            'hero_features' => [
                'Laravel Expert',
                'SEO Service',
                'AI Service',
                'Produk Digital',
            ],
            'css_content' => $cssContent,
            'html_content' => $htmlContent,
            'hero_bg_color' => '#0f172a',
        ]);
    }

    public function down(): void
    {
        Page::where('slug', 'about')->delete();
    }
};
