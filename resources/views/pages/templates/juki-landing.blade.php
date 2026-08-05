<!-- Juki Website Developer Solo - Landing Page Template (Light Theme, Navy + Gold) -->
@extends('layouts.frontend')

@section('title', 'Web Developer Solo | Jasa Pembuatan Website & Web Design Solo - Juki')
@section('meta_description', 'Web Developer Solo: Jasa pembuatan website Solo & Surakarta mulai Rp 2,5 juta (WordPress), sistem informasi mulai Rp 10 juta (Laravel, CodeIgniter). Pengerjaan website maksimal 2 minggu. Garansi support WhatsApp 0822-1302-8718.')

@section('meta_keywords', 'web developer solo, web design solo, jasa pembuatan website solo, jasa web solo, jasa pembuatan website surakarta, web developer surakarta, web design surakarta, jasa buat web solo, pembuatan website murah solo, jasa website umkm solo, website company profile solo, jasa toko online solo, landing page solo, jasa pembuatan website grogol, jasa website sukoharjo, jasa web jawa tengah, web programmer solo, laravel developer solo, codeigniter developer, wordpress developer solo, jasa seo solo, jasa backlink solo, pembuatan sistem informasi, jasa pembuatan aplikasi web, website profesional solo')

@section('og_title', 'Web Developer Solo - Jasa Pembuatan Website & Web Design Profesional')
@section('og_description', 'Jasa pembuatan website Solo & Surakarta mulai Rp 2,5 juta, sistem informasi mulai Rp 10 juta. Pengerjaan cepat, garansi support WhatsApp.')
@section('og_image', asset('assets/og-default.jpg'))
@section('og_type', 'website')

@section('twitter_card', 'summary_large_image')
@section('twitter_title', 'Web Developer Solo | Jasa Pembuatan Website Profesional')
@section('twitter_description', 'Website company profile, toko online, sistem informasi, dan SEO backlink di Solo & Surakarta. Konsultasi gratis via WhatsApp.')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@verbatim
<style>
    /* ===== Juki Landing - Brand Palette & Typography ===== */
    .juki-landing {
        font-family: 'Plus Jakarta Sans', 'Inter', ui-sans-serif, system-ui, sans-serif;
    }

    /* The global layout footer is replaced by the template's own footer */
    body > footer { display: none; }

    /* ===== Scroll Reveal ===== */
    .juki-landing .reveal {
        transition: opacity .7s cubic-bezier(.22, .61, .36, 1), transform .7s cubic-bezier(.22, .61, .36, 1);
    }
    .juki-landing .reveal.is-animating {
        opacity: 0;
        transform: translateY(28px);
    }
    .juki-landing .reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    @media (prefers-reduced-motion: reduce) {
        .juki-landing .reveal { transition: none !important; }
        .juki-landing .reveal.is-animating { opacity: 1 !important; transform: none !important; }
    }

    /* ===== Decorative helpers ===== */
    @keyframes juki-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-18px); }
    }
    .juki-float { animation: juki-float 7s ease-in-out infinite; }

    @keyframes juki-bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(8px); }
    }
    .juki-scroll-hint { animation: juki-bounce 2.2s ease-in-out infinite; }

    @media (prefers-reduced-motion: reduce) {
        .juki-float, .juki-scroll-hint { animation: none; }
    }

    .juki-card {
        transition: transform .35s cubic-bezier(.22, .61, .36, 1), box-shadow .35s ease, border-color .35s ease;
    }
    .juki-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 45px -18px rgba(21, 37, 63, 0.22);
    }

    .juki-badge {
        position: relative;
        overflow: hidden;
    }
    .juki-badge::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
        transition: left .6s ease;
    }
    .juki-badge:hover::after { left: 100%; }

    .juki-map {
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 25px 50px -20px rgba(0, 0, 0, 0.35);
    }

    /* ===== Animated gradient text ===== */
    .juki-gradient-text {
        background: linear-gradient(120deg, #15253F 0%, #CE9A45 45%, #9A6B16 55%, #15253F 100%);
        background-size: 220% auto;
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        animation: juki-text-shine 5s linear infinite;
    }
    @keyframes juki-text-shine {
        to { background-position: 220% center; }
    }

    /* ===== Hero mockup entrance ===== */
    .juki-mockup {
        transform-origin: center bottom;
        animation: juki-rise 0.9s cubic-bezier(.22, .61, .36, 1) both;
    }
    @keyframes juki-rise {
        from { opacity: 0; transform: translateY(40px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @media (prefers-reduced-motion: reduce) {
        .juki-gradient-text { animation: none; }
        .juki-mockup { animation: none; }
    }

    /* ===== Lazy render per section ===== */
    .juki-landing > section {
        content-visibility: auto;
        contain-intrinsic-size: auto 500px;
    }

    /* ===== Honeypot anti-spam (hidden from humans) ===== */
    .hp-field {
        position: absolute !important;
        left: -9999px !important;
        width: 1px !important;
        height: 1px !important;
        overflow: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    /* ===== Live Chat Widget ===== */
    .juki-chat-panel {
        display: flex;
        flex-direction: column;
        max-height: min(540px, calc(100vh - 120px));
    }
    .juki-chat-body {
        overflow-y: auto;
        scrollbar-width: thin;
    }
    .juki-chat-input:focus {
        outline: none;
        border-color: #25D366 !important;
        box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.15);
    }
</style>
@endverbatim
@endsection

@section('schema_markup')
@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "@id": "https://juki.eu.org/#localbusiness",
  "name": "Juki Website Developer Solo",
  "url": "https://juki.eu.org",
  "description": "Web developer & web designer Solo: jasa pembuatan website company profile, toko online, landing page, sistem informasi ERP/CRM, AI automation, dan jasa SEO backlink di Surakarta, Jawa Tengah.",
  "telephone": "+62-822-1302-8718",
  "priceRange": "Rp 2.500.000 - Rp 10.000.000+",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Home Parangjoro 2, Parangjoro, Grogol",
    "addressLocality": "Sukoharjo",
    "addressRegion": "Jawa Tengah",
    "postalCode": "57552",
    "addressCountry": "ID"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "-7.5992",
    "longitude": "110.7987"
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    "opens": "09:00",
    "closes": "17:00"
  },
  "sameAs": [
    "https://juki.eu.org/"
  ],
  "areaServed": [
    { "@type": "City", "name": "Surakarta" },
    { "@type": "City", "name": "Sukoharjo" },
    { "@type": "State", "name": "Jawa Tengah" },
    { "@type": "Country", "name": "Indonesia" }
  ],
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Layanan Web Developer Solo",
    "itemListElement": [
      {
        "@type": "Offer",
        "price": "2500000",
        "priceCurrency": "IDR",
        "itemOffered": {
          "@type": "Service",
          "name": "Jasa Pembuatan Website WordPress",
          "description": "Website company profile, toko online, landing page berbasis CMS WordPress. Responsive, SEO-friendly, pengerjaan maksimal 2 minggu.",
          "serviceType": "Web Design & Development"
        }
      },
      {
        "@type": "Offer",
        "price": "10000000",
        "priceCurrency": "IDR",
        "itemOffered": {
          "@type": "Service",
          "name": "Pembuatan Sistem Informasi",
          "description": "Custom web application, ERP, CRM, inventory, POS menggunakan Laravel dan CodeIgniter. Program modular 1 bulan termasuk training.",
          "serviceType": "Software Development"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "AI Automation & Chatbot",
          "description": "WhatsApp chatbot, customer service automation, integrasi API untuk efisiensi bisnis.",
          "serviceType": "Artificial Intelligence Services"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Jasa SEO & Backlink Solo",
          "description": "Link building, backlink berkualitas, Google Maps optimization, local SEO Surakarta.",
          "serviceType": "SEO Services"
        }
      }
    ]
  }
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ProfessionalService",
  "name": "Juki Website Developer Solo",
  "url": "https://juki.eu.org",
  "image": "https://juki.eu.org/assets/og-default.jpg",
  "telephone": "+62-822-1302-8718",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Home Parangjoro 2, Parangjoro, Grogol",
    "addressLocality": "Sukoharjo",
    "addressRegion": "Jawa Tengah",
    "postalCode": "57552",
    "addressCountry": "ID"
  },
  "description": "Jasa pembuatan website dan sistem informasi di Solo & Surakarta menggunakan Laravel, CodeIgniter, dan CMS WordPress.",
  "knowsAbout": [
    "Laravel",
    "CodeIgniter",
    "WordPress",
    "PHP",
    "MySQL",
    "Web Design",
    "Responsive Design",
    "SEO Optimization",
    "Link Building",
    "AI Automation",
    "ERP Systems",
    "CRM Implementation"
  ]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://juki.eu.org"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Web Developer Solo"
    }
  ]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Berapa harga jasa pembuatan website di Solo?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Website berbasis WordPress mulai Rp 2.500.000. Sistem informasi mulai Rp 10.000.000. Harga dapat disesuaikan dengan kebutuhan proyek Anda."
      }
    },
    {
      "@type": "Question",
      "name": "Berapa lama pengerjaan pembuatan website?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Pembuatan website maksimal 2 minggu. Sistem informasi dengan program modular selesai dalam 1 bulan termasuk training. Untuk custom program, waktu menyesuaikan kompleksitas proyek."
      }
    },
    {
      "@type": "Question",
      "name": "Teknologi apa yang digunakan untuk pembuatan website?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Kami menggunakan Laravel, CodeIgniter, dan CMS WordPress sesuai kebutuhan proyek Anda."
      }
    },
    {
      "@type": "Question",
      "name": "Apakah ada garansi support setelah website selesai?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ya, kami memberikan garansi support langsung via WhatsApp ke nomor 0822-1302-8718."
      }
    },
    {
      "@type": "Question",
      "name": "Bagaimana cara memulai proyek pembuatan website?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Pastikan Anda memiliki gambaran jelas tentang proyek yang ingin dikerjakan agar tidak ada interupsi di tengah jalan. Lalu konsultasikan gratis melalui WhatsApp."
      }
    }
  ]
}
</script>
@endverbatim
@endsection

