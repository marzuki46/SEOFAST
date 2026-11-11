@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;
    $siteName = SystemSetting::get('site_name', config('app.name', 'Juki'));
    $waNumber = SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
    $canonicalUrl = url('/' . ltrim($page->slug, '/'));
@endphp

@section('title', 'Layanan Digital: Web Development, SEO, Otomasi & AI — ' . $siteName)
@section('meta_description', 'Solusi rekayasa digital untuk pertumbuhan bisnis: jasa pembuatan website profesional, optimasi SEO Google Maps, otomatisasi alur kerja n8n, dan integrasi AI terapan.')
@section('canonical_url', $canonicalUrl)

@section('head_extra')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Service",
    "name": "Layanan Digital Tri Marzuki",
    "serviceType": "Digital Development & Marketing Services",
    "provider": {
        "@type": "Person",
        "name": "Tri Marzuki",
        "url": "{{ url('/') }}",
        "telephone": "+{{ $waNumber }}"
    },
    "areaServed": "Indonesia",
    "description": "Layanan spesialis pengembangan website modern, optimasi SEO teknis & lokal Google Maps, otomatisasi alur kerja n8n, serta integrasi solusi AI bisnis.",
    "hasOfferCatalog": {
        "@@type": "OfferCatalog",
        "name": "Katalog Layanan Digital Utama",
        "itemListElement": [
            {
                "@@type": "Offer",
                "itemOffered": {
                    "@@type": "Service",
                    "name": "Pengembangan Web Profesional",
                    "url": "{{ url('/jasa/website') }}"
                }
            },
            {
                "@@type": "Offer",
                "itemOffered": {
                    "@@type": "Service",
                    "name": "SEO Teknis & Pencarian Lokal",
                    "url": "{{ url('/jasa/seo') }}"
                }
            },
            {
                "@@type": "Offer",
                "itemOffered": {
                    "@@type": "Service",
                    "name": "Otomasi Alur Kerja Bisnis & n8n",
                    "url": "{{ url('/jasa/otomasi') }}"
                }
            },
            {
                "@@type": "Offer",
                "itemOffered": {
                    "@@type": "Service",
                    "name": "Solusi Terapan AI Bisnis",
                    "url": "{{ url('/jasa/ai') }}"
                }
            }
        ]
    }
}
</script>
@endsection

@section('content')
<!-- Hero Section (Identik Style Hero Juki) -->
@include('partials.juki-hero', [
    'badge' => 'Layanan Spesialis Digital',
    'heading' => "Solusi Rekayasa Digital: Web Development, SEO, Otomasi & AI",
    'subheadline' => 'Empat pilar layanan komprehensif untuk membantu bisnis Anda membangun kehadiran online yang kredibel, menjaring prospek tertarget dari pencarian Google, dan mengotomasi alur operasional.',
    'ctaPrimaryText' => 'Konsultasi via WhatsApp',
    'ctaPrimaryUrl' => 'https://wa.me/' . $waNumber . '?text=' . urlencode('Halo Tri Marzuki, saya ingin konsultasi mengenai layanan digital untuk bisnis saya.'),
    'ctaSecondaryText' => 'Kirim Brief Proyek',
    'ctaSecondaryUrl' => url('/contact'),
])

