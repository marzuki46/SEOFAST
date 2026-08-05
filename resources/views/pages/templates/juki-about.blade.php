{{-- Juki Digital Marketing — About Us (Navy + Gold, full-page template) --}}
@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? 'Tentang Juki Digital Marketing oleh Tri Marzuki — AI Automation, Web & App Developer, Digital Marketing & SEO Specialist di Grogol, Sukoharjo, Jawa Tengah.')

@section('og_title', 'Tentang Juki Digital Marketing — Tri Marzuki, Web & App Developer Solo')
@section('og_description', 'AI Automation, Web & App Development, Digital Marketing & SEO Specialist. Berpusat di Grogol, Sukoharjo, Jawa Tengah.')
@section('og_type', 'website')

@section('styles')
@verbatim
<style>
    /* ===== Juki About - Brand Palette & Typography ===== */
    .juki-about {
        font-family: 'Plus Jakarta Sans', 'Inter', ui-sans-serif, system-ui, sans-serif;
    }

    .juki-about .reveal {
        transition: opacity .7s cubic-bezier(.22, .61, .36, 1), transform .7s cubic-bezier(.22, .61, .36, 1);
    }
    .juki-about .reveal.is-animating {
        opacity: 0;
        transform: translateY(28px);
    }
    .juki-about .reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    @media (prefers-reduced-motion: reduce) {
        .juki-about .reveal { transition: none !important; }
        .juki-about .reveal.is-animating { opacity: 1 !important; transform: none !important; }
        .juki-about .juki-float { animation: none !important; }
    }

    @keyframes juki-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-18px); }
    }
    .juki-about .juki-float { animation: juki-float 7s ease-in-out infinite; }

    .juki-about .juki-card {
        transition: transform .35s cubic-bezier(.22, .61, .36, 1), box-shadow .35s ease, border-color .35s ease;
    }
    .juki-about .juki-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 45px -18px rgba(21, 37, 63, 0.22);
    }

    .juki-about .juki-gradient-text {
        background: linear-gradient(120deg, #CE9A45 0%, #F0C878 35%, #9A6B16 60%, #CE9A45 100%);
        background-size: 220% auto;
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: juki-text-shine 5s linear infinite;
    }
    @keyframes juki-text-shine {
        to { background-position: 220% center; }
    }
    @media (prefers-reduced-motion: reduce) {
        .juki-about .juki-gradient-text { animation: none; }
    }

    .juki-about > section {
        content-visibility: auto;
        contain-intrinsic-size: auto 500px;
    }
</style>
@endverbatim
@endsection

@section('schema_markup')
@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "mainEntity": {
    "@type": "Person",
    "name": "Tri Marzuki",
    "jobTitle": "Web & App Developer, Digital Marketing & SEO Specialist",
    "url": "https://juki.eu.org/about-us",
    "telephone": "+62-822-1302-8718",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Home Parangjoro 2, Parangjoro, Grogol",
      "addressLocality": "Sukoharjo",
      "addressRegion": "Jawa Tengah",
      "postalCode": "57552",
      "addressCountry": "ID"
    },
    "worksFor": {
      "@type": "Organization",
      "name": "Juki Digital Marketing",
      "url": "https://juki.eu.org"
    },
    "knowsAbout": [
      "AI Automation", "Web Development", "App Developer", "SEO Optimization",
      "Digital Marketing", "Marketing Tools", "Laravel", "CodeIgniter", "WordPress"
    ]
  }
}
</script>
@endverbatim
@endsection

