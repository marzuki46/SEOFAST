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
@php
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            ['@type' => 'Question', 'name' => 'Berapa lama pembuatan website?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Waktu pengerjaan bergantung pada jenis dan ruang lingkup website. Setelah kebutuhan dipetakan, Juki memberikan rencana kerja dan milestone yang jelas.']],
            ['@type' => 'Question', 'name' => 'Apakah website dibuat SEO-friendly?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Ya. Struktur heading, metadata, performa, responsif mobile, sitemap, dan fondasi on-page SEO disiapkan sejak proses pengembangan.']],
            ['@type' => 'Question', 'name' => 'Apakah website bisa terhubung ke WhatsApp?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Bisa. Alur inquiry, tombol WhatsApp, formulir kontak, dan integrasi API dapat disesuaikan dengan kebutuhan bisnis.']],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif
@endsection

@if($isWebsiteService)
@section('styles')
<style>
    .juki-web-landing { --jw-ink:#15253f; --jw-muted:#526174; --jw-gold:#ce9a45; --jw-gold-dark:#9a6b16; --jw-soft:#fbf6eb; --jw-line:#eadfca; color:var(--jw-ink); background:#f8f7f3; font-family:'Plus Jakarta Sans','Inter',ui-sans-serif,system-ui,sans-serif; }
    .juki-web-landing .jw-container { width:min(1180px,calc(100% - 32px)); margin-inline:auto; }
    .juki-web-landing .jw-hero { position:relative; overflow:hidden; background:linear-gradient(135deg,#101d32 0%,#15253f 58%,#213756 100%); color:#fff; }
    .juki-web-landing .jw-hero:after { content:""; position:absolute; width:520px; height:520px; right:-180px; top:-240px; border:1px solid rgba(206,154,69,.3); border-radius:50%; box-shadow:0 0 0 50px rgba(206,154,69,.06),0 0 0 100px rgba(206,154,69,.035); }
    .juki-web-landing .jw-hero-inner { position:relative; z-index:1; display:grid; grid-template-columns:minmax(0,1.1fr) minmax(300px,.9fr); gap:56px; align-items:center; padding:80px 0 72px; }
    .juki-web-landing .jw-eyebrow { display:inline-flex; align-items:center; gap:9px; color:#e8c982; font-size:.78rem; font-weight:800; letter-spacing:.13em; text-transform:uppercase; }
    .juki-web-landing .jw-eyebrow:before { content:""; width:28px; height:2px; background:var(--jw-gold); }
    .juki-web-landing .jw-hero h1 { max-width:720px; margin:18px 0 20px; color:#fff; font-size:clamp(2.3rem,5vw,4.7rem); line-height:1.03; letter-spacing:-.055em; font-weight:800; }
    .juki-web-landing .jw-hero h1 em { color:#e8c982; font-style:normal; }
    .juki-web-landing .jw-lead { max-width:650px; color:#e4e9f0; font-size:clamp(1rem,1.7vw,1.2rem); line-height:1.75; }
    .juki-web-landing .jw-actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:30px; }
    .juki-web-landing .jw-btn { min-height:48px; display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:0 20px; border-radius:10px; font-weight:800; text-decoration:none; transition:transform .2s ease,box-shadow .2s ease,background .2s ease; }
    .juki-web-landing .jw-btn:hover { transform:translateY(-2px); box-shadow:0 12px 26px rgba(206,154,69,.25); }
    .juki-web-landing .jw-btn:focus-visible,.juki-web-landing a:focus-visible { outline:3px solid #fbbf24; outline-offset:3px; }
    .juki-web-landing .jw-btn-primary { background:var(--jw-gold); color:#15253f; }
    .juki-web-landing .jw-btn-ghost { border:1px solid rgba(232,201,130,.5); color:#fff; }
    .juki-web-landing .jw-hero-card { padding:26px; border:1px solid rgba(232,201,130,.25); border-radius:20px; background:rgba(15,23,42,.45); box-shadow:0 24px 70px rgba(2,8,23,.28); backdrop-filter:blur(12px); }
    .juki-web-landing .jw-card-label { color:#e8c982; font-size:.72rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
    .juki-web-landing .jw-hero-card h2 { margin:10px 0 22px; color:#fff; font-size:1.45rem; line-height:1.25; }
    .juki-web-landing .jw-check { display:flex; gap:10px; align-items:flex-start; margin:14px 0; color:#e4e9f0; line-height:1.5; }
    .juki-web-landing .jw-check svg { flex:0 0 20px; margin-top:2px; color:#e8c982; }
    .juki-web-landing .jw-proof { background:#fff; border-bottom:1px solid var(--jw-line); }
    .juki-web-landing .jw-proof-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; padding:24px 0; }
    .juki-web-landing .jw-proof strong { display:block; font-size:1rem; }
    .juki-web-landing .jw-proof span { display:block; margin-top:4px; color:var(--jw-muted); font-size:.82rem; }
    .juki-web-landing .jw-section { padding:82px 0; }
    .juki-web-landing .jw-section-alt { background:#fff; }
    .juki-web-landing .jw-kicker { color:var(--jw-gold-dark); font-size:.75rem; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
    .juki-web-landing .jw-section h2 { max-width:780px; margin:10px 0 16px; font-size:clamp(1.8rem,3.5vw,3rem); line-height:1.1; letter-spacing:-.04em; }
    .juki-web-landing .jw-intro { max-width:720px; color:var(--jw-muted); font-size:1.06rem; line-height:1.75; }
    .juki-web-landing .jw-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-top:32px; }
    .juki-web-landing .jw-service { display:flex; flex-direction:column; padding:22px; border:1px solid #e2e8f0; border-radius:16px; background:#fff; box-shadow:0 8px 24px rgba(15,23,42,.05); transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease; }
    .juki-web-landing .jw-service:hover { transform:translateY(-5px); border-color:#d4ad64; box-shadow:0 18px 36px rgba(154,107,22,.12); }
    .juki-web-landing .jw-service-icon { width:42px; height:42px; display:grid; place-items:center; margin-bottom:18px; border-radius:12px; background:var(--jw-soft); color:var(--jw-gold-dark); }
    .juki-web-landing .jw-service h3 { margin:0 0 8px; font-size:1.05rem; }
    .juki-web-landing .jw-service p { margin:0; color:var(--jw-muted); font-size:.9rem; line-height:1.65; }
    .juki-web-landing .jw-aida { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-top:34px; }
    .juki-web-landing .jw-step { padding:22px; border-top:3px solid var(--jw-gold); background:#f8f7f3; }
    .juki-web-landing .jw-step b { display:block; color:var(--jw-gold-dark); font-size:.75rem; letter-spacing:.12em; text-transform:uppercase; }
    .juki-web-landing .jw-step h3 { margin:10px 0 7px; font-size:1.05rem; }
    .juki-web-landing .jw-step p { margin:0; color:var(--jw-muted); font-size:.9rem; line-height:1.6; }
    .juki-web-landing .jw-columns { display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:start; }
    .juki-web-landing .jw-longform { max-width:820px; }
    .juki-web-landing .jw-longform h2 { margin-top:42px; }
    .juki-web-landing .jw-longform h2:first-of-type { margin-top:28px; }
    .juki-web-landing .jw-longform h3 { margin:28px 0 10px; font-size:1.3rem; line-height:1.25; letter-spacing:-.02em; }
    .juki-web-landing .jw-longform p { max-width:780px; margin:0 0 16px; color:var(--jw-muted); font-size:1.02rem; line-height:1.85; }
    .juki-web-landing .jw-longform a { color:#0369a1; font-weight:700; text-decoration:underline; text-decoration-thickness:1px; text-underline-offset:3px; }
    .juki-web-landing .jw-list { display:grid; gap:13px; margin:24px 0 0; padding:0; list-style:none; }
    .juki-web-landing .jw-list li { display:flex; gap:11px; color:var(--jw-muted); line-height:1.55; }
    .juki-web-landing .jw-list svg { flex:0 0 20px; color:var(--jw-gold-dark); margin-top:2px; }
    .juki-web-landing .jw-related { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-top:28px; }
    .juki-web-landing .jw-related a { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:18px; border:1px solid #eadfca; border-radius:12px; background:var(--jw-soft); color:var(--jw-ink); font-weight:800; text-decoration:none; }
    .juki-web-landing .jw-related a:hover { background:#f4ead5; }
    .juki-web-landing .jw-faq { display:grid; gap:12px; max-width:820px; margin-top:28px; }
    .juki-web-landing details { border:1px solid #e2e8f0; border-radius:12px; background:#fff; }
    .juki-web-landing summary { cursor:pointer; padding:18px 20px; font-weight:800; list-style-position:inside; }
    .juki-web-landing details p { margin:0; padding:0 20px 20px; color:var(--jw-muted); line-height:1.7; }
    .juki-web-landing .jw-final { padding:70px 0; background:var(--jw-ink); color:#fff; text-align:center; }
    .juki-web-landing .jw-final h2 { max-width:700px; margin:10px auto 16px; color:#fff; }
    .juki-web-landing .jw-final p { max-width:630px; margin:0 auto; color:#cbd5e1; line-height:1.7; }
    @media (max-width:900px) { .juki-web-landing .jw-hero-inner,.juki-web-landing .jw-columns { grid-template-columns:1fr; gap:32px; } .juki-web-landing .jw-grid,.juki-web-landing .jw-aida { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:620px) { .juki-web-landing .jw-container { width:min(100% - 24px,1180px); } .juki-web-landing .jw-hero-inner { padding:58px 0 52px; } .juki-web-landing .jw-proof-grid,.juki-web-landing .jw-grid,.juki-web-landing .jw-aida,.juki-web-landing .jw-related { grid-template-columns:1fr; } .juki-web-landing .jw-section { padding:58px 0; } .juki-web-landing .jw-actions .jw-btn { width:100%; } }
    @media (prefers-reduced-motion:reduce) { .juki-web-landing *,.juki-web-landing *:before,.juki-web-landing *:after { scroll-behavior:auto!important; transition:none!important; } }
</style>
@endsection
@endif

@section('content')
@if($isWebsiteService)
<main class="juki-web-landing">
    <section class="jw-hero">
        <div class="jw-container jw-hero-inner">
            <div>
                <span class="jw-eyebrow">Web engineering · SEO · conversion</span>
                <h1>Website yang terlihat <em>profesional</em> dan bekerja untuk bisnis Anda.</h1>
                <p class="jw-lead">Jasa pembuatan website di Solo dan Surakarta untuk membangun kepercayaan, mendapatkan inquiry, dan mengubah pencarian Google menjadi peluang bisnis.</p>
                <div class="jw-actions">
                    <a class="jw-btn jw-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi jasa pembuatan website untuk bisnis saya.') }}">Konsultasi via WhatsApp <span aria-hidden="true">→</span></a>
                    <a class="jw-btn jw-btn-ghost" href="{{ url('/contact') }}">Kirim brief proyek</a>
                </div>
            </div>
            <aside class="jw-hero-card" aria-label="Keunggulan layanan website">
                <span class="jw-card-label">Fondasi yang dibangun sejak awal</span>
                <h2>Bukan sekadar website. Ini aset penjualan digital Anda.</h2>
                <div class="jw-check"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Struktur SEO on-page dan konten yang mudah dipahami Google.</span></div>
                <div class="jw-check"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Pengalaman mobile-first dengan CTA yang jelas.</span></div>
                <div class="jw-check"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Kode bersih, aman, cepat, dan siap dikembangkan.</span></div>
            </aside>
        </div>
    </section>
    <section class="jw-proof" aria-label="Fokus layanan">
        <div class="jw-container jw-proof-grid"><div><strong>Mobile-first</strong><span>Nyaman di setiap layar</span></div><div><strong>SEO-ready</strong><span>Struktur siap ditemukan</span></div><div><strong>Conversion-led</strong><span>CTA menuju inquiry</span></div><div><strong>Ownership penuh</strong><span>Aset tetap milik bisnis</span></div></div>
    </section>
    <section class="jw-section">
        <div class="jw-container"><span class="jw-kicker">Satu website. Satu tujuan: lebih banyak inquiry.</span><h2>Yang Anda dapatkan bukan halaman cantik—tetapi aset bisnis yang siap bekerja.</h2><p class="jw-intro">Kami merancang pesan, struktur, tampilan, dan CTA berdasarkan cara calon pelanggan memilih jasa. Hasilnya terlihat profesional, mudah dipahami, dan punya jalur jelas menuju WhatsApp atau inquiry.</p>
            <div class="jw-grid">
                <article class="jw-service"><div class="jw-service-icon"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M8 21h8M12 18v3"/></svg></div><h3>Terlihat kredibel</h3><p>Visual premium, pesan yang tajam, dan struktur yang membuat calon pelanggan yakin untuk bertanya.</p></article>
                <article class="jw-service"><div class="jw-service-icon"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h16v14H4z"/><path d="M8 9h8M8 13h5"/></svg></div><h3>Siap ditemukan</h3><p>Fondasi SEO, heading, metadata, schema, dan internal link dibangun untuk search intent yang tepat.</p></article>
                <article class="jw-service"><div class="jw-service-icon"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 7h12l1 13H5L6 7Z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg></div><h3>Mudah dihubungi</h3><p>CTA WhatsApp, formulir, dan alur inquiry ditempatkan saat pengunjung sudah siap mengambil tindakan.</p></article>
                <article class="jw-service"><div class="jw-service-icon"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16v16H4z"/><path d="M8 8h3v3H8zM13 8h3M13 12h3M8 16h8"/></svg></div><h3>Siap dikembangkan</h3><p>Mulai dari website layanan hingga sistem informasi, fondasinya dapat tumbuh mengikuti bisnis Anda.</p></article>
            </div>
        </div>
    </section>
    <section class="jw-section jw-section-alt">
        <div class="jw-container"><span class="jw-kicker">Pilih titik mulai Anda</span><h2>Solusi website untuk kebutuhan yang berbeda.</h2><div class="jw-grid"><article class="jw-service"><span class="jw-card-label">Mulai Rp 2,5 juta</span><h3>Website bisnis</h3><p>Company profile, landing page, atau toko online untuk bisnis yang ingin tampil profesional dan mulai menerima inquiry.</p><a class="jw-btn jw-btn-primary" style="margin-top:20px" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya tertarik Paket Website mulai Rp 2.500.000.') }}">Tanya paket ini →</a></article><article class="jw-service"><span class="jw-card-label">Mulai Rp 10 juta</span><h3>Sistem informasi</h3><p>CRM, ERP, inventory, POS, dashboard, atau aplikasi web custom yang mengikuti proses kerja perusahaan.</p><a class="jw-btn jw-btn-primary" style="margin-top:20px" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi sistem informasi bisnis.') }}">Diskusikan kebutuhan →</a></article><article class="jw-service"><span class="jw-card-label">Custom / enterprise</span><h3>Solusi terintegrasi</h3><p>Website, SEO, otomasi, AI, dan integrasi API untuk kebutuhan khusus dengan scope yang disusun bersama.</p><a class="jw-btn jw-btn-primary" style="margin-top:20px" href="{{ url('/contact') }}">Kirim brief proyek →</a></article></div></div>
    </section>
    <section class="jw-section jw-section-alt" aria-label="Alur keputusan pelanggan">
        <div class="jw-container"><span class="jw-kicker">Mengapa halaman ini efektif</span><h2>Alur AIDA yang terasa natural, bukan teori di atas kertas.</h2><div class="jw-aida"><article class="jw-step"><b>01 · Attention</b><h3>Masalah langsung terasa</h3><p>Headline berbicara kepada bisnis yang ingin dipercaya dan mendapatkan inquiry.</p></article><article class="jw-step"><b>02 · Interest</b><h3>Solusinya mudah dipahami</h3><p>Kartu manfaat menjelaskan apa yang dibangun dan untuk siapa.</p></article><article class="jw-step"><b>03 · Desire</b><h3>Nilainya menjadi nyata</h3><p>Paket, harga awal, SEO, proses, dan kepemilikan mengurangi keraguan.</p></article><article class="jw-step"><b>04 · Action</b><h3>CTA ada di saat yang tepat</h3><p>Pengunjung dapat langsung menghubungi Juki tanpa mencari-cari tombol.</p></article></div></div>
    </section>
    <section class="jw-section"><div class="jw-container jw-columns"><div><span class="jw-kicker">Dibangun untuk ditemukan dan dipercaya</span><h2>Premium di mata pelanggan. Sehat di mata Google.</h2><p class="jw-intro">Keindahan visual tidak berdiri sendiri. Setiap halaman disusun agar mudah dipindai, cepat digunakan, dan memiliki konteks SEO yang jelas.</p></div><ul class="jw-list"><li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Mobile-first, aksesibel, dan nyaman dibaca.</span></li><li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Meta title, schema, sitemap, canonical, dan internal link.</span></li><li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Struktur cepat, aman, dan siap dikembangkan.</span></li><li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Integrasi WhatsApp, formulir, API, dan kebutuhan bisnis.</span></li></ul></div></section>
    <section class="jw-section"><div class="jw-container"><span class="jw-kicker">Cara kerja sederhana</span><h2>Dari ide menjadi website yang siap dipakai.</h2><div class="jw-aida"><article class="jw-step"><b>01</b><h3>Konsultasi</h3><p>Ceritakan bisnis, target pelanggan, layanan, dan tujuan utama Anda.</p></article><article class="jw-step"><b>02</b><h3>Rancang</h3><p>Kami susun pesan, struktur halaman, visual, dan kebutuhan teknis.</p></article><article class="jw-step"><b>03</b><h3>Bangun</h3><p>Website dikembangkan responsif, SEO-ready, dan diuji sebelum live.</p></article><article class="jw-step"><b>04</b><h3>Tumbuhkan</h3><p>Setelah live, lanjutkan dengan SEO, konten, otomasi, atau AI.</p></article></div></div></section>
    <section class="jw-section jw-section-alt"><div class="jw-container"><span class="jw-kicker">Layanan yang bisa mengikuti</span><h2>Website adalah fondasi. Pertumbuhan berikutnya ada di sini.</h2><p class="jw-intro">Hubungkan website Anda dengan layanan yang relevan ketika bisnis mulai tumbuh.</p><div class="jw-related">@foreach($relatedLinks as $rl)<a href="{{ url($rl['url']) }}"><span>{{ $rl['title'] }}</span><span aria-hidden="true">↗</span></a>@endforeach</div></div></section>
    <section class="jw-section jw-section-alt"><div class="jw-container"><span class="jw-kicker">Pertanyaan umum</span><h2>Masih ingin memastikan sebelum mulai?</h2><div class="jw-faq"><details><summary>Berapa harga website?</summary><p>Paket website mulai Rp 2.500.000. Sistem informasi mulai Rp 10.000.000. Kebutuhan custom dibahas berdasarkan fitur dan scope proyek.</p></details><details><summary>Berapa lama pengerjaannya?</summary><p>Website standar dapat diselesaikan maksimal dua minggu. Sistem modular sekitar satu bulan, sedangkan proyek custom menyesuaikan kompleksitas.</p></details><details><summary>Apakah website sudah SEO-friendly?</summary><p>Ya. Struktur heading, metadata, schema, sitemap, performa, mobile-first, dan internal link disiapkan sejak awal.</p></details></div></div></section>
    <section class="jw-final"><div class="jw-container"><span class="jw-kicker" style="color:#e8c982">Action berikutnya</span><h2>Website Anda seharusnya membantu menjual, bukan hanya terlihat ada.</h2><p>Ceritakan kebutuhan Anda. Kami bantu menentukan solusi yang paling masuk akal untuk bisnis, anggaran, dan target pelanggan Anda.</p><div class="jw-actions" style="justify-content:center"><a class="jw-btn jw-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi jasa pembuatan website untuk bisnis saya.') }}">Konsultasi via WhatsApp <span aria-hidden="true">→</span></a><a class="jw-btn jw-btn-ghost" href="{{ url('/contact') }}">Kirim brief proyek</a></div></div></section>
</main>
@else
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
@endif
@endsection
