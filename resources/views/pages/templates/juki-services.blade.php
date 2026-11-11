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
                    "url": "{{ url('/id/jasa/website') }}"
                }
            },
            {
                "@@type": "Offer",
                "itemOffered": {
                    "@@type": "Service",
                    "name": "SEO & SEO Lokal Google Maps",
                    "url": "{{ url('/id/jasa/seo') }}"
                }
            },
            {
                "@@type": "Offer",
                "itemOffered": {
                    "@@type": "Service",
                    "name": "Otomasi Alur Kerja Bisnis & n8n",
                    "url": "{{ url('/id/jasa/otomasi') }}"
                }
            },
            {
                "@@type": "Offer",
                "itemOffered": {
                    "@@type": "Service",
                    "name": "Solusi & Integrasi AI Bisnis",
                    "url": "{{ url('/id/jasa/ai') }}"
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
    'badge' => 'Solusi Digital Menyeluruh',
    'heading' => "Jasa Pembuatan Website, SEO, Otomasi & AI untuk Pertumbuhan Bisnis Anda",
    'subheadline' => 'Mulai dari website berkecepatan tinggi, dominasi pencarian Google & Maps, otomasi alur kerja n8n, hingga integrasi kecerdasan buatan (AI) yang disesuaikan secara presisi dengan kebutuhan bisnis nyata Anda.',
    'ctaPrimaryText' => 'Konsultasi via WhatsApp',
    'ctaSecondaryText' => 'Kirim Brief Proyek',
    'ctaSecondaryUrl' => url('/contact'),
])