@section('content')
@php
    $brandLogo = \App\Models\SystemSetting::get('logo_url');
    $brandLogoAlt = \App\Models\SystemSetting::get('logo_alt', 'Juki Website Developer Solo');
    $waNumber = \App\Models\SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
    $headline = $page->hero_headline ?? $page->title;
    $subheadline = $page->hero_subheadline ?? 'AI Automation • Website & App Developer • Digital Marketing & SEO Specialist';
    $features = is_array($page->hero_features) ? array_values($page->hero_features) : [];

    $serviceIcons = [
        'AI Automation' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9z"/></svg>',
        'Web Development' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>',
        'SEO Optimization' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.25.843l-1.5 1.5m-7.5 0l-1.5-1.5m6.75 8.25a5.25 5.25 0 100-10.5 5.25 5.25 0 000 10.5z"/></svg>',
        'Digital Marketing' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/></svg>',
        'App Developer' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>',
        'Marketing Tools' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/></svg>',
    ];
    $serviceDescs = [
        'AI Automation' => 'WhatsApp chatbot, customer service automation, dan integrasi API untuk efisiensi bisnis Anda.',
        'Web Development' => 'Website company profile, toko online, dan landing page dengan Laravel, CodeIgniter, dan WordPress.',
        'SEO Optimization' => 'Optimasi on-page, struktur schema JSON-LD, dan riset keyword agar bisnis mudah ditemukan di Google.',
        'Digital Marketing' => 'Strategi pemasaran digital terukur untuk meningkatkan traffic, engagement, dan konversi.',
        'App Developer' => 'Pengembangan aplikasi web custom — sistem informasi, ERP, CRM, dan inventory sesuai alur bisnis.',
        'Marketing Tools' => 'Racikan tools marketing otomatis untuk mempercepat growth dan menghemat waktu operasional.',
    ];
    $fallbackIcon = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    $techs = ['Laravel', 'CodeIgniter', 'WordPress', 'PHP', 'MySQL', 'Tailwind CSS', 'REST API', 'UI/UX Design', 'SEO', 'Google Search Console', 'AI Tools', 'Digital Ads'];
    $reasons = [
        ['icon' => 'bolt',     'title' => 'Pengerjaan Cepat', 'desc' => 'Website maksimal 2 minggu. Sistem modular selesai 1 bulan lengkap dengan training.'],
        ['icon' => 'target',   'title' => 'Hasil Terukur', 'desc' => 'Progres jelas dan komunikasi langsung tanpa perantara di setiap tahap proyek.'],
        ['icon' => 'shield',   'title' => 'Keamanan Terjamin', 'desc' => 'Best practice keamanan dan kode bersih untuk melindungi data bisnis Anda.'],
        ['icon' => 'chat',     'title' => 'Garansi Support WhatsApp', 'desc' => 'Support langsung via WhatsApp 0822-1302-8718 — cepat dan personal.'],
        ['icon' => 'briefcase','title' => 'Berpengalaman', 'desc' => 'Berpengalaman menangani website UMKM dan perusahaan di Solo & Jawa Tengah.'],
        ['icon' => 'wallet',   'title' => 'Harga Transparan', 'desc' => 'Paket jelas tanpa biaya tersembunyi. Konsultasikan kebutuhan Anda secara gratis.'],
    ];