@section('content')
@php
    $brandLogo = \App\Models\SystemSetting::get('logo_url');
    $brandLogoAlt = \App\Models\SystemSetting::get('logo_alt', 'Juki Website Developer Solo');
    $waNumber = \App\Models\SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
@endphp
<div class="juki-landing bg-[#F8FAFC] text-[#0F172A] overflow-x-hidden">

    {{-- ============ HERO ============ --}}
    <section id="home" class="relative overflow-hidden bg-gradient-to-br from-white via-[#F4F7FB] to-[#E8EEF6]">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-brand-navy/5 blur-3xl juki-float"></div>
        <div class="absolute bottom-10 left-0 w-72 h-72 rounded-full bg-brand-gold/10 blur-3xl juki-float" style="animation-delay: 1.5s;"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(21,37,63,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(21,37,63,0.04)_1px,transparent_1px)] bg-[size:56px_56px]"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 lg:pt-32 lg:pb-24 text-center">
            @if($brandLogo)
            <div class="flex justify-center mb-10 reveal">
                <img src="{{ $brandLogo }}" alt="{{ $brandLogoAlt }}" class="h-16 sm:h-20 w-auto drop-shadow-xl" loading="eager">
            </div>
            @endif
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-[#E2E8F0] shadow-sm text-sm font-semibold text-brand-navy mb-8 reveal">
                <svg class="w-4 h-4 text-brand-gold-dark" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"/></svg>
                Web Developer &amp; Web Designer Solo — Surakarta, Jawa Tengah
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.1] tracking-tight text-[#0F172A] mb-6 reveal" data-delay="80">
                Web Developer Solo: Jasa Pembuatan Website <span class="juki-gradient-text">Profesional</span>
            </h1>

            <p class="text-lg lg:text-xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed reveal" data-delay="160">
                Jasa web design &amp; pembuatan website di Solo dan Surakarta — company profile, toko online,
                landing page, sistem informasi, hingga AI automation. Dibangun dengan
                <strong class="text-brand-navy">Laravel</strong>, <strong class="text-brand-navy">CodeIgniter</strong>,
                dan <strong class="text-brand-navy">CMS WordPress</strong>. Pengerjaan website maksimal 2 minggu.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-10 reveal" data-delay="240">
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Juki%20Website%20Developer%20Solo%2C%20saya%20ingin%20konsultasi%20pembuatan%20website" target="_blank" rel="noopener" class="juki-badge inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-brand-gold hover:bg-brand-gold/90 text-brand-navy font-bold text-lg shadow-xl shadow-brand-gold/25 transition-all hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Konsultasi Gratis via WhatsApp
                </a>
                <a href="#harga" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-white border-2 border-brand-navy/15 text-brand-navy hover:border-brand-navy/40 font-bold text-lg transition-all hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    Lihat Layanan &amp; Harga
                </a>
            </div>

            <div class="flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm font-semibold text-slate-600 reveal" data-delay="320">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-navy" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Garansi Support WhatsApp
                </span>
                <span class="inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-navy" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Pengerjaan Website Maks 2 Minggu
                </span>
                <span class="inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-navy" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    SEO-Friendly &amp; Responsive
                </span>
            </div>

            {{-- ============ HERO VISUAL ============ --}}
            <div class="relative mt-16 sm:mt-20 max-w-3xl mx-auto reveal" data-delay="400">
                <div class="absolute -inset-8 bg-brand-gold/10 blur-3xl rounded-full"></div>
                <div class="relative">
                    <div class="juki-mockup rounded-2xl bg-white border border-[#E2E8F0] shadow-2xl shadow-brand-navy/10 overflow-hidden text-left">
                        <div class="flex items-center gap-2 px-5 py-3 bg-[#F1F5F9] border-b border-[#E2E8F0]">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                            <div class="flex-1 ml-3 h-6 bg-white border border-[#E2E8F0] rounded-md text-[11px] text-slate-400 flex items-center px-3">
                                <svg class="w-3 h-3 mr-1.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                juki.eu.org
                            </div>
                        </div>
                        <div class="bg-[#F8FAFC] p-6 sm:p-8">
                            <div class="grid grid-cols-1 sm:grid-cols-5 gap-6 items-center">
                                <div class="sm:col-span-2">
                                    @if($brandLogo)
                                    <img src="{{ $brandLogo }}" alt="{{ $brandLogoAlt }}" class="h-7 w-auto mb-4" loading="eager" decoding="async">
                                    @endif
                                    <div class="h-3 w-24 bg-brand-navy rounded mb-2"></div>
                                    <div class="h-3 w-32 bg-slate-200 rounded mb-3"></div>
                                    <p class="text-xs text-slate-400 leading-relaxed mb-4">Website cepat, SEO-friendly, mobile-first. Selesai maksimal 2 minggu.</p>
                                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-gold text-brand-navy text-xs font-bold shadow-lg shadow-brand-gold/25">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm-1 15v-4H7v-2h4V7h2v4h4v2h-4v4h-2z"/></svg>
                                        Mulai Rp 2,5 Jt
                                    </div>
                                </div>
                                <div class="sm:col-span-3 bg-white border border-[#E2E8F0] rounded-xl p-4 shadow-sm">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="h-3 w-20 bg-brand-navy rounded"></div>
                                        <div class="w-8 h-8 rounded-full bg-brand-gold/20"></div>
                                    </div>
                                    <div class="space-y-2.5">
                                        <div class="h-2 bg-slate-100 rounded w-full"></div>
                                        <div class="h-2 bg-slate-100 rounded w-5/6"></div>
                                        <div class="h-2 bg-slate-100 rounded w-2/3"></div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mt-4">
                                        <div class="h-10 rounded-lg bg-brand-navy/5"></div>
                                        <div class="h-10 rounded-lg bg-brand-gold/15"></div>
                                        <div class="h-10 rounded-lg bg-brand-navy/10"></div>
                                    </div>
                                    <div class="h-8 bg-brand-navy rounded-lg mt-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -top-6 -left-3 sm:-left-10 bg-white border border-[#E2E8F0] rounded-2xl shadow-xl px-4 py-3 flex items-center gap-3 juki-float">
                        <div class="w-10 h-10 rounded-xl bg-brand-gold/15 text-brand-gold-dark flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-bold text-[#0F172A] leading-none">Rating 5.0</p>
                            <p class="text-[11px] text-slate-500 mt-1">Ulasan pelanggan Solo</p>
                        </div>
                    </div>

                    <div class="absolute -bottom-6 -right-3 sm:-right-10 bg-white border border-[#E2E8F0] rounded-2xl shadow-xl px-4 py-3 flex items-center gap-3 juki-float" style="animation-delay: 1.2s;">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-bold text-[#0F172A] leading-none">Garansi Support</p>
                            <p class="text-[11px] text-slate-500 mt-1">Respon cepat via WhatsApp</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="juki-scroll-hint absolute bottom-6 left-1/2 -translate-x-1/2 text-brand-navy/40">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </section>

    {{-- ============ STATS BAR ============ --}}
    <section class="bg-brand-navy text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="reveal">
                    <p class="text-3xl lg:text-4xl font-extrabold text-brand-gold-light juki-counter" data-count="2" data-suffix=" Minggu">2 Minggu</p>
                    <p class="mt-1 text-sm text-blue-100">Pengerjaan Website Maksimal</p>
                </div>
                <div class="reveal" data-delay="80">
                    <p class="text-3xl lg:text-4xl font-extrabold text-brand-gold-light juki-counter" data-count="1" data-suffix=" Bulan">1 Bulan</p>
                    <p class="mt-1 text-sm text-blue-100">Sistem Modular + Training</p>
                </div>
                <div class="reveal" data-delay="160">
                    <p class="text-3xl lg:text-4xl font-extrabold text-brand-gold-light juki-counter" data-count="3" data-suffix="+ Teknologi">3+ Teknologi</p>
                    <p class="mt-1 text-sm text-blue-100">Laravel, CodeIgniter, WordPress</p>
                </div>
                <div class="reveal" data-delay="240">
                    <p class="text-3xl lg:text-4xl font-extrabold text-brand-gold-light juki-counter" data-count="100" data-suffix="%">100%</p>
                    <p class="mt-1 text-sm text-blue-100">Garansi Support WhatsApp</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ SERVICES ============ --}}
    <section id="services" class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">Layanan Unggulan</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-brand-navy mb-4">Layanan Web Developer Solo</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Solusi digital lengkap untuk UMKM, perusahaan, dan instansi di Solo Raya &amp; Jawa Tengah</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Service 1: Website --}}
                <div class="juki-card group relative bg-white border border-[#E2E8F0] rounded-3xl p-8 reveal">
                    <div class="w-16 h-16 rounded-2xl bg-brand-navy flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-brand-navy/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-3">Jasa Pembuatan Website Solo</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Website company profile, toko online, dan landing page dengan desain modern, cepat, dan mobile-friendly.</p>
                    <ul class="space-y-2.5 text-sm text-slate-700">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>CMS WordPress / custom</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Responsive &amp; mobile-first</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>SEO-friendly &amp; cepat</li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <p class="text-sm text-slate-500 mb-3">Mulai <span class="font-bold text-brand-gold-dark">Rp 2.500.000</span> · maks 2 minggu</p>
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-navy hover:text-brand-gold-dark transition-colors">Konsultasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>

                {{-- Service 2: Sistem Informasi --}}
                <div class="juki-card group relative bg-white border border-[#E2E8F0] rounded-3xl p-8 reveal" data-delay="80">
                    <div class="w-16 h-16 rounded-2xl bg-brand-navy flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-brand-navy/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 1.657 3.582 3 8 3s8-1.343 8-3V7M4 7c0 1.657 3.582 3 8 3s8-1.343 8-3-3.582-3-8-3-8 1.343-8 3zm16 5c0 1.657-3.582 3-8 3s-8-1.343-8-3"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-3">Pembuatan Sistem Informasi</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Sistem ERP, CRM, inventory, dan aplikasi web custom sesuai alur bisnis perusahaan Anda.</p>
                    <ul class="space-y-2.5 text-sm text-slate-700">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Laravel &amp; CodeIgniter</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Database &amp; API terintegrasi</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Modular 1 bulan + training</li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <p class="text-sm text-slate-500 mb-3">Mulai <span class="font-bold text-brand-gold-dark">Rp 10.000.000</span></p>
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-navy hover:text-brand-gold-dark transition-colors">Konsultasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>

                {{-- Service 3: AI Automation --}}
                <div class="juki-card group relative bg-white border border-[#E2E8F0] rounded-3xl p-8 reveal" data-delay="160">
                    <div class="w-16 h-16 rounded-2xl bg-brand-gold flex items-center justify-center text-brand-navy mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-brand-gold/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-3">AI Automation &amp; Chatbot</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Otomatisasi layanan pelanggan dan proses bisnis dengan kecerdasan buatan.</p>
                    <ul class="space-y-2.5 text-sm text-slate-700">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>WhatsApp chatbot</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Customer service automation</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Integrasi API &amp; bisnis</li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-navy hover:text-brand-gold-dark transition-colors">Konsultasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>

                {{-- Service 4: SEO & Backlink --}}
                <div class="juki-card group relative bg-white border border-[#E2E8F0] rounded-3xl p-8 reveal" data-delay="240">
                    <div class="w-16 h-16 rounded-2xl bg-brand-navy flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-brand-navy/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-3">Jasa SEO &amp; Backlink Solo</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Naikkan peringkat website Anda di Google dengan strategi SEO lokal dan link building.</p>
                    <ul class="space-y-2.5 text-sm text-slate-700">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Backlink berkualitas &amp; aman</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Google Maps optimization</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Local SEO Surakarta &amp; keyword research</li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-navy hover:text-brand-gold-dark transition-colors">Konsultasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ WHY CHOOSE US ============ --}}
    <section class="py-20 lg:py-24 bg-[#F1F5F9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">Kenapa Memilih Kami</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-brand-navy mb-4">Partner Digital Terpercaya di Solo</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Lebih dari sekadar web developer, kami mitra strategis untuk pertumbuhan bisnis Anda</p>
            </div>

            @php
            $reasons = [
                ['icon' => 'bolt',    'title' => 'Pengerjaan Cepat', 'desc' => 'Website maksimal 2 minggu. Sistem modular selesai 1 bulan lengkap dengan training.' ],
                ['icon' => 'target',  'title' => 'Hasil Terukur', 'desc' => 'Progres jelas dan komunikasi langsung tanpa perantara, setiap tahap proyek.' ],
                ['icon' => 'shield',  'title' => 'Keamanan Terjamin', 'desc' => 'Best practice keamanan dan kode bersih untuk melindungi data bisnis Anda.' ],
                ['icon' => 'chat',    'title' => 'Garansi Support WhatsApp', 'desc' => 'Support langsung via WhatsApp 0822-1302-8718, cepat dan personal.' ],
                ['icon' => 'briefcase','title' => 'Berpengalaman', 'desc' => 'Berpengalaman menangani website UMKM dan perusahaan di Solo & Jawa Tengah.' ],
                ['icon' => 'wallet',  'title' => 'Harga Transparan', 'desc' => 'Paket jelas: website Rp 2,5 juta, sistem informasi mulai Rp 10 juta. Tanpa biaya tersembunyi.' ],
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($reasons as $index => $reason)
                <div class="juki-card bg-white border border-[#E2E8F0] rounded-2xl p-7 reveal" data-delay="{{ $index * 70 }}">
                    <div class="w-14 h-14 rounded-xl bg-brand-navy/5 text-brand-navy flex items-center justify-center mb-5">
                        @if($reason['icon'] === 'bolt')
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @elseif($reason['icon'] === 'target')
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @elseif($reason['icon'] === 'shield')
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
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

    {{-- ============ ABOUT ============ --}}
    <section class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div class="reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">Tentang Kami</p>
                <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-brand-navy mb-5">
                    Jasa Web Developer &amp; Web Designer di Solo Raya
                </h2>
                <p class="text-slate-600 leading-relaxed mb-4">
                    <strong class="text-[#0F172A]">Juki Website Developer</strong> adalah jasa pembuatan website di
                    Solo Raya — berpusat di Grogol, Sukoharjo — melayani UMKM dan perusahaan di
                    Surakarta, Sukoharjo, Karanganyar, Boyolali, Klaten, dan seluruh Jawa Tengah.
                </p>
                <p class="text-slate-600 leading-relaxed mb-4">
                    Kami membangun website yang tidak hanya tampil menarik, tetapi juga cepat, aman, dan
                    <strong class="text-[#0F172A]">SEO-friendly</strong>. Setiap halaman dioptimasi untuk
                    Core Web Vitals, struktur schema JSON-LD, dan mobile-first, sehingga bisnis Anda mudah
                    ditemukan di Google — dari pencarian <em>web developer Solo</em> hingga <em>jasa web design Solo</em>.
                </p>
                <p class="text-slate-600 leading-relaxed">
                    Garansi support langsung via WhatsApp memastikan Anda tidak pernah sendirian setelah
                    website diluncurkan.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#contact" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand-navy hover:bg-brand-navy-deep text-white font-semibold transition-all">Hubungi Kami</a>
                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white border-2 border-brand-gold-dark text-brand-gold-dark hover:bg-brand-gold hover:text-brand-navy font-semibold transition-all">Chat WhatsApp</a>
                </div>
            </div>

            <div class="reveal" data-delay="120">
                <div class="bg-[#F1F5F9] border border-[#E2E8F0] rounded-3xl p-8">
                    <h3 class="text-lg font-bold text-brand-navy mb-6">Teknologi &amp; Keahlian</h3>
                    <div class="flex flex-wrap gap-3 mb-8">
                        @php
                        $techs = ['Laravel', 'CodeIgniter', 'WordPress', 'PHP', 'MySQL', 'React.js', 'Vue.js', 'Tailwind CSS', 'REST API', 'UI/UX Design', 'SEO', 'Google Search Console'];
                        @endphp
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

    {{-- ============ PRICING ============ --}}
    <section id="harga" class="py-20 lg:py-24 bg-[#F1F5F9]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">Harga Jasa Pembuatan Website Solo</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-brand-navy mb-4">Paket Transparan, Tanpa Biaya Tersembunyi</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Harga dapat disesuaikan dengan kebutuhan. Konsultasikan proyek Anda secara gratis.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                {{-- Paket Website --}}
                <div class="juki-card relative bg-white border border-[#E2E8F0] rounded-3xl p-8 lg:p-10 reveal">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-[#0F172A]">Paket Website</h3>
                        <span class="px-3 py-1 rounded-full bg-brand-navy/10 text-brand-navy text-xs font-bold uppercase tracking-wide">CMS WordPress</span>
                    </div>
                    <p class="flex items-baseline gap-2 mb-6">
                        <span class="text-sm text-slate-500">Mulai</span>
                        <span class="text-4xl font-extrabold text-brand-gold-dark">Rp 2.500.000</span>
                    </p>
                    <ul class="space-y-3 text-sm text-slate-700 mb-8">
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Company profile, landing page, atau toko online</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Desain responsive, modern, mobile-first</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Struktur SEO-friendly &amp; cepat loading</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Pengerjaan maksimal 2 minggu</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-navy mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Garansi support via WhatsApp</li>
                    </ul>
                    <a href="https://wa.me/{{ $waNumber }}?text=Halo%2C%20saya%20tertarik%20dengan%20Paket%20Website%20Rp%202.500.000" target="_blank" rel="noopener" class="juki-badge inline-flex w-full items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-brand-navy hover:bg-brand-navy-deep text-white font-bold transition-all">Pilih Paket Website</a>
                </div>

                {{-- Paket Sistem Informasi --}}
                <div class="juki-card relative bg-brand-navy text-white rounded-3xl p-8 lg:p-10 reveal" data-delay="120">
                    <div class="absolute top-6 right-6">
                        <span class="px-3 py-1 rounded-full bg-brand-gold text-brand-navy text-xs font-bold uppercase tracking-wide shadow-lg shadow-brand-gold/30">Premium</span>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold">Paket Sistem Informasi</h3>
                        <span class="px-3 py-1 rounded-full bg-white/10 text-blue-100 text-xs font-bold uppercase tracking-wide">Laravel · CodeIgniter</span>
                    </div>
                    <p class="flex items-baseline gap-2 mb-6">
                        <span class="text-sm text-blue-100">Mulai</span>
                        <span class="text-4xl font-extrabold text-brand-gold-light">Rp 10.000.000</span>
                    </p>
                    <ul class="space-y-3 text-sm text-blue-100 mb-8">
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>ERP, CRM, inventory, POS, aplikasi web custom</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Program modular selesai 1 bulan + training</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Custom program menyesuaikan kebutuhan &amp; kompleksitas</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Database, keamanan &amp; API terintegrasi</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Garansi support via WhatsApp</li>
                    </ul>
                    <a href="https://wa.me/{{ $waNumber }}?text=Halo%2C%20saya%20tertarik%20dengan%20Paket%20Sistem%20Informasi%20Rp%2010.000.000" target="_blank" rel="noopener" class="juki-badge inline-flex w-full items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-brand-gold hover:bg-brand-gold/90 text-brand-navy font-bold transition-all">Pilih Paket Sistem Informasi</a>
                </div>
            </div>

            <p class="text-center text-sm text-slate-500 mt-8 reveal">
                Butuh penawaran khusus atau proyek skala besar? <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="font-bold text-brand-gold-dark hover:underline">Konsultasikan kebutuhan Anda</a> — gratis.
            </p>

            {{-- ===== Paket Custom / Enterprise ===== --}}
            <div id="enterprise" class="mt-12 rounded-3xl overflow-hidden reveal" style="background:linear-gradient(135deg,#15253F 0%,#1D3357 60%,#233A63 100%);">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 p-8 lg:p-12 items-stretch">
                    <div class="text-white">
                        <span class="px-3 py-1 rounded-full bg-brand-gold text-brand-navy text-xs font-bold uppercase tracking-wide shadow-lg shadow-brand-gold/30">Custom / Enterprise</span>
                        <h3 class="text-3xl font-extrabold mt-5 mb-4">Paket Custom / Enterprise</h3>
                        <p class="text-blue-100 leading-relaxed mb-6">
                            Kebutuhan khusus atau skala besar — sistem informasi kompleks, ERP/CRM terintegrasi,
                            AI automation enterprise, hingga retained SEO. Penawaran disusun sesuai spesifikasi
                            dan alur proyek Anda.
                        </p>
                        <ul class="space-y-3 text-sm text-blue-100 mb-8">
                            <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Analisis kebutuhan &amp; scoping proyek gratis</li>
                            <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>ERP, CRM, POS, inventory, HRIS custom</li>
                            <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>AI automation &amp; integrasi API enterprise</li>
                            <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Retained SEO &amp; digital marketing bulanan</li>
                            <li class="flex items-start gap-3"><svg class="w-5 h-5 text-brand-gold-light mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Dukungan prioritas &amp; SLA khusus</li>
                        </ul>
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                            <p class="font-bold text-white mb-3">Alur kerja:</p>
                            <ol class="space-y-2 text-sm text-blue-100">
                                <li>1. Kirim kebutuhan melalui form di samping</li>
                                <li>2. Diskusi &amp; scoping detail via WhatsApp</li>
                                <li>3. Proposal &amp; penawaran resmi</li>
                                <li>4. Eksekusi bertahap + support</li>
                            </ol>
                        </div>
                    </div>

                    <div class="bg-white text-slate-700 rounded-2xl p-6 lg:p-8 shadow-2xl">
                        <h4 class="text-lg font-bold text-[#0F172A] mb-1">Diskusikan Kebutuhan Anda</h4>
                        <p class="text-sm text-slate-500 mb-6">Data tersimpan &amp; terkirim langsung ke WhatsApp dengan format terstruktur.</p>
                        <form action="{{ route('lead.store') }}" method="POST" class="space-y-4" onsubmit="disableButton(this)">
                            @csrf
                            <div>
                                <label for="ent_email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="ent_email" name="email" required placeholder="nama@perusahaan.com"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all">
                            </div>
                            <div>
                                <label for="ent_alur" class="block text-sm font-semibold text-slate-700 mb-1.5">Alur Proses yang Diinginkan <span class="text-red-500">*</span></label>
                                <select id="ent_alur" name="alur" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all bg-white">
                                    <option value="">— Pilih Alur —</option>
                                    <option value="Saya sudah tahu kebutuhan, mohon penawaran">Saya sudah tahu kebutuhan, mohon penawaran</option>
                                    <option value="Saya butuh konsultasi & scoping dulu">Saya butuh konsultasi &amp; scoping dulu</option>
                                    <option value="Saya ingin proposal resmi perusahaan">Saya ingin proposal resmi perusahaan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label for="ent_msg" class="block text-sm font-semibold text-slate-700 mb-1.5">Kebutuhan / Deskripsi Proyek <span class="text-red-500">*</span></label>
                                <textarea id="ent_msg" name="message" rows="4" required placeholder="Jelaskan kebutuhan proyek Anda secara detail..."
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all resize-y"></textarea>
                            </div>
                            <div>
                                <label for="ent_phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor WhatsApp Anda <span class="text-red-500">*</span></label>
                                <input type="tel" id="ent_phone" name="phone" required placeholder="082213028718"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all">
                            </div>
                            <input type="hidden" name="source" value="enterprise">
                            <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-brand-navy hover:bg-brand-navy-deep text-white font-bold shadow-lg transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Kirim &amp; Lanjut ke WhatsApp
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ BLOG ============ --}}
    @php
        use App\Models\Content;
        $recentPosts = Content::where('status', 'published')
            ->whereNotNull('body_raw')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
    @endphp
    <section id="blog" class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">Dari Blog</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-brand-navy mb-4">Artikel &amp; Tips Website</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Wawasan seputar pembuatan website, SEO, dan digital marketing untuk bisnis Anda</p>
            </div>

            @if($recentPosts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recentPosts as $post)
                <article class="juki-card group bg-white border border-[#E2E8F0] rounded-3xl overflow-hidden flex flex-col reveal">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden bg-slate-100">
                        <img src="{{ $post->featured_image_url ?: asset('assets/seofast-placeholder.svg') }}" alt="{{ $post->featured_image_alt ?? $post->title }}" loading="lazy" decoding="async" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    </a>
                    <div class="p-6 flex flex-col flex-1">
                        @if($post->published_at)
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">{{ $post->published_at->format('d M Y') }}</span>
                        @endif
                        <h3 class="font-bold text-lg text-[#0F172A] mb-3 line-clamp-2">
                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-brand-gold-dark transition-colors">{{ $post->title }}</a>
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-5 line-clamp-3 flex-1">{{ $post->excerpt }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-brand-navy hover:text-brand-gold-dark transition-colors">Baca Selengkapnya <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </article>
                @endforeach
            </div>
            @endif

            <div class="text-center mt-12 reveal">
                <a href="https://juki.eu.org/blog" target="_blank" rel="noopener" class="juki-badge inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-brand-navy hover:bg-brand-navy-deep text-white font-bold text-lg shadow-xl shadow-brand-navy/20 transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"/></svg>
                    Kunjungi Blog &amp; Artikel
                </a>
            </div>
        </div>
    </section>


    {{-- ============ FAQ ============ --}}
    <section id="faq" class="py-20 lg:py-24 bg-[#F1F5F9]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">FAQ</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-brand-navy mb-4">Pertanyaan yang Sering Diajukan</h2>
            </div>

            <div class="space-y-4" x-data="{ open: 0 }">
                @php
                $faqs = [
                    ['q' => 'Berapa harga jasa pembuatan website di Solo?', 'a' => 'Website berbasis WordPress mulai Rp 2.500.000. Sistem informasi mulai Rp 10.000.000. Harga dapat disesuaikan dengan kebutuhan dan fitur proyek Anda.'],
                    ['q' => 'Berapa lama pengerjaan pembuatan website?', 'a' => 'Pembuatan website maksimal 2 minggu. Sistem informasi dengan program modular selesai dalam 1 bulan termasuk training. Untuk custom program, waktu pengerjaan menyesuaikan kompleksitas proyek.'],
                    ['q' => 'Teknologi apa yang digunakan untuk pembuatan website?', 'a' => 'Kami menggunakan Laravel, CodeIgniter, dan CMS WordPress sesuai kebutuhan proyek Anda. Semua dikerjakan dengan kode bersih, responsive, dan SEO-friendly.'],
                    ['q' => 'Apakah ada garansi support setelah website selesai?', 'a' => 'Ya, kami memberikan garansi support langsung via WhatsApp ke nomor 0822-1302-8718. Anda bisa langsung menghubungi kami kapan pun ada kendala.'],
                    ['q' => 'Bagaimana cara memulai proyek pembuatan website?', 'a' => 'Pastikan Anda memiliki gambaran jelas tentang proyek yang ingin dikerjakan — desain, fitur, dan tujuan — agar tidak ada interupsi di tengah jalan. Lalu konsultasikan kebutuhan Anda secara gratis melalui WhatsApp.'],
                    ['q' => 'Apakah website dioptimasi untuk SEO?', 'a' => 'Ya, setiap website kami bangun dengan struktur SEO-friendly, schema JSON-LD, optimasi kecepatan (Core Web Vitals), dan responsive design agar mudah ditemukan di Google.'],
                ];
                @endphp

                @foreach($faqs as $index => $faq)
                <div class="bg-white border border-[#E2E8F0] rounded-2xl overflow-hidden reveal" data-delay="{{ $index * 60 }}">
                    <button type="button" @click="open = (open === {{ $index + 1 }} ? 0 : {{ $index + 1 }})" :aria-expanded="open === {{ $index + 1 }} ? 'true' : 'false'" class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left">
                        <span class="font-bold text-[#0F172A]">{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 text-brand-gold-dark shrink-0 transition-transform duration-300" :class="open === {{ $index + 1 }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open === {{ $index + 1 }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="px-6 pb-5" style="display: none;">
                        <p class="text-slate-600 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ SEO CONTENT ============ --}}
    <section class="py-20 lg:py-24 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-dark mb-3">Layanan Kami</p>
                <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-brand-navy mb-4">Jasa Web Developer Profesional di Solo</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Solusi website &amp; sistem digital lengkap untuk bisnis di Surakarta, Sukoharjo, dan Jawa Tengah</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-white border border-[#E2E8F0] rounded-2xl p-7 juki-card reveal">
                    <h3 class="text-lg font-bold text-brand-navy mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6 text-brand-gold-dark" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Layanan Web Development Solo
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Sebagai <strong>web developer Solo</strong> yang berpengalaman, kami menyediakan jasa pembuatan
                        website company profile, <strong>toko online</strong>, landing page conversion-focused, dan
                        aplikasi web custom menggunakan <strong>Laravel</strong>, <strong>CodeIgniter</strong>,
                        <strong>WordPress</strong>, React.js, dan Vue.js. Setiap proyek dioptimasi untuk
                        Core Web Vitals, mobile-responsive, dan struktur SEO-friendly.
                    </p>
                </div>

                <div class="bg-white border border-[#E2E8F0] rounded-2xl p-7 juki-card reveal" data-delay="80">
                    <h3 class="text-lg font-bold text-brand-navy mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6 text-brand-gold-dark" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                        Sistem Informasi &amp; Aplikasi Custom
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Kami mengembangkan <strong>sistem informasi bisnis</strong> tailored untuk kebutuhan
                        perusahaan Anda — termasuk ERP system, CRM customization, inventory management, POS system,
                        HRIS, dan database architecture. Program modular selesai 1 bulan, custom program
                        menyesuaikan kompleksitas. Solusi kami mendukung efisiensi operasional bisnis di
                        Surakarta dan seluruh Jawa Tengah.
                    </p>
                </div>

                <div class="bg-white border border-[#E2E8F0] rounded-2xl p-7 juki-card reveal">
                    <h3 class="text-lg font-bold text-brand-navy mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6 text-brand-gold-dark" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
                        AI Automation &amp; Chatbot Indonesia
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Tingkatkan produktivitas dengan <strong>AI automation solutions</strong>: WhatsApp chatbot,
                        customer service automation, business process automation, data processing, dan integrasi
                        API third-party. Solusi cerdas untuk transformasi digital bisnis modern.
                    </p>
                </div>

                <div class="bg-white border border-[#E2E8F0] rounded-2xl p-7 juki-card reveal" data-delay="80">
                    <h3 class="text-lg font-bold text-brand-navy mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6 text-brand-gold-dark" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.259-.966zM4.429 4.468a1 1 0 00-1.287-.143l-.898.542a1 1 0 00.144 1.731l.898.543a1 1 0 001.287-.144l.898-.898a1 1 0 00-.144-1.287l-.898-.542zm10.616-.39a1 1 0 011.287.144l.898.898a1 1 0 01-.144 1.287l-.898.542a1 1 0 01-1.287-.144l-.898-.898a1 1 0 01.144-1.287l.898-.542zM12.2 3.9a1 1 0 011.932.518l-.259.966a1 1 0 01-1.932-.518l.259-.966zM3 8a1 1 0 011-1h2a1 1 0 110 2H4a1 1 0 01-1-1zm5.5-4.5A1.5 1.5 0 0110 2h1.5a1.5 1.5 0 010 3H10A1.5 1.5 0 018.5 3.5zm-1.187 5.93a1 1 0 01.185 1.399l-1.5 2a1 1 0 01-1.399.185l-2-1.5a1 1 0 01-.185-1.399l1.5-2a1 1 0 011.399-.185l2 1.5zm7.5-1.5a1 1 0 011.399-.185l2 1.5a1 1 0 01-.185 1.399l-1.5 2a1 1 0 01-1.399.185l-2-1.5a1 1 0 01.185-1.399l1.5-2z" clip-rule="evenodd"/></svg>
                        Jasa SEO &amp; Link Building Solo
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Layanan <strong>SEO specialist Solo</strong>: optimasi on-page, technical SEO audit, keyword
                        research, <strong>link building</strong> dengan strategi backlink yang aman, guest post di situs
                        authoritative, Google Maps optimization untuk local SEO, dan content marketing untuk
                        meningkatkan organic traffic tanpa risiko penalti.
                    </p>
                </div>
            </div>

            <div class="bg-[#F1F5F9] border border-[#E2E8F0] rounded-3xl p-8 reveal">
                <h3 class="text-xl font-bold text-brand-navy mb-6">Area Layanan Web Developer Solo</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-slate-700">
                    @php
                    $areas = ['Surakarta / Solo', 'Sukoharjo', 'Grogol', 'Kartasura', 'Karanganyar', 'Boyolali', 'Klaten', 'Jawa Tengah', 'Yogyakarta', 'Semarang', 'Jabodetabek', 'Seluruh Indonesia'];
                    @endphp
                    @foreach($areas as $area)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-navy shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $area }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CONTACT ============ --}}
    <section id="contact" class="py-20 lg:py-24 bg-brand-navy text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-brand-gold-light mb-3">Lokasi &amp; Kontak</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight mb-4">Hubungi Web Developer Solo</h2>
                <p class="text-lg text-blue-100 max-w-2xl mx-auto">Kunjungi kami atau hubungi untuk konsultasi gratis</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-stretch">
                <div class="flex flex-col justify-center reveal">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-8">
                        <h3 class="text-2xl font-bold mb-6">Kontak Kami</h3>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-brand-gold/20 flex items-center justify-center text-brand-gold-light shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">Alamat</h4>
                                    <p class="text-blue-100">Home Parangjoro 2, Parangjoro, Grogol, Sukoharjo, Jawa Tengah 57552</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-300 shrink-0">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">WhatsApp</h4>
                                    <p class="text-blue-100">0822-1302-8718</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-brand-gold/20 flex items-center justify-center text-brand-gold-light shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">Jam Operasional</h4>
                                    <p class="text-blue-100">Senin - Jumat: 09:00 - 17:00 WIB</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-white/10">
                            <a href="https://wa.me/{{ $waNumber }}?text=Halo%20Juki%20Website%20Developer%20Solo%2C%20saya%20ingin%20konsultasi%20pembuatan%20website" target="_blank" rel="noopener" class="juki-badge inline-flex w-full items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-[#25D366] hover:bg-[#1FBD5A] text-white font-bold text-lg transition-all">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Chat WhatsApp Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-center reveal" data-delay="120">
                    <div class="bg-white text-slate-700 rounded-3xl p-8 shadow-2xl">
                        <h3 class="text-2xl font-bold text-[#0F172A] mb-1">Konsultasi Gratis</h3>
                        <p class="text-sm text-slate-500 mb-6">Isi form ini — data tersimpan &amp; langsung terkirim ke WhatsApp saya dengan format terstruktur.</p>
                        <form action="{{ route('lead.store') }}" method="POST" class="space-y-4" onsubmit="disableButton(this)">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="ct_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" id="ct_name" name="name" required maxlength="255" placeholder="Tri Marzuki"
                                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all">
                                </div>
                                <div>
                                    <label for="ct_phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor WhatsApp</label>
                                    <input type="tel" id="ct_phone" name="phone" maxlength="50" placeholder="082213028718"
                                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all">
                                </div>
                            </div>
                            <div>
                                <label for="ct_subject" class="block text-sm font-semibold text-slate-700 mb-1.5">Layanan yang Dibutuhkan</label>
                                <select id="ct_subject" name="subject" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all bg-white">
                                    <option value="">— Pilih Layanan —</option>
                                    <option value="Jasa Pembuatan Website">Jasa Pembuatan Website</option>
                                    <option value="Sistem Informasi / Aplikasi">Sistem Informasi / Aplikasi</option>
                                    <option value="AI Automation & Chatbot">AI Automation &amp; Chatbot</option>
                                    <option value="Jasa SEO & Backlink">Jasa SEO &amp; Backlink</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label for="ct_msg" class="block text-sm font-semibold text-slate-700 mb-1.5">Pesan <span class="text-red-500">*</span></label>
                                <textarea id="ct_msg" name="message" rows="4" required maxlength="5000" placeholder="Ceritakan kebutuhan project Anda..."
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all resize-y"></textarea>
                            </div>
                            <input type="hidden" name="source" value="contact">
                            <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-brand-navy hover:bg-brand-navy-deep text-white font-bold shadow-lg transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Kirim &amp; Lanjut ke WhatsApp
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="juki-map mt-10 reveal" data-delay="80">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.4148800710145!2d110.8178768!3d-7.6384539999999985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a3da40ed41c69%3A0x2601c6922ac80dd4!2sJuki%20Website%20Developer%20Solo!5e0!3m2!1sid!2sid!4v1785748798166!5m2!1sid!2sid"
                    width="100%"
                    height="400"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi Juki Website Developer Solo"></iframe>
            </div>
        </div>
    </section>

    {{-- ============ FINAL CTA ============ --}}
    <section class="py-20 lg:py-24 bg-gradient-to-br from-brand-navy-mid via-brand-navy to-brand-navy-deep text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(206,154,69,0.28),transparent_70%)]"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight mb-6 reveal">
                Siap Transformasi Digital <span class="text-brand-gold-light">Bisnis Anda?</span>
            </h2>
            <p class="text-lg lg:text-xl text-blue-100 mb-10 max-w-2xl mx-auto reveal" data-delay="80">
                Mulai dari <strong class="text-white">Rp 2.500.000</strong> untuk website profesional. Konsultasikan
                proyek Anda hari ini — gratis dan tanpa komitmen.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 reveal" data-delay="160">
                <a href="https://wa.me/{{ $waNumber }}?text=Halo%2C%20saya%20ingin%20mulai%20proyek%20website%20segera" target="_blank" rel="noopener" class="juki-badge inline-flex items-center justify-center gap-2 px-10 py-5 rounded-2xl bg-brand-gold hover:bg-brand-gold/90 text-brand-navy font-bold text-xl shadow-2xl shadow-brand-gold/30 transition-all hover:-translate-y-0.5">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Mulai Proyek Sekarang
                </a>
                <a href="#harga" class="inline-flex items-center justify-center gap-2 px-10 py-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold text-xl hover:bg-white/20 transition-all">
                    Lihat Paket Harga
                </a>
            </div>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-slate-50 border-t border-slate-200/80 text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <div class="lg:col-span-2">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-5">
                        @if($brandLogo)
                        <img src="{{ $brandLogo }}" alt="{{ $brandLogoAlt }}" class="h-10 w-auto" loading="lazy" decoding="async">
                        @else
                        <span class="font-extrabold text-2xl tracking-tight text-brand-navy">Juki Website Developer Solo</span>
                        @endif
                    </a>
                    <p class="text-sm leading-relaxed text-slate-600 max-w-md mb-6">
                        Web Developer &amp; Web Designer di Surakarta, Jawa Tengah. Membangun website cepat,
                        SEO-friendly, dan mobile-first untuk bisnis Anda.
                    </p>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-navy shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Home Parangjoro 2, Parangjoro, Grogol, Sukoharjo, Jawa Tengah 57552</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-navy shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="hover:text-brand-navy transition-colors">0822-1302-8718</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-navy shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Senin - Jumat: 09:00 - 17:00 WIB</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <p class="text-sm font-bold uppercase tracking-widest text-brand-navy mb-4">Layanan</p>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#harga" class="hover:text-brand-navy transition-colors">Paket Website</a></li>
                        <li><a href="#services" class="hover:text-brand-navy transition-colors">Layanan &amp; Fitur</a></li>
                        <li><a href="#enterprise" class="hover:text-brand-navy transition-colors">Paket Custom / Enterprise</a></li>
                        <li><a href="#blog" class="hover:text-brand-navy transition-colors">Artikel &amp; Tips Website</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-sm font-bold uppercase tracking-widest text-brand-navy mb-4">Navigasi</p>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#home" class="hover:text-brand-navy transition-colors">Beranda</a></li>
                        <li><a href="#harga" class="hover:text-brand-navy transition-colors">Harga Jasa Website</a></li>
                        <li><a href="#contact" class="hover:text-brand-navy transition-colors">Hubungi Kami</a></li>
                        <li>
                            <a href="https://juki.eu.org/blog" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 hover:text-brand-navy transition-colors">
                                Blog &amp; Artikel
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-500">
                    © {{ date('Y') }} <strong class="text-brand-navy">Juki Website Developer Solo</strong>. All rights reserved.
                </p>
                <div class="flex gap-6 text-sm text-slate-500">
                    <a href="#contact" class="hover:text-brand-navy transition-colors">Kontak</a>
                    <a href="{{ url('/privacy-policy') }}" class="hover:text-brand-navy transition-colors">Privacy Policy</a>
                    <a href="{{ url('/terms-of-service') }}" class="hover:text-brand-navy transition-colors">Terms of Service</a>
                    <a href="{{ url('/sitemap.xml') }}" class="hover:text-brand-navy transition-colors" target="_blank">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Live Chat WhatsApp Widget dipindah ke partials/floating-whatsapp (global semua halaman) --}}
