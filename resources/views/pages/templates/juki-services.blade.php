@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;
    $siteName = SystemSetting::get('site_name', config('app.name', 'Juki'));
    $logoUrl = SystemSetting::get('logo_url', 'https://juki.eu.org/storage/content-images/xjuki-website-developer-solo-landscape-1785293658.webp.pagespeed.ic.ehTKUU0dbo.webp');
    $logoAlt = SystemSetting::get('logo_alt', 'Juki Website Developer Solo');
    $waNumber = SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
    $canonicalUrl = url('/' . ltrim($page->slug, '/'));
@endphp

@section('title', ($page->meta_title ?: $page->title) . ' — ' . $siteName)
@section('meta_description', $page->meta_description ?: 'Layanan jasa pembuatan website profesional, audit SEO teknis & lokal Google Maps, otomatisasi alur kerja n8n, dan integrasi solusi AI bisnis oleh Tri Marzuki.')
@section('canonical_url', $canonicalUrl)

@section('head_extra')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Service",
    "name": "{{ $page->title }}",
    "serviceType": "Digital Development & Marketing Services",
    "provider": {
        "@type": "Person",
        "name": "Tri Marzuki",
        "url": "{{ url('/') }}",
        "telephone": "+6282213028718"
    },
    "areaServed": "Indonesia",
    "description": "{{ $page->meta_description }}",
    "hasOfferCatalog": {
        "@@type": "OfferCatalog",
        "name": "Katalog Layanan Digital",
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
                    "name": "SEO & SEO Lokal Google Maps",
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
                    "name": "Solusi & Integrasi AI Bisnis",
                    "url": "{{ url('/jasa/ai') }}"
                }
            }
        ]
    }
}
</script>
@endsection

@section('content')
<!-- Hero Section (Identik Style dengan Katalog Produk) -->
@include('partials.juki-hero', [
    'badge' => 'Katalog Layanan Digital Spesialis',
    'heading' => "Jasa Pembuatan Website, SEO, Otomasi & AI untuk Pertumbuhan Bisnis Anda",
    'subheadline' => 'Pilih solusi digital terukur yang sesuai dengan fase bisnis Anda. Dibangun dengan fokus rekayasa performa tinggi, keamanan andal, dan aliran prospek nyata ke WhatsApp bisnis Anda.',
    'ctaPrimaryText' => 'Konsultasi via WhatsApp',
    'ctaPrimaryUrl' => 'https://wa.me/' . $waNumber . '?text=' . urlencode('Halo Tri Marzuki, saya ingin konsultasi mengenai layanan digital untuk bisnis saya.'),
    'ctaSecondaryText' => 'Kirim Brief Proyek',
    'ctaSecondaryUrl' => url('/contact'),
])

