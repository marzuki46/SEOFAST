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
        <div class="jw-container"><span class="jw-kicker">Solusi sesuai tahap bisnis</span><h2>Website yang dirancang untuk tujuan yang jelas.</h2><p class="jw-intro">Mulai dari halaman kredibilitas hingga sistem operasional custom, setiap solusi dibangun berdasarkan kebutuhan pelanggan dan alur bisnis Anda.</p>
            <div class="jw-grid">
                <article class="jw-service"><div class="jw-service-icon"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M8 21h8M12 18v3"/></svg></div><h3>Company profile</h3><p>Bangun kredibilitas untuk perusahaan, kontraktor, konsultan, dan jasa profesional.</p></article>
                <article class="jw-service"><div class="jw-service-icon"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h16v14H4z"/><path d="M8 9h8M8 13h5"/></svg></div><h3>Landing page</h3><p>Fokus pada satu penawaran dengan alur AIDA dan CTA untuk kampanye atau lead generation.</p></article>
                <article class="jw-service"><div class="jw-service-icon"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 7h12l1 13H5L6 7Z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg></div><h3>Toko online</h3><p>Katalog produk, order WhatsApp, dan alur transaksi yang lebih mudah dikelola.</p></article>
                <article class="jw-service"><div class="jw-service-icon"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16v16H4z"/><path d="M8 8h3v3H8zM13 8h3M13 12h3M8 16h8"/></svg></div><h3>Aplikasi web custom</h3><p>Dashboard, CRM, sistem informasi, dan integrasi API untuk proses kerja yang lebih rapi.</p></article>
            </div>
        </div>
    </section>
    <section class="jw-section jw-section-alt">
        <div class="jw-container"><span class="jw-kicker">Cara halaman ini menjual</span><h2>Alur AIDA yang memandu pengunjung sampai siap menghubungi Anda.</h2><div class="jw-aida"><article class="jw-step"><b>01 · Attention</b><h3>Pesan langsung jelas</h3><p>Pengunjung langsung memahami layanan, target bisnis, dan manfaat utama website.</p></article><article class="jw-step"><b>02 · Interest</b><h3>Masalahnya terjawab</h3><p>Konten membahas kebutuhan website, SEO, performa, mobile, dan integrasi bisnis.</p></article><article class="jw-step"><b>03 · Desire</b><h3>Nilai makin terasa</h3><p>Struktur, keamanan, kepemilikan aset, dan solusi yang relevan membangun keyakinan.</p></article><article class="jw-step"><b>04 · Action</b><h3>Langkah berikutnya mudah</h3><p>CTA WhatsApp dan kontak tersedia saat calon pelanggan sudah siap berdiskusi.</p></article></div></div>
    </section>
    <section class="jw-section">
        <div class="jw-container jw-columns"><div><span class="jw-kicker">SEO dan engineering</span><h2>Fondasi teknis yang tidak terlihat, tetapi terasa hasilnya.</h2><p class="jw-intro">Desain yang indah perlu didukung struktur yang sehat agar cepat, mudah dirayapi, dan mudah dikembangkan.</p></div><ul class="jw-list"><li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Metadata, canonical, heading, schema, sitemap, dan struktur internal link.</span></li><li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Responsif mobile, aksesibilitas, fokus keyboard, dan kontras yang nyaman.</span></li><li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Fondasi Core Web Vitals, optimasi gambar, dan kode yang terawat.</span></li><li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Integrasi WhatsApp, formulir inquiry, API, dan kebutuhan operasional.</span></li></ul></div>
    </section>
    <section class="jw-section">
        <div class="jw-container jw-longform">
            <span class="jw-kicker">Untuk bisnis yang ingin tumbuh</span>
            <h2>Jangan biarkan calon pelanggan pergi ke kompetitor karena website Anda tidak meyakinkan</h2>
            <p class="jw-intro">Orang mencari bisnis Anda di Google sebelum menghubungi. Dalam beberapa detik, mereka menilai apakah Anda terlihat profesional, apakah layanan Anda relevan, dan apakah mudah untuk memulai percakapan. Kami membangun website yang menjawab tiga hal itu dengan cepat: apa yang Anda tawarkan, mengapa harus percaya, dan apa langkah berikutnya.</p>
            <h3>Ubah kunjungan menjadi kepercayaan</h3>
            <p>Website company profile bukan halaman formalitas. Ia adalah sales representative yang bekerja saat calon pelanggan mencari Anda di Google, melihat rekomendasi dari teman, atau membuka tautan dari WhatsApp. Konten, visual, portofolio, layanan, area pelayanan, dan CTA disusun agar pengunjung tidak perlu menebak-nebak siapa Anda dan bagaimana cara membeli.</p>
            <p>Halaman profil perusahaan dapat memuat cerita bisnis, layanan utama, portofolio, proses kerja, pertanyaan umum, dan ajakan konsultasi. Untuk perusahaan B2B, kontraktor, konsultan, distributor, atau penyedia jasa profesional, struktur ini membantu calon klien memahami kemampuan Anda sebelum percakapan penjualan dimulai. Website juga dapat menjadi pusat rujukan ketika calon pelanggan menemukan bisnis melalui Google Maps, media sosial, iklan, atau rekomendasi.</p>
            <h3>Landing page yang menjual satu penawaran</h3>
            <p>Jika Anda sedang menjual satu layanan atau menjalankan iklan, halaman yang terlalu ramai akan mengurangi fokus. Kami membuat landing page dengan satu pesan utama, satu target audiens, dan satu CTA. Pengunjung diarahkan dari masalah yang mereka rasakan menuju manfaat yang mereka inginkan, lalu diberi alasan yang masuk akal untuk menghubungi Anda sekarang.</p>
            <p>AIDA tidak ditampilkan sebagai teori kosong. <strong>Attention</strong> hadir melalui headline yang spesifik. <strong>Interest</strong> muncul dari penjelasan masalah dan solusi. <strong>Desire</strong> diperkuat manfaat, proses, teknologi, dan bukti yang tersedia. <strong>Action</strong> ditutup dengan CTA WhatsApp atau inquiry yang mudah dilakukan tanpa formulir berbelit.</p>
            <h3>Toko online dan katalog produk yang mudah digunakan</h3>
            <p>Bisnis yang menjual produk membutuhkan lebih dari sekadar daftar barang. Pelanggan perlu menemukan produk, memahami manfaat dan detailnya, melihat cara pemesanan, lalu menyelesaikan transaksi tanpa kebingungan. Toko online dapat dirancang dengan kategori yang jelas, pencarian, halaman produk, tombol WhatsApp, formulir inquiry, atau integrasi pembayaran sesuai alur bisnis.</p>
            <p>Struktur katalog juga membantu SEO karena setiap kategori dan produk dapat memiliki judul, deskripsi, URL, gambar, dan data terstruktur yang relevan. Pada tahap perencanaan, kami memetakan prioritas halaman agar produk penting tidak terkubur di dalam navigasi. Hasilnya adalah pengalaman belanja yang lebih terarah sekaligus fondasi konten yang bisa dikembangkan.</p>
            <h3>Aplikasi web custom untuk kebutuhan operasional</h3>
            <p>Jika proses bisnis mulai bergantung pada spreadsheet, chat, dan pekerjaan salin-tempel, website biasa mungkin belum cukup. Aplikasi web custom dapat membantu mengatur data pelanggan, pesanan, tugas, laporan, approval, dashboard, atau integrasi antarplatform. Sistem seperti CRM, ERP ringan, portal pelanggan, dan dashboard internal dirancang berdasarkan proses kerja yang benar-benar digunakan tim.</p>
            <p>Pengembangan aplikasi dimulai dari pemetaan peran pengguna, data yang perlu dikelola, aturan akses, dan alur kerja. Dengan pendekatan ini, fitur tidak dibuat hanya karena terlihat menarik, tetapi karena menyelesaikan masalah operasional. Teknologi seperti Laravel, CodeIgniter, PHP modern, database relasional, REST API, dan webhook dapat dipilih sesuai kebutuhan keamanan, kecepatan, dan pemeliharaan.</p>
            <h2>Ditemukan Google, dipahami manusia, menghasilkan inquiry</h2>
            <p>SEO bukan pekerjaan yang ditempelkan setelah website selesai. Struktur teknis dan editorial perlu dipikirkan sejak awal agar mesin pencari dapat memahami hubungan antarhalaman, sementara pengunjung mendapatkan jawaban yang relevan. Kami menyiapkan hierarki heading yang logis, meta title, meta description, URL yang mudah dibaca, canonical URL, sitemap, Open Graph, dan schema markup sesuai jenis halaman.</p>
            <p>Untuk bisnis lokal, informasi wilayah layanan, profil bisnis, halaman kontak, dan sinyal kepercayaan perlu ditulis secara konsisten. Untuk website jasa, halaman utama dapat menjadi hub yang mengarahkan pengunjung ke halaman SEO, otomasi, AI, atau layanan spesifik lainnya. Internal link dengan anchor yang deskriptif membantu pengguna menemukan layanan lanjutan dan membantu mesin pencari memahami topical relevance.</p>
            <p>Konten juga perlu menjawab search intent. Orang yang mencari “jasa pembuatan website” mungkin sedang mencari vendor. Orang yang mencari “harga website company profile” membutuhkan informasi pertimbangan dan penawaran. Sementara pencarian seperti “website toko online WhatsApp” menunjukkan kebutuhan yang lebih spesifik. Dengan memahami perbedaan ini, halaman dapat membahas topik secara natural tanpa memaksakan satu keyword di setiap paragraf.</p>
            <h2>Terlihat premium tanpa mengorbankan kecepatan</h2>
            <p>Website yang menarik harus tetap nyaman digunakan. Kami memperhatikan ukuran gambar, loading resource, layout shift, ukuran teks, kontras warna, fokus keyboard, serta target sentuh pada perangkat mobile. Pengunjung tidak seharusnya kehilangan konteks ketika halaman dibuka melalui ponsel atau ketika koneksi sedang tidak ideal.</p>
            <p>Keamanan juga menjadi bagian dari kualitas website. Formulir perlu divalidasi, endpoint harus dilindungi, input pengguna perlu disanitasi, dan akses administratif harus dibatasi sesuai peran. Untuk integrasi API, webhook, dan data pelanggan, desain alur perlu mempertimbangkan autentikasi, logging, rate limiting, serta pemulihan ketika layanan eksternal mengalami gangguan.</p>
            <h2>Anda tahu apa yang dibangun dan mengapa</h2>
            <p>Proyek dimulai dengan konsultasi singkat untuk memahami bisnis, audiens, tujuan, layanan yang ingin ditonjolkan, dan tindakan utama yang diharapkan dari pengunjung. Setelah itu, kami menyusun struktur halaman, prioritas konten, arah visual, dan kebutuhan teknis. Tahap ini membantu mencegah website terlihat bagus tetapi tidak menjawab kebutuhan penjualan.</p>
            <p>Pada tahap desain dan pengembangan, komponen utama dibangun secara responsif agar konsisten di berbagai ukuran layar. Konten kemudian ditata dengan alur yang mudah dipindai, CTA ditempatkan pada momen yang tepat, dan link layanan terkait ditambahkan untuk membentuk perjalanan pengguna. Sebelum peluncuran, halaman diperiksa dari sisi responsif, metadata, schema, formulir, keamanan, dan performa dasar.</p>
            <p>Setelah website online, pekerjaan tidak berhenti. Data dari Search Console, inquiry, perilaku pengunjung, dan halaman yang paling banyak dibaca dapat digunakan untuk menentukan perbaikan berikutnya. Anda dapat memperluas topical authority melalui artikel blog, halaman layanan turunan, optimasi pencarian lokal, serta otomasi follow-up prospek.</p>
            <h2>Bukan template. Website yang dibangun untuk bisnis Anda.</h2>
            <p>Juki menggabungkan web development, SEO, copywriting, dan otomasi bisnis dalam satu alur kerja. Kami tidak hanya mengganti warna dan memasang template, tetapi memetakan pesan, audiens, layanan, dan alur inquiry agar website punya fungsi komersial yang jelas. Anda tetap memiliki kendali atas domain, data, source code, dan aset digital yang dikembangkan.</p>
            <p>Dengan perencanaan yang tepat, website dapat menjadi pusat ekosistem pemasaran: halaman layanan menangkap kebutuhan komersial, artikel blog menjawab pertanyaan informasional, dan internal link menghubungkan keduanya secara logis. Pengunjung mendapatkan jalur yang jelas dari membaca, membandingkan, hingga berkonsultasi. Mesin pencari pun memperoleh konteks yang lebih kuat tentang keahlian dan layanan utama bisnis Anda.</p>
            <p>Jika Anda sedang mencari <strong>jasa pembuatan website di Solo, Surakarta, atau wilayah Indonesia</strong>, sampaikan tujuan dan kebutuhan Anda. Kami dapat membantu memilih antara company profile, landing page, toko online, atau aplikasi web custom, kemudian menghubungkannya dengan layanan <a href="{{ url('/jasa/seo') }}">SEO dan pencarian lokal</a>, <a href="{{ url('/jasa/otomasi') }}">otomasi bisnis</a>, atau <a href="{{ url('/jasa/ai') }}">solusi AI</a> sesuai tahap pertumbuhan bisnis.</p>
        </div>
    </section>
    <section class="jw-section jw-section-alt"><div class="jw-container"><span class="jw-kicker">Langkah pertumbuhan berikutnya</span><h2>Setelah website siap, bawa lebih banyak calon pelanggan masuk.</h2><p class="jw-intro">Website adalah pusatnya. SEO mendatangkan pencarian, otomasi mempercepat follow-up, dan AI membantu Anda melayani serta memproduksi konten dengan lebih konsisten.</p><div class="jw-related">@foreach($relatedLinks as $rl)<a href="{{ url($rl['url']) }}"><span>{{ $rl['title'] }}</span><span aria-hidden="true">↗</span></a>@endforeach</div></div></section>
    <section class="jw-section jw-section-alt"><div class="jw-container"><span class="jw-kicker">Pertanyaan umum</span><h2>Sebelum memulai proyek website.</h2><div class="jw-faq"><details><summary>Berapa lama pembuatan website?</summary><p>Waktu pengerjaan bergantung pada jenis dan ruang lingkup website. Setelah kebutuhan dipetakan, Juki memberikan rencana kerja dan milestone yang jelas.</p></details><details><summary>Apakah website dibuat SEO-friendly?</summary><p>Ya. Struktur heading, metadata, performa, responsif mobile, sitemap, schema, dan fondasi on-page SEO disiapkan sejak proses pengembangan.</p></details><details><summary>Apakah website bisa terhubung ke WhatsApp?</summary><p>Bisa. Alur inquiry, tombol WhatsApp, formulir kontak, dan integrasi API dapat disesuaikan dengan kebutuhan bisnis.</p></details></div></div></section>
    <section class="jw-final"><div class="jw-container"><span class="jw-kicker" style="color:#e8c982">Mulai dari percakapan</span><h2>Siap membangun website yang pantas dipercaya pelanggan?</h2><p>Ceritakan bisnis, target pelanggan, dan kebutuhan Anda. Kami bantu memilih solusi yang tepat—bukan sekadar membuat halaman online.</p><div class="jw-actions" style="justify-content:center"><a class="jw-btn jw-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi jasa pembuatan website untuk bisnis saya.') }}">Konsultasi via WhatsApp <span aria-hidden="true">→</span></a><a class="jw-btn jw-btn-ghost" href="{{ url('/contact') }}">Kirim brief proyek</a></div></div></section>
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