<!-- 4 Pilar Layanan Utama (Grid Model Landing Page) -->
<section class="max-w-7xl mx-auto py-16 sm:py-20 px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-white border border-[#E2E8F0] shadow-sm text-xs font-bold uppercase tracking-wider text-brand-navy mb-3">
            Pilar Layanan Utama
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] font-outfit tracking-tight">
            Fokus Keahlian untuk Pertumbuhan Bisnis Anda
        </h2>
        <p class="mt-4 text-base sm:text-lg text-slate-600 font-sans leading-relaxed">
            Setiap pilar dirancang secara modular dan dapat diintegrasikan sesuai dengan tahap perkembangan dan kebutuhan operasional bisnis Anda.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
        <!-- Pilar 1: Web Development -->
        <div class="bg-white border border-[#E2E8F0] rounded-3xl p-8 sm:p-10 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-bl-full pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-blue-500/25 group-hover:scale-105 transition-transform font-outfit">
                        01
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                        Web Engineering
                    </span>
                </div>

                <h3 class="text-2xl font-bold text-[#0F172A] font-outfit mb-3 group-hover:text-blue-600 transition-colors">
                    <a href="{{ url('/jasa/website') }}">Pengembangan Web</a>
                </h3>

                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6 font-sans">
                    Pembuatan website profesional yang dirancang khusus untuk kenyamanan pengunjung ponsel cerdas, berkecepatan tinggi, dan teroptimasi sejak awal untuk mesin pencari.
                </p>

                <div class="space-y-2.5 mb-8 border-t border-slate-100 pt-5">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Company Profile B2B & Katalog Korporat</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Landing Page Iklan Berkonversi Tinggi</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Toko Online Mandiri Terintegrasi WhatsApp</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Aplikasi Web Kustom & Dashboard Sistem</span>
                    </div>
                </div>
            </div>

            <a href="{{ url('/jasa/website') }}" class="w-full inline-flex items-center justify-between px-6 py-4 rounded-2xl text-sm font-bold text-brand-navy bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 transition-all font-outfit">
                <span>Pelajari Detail Layanan Web</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- Pilar 2: SEO & SEO Lokal -->
        <div class="bg-white border border-[#E2E8F0] rounded-3xl p-8 sm:p-10 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-bl-full pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-600 text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-emerald-500/25 group-hover:scale-105 transition-transform font-outfit">
                        02
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                        Search Authority
                    </span>
                </div>

                <h3 class="text-2xl font-bold text-[#0F172A] font-outfit mb-3 group-hover:text-emerald-600 transition-colors">
                    <a href="{{ url('/jasa/seo') }}">SEO & Pencarian Lokal</a>
                </h3>

                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6 font-sans">
                    Strategi penataan struktur website dan optimasi Google Business Profile agar profil bisnis Anda mudah ditemukan pelanggan potensial yang siap membeli.
                </p>

                <div class="space-y-2.5 mb-8 border-t border-slate-100 pt-5">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Audit Teknis & Pembersihan Error Crawling Googlebot</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Optimasi Google Maps & Peringkat 3-Pack Lokal</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Penerapan Schema JSON-LD (Service, LocalBusiness, FAQ)</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Arsitektur Konten Siloing Berbasis Search Intent</span>
                    </div>
                </div>
            </div>

            <a href="{{ url('/jasa/seo') }}" class="w-full inline-flex items-center justify-between px-6 py-4 rounded-2xl text-sm font-bold text-brand-navy bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-200 transition-all font-outfit">
                <span>Pelajari Detail Layanan SEO</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- Pilar 3: Otomasi Alur Kerja & API -->
        <div class="bg-white border border-[#E2E8F0] rounded-3xl p-8 sm:p-10 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-bl-full pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-600 text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-amber-500/25 group-hover:scale-105 transition-transform font-outfit">
                        03
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-3 py-1 rounded-full border border-amber-100">
                        Workflow Automation
                    </span>
                </div>

                <h3 class="text-2xl font-bold text-[#0F172A] font-outfit mb-3 group-hover:text-amber-600 transition-colors">
                    <a href="{{ url('/jasa/otomasi') }}">Otomasi Bisnis & API</a>
                </h3>

                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6 font-sans">
                    Penghapusan pekerjaan manual yang menyita waktu melalui perancangan skenario alur kerja otomatis mandiri bebas biaya langganan bulanan mahal.
                </p>

                <div class="space-y-2.5 mb-8 border-t border-slate-100 pt-5">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Setup Platform Otomasi n8n Self-Hosted di VPS Mandiri</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Integrasi REST API Antar-Aplikasi & Webhook HMAC</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Notifikasi Instan WhatsApp Prospek Baru ke Admin</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Sinkronisasi Data Dua Arah Website ke Spreadsheet/CRM</span>
                    </div>
                </div>
            </div>

            <a href="{{ url('/jasa/otomasi') }}" class="w-full inline-flex items-center justify-between px-6 py-4 rounded-2xl text-sm font-bold text-brand-navy bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-200 transition-all font-outfit">
                <span>Pelajari Detail Layanan Otomasi</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <!-- Pilar 4: Solusi Terapan AI -->
        <div class="bg-white border border-[#E2E8F0] rounded-3xl p-8 sm:p-10 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-bl-full pointer-events-none"></div>
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-purple-600 to-pink-600 text-white flex items-center justify-center font-bold text-xl shadow-lg shadow-purple-500/25 group-hover:scale-105 transition-transform font-outfit">
                        04
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-purple-700 bg-purple-50 px-3 py-1 rounded-full border border-purple-100">
                        Applied AI
                    </span>
                </div>

                <h3 class="text-2xl font-bold text-[#0F172A] font-outfit mb-3 group-hover:text-purple-600 transition-colors">
                    <a href="{{ url('/jasa/ai') }}">Solusi Terapan AI</a>
                </h3>

                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-6 font-sans">
                    Integrasi kecerdasan buatan untuk mempercepat proses riset, produksi konten SEO terstruktur, dan asisten interaktif dengan kendali mutu manusia.
                </p>

                <div class="space-y-2.5 mb-8 border-t border-slate-100 pt-5">
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>AI Content Engine Berbasis Entity & Keyword Research</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Sistem Konten Programmatic SEO Aman Google Update</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Chatbot Customer Support Khusus Dokumen Pengetahuan Bisnis</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm text-slate-700 font-medium">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Filosofi Human-in-the-Loop untuk Verifikasi Akurasi Fakta</span>
                    </div>
                </div>
            </div>

            <a href="{{ url('/jasa/ai') }}" class="w-full inline-flex items-center justify-between px-6 py-4 rounded-2xl text-sm font-bold text-brand-navy bg-slate-50 hover:bg-purple-50 border border-slate-200 hover:border-purple-200 transition-all font-outfit">
                <span>Pelajari Detail Layanan AI</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- Section Pendekatan & Keunggulan Rekayasa -->