<!-- Grid Kartu Layanan (Model Katalog Produk) -->
<section class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 pb-4 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-indigo bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">Solusi Berbasis Hasil</span>
            <h2 class="text-3xl font-extrabold text-slate-900 font-outfit mt-2">Daftar Layanan & Spesialisasi</h2>
        </div>
        <p class="text-sm text-slate-500 max-w-md">
            Arsitektur modern, kode bersih, kecepatan tinggi Core Web Vitals, dan integrasi WhatsApp instan di setiap proyek.
        </p>
    </div>

    <!-- 4 Pilar Layanan Utama (Grid Model Produk) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
        <!-- Card 1: Web Development -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
            <div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xl mb-5 shadow-md shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    01
                </div>
                <div class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Web Development</div>
                <h3 class="text-xl font-bold text-slate-900 font-outfit mb-3 group-hover:text-brand-indigo transition-colors">
                    <a href="{{ url('/jasa/website') }}">Pengembangan Web</a>
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                    Website modern, responsif, dan ultra cepat dengan kode bersih — dari company profile, toko online, hingga custom web app.
                </p>
                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">Company Profile</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">Toko Online</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">Landing Page</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">Laravel / CI4</span>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-3 border-t border-slate-100 pt-3">
                    Termasuk: Core Web Vitals Hijau, SEO-ready, Mobile First
                </div>
                <a href="{{ url('/jasa/website') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-navy hover:bg-slate-800 transition-all shadow-sm">
                    Lihat Layanan
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <!-- Card 2: SEO & SEO Lokal -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
            <div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-600 text-white flex items-center justify-center font-bold text-xl mb-5 shadow-md shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                    02
                </div>
                <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Search Optimization</div>
                <h3 class="text-xl font-bold text-slate-900 font-outfit mb-3 group-hover:text-emerald-600 transition-colors">
                    <a href="{{ url('/jasa/seo') }}">SEO & SEO Lokal</a>
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                    Audit teknis mendalam, optimasi on-page berbasis entity semantik, dan dominasi kata kunci pencarian lokal di Google Maps.
                </p>
                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700">Audit Teknis</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700">Google Maps 3-Pack</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700">Local SEO</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700">Schema JSON-LD</span>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-3 border-t border-slate-100 pt-3">
                    Fokus: Trafik komersial tertarget & siap beli
                </div>
                <a href="{{ url('/jasa/seo') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-navy hover:bg-slate-800 transition-all shadow-sm">
                    Lihat Layanan
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <!-- Card 3: Otomasi Alur Kerja Bisnis -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
            <div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-600 text-white flex items-center justify-center font-bold text-xl mb-5 shadow-md shadow-amber-500/20 group-hover:scale-110 transition-transform">
                    03
                </div>
                <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Business Automation</div>
                <h3 class="text-xl font-bold text-slate-900 font-outfit mb-3 group-hover:text-amber-600 transition-colors">
                    <a href="{{ url('/jasa/otomasi') }}">Otomasi Alur Kerja</a>
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                    Otomasi alur kerja bebas biaya langganan mahal dengan n8n self-hosted, sinkronisasi API, webhook, dan notifikasi WhatsApp otomatis.
                </p>
                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700">Self-Hosted n8n</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700">REST API</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700">Auto WhatsApp</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700">Webhooks</span>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-3 border-t border-slate-100 pt-3">
                    Manfaat: Hemat puluhan jam kerja staf manual
                </div>
                <a href="{{ url('/jasa/otomasi') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-navy hover:bg-slate-800 transition-all shadow-sm">
                    Lihat Layanan
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <!-- Card 4: Solusi AI Bisnis -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
            <div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-purple-600 to-pink-600 text-white flex items-center justify-center font-bold text-xl mb-5 shadow-md shadow-purple-500/20 group-hover:scale-110 transition-transform">
                    04
                </div>
                <div class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">Applied AI Solutions</div>
                <h3 class="text-xl font-bold text-slate-900 font-outfit mb-3 group-hover:text-purple-600 transition-colors">
                    <a href="{{ url('/jasa/ai') }}">Solusi Terapan AI</a>
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                    Penerapan kecerdasan buatan terapan untuk bisnis: programmatic SEO terstruktur, audit gap konten, dan chatbot asisten internal.
                </p>
                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700">Content Engine</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700">Programmatic SEO</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700">AI Chatbot</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700">Human Review</span>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-3 border-t border-slate-100 pt-3">
                    Prinsip: Dibantu AI, bukan bergantung buta pada AI
                </div>
                <a href="{{ url('/jasa/ai') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-navy hover:bg-slate-800 transition-all shadow-sm">
                    Lihat Layanan
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Spesialisasi Lengkap Berdasarkan Kategori (Quick Jump) -->
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 sm:p-8 mb-16">
        <h2 class="text-2xl font-bold text-slate-900 font-outfit mb-6">Daftar Lengkap 17 Layanan Spesialisasi</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <div class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    Pembuatan Website
                </div>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="{{ url('/jasa/website') }}" class="hover:text-brand-indigo hover:underline">• Jasa Pembuatan Website Profesional</a></li>
                    <li><a href="{{ url('/jasa/website/company-profile') }}" class="hover:text-brand-indigo hover:underline">• Website Company Profile Perusahaan</a></li>
                    <li><a href="{{ url('/jasa/website/landing-page') }}" class="hover:text-brand-indigo hover:underline">• Landing Page Iklan & Konversi</a></li>
                    <li><a href="{{ url('/jasa/website/toko-online') }}" class="hover:text-brand-indigo hover:underline">• Website Toko Online (E-Commerce)</a></li>
                    <li><a href="{{ url('/jasa/website/wordpress') }}" class="hover:text-brand-indigo hover:underline">• Website Bisnis WordPress Ringan</a></li>
                    <li><a href="{{ url('/jasa/aplikasi-web') }}" class="hover:text-brand-indigo hover:underline">• Aplikasi Web & Dashboard Kustom</a></li>
                    <li><a href="{{ url('/jasa/maintenance-website') }}" class="hover:text-brand-indigo hover:underline">• Jasa Maintenance Website Berkala</a></li>
                    <li><a href="{{ url('/jasa/perbaikan-website') }}" class="hover:text-brand-indigo hover:underline">• Jasa Perbaikan Website Error</a></li>
                    <li><a href="{{ url('/jasa/website/solo') }}" class="hover:text-brand-indigo hover:underline">• Web Developer Solo Surakarta</a></li>
                </ul>
            </div>

            <div>
                <div class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    SEO & Pencarian Lokal
                </div>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="{{ url('/jasa/seo') }}" class="hover:text-emerald-600 hover:underline">• Jasa SEO Teknis & Organik</a></li>
                    <li><a href="{{ url('/jasa/seo-lokal') }}" class="hover:text-emerald-600 hover:underline">• Jasa SEO Lokal Google Maps & GBP</a></li>
                    <li><a href="{{ url('/jasa/google-maps-embed') }}" class="hover:text-emerald-600 hover:underline">• Jasa Google Maps Embed Booster</a></li>
                    <li><a href="{{ url('/jasa/seo') }}#audit" class="hover:text-emerald-600 hover:underline">• Audit On-Page & Arsitektur Silo</a></li>
                    <li><a href="{{ url('/jasa/seo') }}#schema" class="hover:text-emerald-600 hover:underline">• Optimasi Schema.org & Semantic Entity</a></li>
                </ul>
            </div>

            <div>
                <div class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                    Otomasi Bisnis & API
                </div>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="{{ url('/jasa/otomasi') }}" class="hover:text-amber-600 hover:underline">• Jasa Otomasi Alur Kerja Bisnis</a></li>
                    <li><a href="{{ url('/jasa/otomasi/n8n') }}" class="hover:text-amber-600 hover:underline">• Setup & Integrasi Workflow n8n</a></li>
                    <li><a href="{{ url('/jasa/otomasi') }}#api" class="hover:text-amber-600 hover:underline">• Integrasi REST API & Webhook HMAC</a></li>
                    <li><a href="{{ url('/jasa/otomasi') }}#whatsapp" class="hover:text-amber-600 hover:underline">• Otomasi Notifikasi WhatsApp & Email</a></li>
                </ul>
            </div>

            <div>
                <div class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    Solusi Terapan AI
                </div>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="{{ url('/jasa/ai') }}" class="hover:text-purple-600 hover:underline">• Konsultasi & Integrasi AI Praktis</a></li>
                    <li><a href="{{ url('/jasa/ai/content-engine') }}" class="hover:text-purple-600 hover:underline">• AI Content Engine & Programmatic SEO</a></li>
                    <li><a href="{{ url('/jasa/ai') }}#audit" class="hover:text-purple-600 hover:underline">• Audit SEO & Analisis Konten AI</a></li>
                    <li><a href="{{ url('/jasa/ai') }}#chatbot" class="hover:text-purple-600 hover:underline">• Chatbot & Asisten Internal Bisnis</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Content Komprehensif Mendalam tentang Jasa (1.000+ Kata, Struktur Heading SEO Terstandarisasi) -->
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-16 text-slate-700 leading-relaxed font-sans border-t border-slate-200">
    <div class="prose prose-slate prose-lg max-w-none">
        
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-outfit mb-6 tracking-tight">
            Pendekatan Holistik: Solusi Rekayasa Digital yang Bekerja Nyata untuk Bisnis Anda
        </h2>

        <p class="text-lg leading-relaxed mb-6">
            Di era digital yang bergerak sangat cepat, memiliki aset online bukan lagi sekadar formalitas agar bisnis terlihat modern di mata rekanan. Sebagian besar kegagalan digital marketing dan investasi website berawal dari asumsi bahwa tampilan yang memikat sudah cukup untuk mendatangkan pelanggan. Kenyataannya, website yang indah tetapi lambat diakses di jaringan smartphone hanya akan membuang anggaran iklan. Sebaliknya, website yang cepat tetapi tidak teroptimasi untuk algoritma pencarian Google hanya akan menjadi rahasia paling sunyi di internet.
        </p>

        <p class="mb-8 leading-relaxed">
            Sebagai spesialis web developer, praktisi SEO teknis, dan konsultan otomasi alur kerja berbasis di Solo, Jawa Tengah, saya menawarkan pendekatan rekayasa yang berfokus pada hasil terukur (outcome-driven development). Setiap baris kode yang ditulis, arsitektur database yang dirancang, riset kata kunci yang dianalisis, hingga skenario otomasi n8n yang dibangun memiliki satu tujuan utama: <strong>membuat bisnis Anda dipahami dengan cepat oleh audiens, mendominasi saluran pencarian organik, menghemat ratusan jam kerja repetitif, dan menghasilkan aliran konversi nyata ke kontak WhatsApp bisnis Anda</strong>.
        </p>

        <h3 class="text-2xl font-bold text-slate-900 font-outfit mt-10 mb-4">
            1. Mengapa Website Berkinerja Tinggi Menjadi Fondasi Utama Keberhasilan Digital
        </h3>

        <p class="mb-4 leading-relaxed">
            Website adalah satu-satunya aset digital di mana Anda memiliki kendali penuh atas data, citra jenama, dan alur pengalaman pengguna tanpa bergantung pada kebijakan algoritma media sosial yang berubah-ubah sewaktu-waktu. Namun, membangun website yang efektif membutuhkan keseimbangan antara estetika visual, kenyamanan pengalaman pengguna (UX), dan ketahanan arsitektur teknis.
        </p>

        <p class="mb-4 leading-relaxed">
            Dalam seluruh proyek pengembangan website yang saya tangani—baik itu <em>Company Profile</em> B2B, landing page kampanye iklan berkonversi tinggi, toko online katalog produk, hingga aplikasi web internal—saya menerapkan standar mutu rekayasa modern tanpa kompromi:
        </p>

        <ul class="space-y-2 mb-6 list-disc pl-6">
            <li><strong>Skor Core Web Vitals Hijau:</strong> Kami memastikan metrik LCP (Largest Contentful Paint), INP (Interaction to Next Paint), dan CLS (Cumulative Layout Shift) berada pada rentang ideal Google, sehingga halaman langsung terbuka dalam hitungan milidetik.</li>
            <li><strong>Struktur Semantik Bersih & Kode Ringan:</strong> Meminimalisasi ketergantungan pada plugin pihak ketiga yang sering menyebabkan kerentanan keamanan dan memperlambat waktu respon server.</li>
            <li><strong>Tata Letak Responsif Mobile-First:</strong> Mengingat lebih dari 80% calon konsumen di Indonesia mengakses internet melalui perangkat ponsel cerdas, navigasi dan tombol konversi selalu diposisikan agar ergonomis dioperasikan dengan satu tangan.</li>
            <li><strong>Kesiapan Fondasi On-Page SEO:</strong> Setiap struktur heading (H1 hingga H4), tautan kanonikal, meta title dinamis, dan sitemap XML telah dipersiapkan sejak hari pertama peluncuran.</li>
        </ul>

        <h3 class="text-2xl font-bold text-slate-900 font-outfit mt-10 mb-4">
            2. Strategi SEO Berbasis Data: Ditemukan di Google, Dipilih di Google Maps
        </h3>

        <p class="mb-4 leading-relaxed">
            Mendapatkan ribuan kunjungan acak bukanlah tujuan utama dari optimasi mesin pencari profesional. Yang Anda butuhkan adalah trafik berkualitas tinggi yang memiliki niat beli (commercial and transactional search intent). Layanan SEO yang saya terapkan menggabungkan penguasaan teknis internal website dan optimasi pencarian lokal:
        </p>

        <p class="mb-4 leading-relaxed">
            <strong>Optimasi SEO Teknis & On-Page:</strong> Menyelidiki hambatan teknis yang menghalangi perayapan Googlebot (crawling & indexing), memperbaiki duplikasi konten, menerapkan arsitektur siloing berbasis entity, serta menyuntikkan Schema Markup JSON-LD terstruktur (LocalBusiness, Organization, Service, FAQPage) agar mesin pencari maupun kecerdasan buatan (seperti Google AI Overviews dan ChatGPT Search) mengenali entitas bisnis Anda dengan otoritas tinggi.
        </p>

        <p class="mb-6 leading-relaxed">
            <strong>Dominasi SEO Lokal & Google Business Profile (GBP):</strong> Bagi penyedia jasa, klinik, restoran, dan bisnis lokal, sebagian besar keputusan pembelian terjadi saat orang mencari kata kunci "terdekat" atau "di [nama kota]". Melalui optimasi profil Google Bisnisku yang menyeluruh—meliputi pemilihan kategori primer yang presisi, pengisian atribut layanan, pembaruan konten visual geotagged, konsistensi data NAP (Name, Address, Phone) di berbagai direktori sitasi lokal, serta integrasi Google Maps Embed otoritatif—bisnis Anda akan muncul di jajaran 3-Pack Google Maps saat calon pelanggan di sekitar Anda siap bertransaksi.
        </p>

        <h3 class="text-2xl font-bold text-slate-900 font-outfit mt-10 mb-4">
            3. Otomasi Alur Kerja Bisnis: Menghapus Pekerjaan Manual yang Menyita Waktu
        </h3>

        <p class="mb-4 leading-relaxed">
            Banyak pemilik usaha dan tim operasional mengeluh bahwa waktu kerja mereka habis hanya untuk menyalin data prospek dari formulir website ke Google Sheets, meneruskan pesan secara manual ke nomor customer service, atau mengirim konfirmasi invoice pembayaran satu per satu. Pertumbuhan bisnis yang sehat tidak boleh terhambat oleh pekerjaan repetitif yang melelahkan.
        </p>

        <p class="mb-4 leading-relaxed">
            Melalui implementasi platform otomasi mandiri (seperti <em>self-hosted n8n</em>), integrasi REST API, dan konfigurasi webhook yang aman, saya membantu merancang sistem otomatisasi terintegrasi:
        </p>

        <ul class="space-y-2 mb-6 list-disc pl-6">
            <li><strong>Notifikasi Instan Multi-Kanal:</strong> Setiap ada pesanan atau inquiry baru di website, pesan detail langsung terkirim ke grup WhatsApp manajemen atau bot Telegram privat dalam hitungan detik.</li>
            <li><strong>Sinkronisasi Data Dua Arah:</strong> Integrasi mulus antara website, basis data inventaris, CRM, dan Google Sheets secara otomatis tanpa risiko salah ketik (human error).</li>
            <li><strong>Penghematan Biaya Operasional:</strong> Menggunakan platform open-source mandiri sehingga bisnis Anda terbebas dari tagihan langganan bulanan platform SaaS luar negeri yang terus melonjak seiring kenaikan volume data.</li>
        </ul>

        <h3 class="text-2xl font-bold text-slate-900 font-outfit mt-10 mb-4">
            4. Pemanfaatan AI Bertanggung Jawab: Akselerasi Nyata Tanpa Halusinasi
        </h3>

        <p class="mb-4 leading-relaxed">
            Kecerdasan buatan (AI) adalah pengganda daya kerja (force multiplier) yang luar biasa jika digunakan secara bijak. Namun, mengandalkan teks buatan AI mentah tanpa kontrol mutu manusia justru dapat merusak reputasi domain Anda di mata algoritma Google (resiko de-indeks Helpful Content Update) serta mengurangi kepercayaan calon pembeli.
        </p>

        <p class="mb-6 leading-relaxed">
            Layanan AI terapan kami memadukan kecerdasan komputasi LLM terkini dengan filosofi <em>Human-in-the-Loop</em>. Kami menggunakan model AI untuk riset topik komprehensif, pemetaan entity gap kompetitor, pembuatan struktur kluster kata kunci, dan pembuatan gambar WebP ringan secara otomatis, namun tetap memastikan kurasi akhir, fakta bisnis, dan sentuhan empati manusia tetap menjadi standar tertinggi sebelum konten dipublikasikan.
        </p>

        <!-- Pertanyaan yang Sering Diajukan (FAQ) -->
        <h3 class="text-2xl font-bold text-slate-900 font-outfit mt-12 mb-6">
            Pertanyaan Umum Seputar Layanan Jasa
        </h3>

        <div class="space-y-4 not-prose mb-12">
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                <div class="font-bold text-slate-900 text-base mb-2">Berapa lama estimasi pengerjaan website atau landing page?</div>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Untuk landing page konversi satu halaman, pengerjaan umumnya memakan waktu 3–5 hari kerja setelah materi siap. Untuk website Company Profile atau Toko Online berkisar antara 7–14 hari kerja tergantung kelengkapan konten dan fitur kustom yang dibutuhkan.
                </p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                <div class="font-bold text-slate-900 text-base mb-2">Apakah saya mendapatkan akses penuh setelah website selesai?</div>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Tentu. Anda mendapatkan hak kepemilikan 100% atas domain, hosting/server, repositori kode sumber, dan panel admin konten. Kami juga menyediakan panduan penggunaan agar Anda atau tim internal dapat memperbarui teks dan foto secara mandiri.
                </p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                <div class="font-bold text-slate-900 text-base mb-2">Apakah bisa melayani konsultasi atau pertemuan langsung di wilayah Solo?</div>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Sangat bisa. Untuk klien di area Solo Raya (Surakarta, Sukoharjo, Karanganyar, Boyolali, Klaten), kita dapat menjadwalkan diskusi tatap muka langsung. Untuk klien di luar kota atau luar pulau, konsultasi dilakukan secara intensif melalui panggilan video Google Meet dan WhatsApp.
                </p>
            </div>
        </div>

    </div>
</article>

<!-- Penutup & Universal Call-to-Action WhatsApp -->
@include('partials.juki-project-inquiry')
@endsection
