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
        box-shadow: 0 20px 45px -18px rgba(30, 58, 95, 0.22);
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
<div class="juki-landing bg-[#F8FAFC] text-[#0F172A] overflow-x-hidden">

    {{-- ============ HERO ============ --}}
    <section id="home" class="relative overflow-hidden bg-gradient-to-br from-white via-[#F4F7FB] to-[#E8EEF6]">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-[#1E3A5F]/5 blur-3xl juki-float"></div>
        <div class="absolute bottom-10 left-0 w-72 h-72 rounded-full bg-[#A16207]/10 blur-3xl juki-float" style="animation-delay: 1.5s;"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(30,58,95,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(30,58,95,0.03)_1px,transparent_1px)] bg-[size:56px_56px]"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 lg:pt-32 lg:pb-24 text-center">
            <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-[#E2E8F0] shadow-sm text-sm font-semibold text-[#1E3A5F] mb-8 reveal">
                <svg class="w-4 h-4 text-[#A16207]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"/></svg>
                Web Developer &amp; Web Designer Solo — Surakarta, Jawa Tengah
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.1] tracking-tight text-[#0F172A] mb-6 reveal" data-delay="80">
                Web Developer Solo: Jasa Pembuatan Website <span class="text-[#A16207]">Profesional</span>
            </h1>

            <p class="text-lg lg:text-xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed reveal" data-delay="160">
                Jasa web design &amp; pembuatan website di Solo dan Surakarta — company profile, toko online,
                landing page, sistem informasi, hingga AI automation. Dibangun dengan
                <strong class="text-[#1E3A5F]">Laravel</strong>, <strong class="text-[#1E3A5F]">CodeIgniter</strong>,
                dan <strong class="text-[#1E3A5F]">CMS WordPress</strong>. Pengerjaan website maksimal 2 minggu.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-10 reveal" data-delay="240">
                <a href="https://wa.me/6282213028718?text=Halo%20Juki%20Website%20Developer%20Solo%2C%20saya%20ingin%20konsultasi%20pembuatan%20website" target="_blank" rel="noopener" class="juki-badge inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-[#A16207] hover:bg-[#B4750C] text-white font-bold text-lg shadow-xl shadow-[#A16207]/25 transition-all hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Konsultasi Gratis via WhatsApp
                </a>
                <a href="#services" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-white border-2 border-[#1E3A5F]/15 text-[#1E3A5F] hover:border-[#1E3A5F]/40 font-bold text-lg transition-all hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    Lihat Layanan &amp; Harga
                </a>
            </div>

            <div class="flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm font-semibold text-slate-600 reveal" data-delay="320">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Garansi Support WhatsApp
                </span>
                <span class="inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Pengerjaan Website Maks 2 Minggu
                </span>
                <span class="inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    SEO-Friendly &amp; Responsive
                </span>
            </div>
        </div>

        <div class="juki-scroll-hint absolute bottom-6 left-1/2 -translate-x-1/2 text-[#1E3A5F]/40">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </section>

    {{-- ============ STATS BAR ============ --}}
    <section class="bg-[#1E3A5F] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <div class="reveal">
                    <p class="text-3xl lg:text-4xl font-extrabold text-[#F4C95D]">2 Minggu</p>
                    <p class="mt-1 text-sm text-blue-100">Pengerjaan Website Maksimal</p>
                </div>
                <div class="reveal" data-delay="80">
                    <p class="text-3xl lg:text-4xl font-extrabold text-[#F4C95D]">1 Bulan</p>
                    <p class="mt-1 text-sm text-blue-100">Sistem Modular + Training</p>
                </div>
                <div class="reveal" data-delay="160">
                    <p class="text-3xl lg:text-4xl font-extrabold text-[#F4C95D]">3+ Teknologi</p>
                    <p class="mt-1 text-sm text-blue-100">Laravel, CodeIgniter, WordPress</p>
                </div>
                <div class="reveal" data-delay="240">
                    <p class="text-3xl lg:text-4xl font-extrabold text-[#F4C95D]">100%</p>
                    <p class="mt-1 text-sm text-blue-100">Garansi Support WhatsApp</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ SERVICES ============ --}}
    <section id="services" class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-[#A16207] mb-3">Layanan Unggulan</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-[#1E3A5F] mb-4">Layanan Web Developer Solo</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Solusi digital lengkap untuk UMKM, perusahaan, dan instansi di Solo Raya &amp; Jawa Tengah</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Service 1: Website --}}
                <div class="juki-card group relative bg-white border border-[#E2E8F0] rounded-3xl p-8 reveal">
                    <div class="w-16 h-16 rounded-2xl bg-[#1E3A5F] flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-[#1E3A5F]/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-3">Jasa Pembuatan Website Solo</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Website company profile, toko online, dan landing page dengan desain modern, cepat, dan mobile-friendly.</p>
                    <ul class="space-y-2.5 text-sm text-slate-700">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>CMS WordPress / custom</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Responsive &amp; mobile-first</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>SEO-friendly &amp; cepat</li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <p class="text-sm text-slate-500 mb-3">Mulai <span class="font-bold text-[#A16207]">Rp 2.500.000</span> · maks 2 minggu</p>
                        <a href="https://wa.me/6282213028718" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#1E3A5F] hover:text-[#A16207] transition-colors">Konsultasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>

                {{-- Service 2: Sistem Informasi --}}
                <div class="juki-card group relative bg-white border border-[#E2E8F0] rounded-3xl p-8 reveal" data-delay="80">
                    <div class="w-16 h-16 rounded-2xl bg-[#2563EB] flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-[#2563EB]/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 1.657 3.582 3 8 3s8-1.343 8-3V7M4 7c0 1.657 3.582 3 8 3s8-1.343 8-3-3.582-3-8-3-8 1.343-8 3zm16 5c0 1.657-3.582 3-8 3s-8-1.343-8-3"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-3">Pembuatan Sistem Informasi</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Sistem ERP, CRM, inventory, dan aplikasi web custom sesuai alur bisnis perusahaan Anda.</p>
                    <ul class="space-y-2.5 text-sm text-slate-700">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Laravel &amp; CodeIgniter</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Database &amp; API terintegrasi</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Modular 1 bulan + training</li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <p class="text-sm text-slate-500 mb-3">Mulai <span class="font-bold text-[#A16207]">Rp 10.000.000</span></p>
                        <a href="https://wa.me/6282213028718" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#1E3A5F] hover:text-[#A16207] transition-colors">Konsultasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>

                {{-- Service 3: AI Automation --}}
                <div class="juki-card group relative bg-white border border-[#E2E8F0] rounded-3xl p-8 reveal" data-delay="160">
                    <div class="w-16 h-16 rounded-2xl bg-[#A16207] flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-[#A16207]/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-3">AI Automation &amp; Chatbot</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Otomatisasi layanan pelanggan dan proses bisnis dengan kecerdasan buatan.</p>
                    <ul class="space-y-2.5 text-sm text-slate-700">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>WhatsApp chatbot</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Customer service automation</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Integrasi API &amp; bisnis</li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <a href="https://wa.me/6282213028718" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#1E3A5F] hover:text-[#A16207] transition-colors">Konsultasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>

                {{-- Service 4: SEO & Backlink --}}
                <div class="juki-card group relative bg-white border border-[#E2E8F0] rounded-3xl p-8 reveal" data-delay="240">
                    <div class="w-16 h-16 rounded-2xl bg-[#0F766E] flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-[#0F766E]/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#0F172A] mb-3">Jasa SEO &amp; Backlink Solo</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-5">Naikkan peringkat website Anda di Google dengan strategi SEO lokal dan link building.</p>
                    <ul class="space-y-2.5 text-sm text-slate-700">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Backlink berkualitas &amp; aman</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Google Maps optimization</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Local SEO Surakarta &amp; keyword research</li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <a href="https://wa.me/6282213028718" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#1E3A5F] hover:text-[#A16207] transition-colors">Konsultasi <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ WHY CHOOSE US ============ --}}
    <section class="py-20 lg:py-24 bg-[#F1F5F9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-[#A16207] mb-3">Kenapa Memilih Kami</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-[#1E3A5F] mb-4">Partner Digital Terpercaya di Solo</h2>
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
                    <div class="w-14 h-14 rounded-xl bg-[#1E3A5F]/5 text-[#1E3A5F] flex items-center justify-center mb-5">
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
                <p class="text-sm font-bold uppercase tracking-widest text-[#A16207] mb-3">Tentang Kami</p>
                <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-[#1E3A5F] mb-5">
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
                    <a href="#contact" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#1E3A5F] hover:bg-[#16283F] text-white font-semibold transition-all">Hubungi Kami</a>
                    <a href="https://wa.me/6282213028718" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white border-2 border-[#A16207] text-[#A16207] hover:bg-[#A16207] hover:text-white font-semibold transition-all">Chat WhatsApp</a>
                </div>
            </div>

            <div class="reveal" data-delay="120">
                <div class="bg-[#F1F5F9] border border-[#E2E8F0] rounded-3xl p-8">
                    <h3 class="text-lg font-bold text-[#1E3A5F] mb-6">Teknologi &amp; Keahlian</h3>
                    <div class="flex flex-wrap gap-3 mb-8">
                        @php
                        $techs = ['Laravel', 'CodeIgniter', 'WordPress', 'PHP', 'MySQL', 'React.js', 'Vue.js', 'Tailwind CSS', 'REST API', 'UI/UX Design', 'SEO', 'Google Search Console'];
                        @endphp
                        @foreach($techs as $tech)
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#E2E8F0] text-sm font-semibold text-[#1E3A5F] shadow-sm">
                            <svg class="w-4 h-4 text-[#A16207]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd"/></svg>
                            {{ $tech }}
                        </span>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-slate-700">Responsive &amp; mobile-first design</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-slate-700">Optimasi kecepatan &amp; Core Web Vitals</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-slate-700">Struktur SEO &amp; schema JSON-LD</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
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
                <p class="text-sm font-bold uppercase tracking-widest text-[#A16207] mb-3">Harga Jasa Pembuatan Website Solo</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-[#1E3A5F] mb-4">Paket Transparan, Tanpa Biaya Tersembunyi</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Harga dapat disesuaikan dengan kebutuhan. Konsultasikan proyek Anda secara gratis.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                {{-- Paket Website --}}
                <div class="juki-card relative bg-white border border-[#E2E8F0] rounded-3xl p-8 lg:p-10 reveal">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-[#0F172A]">Paket Website</h3>
                        <span class="px-3 py-1 rounded-full bg-[#1E3A5F]/10 text-[#1E3A5F] text-xs font-bold uppercase tracking-wide">CMS WordPress</span>
                    </div>
                    <p class="flex items-baseline gap-2 mb-6">
                        <span class="text-sm text-slate-500">Mulai</span>
                        <span class="text-4xl font-extrabold text-[#A16207]">Rp 2.500.000</span>
                    </p>
                    <ul class="space-y-3 text-sm text-slate-700 mb-8">
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Company profile, landing page, atau toko online</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Desain responsive, modern, mobile-first</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Struktur SEO-friendly &amp; cepat loading</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Pengerjaan maksimal 2 minggu</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Garansi support via WhatsApp</li>
                    </ul>
                    <a href="https://wa.me/6282213028718?text=Halo%2C%20saya%20tertarik%20dengan%20Paket%20Website%20Rp%202.500.000" target="_blank" rel="noopener" class="juki-badge inline-flex w-full items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-[#1E3A5F] hover:bg-[#16283F] text-white font-bold transition-all">Pilih Paket Website</a>
                </div>

                {{-- Paket Sistem Informasi --}}
                <div class="juki-card relative bg-[#1E3A5F] text-white rounded-3xl p-8 lg:p-10 reveal" data-delay="120">
                    <div class="absolute top-6 right-6">
                        <span class="px-3 py-1 rounded-full bg-[#A16207] text-white text-xs font-bold uppercase tracking-wide shadow-lg shadow-[#A16207]/30">Premium</span>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold">Paket Sistem Informasi</h3>
                        <span class="px-3 py-1 rounded-full bg-white/10 text-blue-100 text-xs font-bold uppercase tracking-wide">Laravel · CodeIgniter</span>
                    </div>
                    <p class="flex items-baseline gap-2 mb-6">
                        <span class="text-sm text-blue-100">Mulai</span>
                        <span class="text-4xl font-extrabold text-[#F4C95D]">Rp 10.000.000</span>
                    </p>
                    <ul class="space-y-3 text-sm text-blue-100 mb-8">
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-[#F4C95D] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>ERP, CRM, inventory, POS, aplikasi web custom</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-[#F4C95D] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Program modular selesai 1 bulan + training</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-[#F4C95D] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Custom program menyesuaikan kebutuhan &amp; kompleksitas</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-[#F4C95D] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Database, keamanan &amp; API terintegrasi</li>
                        <li class="flex items-start gap-3"><svg class="w-5 h-5 text-[#F4C95D] mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>Garansi support via WhatsApp</li>
                    </ul>
                    <a href="https://wa.me/6282213028718?text=Halo%2C%20saya%20tertarik%20dengan%20Paket%20Sistem%20Informasi%20Rp%2010.000.000" target="_blank" rel="noopener" class="juki-badge inline-flex w-full items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-[#A16207] hover:bg-[#B4750C] text-white font-bold transition-all">Pilih Paket Sistem Informasi</a>
                </div>
            </div>

            <p class="text-center text-sm text-slate-500 mt-8 reveal">
                Butuh penawaran khusus atau proyek skala besar? <a href="https://wa.me/6282213028718" target="_blank" rel="noopener" class="font-bold text-[#A16207] hover:underline">Konsultasikan kebutuhan Anda</a> — gratis.
            </p>
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
    @if($recentPosts->isNotEmpty())
    <section id="blog" class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-[#A16207] mb-3">Dari Blog</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-[#1E3A5F] mb-4">Artikel &amp; Tips Website</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Wawasan seputar pembuatan website, SEO, dan digital marketing untuk bisnis Anda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recentPosts as $post)
                <article class="juki-card bg-white border border-[#E2E8F0] rounded-3xl overflow-hidden flex flex-col reveal">
                    <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden bg-slate-100">
                        <img src="{{ $post->featured_image_url ?: asset('images/seofast-placeholder.svg') }}" alt="{{ $post->featured_image_alt ?? $post->title }}" loading="lazy" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    </a>
                    <div class="p-6 flex flex-col flex-1">
                        @if($post->published_at)
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">{{ $post->published_at->format('d M Y') }}</span>
                        @endif
                        <h3 class="font-bold text-lg text-[#0F172A] mb-3 line-clamp-2">
                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-[#A16207] transition-colors">{{ $post->title }}</a>
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-5 line-clamp-3 flex-1">{{ $post->excerpt }}</p>
                        <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#1E3A5F] hover:text-[#A16207] transition-colors">Baca Selengkapnya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============ FAQ ============ --}}
    <section id="faq" class="py-20 lg:py-24 bg-[#F1F5F9]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-[#A16207] mb-3">FAQ</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-[#1E3A5F] mb-4">Pertanyaan yang Sering Diajukan</h2>
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
                        <svg class="w-5 h-5 text-[#A16207] shrink-0 transition-transform duration-300" :class="open === {{ $index + 1 }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
                <p class="text-sm font-bold uppercase tracking-widest text-[#A16207] mb-3">Layanan Kami</p>
                <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight text-[#1E3A5F] mb-4">Jasa Web Developer Profesional di Solo</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Solusi website &amp; sistem digital lengkap untuk bisnis di Surakarta, Sukoharjo, dan Jawa Tengah</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-white border border-[#E2E8F0] rounded-2xl p-7 juki-card reveal">
                    <h3 class="text-lg font-bold text-[#1E3A5F] mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#2563EB]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
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
                    <h3 class="text-lg font-bold text-[#1E3A5F] mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#2563EB]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
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
                    <h3 class="text-lg font-bold text-[#1E3A5F] mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#2563EB]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
                        AI Automation &amp; Chatbot Indonesia
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Tingkatkan produktivitas dengan <strong>AI automation solutions</strong>: WhatsApp chatbot,
                        customer service automation, business process automation, data processing, dan integrasi
                        API third-party. Solusi cerdas untuk transformasi digital bisnis modern.
                    </p>
                </div>

                <div class="bg-white border border-[#E2E8F0] rounded-2xl p-7 juki-card reveal" data-delay="80">
                    <h3 class="text-lg font-bold text-[#1E3A5F] mb-3 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#2563EB]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.259-.966zM4.429 4.468a1 1 0 00-1.287-.143l-.898.542a1 1 0 00.144 1.731l.898.543a1 1 0 001.287-.144l.898-.898a1 1 0 00-.144-1.287l-.898-.542zm10.616-.39a1 1 0 011.287.144l.898.898a1 1 0 01-.144 1.287l-.898.542a1 1 0 01-1.287-.144l-.898-.898a1 1 0 01.144-1.287l.898-.542zM12.2 3.9a1 1 0 011.932.518l-.259.966a1 1 0 01-1.932-.518l.259-.966zM3 8a1 1 0 011-1h2a1 1 0 110 2H4a1 1 0 01-1-1zm5.5-4.5A1.5 1.5 0 0110 2h1.5a1.5 1.5 0 010 3H10A1.5 1.5 0 018.5 3.5zm-1.187 5.93a1 1 0 01.185 1.399l-1.5 2a1 1 0 01-1.399.185l-2-1.5a1 1 0 01-.185-1.399l1.5-2a1 1 0 011.399-.185l2 1.5zm7.5-1.5a1 1 0 011.399-.185l2 1.5a1 1 0 01-.185 1.399l-1.5 2a1 1 0 01-1.399.185l-2-1.5a1 1 0 01.185-1.399l1.5-2z" clip-rule="evenodd"/></svg>
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
                <h3 class="text-xl font-bold text-[#1E3A5F] mb-6">Area Layanan Web Developer Solo</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-slate-700">
                    @php
                    $areas = ['Surakarta / Solo', 'Sukoharjo', 'Grogol', 'Kartasura', 'Karanganyar', 'Boyolali', 'Klaten', 'Jawa Tengah', 'Yogyakarta', 'Semarang', 'Jabodetabek', 'Seluruh Indonesia'];
                    @endphp
                    @foreach($areas as $area)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $area }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CONTACT ============ --}}
    <section id="contact" class="py-20 lg:py-24 bg-[#1E3A5F] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <p class="text-sm font-bold uppercase tracking-widest text-[#F4C95D] mb-3">Lokasi &amp; Kontak</p>
                <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight mb-4">Hubungi Web Developer Solo</h2>
                <p class="text-lg text-blue-100 max-w-2xl mx-auto">Kunjungi kami atau hubungi untuk konsultasi gratis</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div class="juki-map reveal">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.4148800710145!2d110.8178768!3d-7.6384539999999985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a3da40ed41c69%3A0x2601c6922ac80dd4!2sJuki%20Website%20Developer%20Solo!5e0!3m2!1sid!2sid!4v1785748798166!5m2!1sid!2sid"
                        width="100%"
                        height="500"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Juki Website Developer Solo"></iframe>
                </div>

                <div class="flex flex-col justify-center space-y-6 reveal" data-delay="120">
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-8">
                        <h3 class="text-2xl font-bold mb-6">Kontak Kami</h3>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-[#A16207]/20 flex items-center justify-center text-[#F4C95D] shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">Alamat</h4>
                                    <p class="text-blue-100">Home Parangjoro 2, Parangjoro, Grogol, Sukoharjo, Jawa Tengah 57552</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-[#2563EB]/20 flex items-center justify-center text-blue-300 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">Email</h4>
                                    <p class="text-blue-100">contact@juki.eu.org</p>
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
                                <div class="w-12 h-12 rounded-xl bg-[#A16207]/20 flex items-center justify-center text-[#F4C95D] shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-1">Jam Operasional</h4>
                                    <p class="text-blue-100">Senin - Jumat: 09:00 - 17:00 WIB</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-white/10">
                            <a href="https://wa.me/6282213028718?text=Halo%20Juki%20Website%20Developer%20Solo%2C%20saya%20ingin%20konsultasi%20pembuatan%20website" target="_blank" rel="noopener" class="juki-badge inline-flex w-full items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-[#25D366] hover:bg-[#1FBD5A] text-white font-bold text-lg transition-all">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Chat WhatsApp Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FINAL CTA ============ --}}
    <section class="py-20 lg:py-24 bg-gradient-to-br from-[#16283F] via-[#1E3A5F] to-[#0F1B2E] text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(161,98,7,0.25),transparent_70%)]"></div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-5xl font-extrabold tracking-tight mb-6 reveal">
                Siap Transformasi Digital <span class="text-[#F4C95D]">Bisnis Anda?</span>
            </h2>
            <p class="text-lg lg:text-xl text-blue-100 mb-10 max-w-2xl mx-auto reveal" data-delay="80">
                Mulai dari <strong class="text-white">Rp 2.500.000</strong> untuk website profesional. Konsultasikan
                proyek Anda hari ini — gratis dan tanpa komitmen.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 reveal" data-delay="160">
                <a href="https://wa.me/6282213028718?text=Halo%2C%20saya%20ingin%20mulai%20proyek%20website%20segera" target="_blank" rel="noopener" class="juki-badge inline-flex items-center justify-center gap-2 px-10 py-5 rounded-2xl bg-[#A16207] hover:bg-[#B4750C] text-white font-bold text-xl shadow-2xl shadow-[#A16207]/30 transition-all hover:-translate-y-0.5">
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
    <footer class="bg-[#0F1B2E] text-slate-300 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">
                © {{ date('Y') }} <strong class="text-white">Juki Website Developer Solo</strong>.
                Web Developer &amp; Web Designer di Surakarta, Jawa Tengah. All rights reserved.
            </p>
        </div>
    </footer>

    {{-- Floating WhatsApp Button --}}
    <a href="https://wa.me/6282213028718" target="_blank" rel="noopener" aria-label="Chat WhatsApp Juki Website Developer Solo"
       class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-[#25D366] hover:bg-[#1FBD5A] text-white flex items-center justify-center shadow-xl shadow-black/25 transition-transform hover:scale-110">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
    </a>
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
    })();
</script>
@endsection