<section class="bg-white border-y border-[#E2E8F0] py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold uppercase tracking-wider mb-3">
                Standar Mutu
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] font-outfit tracking-tight">
                Kenapa Bekerja Sama dengan Saya?
            </h2>
            <p class="mt-4 text-base sm:text-lg text-slate-600 font-sans leading-relaxed">
                Kombinasi kemampuan rekayasa perangkat lunak, optimasi mesin pencari teknis, dan pemahaman operasional bisnis komersial.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-brand-navy text-white flex items-center justify-center mb-6 font-bold font-outfit">
                    A
                </div>
                <h3 class="text-xl font-bold text-[#0F172A] font-outfit mb-3">Rekayasa Tanpa Bloatware</h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-sans">
                    Fokus pada kode bersih berbasis framework modern (Laravel, CI4, native PHP 8.4). Menghindari tumpukan plugin pihak ketiga yang memperlambat server dan rawan celah keamanan.
                </p>
            </div>

            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-brand-navy text-white flex items-center justify-center mb-6 font-bold font-outfit">
                    B
                </div>
                <h3 class="text-xl font-bold text-[#0F172A] font-outfit mb-3">Orientasi Hasil Komersial</h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-sans">
                    Setiap halaman, kata kunci yang dibidik, dan alur otomatisasi dirancang untuk mengarahkan audiens pada keputusan transaksi nyata, bukan sekadar metrik kunjungan acak.
                </p>
            </div>

            <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-2xl p-8">
                <div class="w-12 h-12 rounded-xl bg-brand-navy text-white flex items-center justify-center mb-6 font-bold font-outfit">
                    C
                </div>
                <h3 class="text-xl font-bold text-[#0F172A] font-outfit mb-3">Kepemilikan Penuh & Mandiri</h3>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-sans">
                    Anda memegang kontrol penuh atas server, source code, lisensi data, dan panel admin tanpa biaya royalti tersembunyi atau ketergantungan sepihak.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Section Alur Kerja Kolaborasi -->