<!-- Grid Kartu Layanan (Model Card Produk) -->
<section class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 pb-4 border-b border-slate-200">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-indigo bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">Katalog Layanan Digital</span>
            <h2 class="text-3xl font-extrabold text-slate-900 font-outfit mt-2">Daftar Layanan & Spesialisasi</h2>
        </div>
        <p class="text-sm text-slate-500 max-w-md">
            Pilih pilar solusi yang sesuai dengan fase bisnis Anda saat ini. Seluruh layanan dibangun dengan fokus pada performa, keamanan, dan hasil yang dapat diukur.
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
                    <a href="{{ url('/id/jasa/website') }}">Pengembangan Web</a>
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
                <a href="{{ url('/id/jasa/website') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-navy hover:bg-slate-800 transition-all shadow-sm">
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
                    <a href="{{ url('/id/jasa/seo') }}">SEO & SEO Lokal</a>
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                    Optimasi teknis dan Google Business Profile berbasis data untuk meraih peringkat atas pencarian dan mendatangkan pelanggan sekitar.
                </p>
                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700">Audit Teknis</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700">GBP / Maps</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700">Entity SEO</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700">Local Citation</span>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-3 border-t border-slate-100 pt-3">
                    Fokus: Ditemukan di Google, Dipilih di Google Maps
                </div>
                <a href="{{ url('/id/jasa/seo') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-navy hover:bg-slate-800 transition-all shadow-sm">
                    Lihat Layanan
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <!-- Card 3: Otomasi Bisnis -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
            <div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-600 text-white flex items-center justify-center font-bold text-xl mb-5 shadow-md shadow-orange-500/20 group-hover:scale-110 transition-transform">
                    03
                </div>
                <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">Business Automation</div>
                <h3 class="text-xl font-bold text-slate-900 font-outfit mb-3 group-hover:text-amber-600 transition-colors">
                    <a href="{{ url('/id/jasa/otomasi') }}">Otomasi Alur Kerja</a>
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                    Sederhanakan alur kerja bisnis — hubungkan tools, hilangkan copy-paste data manual, dan otomatisasi komunikasi pelanggan via n8n.
                </p>
                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-800">n8n Workflow</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-800">Integrasi API</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-800">Auto WhatsApp</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-800">Sync Data</span>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-3 border-t border-slate-100 pt-3">
                    Keunggulan: Self-hosted n8n, aman, hemat biaya langganan
                </div>
                <a href="{{ url('/id/jasa/otomasi') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-navy hover:bg-slate-800 transition-all shadow-sm">
                    Lihat Layanan
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <!-- Card 4: Solusi AI -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
            <div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-purple-600 to-pink-600 text-white flex items-center justify-center font-bold text-xl mb-5 shadow-md shadow-purple-500/20 group-hover:scale-110 transition-transform">
                    04
                </div>
                <div class="text-xs font-semibold text-purple-600 uppercase tracking-wider mb-1">AI Solutions</div>
                <h3 class="text-xl font-bold text-slate-900 font-outfit mb-3 group-hover:text-purple-600 transition-colors">
                    <a href="{{ url('/id/jasa/ai') }}">Solusi Terapan AI</a>
                </h3>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                    Pemanfaatan kecerdasan buatan untuk produksi konten SEO mendalam, riset entitas, sistem chatbot bisnis, dan automasi terukur.
                </p>
                <div class="flex flex-wrap gap-1.5 mb-6">
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700">Content Engine</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700">AI SEO Audit</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700">Gemini & OpenAI</span>
                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-purple-50 text-purple-700">Human-in-Loop</span>
                </div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-3 border-t border-slate-100 pt-3">
                    Prinsip: Dibantu AI, bukan bergantung buta pada AI
                </div>
                <a href="{{ url('/id/jasa/ai') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-navy hover:bg-slate-800 transition-all shadow-sm">
                    Lihat Layanan
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Spesialisasi Lengkap Berdasarkan Kategori (Quick Jump) -->
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 sm:p-8 mb-16">
        <h2 class="text-2xl font-bold text-slate-900 font-outfit mb-6">Daftar Lengkap Layanan Spesialisasi</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <div class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    Pembuatan Website
                </div>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="{{ url('/id/jasa/website') }}" class="hover:text-brand-indigo hover:underline">• Jasa Pembuatan Website Profesional</a></li>
                    <li><a href="{{ url('/id/jasa/website/company-profile') }}" class="hover:text-brand-indigo hover:underline">• Website Company Profile Perusahaan</a></li>
                    <li><a href="{{ url('/id/jasa/website/landing-page') }}" class="hover:text-brand-indigo hover:underline">• Landing Page Iklan & Konversi</a></li>
                    <li><a href="{{ url('/id/jasa/website/toko-online') }}" class="hover:text-brand-indigo hover:underline">• Website Toko Online (E-Commerce)</a></li>
                    <li><a href="{{ url('/id/jasa/website/wordpress') }}" class="hover:text-brand-indigo hover:underline">• Website Bisnis WordPress Ringan</a></li>
                    <li><a href="{{ url('/id/jasa/aplikasi-web') }}" class="hover:text-brand-indigo hover:underline">• Aplikasi Web & Dashboard Kustom</a></li>
                    <li><a href="{{ url('/id/jasa/maintenance-website') }}" class="hover:text-brand-indigo hover:underline">• Jasa Maintenance Website Berkala</a></li>
                    <li><a href="{{ url('/id/jasa/perbaikan-website') }}" class="hover:text-brand-indigo hover:underline">• Jasa Perbaikan Website Error</a></li>
                    <li><a href="{{ url('/id/jasa/website/solo') }}" class="hover:text-brand-indigo hover:underline">• Web Developer Solo Surakarta</a></li>
                </ul>
            </div>

            <div>
                <div class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    SEO & Pencarian Lokal
                </div>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="{{ url('/id/jasa/seo') }}" class="hover:text-emerald-600 hover:underline">• Jasa SEO Teknis & Organik</a></li>
                    <li><a href="{{ url('/id/jasa/seo-lokal') }}" class="hover:text-emerald-600 hover:underline">• Jasa SEO Lokal Google Maps & GBP</a></li>
                    <li><a href="{{ url('/id/jasa/google-maps-embed') }}" class="hover:text-emerald-600 hover:underline">• Jasa Google Maps Embed Booster</a></li>
                    <li><a href="{{ url('/id/jasa/seo') }}#audit" class="hover:text-emerald-600 hover:underline">• Audit On-Page & Arsitektur Silo</a></li>
                    <li><a href="{{ url('/id/jasa/seo') }}#schema" class="hover:text-emerald-600 hover:underline">• Optimasi Schema.org & Semantic Entity</a></li>
                </ul>
            </div>

            <div>
                <div class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                    Otomasi Bisnis & API
                </div>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="{{ url('/id/jasa/otomasi') }}" class="hover:text-amber-600 hover:underline">• Jasa Otomasi Alur Kerja Bisnis</a></li>
                    <li><a href="{{ url('/id/jasa/otomasi/n8n') }}" class="hover:text-amber-600 hover:underline">• Setup & Integrasi Workflow n8n</a></li>
                    <li><a href="{{ url('/id/jasa/otomasi') }}#api" class="hover:text-amber-600 hover:underline">• Integrasi REST API & Webhook HMAC</a></li>
                    <li><a href="{{ url('/id/jasa/otomasi') }}#whatsapp" class="hover:text-amber-600 hover:underline">• Otomasi Notifikasi WhatsApp & Email</a></li>
                </ul>
            </div>

            <div>
                <div class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                    Solusi Terapan AI
                </div>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="{{ url('/id/jasa/ai') }}" class="hover:text-purple-600 hover:underline">• Konsultasi & Integrasi AI Praktis</a></li>
                    <li><a href="{{ url('/id/jasa/ai/content-engine') }}" class="hover:text-purple-600 hover:underline">• AI Content Engine & Programmatic SEO</a></li>
                    <li><a href="{{ url('/id/jasa/ai') }}#audit" class="hover:text-purple-600 hover:underline">• Audit SEO & Analisis Konten AI</a></li>
                    <li><a href="{{ url('/id/jasa/ai') }}#chatbot" class="hover:text-purple-600 hover:underline">• Chatbot & Asisten Internal Bisnis</a></li>
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
            <li><strong>Integrasi Lead Instan:</strong> Setiap prospek yang mengisi formulir di website otomatis diteruskan ke database CRM dan memicu notifikasi instan ke grup WhatsApp tim penjualan dalam waktu kurang dari 3 detik.</li>
            <li><strong>Sinkronisasi Data Multi-Platform:</strong> Memastikan inventaris stok, status pesanan, dan laporan keuangan tetap selaras antar sistem tanpa perlu input ganda.</li>
            <li><strong>Efisiensi Anggaran:</strong> Menggunakan platform self-hosted di cloud VPS Anda sendiri membuat bisnis terbebas dari beban biaya bulanan mahal dari platform otomasi SaaS proprietary seperti Zapier atau Make.</li>
        </ul>

        <h3 class="text-2xl font-bold text-slate-900 font-outfit mt-10 mb-4">
            4. Pemanfaatan AI yang Bertanggung Jawab: Dibantu AI, Bukan Bergantung pada AI
        </h3>

        <p class="mb-4 leading-relaxed">
            Kecerdasan buatan generatif membuka peluang efisiensi riset dan produksi konten yang belum pernah ada sebelumnya. Namun, menerbitkan artikel AI mentah tanpa filter hanya akan merusak reputasi domain, memicu penalti kualitas Google, dan membingungkan pembaca dengan fakta halusinasi.
        </p>

        <p class="mb-6 leading-relaxed">
            Pendekatan Solusi AI yang kami bangun selalu mengedepankan prinsip <em>Human-in-the-Loop</em>. Kami merancang arsitektur pembuatan konten berbasis entity, audit gap konten semantik, serta asisten internal bisnis yang diawasi oleh aturan editorial ketat. AI mempercepat fase riset ide dan draf awal, sementara keakuratan data, validasi penawaran, dan sentuhan otentik manusia tetap menjadi garda terdepan sebelum konten atau sistem berinteraksi dengan publik.
        </p>

        <h3 class="text-2xl font-bold text-slate-900 font-outfit mt-10 mb-4">
            5. Alur Kerja Transparan dan Kepemilikan Aset Digital Penuh di Tangan Anda
        </h3>

        <p class="mb-4 leading-relaxed">
            Kerja sama yang baik selalu berlandaskan transparansi dan rasa saling percaya. Setiap proyek diawali dengan konsultasi kebutuhan mendalam untuk merumuskan ruang lingkup pekerjaan, jadwal pengerjaan terperinci, serta estimasi biaya investasi yang masuk akal tanpa biaya siluman di belakang hari.
        </p>

        <p class="mb-6 leading-relaxed">
            Setelah proyek selesai diuji dan diluncurkan, <strong>seluruh hak kepemilikan akun, akses domain, panel hosting/VPS, repositori kode sumber, dan dokumentasi operasional diserahkan sepenuhnya kepada Anda</strong>. Anda tidak akan pernah terkunci oleh ketergantungan sepihak, karena kami percaya kemitraan yang berkelanjutan tumbuh dari rasa puas atas kualitas hasil kerja.
        </p>

    </div>
</article>

<!-- Call to Action Banner Terstandarisasi -->
@include('partials.juki-project-inquiry')
@endsection
