@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;
    $siteName = SystemSetting::get('site_name', config('app.name', 'Juki'));
    $logoUrl = SystemSetting::get('logo_url', 'https://juki.eu.org/storage/content-images/xjuki-website-developer-solo-landscape-1785293658.webp.pagespeed.ic.ehTKUU0dbo.webp');
    $logoAlt = SystemSetting::get('logo_alt', 'Juki Website Developer Solo');
    $waNumber = SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
    $canonicalUrl = url('/' . ltrim($page->slug, '/'));
    $p = $serviceParams ?? [];
    $isWebsiteService = trim($page->slug, '/') === 'jasa/website';
    $seoTitle = $isWebsiteService
        ? 'Jasa Pembuatan Website Profesional di Solo | Juki'
        : ($page->meta_title ?: $page->title) . ' — ' . $siteName;
    $seoDescription = $isWebsiteService
        ? 'Jasa pembuatan website profesional di Solo dan Surakarta untuk company profile, landing page, toko online, dan aplikasi web. Responsif, cepat, aman, dan SEO-friendly.'
        : ($page->meta_description ?: ($p['lead'] ?? ''));
    $relatedLinks = $isWebsiteService ? [
        ['url' => '/jasa/seo', 'title' => 'Jasa SEO & Pencarian Lokal'],
        ['url' => '/jasa/otomasi', 'title' => 'Otomasi Bisnis & Integrasi API'],
        ['url' => '/jasa/ai', 'title' => 'Solusi AI untuk Bisnis'],
        ['url' => '/contact', 'title' => 'Konsultasi Proyek Website'],
    ] : ($p['related_links'] ?? []);
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical_url', $canonicalUrl)

@section('head_extra')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Service",
    "name": "{{ $page->title }}",
    "serviceType": "{{ $p['category'] ?? 'Digital Development & Marketing Services' }}",
    "provider": {
        "@type": "Person",
        "name": "Tri Marzuki",
        "url": "{{ url('/') }}",
        "telephone": "+{{ $waNumber }}"
    },
    "areaServed": "Indonesia",
    "description": "{{ $seoDescription }}",
    "url": "{{ $canonicalUrl }}"
}
</script>
@if($isWebsiteService)
<script type="application/ld+json">@json([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
        ['@type' => 'Question', 'name' => 'Berapa lama pembuatan website?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Waktu pengerjaan bergantung pada jenis dan ruang lingkup website. Setelah kebutuhan dipetakan, Juki memberikan rencana kerja dan milestone yang jelas.']],
        ['@type' => 'Question', 'name' => 'Apakah website dibuat SEO-friendly?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Ya. Struktur heading, metadata, performa, responsif mobile, sitemap, dan fondasi on-page SEO disiapkan sejak proses pengembangan.']],
        ['@type' => 'Question', 'name' => 'Apakah website bisa terhubung ke WhatsApp?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Bisa. Alur inquiry, tombol WhatsApp, formulir kontak, dan integrasi API dapat disesuaikan dengan kebutuhan bisnis.']],
    ],
])</script>
@endif
@endsection

@section('content')
<!-- Hero Section (Konsisten dengan Juki Hero + Produk) -->
@include('partials.juki-hero', [
    'badge' => $p['badge'] ?? 'Layanan Spesialisasi Digital',
     'heading' => $isWebsiteService ? 'Jasa Pembuatan Website Profesional untuk Bisnis Anda' : ($p['heading'] ?? $page->title),
     'subheadline' => $isWebsiteService ? 'Bangun website cepat, responsif, aman, dan siap mendukung SEO serta konversi bisnis di Solo, Surakarta, dan seluruh Indonesia.' : ($p['lead'] ?? $page->meta_description),
    'ctaPrimaryText' => 'Konsultasi via WhatsApp',
    'ctaPrimaryUrl' => 'https://wa.me/' . $waNumber . '?text=' . urlencode('Halo Tri Marzuki, saya tertarik konsultasi layanan ' . ($page->title)),
    'ctaSecondaryText' => 'Kirim Inquiry / RFQ',
    'ctaSecondaryUrl' => url('/contact'),
])

<!-- Navigasi Breadcrumb & Parent Service -->
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
    <nav class="flex items-center text-xs sm:text-sm text-slate-500 gap-2 flex-wrap">
        <a href="{{ url('/') }}" class="hover:text-brand-indigo transition-colors">Beranda</a>
        <span>/</span>
        <a href="{{ url('/jasa') }}" class="hover:text-brand-indigo transition-colors">Layanan</a>
        @if(!empty($p['parent_url']) && !empty($p['parent_title']))
            <span>/</span>
            <a href="{{ url($p['parent_url']) }}" class="hover:text-brand-indigo transition-colors">{{ $p['parent_title'] }}</a>
        @endif
        <span>/</span>
        <span class="text-slate-900 font-medium">{{ $page->title }}</span>
    </nav>
</div>