<section class="max-w-7xl mx-auto py-16 sm:py-24 px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-white border border-[#E2E8F0] shadow-sm text-xs font-bold uppercase tracking-wider text-brand-navy mb-3">
            Alur Pengerjaan
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] font-outfit tracking-tight">
            Bagaimana Proyek Anda Dikerjakan
        </h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 shadow-sm">
            <span class="text-xs font-bold text-slate-400 font-mono">FASE 01</span>
            <h3 class="text-lg font-bold text-[#0F172A] font-outfit mt-2 mb-2">Audit & Konsultasi</h3>
            <p class="text-sm text-slate-600 leading-relaxed font-sans">
                Analisis mendalam kondisi aset digital saat ini, pemetaan masalah, serta penentuan target dan ruang lingkup kerja.
            </p>
        </div>

        <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 shadow-sm">
            <span class="text-xs font-bold text-slate-400 font-mono">FASE 02</span>
            <h3 class="text-lg font-bold text-[#0F172A] font-outfit mt-2 mb-2">Desain & Pengembangan</h3>
            <p class="text-sm text-slate-600 leading-relaxed font-sans">
                Penulisan kode, penataan antarmuka responsif, perancangan skenario otomasi, dan integrasi API yang dibutuhkan.
            </p>
        </div>

        <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 shadow-sm">
            <span class="text-xs font-bold text-slate-400 font-mono">FASE 03</span>
            <h3 class="text-lg font-bold text-[#0F172A] font-outfit mt-2 mb-2">Validasi & Keamanan</h3>
            <p class="text-sm text-slate-600 leading-relaxed font-sans">
                Pengujian performa Core Web Vitals, verifikasi skema data terstruktur Google, serta audit celah keamanan formulir.
            </p>
        </div>

        <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 shadow-sm">
            <span class="text-xs font-bold text-slate-400 font-mono">FASE 04</span>
            <h3 class="text-lg font-bold text-[#0F172A] font-outfit mt-2 mb-2">Serah Terima & Edukasi</h3>
            <p class="text-sm text-slate-600 leading-relaxed font-sans">
                Peluncuran live ke domain utama, penyerahan akses administratif penuh, dan pendampingan operasional berkala.
            </p>
        </div>
    </div>
</section>

<!-- Section Tanya Jawab Ringkas -->
<section class="bg-[#F8FAFC] border-t border-[#E2E8F0] py-16 sm:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0F172A] font-outfit tracking-tight">
                Pertanyaan yang Sering Diajukan
            </h2>
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-[#0F172A] font-outfit mb-2">
                    Layanan mana yang paling tepat untuk bisnis saya saat ini?
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed font-sans">
                    Jika belum memiliki aset online resmi, mulailah dari <strong>Pengembangan Web</strong>. Jika website sudah ada namun sepi pengunjung, pilih <strong>SEO & Pencarian Lokal</strong>. Jika tim Anda kewalahan dengan tugas copy-paste data manual, pilihlah <strong>Otomasi Bisnis</strong>.
                </p>
            </div>

            <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-[#0F172A] font-outfit mb-2">
                    Berapa lama estimasi pengerjaan proyek?
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed font-sans">
                    Landing page iklan selesai dalam 3–5 hari kerja. Website Company Profile atau Toko Online berkisar 7–14 hari kerja. Setup alur otomasi n8n rata-rata memakan waktu 2–4 hari kerja.
                </p>
            </div>

            <div class="bg-white border border-[#E2E8F0] rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-[#0F172A] font-outfit mb-2">
                    Apakah melayani pertemuan langsung untuk wilayah Solo?
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed font-sans">
                    Ya, untuk klien di wilayah Solo Raya (Surakarta, Karanganyar, Sukoharjo, Klaten, Boyolali) dapat dijadwalkan sesi koordinasi tatap muka. Untuk wilayah luar kota, komunikasi dilakukan intensif melalui WhatsApp dan Google Meet.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Universal Call-to-Action WhatsApp -->
@include('partials.juki-project-inquiry')
@endsection