</div>
@endsection

@section('scripts')
<script>
    (function () {
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var hasIO = 'IntersectionObserver' in window;
        var items = document.querySelectorAll('.juki-landing .reveal');

        if (reduce || !hasIO) {
            return;
        }

        items.forEach(function (el) {
            var delay = el.getAttribute('data-delay');
            if (delay) {
                el.style.transitionDelay = delay + 'ms';
            }
            el.classList.add('is-animating');
        });

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        items.forEach(function (el) {
            observer.observe(el);
        });

        var counters = document.querySelectorAll('.juki-landing .juki-counter');
        if (counters.length) {
            var countObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var el = entry.target;
                        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
                        var suffix = el.getAttribute('data-suffix') || '';
                        var duration = 1400;
                        var start = null;
                        function step(ts) {
                            if (!start) start = ts;
                            var p = Math.min((ts - start) / duration, 1);
                            var eased = 1 - Math.pow(1 - p, 3);
                            el.textContent = Math.round(eased * target) + suffix;
                            if (p < 1) requestAnimationFrame(step);
                        }
                        requestAnimationFrame(step);
                        countObserver.unobserve(el);
                    }
                });
            }, { threshold: 0.5 });
            counters.forEach(function (el) {
                countObserver.observe(el);
            });
        }
    })();
</script>
@endsection