<!-- Key Highlights / 4 Value Props (Card Grid) -->
@if(!empty($p['value_props']))
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($p['value_props'] as $idx => $vp)
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="text-xs font-bold text-brand-indigo uppercase tracking-wider mb-1">0{{ $idx + 1 }} · {{ $vp['tag'] }}</div>
            <div class="font-bold text-slate-900 text-sm mb-1 font-outfit">{{ $vp['title'] }}</div>
            <div class="text-xs text-slate-500 leading-relaxed">{{ $vp['desc'] }}</div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Article Body (1000+ Kata, Mengikuti Kaidah 1 H1 & Subheading Terstruktur) -->
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-16 text-slate-700 leading-relaxed font-sans">
    <div class="prose prose-slate prose-lg max-w-none">
        @if($isWebsiteService)
            <p class="lead"><strong>Website bisnis bukan sekadar tampilan online.</strong> Website yang tepat membantu calon pelanggan menemukan layanan Anda, memahami nilai yang ditawarkan, dan mengambil tindakan melalui WhatsApp atau formulir inquiry. Karena itu, Juki membangun website dengan kombinasi desain, rekayasa perangkat lunak, performa, keamanan, dan SEO.</p>

            <h2>Mengapa Bisnis Membutuhkan Website yang Serius?</h2>
            <p>Calon pelanggan biasanya memeriksa website sebelum menghubungi penyedia jasa. Website yang lambat, sulit digunakan di ponsel, atau tidak menjawab kebutuhan pengunjung dapat mengurangi kepercayaan dan membuat peluang hilang. Website profesional memberi bisnis Anda pusat informasi yang kredibel, mudah diperbarui, dan dapat ditemukan melalui mesin pencari.</p>

            <h2>Website yang Mengubah Perhatian Menjadi Inquiry</h2>
            <p>Juki menggunakan pendekatan <strong>AIDA</strong> agar halaman tidak hanya mengejar kunjungan, tetapi juga mengarahkan pengunjung menuju keputusan:</p>
            <h3>Attention — Menarik Perhatian yang Tepat</h3>
            <p>Pesan utama, struktur visual, dan headline menjelaskan layanan Anda sejak awal. Pengunjung langsung memahami siapa yang Anda bantu dan solusi apa yang tersedia.</p>
            <h3>Interest — Menjawab Kebutuhan Pengunjung</h3>
            <p>Konten membahas kebutuhan nyata bisnis: company profile, landing page, toko online, sistem informasi, integrasi WhatsApp, dan aplikasi web custom. Struktur informasi dibuat mudah dipindai di desktop maupun smartphone.</p>
            <h3>Desire — Menunjukkan Manfaat dan Kepercayaan</h3>
            <p>Website dirancang dengan fondasi SEO on-page, kode yang terawat, performa yang baik, dan alur konversi yang jelas. Hasilnya adalah aset digital yang dapat mendukung pemasaran, bukan sekadar brosur online.</p>
            <h3>Action — Mengarahkan Pengunjung Menghubungi Anda</h3>
            <p>Setiap halaman penting memiliki ajakan yang jelas untuk konsultasi, mengirim brief proyek, atau meminta penawaran. Anda dapat mengarahkan calon pelanggan ke WhatsApp atau halaman kontak sesuai alur penjualan bisnis.</p>

            <h2>Jenis Website yang Dapat Kami Bangun</h2>
            <h3>Website Company Profile</h3>
            <p>Cocok untuk perusahaan, kontraktor, distributor, konsultan, dan penyedia jasa profesional yang membutuhkan kredibilitas digital dan struktur informasi yang rapi.</p>
            <h3>Landing Page Bisnis</h3>
            <p>Halaman fokus untuk kampanye iklan, promosi layanan, atau pengumpulan leads dengan pesan, bukti manfaat, dan CTA yang terarah.</p>
            <h3>Toko Online dan Katalog Produk</h3>
            <p>Bangun katalog produk yang mudah dikelola dengan alur order melalui WhatsApp atau integrasi pembayaran sesuai kebutuhan bisnis.</p>
            <h3>Aplikasi Web dan Sistem Informasi</h3>
            <p>Untuk kebutuhan operasional yang lebih kompleks, kami dapat membangun dashboard, CRM, ERP ringan, portal pelanggan, dan aplikasi web custom menggunakan teknologi yang sesuai.</p>

            <h2>Fondasi SEO dan Performa Sejak Awal</h2>
            <ul>
                <li>Struktur heading dan konten yang mengikuti search intent.</li>
                <li>Meta title, meta description, canonical, sitemap, dan schema markup.</li>
                <li>Desain mobile-first dan navigasi yang mudah digunakan.</li>
                <li>Optimasi kecepatan, gambar, dan Core Web Vitals.</li>
                <li>Struktur internal link menuju layanan yang relevan.</li>
                <li>Kode yang bersih dan perhatian pada keamanan formulir serta API.</li>
            </ul>

            <h2>Siap Membuat Website yang Mendukung Pertumbuhan Bisnis?</h2>
            <p>Jika Anda membutuhkan <strong>jasa pembuatan website di Solo atau Surakarta</strong>, sampaikan jenis bisnis, tujuan website, fitur yang dibutuhkan, dan target pelanggan Anda. Juki akan membantu memetakan solusi yang paling sesuai, mulai dari website company profile hingga aplikasi web custom.</p>
            <p><a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi jasa pembuatan website untuk bisnis saya.') }}"><strong>Konsultasikan proyek website Anda melalui WhatsApp</strong></a> atau kirim brief melalui halaman kontak.</p>
        @else
            {!! $page->html_content ?: $page->content !!}
        @endif
    </div>

    <!-- Related Services Nav -->
    @if(!empty($relatedLinks))
    <div class="mt-12 pt-8 border-t border-slate-200">
        <h3 class="text-xl font-bold text-slate-900 font-outfit mb-4">Layanan Terkait Lainnya</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($relatedLinks as $rl)
            <a href="{{ url($rl['url']) }}" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-brand-indigo hover:bg-slate-50 transition-all text-sm font-medium text-slate-800 group">
                <span>{{ $rl['title'] }}</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-brand-indigo group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</article>

<!-- Call to Action Banner Terstandarisasi -->
@include('partials.juki-project-inquiry')
@endsection