@endphp
<div class="juki-about bg-[#F8FAFC] text-[#0F172A] overflow-x-hidden">

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-white via-[#F4F7FB] to-[#E8EEF6]">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-brand-navy/5 blur-3xl juki-float"></div>
        <div class="absolute bottom-10 left-0 w-72 h-72 rounded-full bg-brand-gold/10 blur-3xl juki-float" style="animation-delay: 1.5s;"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(21,37,63,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(21,37,63,0.04)_1px,transparent_1px)] bg-[size:56px_56px]"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-20 lg:pt-28 lg:pb-24 text-center">
            @if($brandLogo)
            <div class="flex justify-center mb-8 reveal">
                <img src="{{ $brandLogo }}" alt="{{ $brandLogoAlt }}" class="h-14 sm:h-16 w-auto drop-shadow-xl" loading="eager">
            </div>
            @endif
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-[#E2E8F0] shadow-sm text-sm font-semibold text-brand-navy mb-8 reveal">
                <svg class="w-4 h-4 text-brand-gold-dark" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                Tentang Saya
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.1] tracking-tight text-[#0F172A] mb-6 reveal" data-delay="80">
                {{ $headline }}
            </h1>

            <p class="text-lg lg:text-xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed reveal" data-delay="160">
                {{ $subheadline }} — membantu bisnis tumbuh lewat
                <span class="juki-gradient-text font-bold">teknologi digital</span> yang cepat, aman, dan hasilnya terukur.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-10 reveal" data-delay="240">
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Juki%20Digital%20Marketing%2C%20saya%20ingin%20konsultasi" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-brand-gold hover:bg-brand-gold/90 text-brand-navy font-bold text-lg shadow-xl shadow-brand-gold/25 transition-all hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    {{ $page->hero_cta_text ?? 'Hubungi via WhatsApp' }}
                </a>
                <a href="{{ $page->hero_cta_url_2 ?? url('/contact') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-white border-2 border-brand-navy/15 text-brand-navy hover:border-brand-navy/40 font-bold text-lg transition-all hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    {{ $page->hero_cta_text_2 ?? 'Kirim Inquiry' }}
                </a>
            </div>

            @if(count($features) > 0)
            <div class="flex flex-wrap justify-center gap-3 reveal" data-delay="320">
                @foreach($features as $feature)
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#E2E8F0] text-slate-700 text-sm font-semibold shadow-sm">
                    <svg class="w-4 h-4 text-brand-navy shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    {{ $feature }}
                </span>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    {{-- ============ STATS BAR ============ --}}
    <section class="bg-brand-navy-deep text-white border-t border-brand-gold/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="reveal">
                    <p class="text-3xl lg:text-4xl font-extrabold text-brand-gold-light">2 Minggu</p>
                    <p class="mt-1 text-sm text-blue-100">Pengerjaan Website Maksimal</p>
                </div>
                <div class="reveal" data-delay="80">
                    <p class="text-3xl lg:text-4xl font-extrabold text-brand-gold-light">1 Bulan</p>
                    <p class="mt-1 text-sm text-blue-100">Sistem Modular + Training</p>
                </div>
                <div class="reveal" data-delay="160">
                    <p class="text-3xl lg:text-4xl font-extrabold text-brand-gold-light">3+ Teknologi</p>
                    <p class="mt-1 text-sm text-blue-100">Laravel, CodeIgniter, WordPress</p>
                </div>
                <div class="reveal" data-delay="240">
                    <p class="text-3xl lg:text-4xl font-extrabold text-brand-gold-light">100%</p>
                    <p class="mt-1 text-sm text-blue-100">Garansi Support WhatsApp</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PROFIL ============ --}}
    <section class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div class="reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">Profil Singkat</p>
                <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-brand-navy mb-5">
                    Tri Marzuki — Founder &amp; Digital Specialist
                </h2>
                <p class="text-slate-600 leading-relaxed mb-4">
                    <strong class="text-[#0F172A]">Juki Digital Marketing</strong> adalah personal brand dari
                    <strong class="text-[#0F172A]">Tri Marzuki</strong> — jasa pembuatan website &amp; sistem informasi
                    di Solo Raya, berpusat di Grogol, Sukoharjo, melayani UMKM dan perusahaan di Surakarta, Sukoharjo,
                    Karanganyar, Boyolali, Klaten, dan seluruh Jawa Tengah.
                </p>
                <p class="text-slate-600 leading-relaxed mb-4">
                    Saya membangun website yang tidak hanya tampil menarik, tetapi juga cepat, aman, dan
                    <strong class="text-[#0F172A]">SEO-friendly</strong>. Setiap halaman dioptimasi untuk Core Web Vitals,
                    struktur schema JSON-LD, dan mobile-first, sehingga bisnis Anda mudah ditemukan di Google.
                </p>
                <p class="text-slate-600 leading-relaxed">
                    Dengan AI automation dan marketing tools, alur kerja saya dirancang agar efisien — project selesai
                    lebih cepat dengan kualitas yang tetap terjaga. Garansi support langsung via WhatsApp memastikan Anda
                    tidak pernah sendirian setelah website diluncurkan.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Juki%20Digital%20Marketing%2C%20saya%20ingin%20konsultasi" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand-navy hover:bg-brand-navy-deep text-white font-semibold transition-all">
                        Chat WhatsApp
                    </a>
                    <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white border-2 border-brand-gold-dark text-brand-gold-dark hover:bg-brand-gold hover:text-brand-navy font-semibold transition-all">
                        Kirim Inquiry
                    </a>
                </div>
            </div>

            <div class="reveal" data-delay="120">
                <div class="bg-[#F1F5F9] border border-[#E2E8F0] rounded-3xl p-8">
                    <h3 class="text-lg font-bold text-brand-navy mb-6">Teknologi &amp; Keahlian</h3>
                    <div class="flex flex-wrap gap-3 mb-8">
                        @foreach($techs as $tech)
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#E2E8F0] text-sm font-semibold text-brand-navy shadow-sm">
                            <svg class="w-4 h-4 text-brand-gold-dark" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"/></svg>
                            {{ $tech }}
                        </span>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-brand-navy shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-slate-700">Responsive &amp; mobile-first design</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-brand-navy shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-slate-700">Optimasi kecepatan &amp; Core Web Vitals</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-brand-navy shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-slate-700">Struktur SEO &amp; schema JSON-LD</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-brand-navy shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-slate-700">Keamanan SSL &amp; backup rutin</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ LAYANAN ============ --}}
    <section class="py-20 lg:py-24 bg-[#F1F5F9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">Layanan Saya</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-brand-navy mb-4">Solusi Digital Lengkap</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Dari website hingga AI automation — satu partner untuk semua kebutuhan digital bisnis Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $index => $feature)
                <div class="juki-card bg-white border border-[#E2E8F0] rounded-3xl p-8 reveal" data-delay="{{ ($index % 3) * 80 }}">
                    <div class="w-16 h-16 rounded-2xl {{ ($index % 3) === 2 ? 'bg-brand-gold text-brand-navy' : 'bg-brand-navy text-white' }} flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-brand-navy/20">
                        {!! $serviceIcons[$feature] ?? $fallbackIcon !!}
                    </div>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-3">{{ $feature }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">{{ $serviceDescs[$feature] ?? 'Layanan profesional yang disesuaikan dengan kebutuhan bisnis Anda.' }}</p>
                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-navy hover:text-brand-gold-dark transition-colors">Konsultasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ KENAPA MEMILIH ============ --}}
    <section class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">Kenapa Memilih Saya</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-brand-navy mb-4">Partner Digital Terpercaya di Solo</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Lebih dari sekadar developer — mitra strategis untuk pertumbuhan bisnis Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($reasons as $index => $reason)
                <div class="juki-card bg-white border border-[#E2E8F0] rounded-2xl p-7 reveal" data-delay="{{ $index * 70 }}">
                    <div class="w-14 h-14 rounded-xl bg-brand-navy/5 text-brand-navy flex items-center justify-center mb-5">
                        @if($reason['icon'] === 'bolt')
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @elseif($reason['icon'] === 'target')
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @elseif($reason['icon'] === 'shield')
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                        @elseif($reason['icon'] === 'chat')
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        @elseif($reason['icon'] === 'briefcase')
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        @elseif($reason['icon'] === 'wallet')
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-[#0F172A] mb-2">{{ $reason['title'] }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $reason['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ CTA ============ --}}
    <section class="py-20 lg:py-24 bg-gradient-to-br from-brand-navy-deep via-brand-navy to-brand-navy-mid">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
            <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-light mb-3">Mulai Sekarang</p>
            <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-white mb-5">Siap Mewujudkan Ide Digital Anda?</h2>
            <p class="text-lg text-blue-100 max-w-2xl mx-auto mb-10">
                Konsultasikan project Anda secara gratis. Saya siap membantu dari konsep hingga hasil akhir.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Juki%20Digital%20Marketing%2C%20saya%20ingin%20konsultasi" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-brand-gold hover:bg-brand-gold/90 text-brand-navy font-bold text-lg shadow-xl shadow-brand-gold/25 transition-all hover:-translate-y-0.5">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Chat via WhatsApp
                </a>
                <a href="{{ url('/contact') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-white/10 border-2 border-brand-gold/40 text-white hover:bg-white/20 font-bold text-lg transition-all hover:-translate-y-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    Kirim Inquiry
                </a>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var els = document.querySelectorAll('.juki-about .reveal');
    if ('IntersectionObserver' in window) {
        els.forEach(function (el) {
            el.classList.add('is-animating');
        });
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('is-animating');
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        els.forEach(function (el) { io.observe(el); });
    } else {
        els.forEach(function (el) { el.classList.add('is-visible'); });
    }
});
</script>
@endpush
