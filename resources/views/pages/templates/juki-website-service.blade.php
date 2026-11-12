@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;
    $siteName = SystemSetting::get('site_name', config('app.name', 'Juki'));
    $logoUrl = SystemSetting::get('logo_url', 'https://juki.eu.org/storage/content-images/xjuki-website-developer-solo-landscape-1785293658.webp.pagespeed.ic.ehTKUU0dbo.webp');
    $logoAlt = SystemSetting::get('logo_alt', 'Juki Website Developer Solo');
    $waNumber = '6282213028718';
    try {
        if (class_exists(SystemSetting::class)) {
            $settingWa = SystemSetting::get('contact_inquiry_whatsapp', '');
            if (!empty($settingWa)) $waNumber = $settingWa;
        }
    } catch (\Throwable $e) {}
    $canonicalUrl = url('/' . ltrim($page->slug, '/'));
    $p = $serviceParams ?? [];
    $isWebsiteService = trim($page->slug, '/') === 'jasa/website';
    $seoTitle = $isWebsiteService
        ? 'Jasa Pembuatan Website Profesional di Solo | Juki Web Developer'
        : ($page->meta_title ?: $page->title) . ' — ' . $siteName;
    $seoDescription = $isWebsiteService
        ? 'Jasa pembuatan website di Solo dan Surakarta untuk membangun kepercayaan, mendapatkan inquiry, dan mengubah pencarian Google menjadi peluang bisnis.'
        : ($page->meta_description ?: ($p['lead'] ?? ''));
    $relatedLinks = $isWebsiteService ? [
        ['url' => '/jasa/seo', 'title' => 'Jasa SEO & Pencarian Lokal'],
        ['url' => '/jasa/otomasi', 'title' => 'Otomasi Bisnis & Integrasi API'],
        ['url' => '/jasa/ai', 'title' => 'Solusi AI untuk Bisnis'],
        ['url' => '/contact', 'title' => 'Konsultasi Proyek Website'],
    ] : ($p['related_links'] ?? []);
    $heroImage = 'https://trimarzuki.my.id/uploads/ai/2026/11/perbandingan-engine-agentic-automation-enterprise-2026-langgraph-vs-camunda-8-vs-temporal-1bb95c3c.webp';
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
        "@@type": "Person",
        "name": "Tri Marzuki",
        "url": "{{ url('/') }}",
        "telephone": "+{{ $waNumber }}"
    },
    "areaServed": "Soloraya (Surakarta, Karanganyar, Sragen, Wonogiri, Boyolali, Klaten, Sukoharjo)",
    "description": "{{ $seoDescription }}",
    "url": "{{ $canonicalUrl }}"
}
</script>
@php if ($isWebsiteService): @endphp
@php
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            ['@type' => 'Question', 'name' => 'Berapa harga pembuatan website?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Paket website bisnis mulai Rp 2.500.000, sistem informasi custom mulai Rp 10.000.000, dan solusi enterprise sesuai kebutuhan proyek.']],
            ['@type' => 'Question', 'name' => 'Berapa lama pengerjaan website?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Website standar diselesaikan dalam waktu 1 sampai 2 minggu. Proyek custom atau sistem informasi disesuaikan dengan scope yang disepakati.']],
            ['@type' => 'Question', 'name' => 'Apakah sudah termasuk SEO?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Ya. Struktur heading, metadata, performa, mobile-first, sitemap, dan schema disiapkan sejak awal agar mudah ditemukan di Google.']],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@php endif; @endphp
@endsection

@section('styles')
<style>
    .juki-web-landing { --jw-ink:#15253f; --jw-muted:#526174; --jw-gold:#ce9a45; --jw-gold-dark:#9a6b16; --jw-soft:#fbf6eb; --jw-line:#eadfca; color:var(--jw-ink); background:#f8f7f3; font-family:'Plus Jakarta Sans','Inter',ui-sans-serif,system-ui,sans-serif; }
    .juki-web-landing h1,.juki-web-landing h2,.juki-web-landing h3 { font-family:'Outfit','Plus Jakarta Sans',ui-sans-serif,system-ui,sans-serif; font-weight:700; }
    .juki-web-landing .jw-container { width:min(1180px,calc(100% - 32px)); margin-inline:auto; }
    .juki-web-landing .jw-hero { position:relative; overflow:hidden; background:linear-gradient(135deg,#101d32 0%,#15253f 58%,#213756 100%); color:#fff; }
    .juki-web-landing .jw-hero:after { content:""; position:absolute; width:520px; height:520px; right:-180px; top:-240px; border:1px solid rgba(206,154,69,.3); border-radius:50%; box-shadow:0 0 0 50px rgba(206,154,69,.06),0 0 0 100px rgba(206,154,69,.035); }
    .juki-web-landing .jw-hero-inner { position:relative; z-index:1; display:grid; grid-template-columns:minmax(0,1.16fr) minmax(300px,.84fr); gap:38px; align-items:center; padding:76px 0 68px; }
    .juki-web-landing .jw-eyebrow { display:inline-flex; align-items:center; gap:9px; color:#e8c982; font-size:.78rem; font-weight:800; letter-spacing:.13em; text-transform:uppercase; }
    .juki-web-landing .jw-eyebrow:before { content:""; width:28px; height:2px; background:var(--jw-gold); }
    .juki-web-landing .jw-hero h1 { max-width:790px; margin:18px 0 20px; color:#fff; font-size:clamp(2rem,3.65vw,3.55rem); line-height:1.08; letter-spacing:-.045em; font-weight:800; text-wrap:balance; }
    .juki-web-landing .jw-hero h1 em { color:#e8c982; font-style:normal; }
    .juki-web-landing .jw-lead { max-width:650px; color:#e4e9f0; font-size:clamp(1rem,1.7vw,1.18rem); line-height:1.75; }
    .juki-web-landing .jw-service-area { margin-top:20px; padding:14px 18px; border:1px solid rgba(232,201,130,.25); border-radius:12px; background:rgba(255,255,255,.05); font-size:.9rem; color:#f1f5f9; line-height:1.6; }
    .juki-web-landing .jw-service-area strong { color:#e8c982; font-weight:700; }
    .juki-web-landing .jw-actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:26px; }
    .juki-web-landing .jw-btn { min-height:48px; display:inline-flex; align-items:center; justify-content:center; gap:9px; padding:0 20px; border-radius:10px; font-weight:800; text-decoration:none; transition:transform .2s ease,box-shadow .2s ease,background .2s ease; }
    .juki-web-landing .jw-btn:hover { transform:translateY(-2px); box-shadow:0 12px 26px rgba(206,154,69,.25); }
    .juki-web-landing .jw-btn-primary { background:var(--jw-gold); color:#15253f; }
    .juki-web-landing .jw-btn-ghost { border:1px solid rgba(232,201,130,.5); color:#fff; }
    .juki-web-landing .jw-hero-visual { position:relative; min-height:350px; display:flex; align-items:center; justify-content:center; }
    .juki-web-landing .jw-browser { position:relative; width:min(100%,490px); overflow:hidden; border:1px solid rgba(232,201,130,.32); border-radius:18px; background:#fff; box-shadow:0 30px 70px rgba(0,0,0,.35); transform:rotate(1.5deg); }
    .juki-web-landing .jw-browser-bar { display:flex; align-items:center; gap:6px; height:34px; padding:0 13px; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    .juki-web-landing .jw-browser-bar i { width:7px; height:7px; border-radius:50%; background:#cbd5e1; }
    .juki-web-landing .jw-browser-bar span { width:105px; height:7px; margin-left:12px; border-radius:99px; background:#e2e8f0; }
    .juki-web-landing .jw-browser img { display:block; width:100%; aspect-ratio:16/10; object-fit:cover; }
    .juki-web-landing .jw-visual-note { position:absolute; right:-18px; bottom:20px; max-width:220px; padding:15px 17px; border:1px solid rgba(232,201,130,.45); border-radius:13px; background:#fff; color:var(--jw-ink); box-shadow:0 15px 35px rgba(0,0,0,.2); }
    .juki-web-landing .jw-visual-note strong { display:block; font-family:'Outfit',sans-serif; font-size:1.05rem; }
    .juki-web-landing .jw-visual-note small { display:block; margin-top:4px; color:var(--jw-muted); line-height:1.45; }
    .juki-web-landing .jw-proof { background:#fff; border-bottom:1px solid var(--jw-line); }
    .juki-web-landing .jw-proof-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; padding:24px 0; }
    .juki-web-landing .jw-proof strong { display:block; font-size:1rem; }
    .juki-web-landing .jw-proof span { display:block; margin-top:4px; color:var(--jw-muted); font-size:.82rem; }
    .juki-web-landing .jw-section { padding:82px 0; }
    .juki-web-landing .jw-section-alt { background:#fff; }
    .juki-web-landing .jw-kicker { color:var(--jw-gold-dark); font-size:.75rem; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
    .juki-web-landing .jw-section h2 { max-width:780px; margin:10px 0 16px; font-size:clamp(1.75rem,3vw,2.55rem); line-height:1.12; letter-spacing:-.035em; font-weight:700; }
    .juki-web-landing .jw-section-center { text-align:center; }
    .juki-web-landing .jw-section-center h2,.juki-web-landing .jw-section-center .jw-kicker,.juki-web-landing .jw-section-center .jw-intro { margin-inline:auto; }
    .juki-web-landing .jw-intro { max-width:720px; color:var(--jw-muted); font-size:1.06rem; line-height:1.75; }
    .juki-web-landing .jw-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-top:32px; }
    .juki-web-landing .jw-grid-3 { grid-template-columns:repeat(3,1fr); }
    .juki-web-landing .jw-service { display:flex; flex-direction:column; padding:25px; border:1px solid #e2e8f0; border-radius:16px; background:#fff; box-shadow:0 8px 24px rgba(15,23,42,.05); transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease; }
    .juki-web-landing .jw-service:hover { transform:translateY(-5px); border-color:#d4ad64; box-shadow:0 18px 36px rgba(154,107,22,.12); }
    .juki-web-landing .jw-service-center { text-align:center; align-items:center; }
    .juki-web-landing .jw-service-icon { width:42px; height:42px; display:grid; place-items:center; margin-bottom:18px; border-radius:12px; background:var(--jw-soft); color:var(--jw-gold-dark); }
    .juki-web-landing .jw-service h3 { margin:0 0 8px; font-size:1.15rem; }
    .juki-web-landing .jw-service p { margin:0 0 16px; color:var(--jw-muted); font-size:.92rem; line-height:1.65; flex-grow:1; }
    .juki-web-landing .jw-price-tag { margin:10px 0 14px; font-family:'Outfit',sans-serif; font-size:1.6rem; font-weight:800; color:var(--jw-ink); }
    .juki-web-landing .jw-pricing-list { list-style:none; padding:0; margin:0 0 20px; text-align:left; width:100%; border-top:1px solid #e2e8f0; padding-top:14px; }
    .juki-web-landing .jw-pricing-list li { font-size:.88rem; color:var(--jw-muted); margin-bottom:8px; display:flex; align-items:center; gap:8px; }
    .juki-web-landing .jw-pricing-list li svg { width:16px; height:16px; color:var(--jw-gold-dark); flex-shrink:0; }
    .juki-web-landing .jw-aida { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-top:34px; }
    .juki-web-landing .jw-step { padding:22px; border-top:3px solid var(--jw-gold); background:#f8f7f3; }
    .juki-web-landing .jw-step b { display:block; color:var(--jw-gold-dark); font-size:.75rem; letter-spacing:.12em; text-transform:uppercase; }
    .juki-web-landing .jw-step h3 { margin:10px 0 7px; font-size:1.05rem; }
    .juki-web-landing .jw-step p { margin:0; color:var(--jw-muted); font-size:.9rem; line-height:1.6; }
    .juki-web-landing .jw-columns { display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:start; }
    .juki-web-landing .jw-sticky-layout { display:grid; grid-template-columns:minmax(0,1fr) 340px; gap:48px; align-items:start; }
    .juki-web-landing .jw-scroll-column { display:grid; gap:16px; }
    .juki-web-landing .jw-scroll-card { padding:28px; border:1px solid var(--jw-line); border-radius:18px; background:#fff; box-shadow:0 10px 28px rgba(21,37,63,.06); }
    .juki-web-landing .jw-scroll-card h3 { margin:0 0 9px; font-size:1.25rem; }
    .juki-web-landing .jw-scroll-card p { margin:0; color:var(--jw-muted); line-height:1.75; }
    .juki-web-landing .jw-sticky-offer { position:sticky; top:24px; padding:28px; border-radius:20px; color:#fff; background:var(--jw-ink); box-shadow:0 22px 48px rgba(21,37,63,.2); }
    .juki-web-landing .jw-sticky-offer .jw-card-label { color:#e8c982; }
    .juki-web-landing .jw-sticky-offer h3 { margin:12px 0 8px; color:#fff; font-size:1.5rem; }
    .juki-web-landing .jw-sticky-offer .jw-price { margin:18px 0; color:#e8c982; font-size:2rem; font-weight:800; letter-spacing:-.04em; }
    .juki-web-landing .jw-sticky-offer p { color:#d5dce6; font-size:.9rem; line-height:1.65; }
    .juki-web-landing .jw-check { display:flex; gap:10px; align-items:flex-start; margin:14px 0; color:#e4e9f0; line-height:1.5; }
    .juki-web-landing .jw-check svg { flex:0 0 20px; width:20px; height:20px; margin-top:2px; color:#e8c982; }
    .juki-web-landing .jw-sticky-offer .jw-btn { width:100%; margin-top:14px; }
    .juki-web-landing .jw-showcase { position:relative; overflow:hidden; padding:96px 0; background:#15253f; color:#fff; }
    .juki-web-landing .jw-showcase:before { content:""; position:absolute; inset:0; opacity:.22; background-image:linear-gradient(rgba(232,201,130,.14) 1px,transparent 1px),linear-gradient(90deg,rgba(232,201,130,.14) 1px,transparent 1px); background-size:58px 58px; mask-image:linear-gradient(90deg,#000,transparent 80%); }
    .juki-web-landing .jw-showcase-grid { position:relative; display:grid; grid-template-columns:minmax(0,.9fr) minmax(0,1.1fr); gap:76px; align-items:start; }
    .juki-web-landing .jw-showcase-sticky { position:sticky; top:28px; text-align:center; transform:translateX(calc(var(--showcase-shift, 0px) * -1)); transition:transform .12s linear,text-align .3s ease; }
    .juki-web-landing .jw-showcase-sticky .jw-eyebrow,.juki-web-landing .jw-showcase-sticky .jw-intro { margin-inline:auto; }
    .juki-web-landing .jw-showcase.is-scrolling .jw-showcase-sticky { text-align:left; }
    .juki-web-landing .jw-showcase.is-scrolling .jw-showcase-sticky .jw-eyebrow,.juki-web-landing .jw-showcase.is-scrolling .jw-showcase-sticky .jw-intro { margin-inline:0; }
    .juki-web-landing .jw-showcase h2 { max-width:560px; margin:12px 0 18px; color:#fff; font-size:clamp(2rem,4vw,3.5rem); line-height:1.03; letter-spacing:-.05em; }
    .juki-web-landing .jw-showcase .jw-intro { color:#cbd5e1; }
    .juki-web-landing .jw-showcase-card { min-height:220px; display:flex; flex-direction:column; justify-content:space-between; margin-bottom:18px; padding:28px; border:1px solid rgba(232,201,130,.23); border-radius:20px; background:rgba(255,255,255,.065); backdrop-filter:blur(8px); opacity:.52; transform:scale(.9); transform-origin:center; transition:transform .18s ease-out,opacity .18s ease-out,border-color .35s ease,background .35s ease,box-shadow .35s ease; }
    .juki-web-landing .jw-showcase-card.is-focus { opacity:1; border-color:#e8c982; background:rgba(255,255,255,.13); box-shadow:0 24px 60px rgba(0,0,0,.22); }
    .juki-web-landing .jw-showcase-card:hover { transform:translateX(8px); border-color:#e8c982; background:rgba(255,255,255,.11); }
    .juki-web-landing .jw-showcase-card:nth-child(2) { margin-left:42px; }
    .juki-web-landing .jw-showcase-card:nth-child(3) { margin-left:84px; }
    .juki-web-landing .jw-showcase-card .jw-card-label { color:#e8c982; }
    .juki-web-landing .jw-showcase-card h3 { margin:20px 0 8px; color:#fff; font-size:1.45rem; }
    .juki-web-landing .jw-showcase-card p { margin:0; color:#cbd5e1; line-height:1.7; }
    .juki-web-landing .jw-showcase-number { display:flex; align-items:center; justify-content:space-between; color:#e8c982; font-family:'Outfit',sans-serif; font-size:2.5rem; font-weight:700; }
    .juki-web-landing .jw-showcase-progress { width:100%; height:2px; margin-top:34px; overflow:hidden; background:rgba(255,255,255,.18); }
    .juki-web-landing .jw-showcase-progress span { display:block; width:0; height:100%; background:var(--jw-gold); transition:width .15s linear; }
    .juki-web-landing .jw-bento .jw-service:first-child { grid-column:span 2; background:var(--jw-ink); color:#fff; }
    .juki-web-landing .jw-bento .jw-service:first-child h3,.juki-web-landing .jw-bento .jw-service:first-child p { color:#fff; }
    .juki-web-landing .jw-bento .jw-service:first-child p { color:#d7dee8; }
    .juki-web-landing .jw-bento .jw-service:nth-child(4) { background:var(--jw-soft); transform:translateY(18px); }
    .juki-web-landing .jw-bento .jw-service:nth-child(4):hover { transform:translateY(13px); }
    .juki-web-landing .jw-section-benefits { background:radial-gradient(circle at 90% 10%,rgba(206,154,69,.12),transparent 35%),#f8f7f3; }
    .juki-web-landing .jw-section-pricing { background:#fff; }
    .juki-web-landing .jw-section-pricing .jw-grid { align-items:stretch; }
    .juki-web-landing .jw-section-pricing .jw-service { position:relative; overflow:hidden; }
    .juki-web-landing .jw-section-pricing .jw-service:nth-child(2) { border:2px solid var(--jw-gold); transform:translateY(-12px); box-shadow:0 22px 42px rgba(154,107,22,.15); }
    .juki-web-landing .jw-section-pricing .jw-service:nth-child(2):before { content:'PALING DIPILIH'; position:absolute; top:0; right:0; padding:7px 12px; border-radius:0 0 0 10px; background:var(--jw-gold); color:var(--jw-ink); font-size:.62rem; font-weight:800; letter-spacing:.1em; }
    .juki-web-landing .jw-section-aida { background:linear-gradient(180deg,#fbf6eb 0%,#fff 100%); }
    .juki-web-landing .jw-sticky-aida-wrapper { display:grid; grid-template-columns:360px minmax(0,1fr); gap:48px; align-items:start; margin-top:36px; }
    .juki-web-landing .jw-sticky-aida-nav { position:sticky; top:32px; display:grid; gap:10px; }
    .juki-web-landing .jw-aida-nav-item { padding:18px 20px; border:1px solid #eadfca; border-radius:14px; background:#fff; color:var(--jw-muted); font-weight:700; text-align:left; cursor:pointer; transition:all .25s ease; box-shadow:0 4px 12px rgba(21,37,63,.03); }
    .juki-web-landing .jw-aida-nav-item.is-active { background:var(--jw-ink); color:#fff; border-color:var(--jw-ink); transform:translateX(6px); box-shadow:0 10px 25px rgba(21,37,63,.15); }
    .juki-web-landing .jw-aida-nav-item b { display:block; font-size:.72rem; letter-spacing:.12em; text-transform:uppercase; color:var(--jw-gold); margin-bottom:4px; }
    .juki-web-landing .jw-sticky-aida-content { display:grid; gap:24px; }
    .juki-web-landing .jw-aida-content-card { padding:36px; border:1px solid #eadfca; border-radius:20px; background:#fff; box-shadow:0 12px 32px rgba(21,37,63,.06); opacity:.4; transform:translateY(16px); transition:all .4s cubic-bezier(.16,1,.3,1); }
    .juki-web-landing .jw-aida-content-card.is-active { opacity:1; transform:none; border-color:var(--jw-gold); box-shadow:0 20px 45px rgba(154,107,22,.15); background:linear-gradient(135deg,#fff 0%,#fbf6eb 100%); }
    .juki-web-landing .jw-aida-content-card b { color:var(--jw-gold-dark); font-size:.8rem; letter-spacing:.12em; text-transform:uppercase; }
    .juki-web-landing .jw-aida-content-card h3 { margin:10px 0 12px; font-size:1.45rem; }
    .juki-web-landing .jw-aida-content-card p { margin:0; color:var(--jw-muted); font-size:1.02rem; line-height:1.75; }
    .juki-web-landing .jw-section-seo { color:#fff; background:linear-gradient(135deg,#15253f,#213756); }
    .juki-web-landing .jw-section-seo h2,.juki-web-landing .jw-section-seo .jw-kicker { color:#fff; }
    .juki-web-landing .jw-section-seo .jw-kicker { color:#e8c982; }
    .juki-web-landing .jw-section-seo .jw-intro,.juki-web-landing .jw-section-seo .jw-list li { color:#d5dce6; }
    .juki-web-landing .jw-section-seo .jw-list svg { color:#e8c982; }
    .juki-web-landing .jw-section-process { background:#f8f7f3; }
    .juki-web-landing .jw-section-process .jw-step { min-height:165px; border:0; border-left:3px solid var(--jw-gold); background:#fff; border-radius:0 14px 14px 0; }
    .juki-web-landing .jw-section-related { background:#fff; }
    .juki-web-landing .jw-section-related .jw-related a { min-height:82px; background:#fff; box-shadow:0 8px 22px rgba(21,37,63,.06); }
    .juki-web-landing .jw-section-faq { background:#f8f7f3; }
    .juki-web-landing .jw-js .jw-reveal { opacity:0; transform:translateY(18px); transition:opacity .6s ease,transform .6s ease; }
    .juki-web-landing .jw-js .jw-reveal.is-visible { opacity:1; transform:none; }
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
    @media (max-width:900px) { .juki-web-landing .jw-hero-inner,.juki-web-landing .jw-columns,.juki-web-landing .jw-sticky-layout,.juki-web-landing .jw-showcase-grid,.juki-web-landing .jw-sticky-aida-wrapper { grid-template-columns:1fr; gap:32px; } .juki-web-landing .jw-sticky-offer,.juki-web-landing .jw-showcase-sticky,.juki-web-landing .jw-sticky-aida-nav { position:relative; top:auto; order:-1; } .juki-web-landing .jw-showcase-sticky { transform:none!important; text-align:left; } .juki-web-landing .jw-showcase-sticky .jw-eyebrow,.juki-web-landing .jw-showcase-sticky .jw-intro { margin-inline:0; } .juki-web-landing .jw-showcase-card { opacity:1; transform:none!important; } .juki-web-landing .jw-grid,.juki-web-landing .jw-aida { grid-template-columns:repeat(2,1fr); } .juki-web-landing .jw-grid-3 { grid-template-columns:repeat(2,1fr); } .juki-web-landing .jw-bento .jw-service:first-child { grid-column:auto; } .juki-web-landing .jw-bento .jw-service:nth-child(4),.juki-web-landing .jw-section-pricing .jw-service:nth-child(2) { transform:none; } .juki-web-landing .jw-showcase-card:nth-child(2),.juki-web-landing .jw-showcase-card:nth-child(3) { margin-left:0; } }
    @media (max-width:620px) { .juki-web-landing .jw-container { width:min(100% - 24px,1180px); } .juki-web-landing .jw-hero-inner { padding:58px 0 52px; } .juki-web-landing .jw-proof-grid,.juki-web-landing .jw-grid,.juki-web-landing .jw-aida,.juki-web-landing .jw-related { grid-template-columns:1fr; } .juki-web-landing .jw-section,.juki-web-landing .jw-showcase { padding:58px 0; } .juki-web-landing .jw-actions .jw-btn { width:100%; } .juki-web-landing .jw-hero-visual { min-height:280px; } .juki-web-landing .jw-visual-note { right:0; bottom:4px; } }
    @media (prefers-reduced-motion:reduce) { .juki-web-landing *,.juki-web-landing *:before,.juki-web-landing *:after { scroll-behavior:auto!important; transition:none!important; animation:none!important; } .juki-web-landing .jw-js .jw-reveal { opacity:1; transform:none; } }
</style>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var landing = document.querySelector('.juki-web-landing');
    if (!landing) return;
    landing.classList.add('jw-js');

    var revealItems = landing.querySelectorAll('.jw-reveal');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries, currentObserver) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                currentObserver.unobserve(entry.target);
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
        revealItems.forEach(function (item) { observer.observe(item); });
    } else {
        revealItems.forEach(function (item) { item.classList.add('is-visible'); });
    }

    // Sticky AIDA Interactive Observer
    var aidaCards = landing.querySelectorAll('.jw-aida-content-card');
    var aidaNavItems = landing.querySelectorAll('.jw-aida-nav-item');
    if ('IntersectionObserver' in window && aidaCards.length && aidaNavItems.length) {
        var aidaObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var idx = entry.target.getAttribute('data-aida-index');
                    aidaCards.forEach(function (c) { c.classList.remove('is-active'); });
                    aidaNavItems.forEach(function (n) { n.classList.remove('is-active'); });
                    entry.target.classList.add('is-active');
                    var activeNav = landing.querySelector('[data-aida-nav="' + idx + '"]');
                    if (activeNav) activeNav.classList.add('is-active');
                }
            });
        }, { rootMargin: '-30% 0px -40% 0px', threshold: 0.1 });
        aidaCards.forEach(function (card) { aidaObserver.observe(card); });

        aidaNavItems.forEach(function (nav) {
            nav.addEventListener('click', function () {
                var idx = nav.getAttribute('data-aida-nav');
                var targetCard = landing.querySelector('[data-aida-index="' + idx + '"]');
                if (targetCard) {
                    targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    }

    var progressBar = landing.querySelector('.jw-showcase-progress span');
    var showcase = landing.querySelector('.jw-showcase');
    var showcaseCards = landing.querySelectorAll('.jw-showcase-card');
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var updateProgress = function () {
        if (!progressBar || !showcase) return;
        var rect = showcase.getBoundingClientRect();
        var travel = Math.max(1, rect.height - window.innerHeight);
        var progress = Math.min(1, Math.max(0, -rect.top / travel));
        progressBar.style.width = (progress * 100) + '%';

        if (!reducedMotion && window.innerWidth > 900) {
            showcase.style.setProperty('--showcase-shift', Math.round(progress * 92) + 'px');
            showcase.classList.toggle('is-scrolling', progress > .08);
            var viewportCenter = window.innerHeight * .52;
            var closest = null;
            var closestDistance = Infinity;
            showcaseCards.forEach(function (card) {
                var cardRect = card.getBoundingClientRect();
                var distance = Math.abs((cardRect.top + cardRect.height / 2) - viewportCenter);
                var focus = Math.max(0, 1 - distance / (window.innerHeight * .58));
                card.style.transform = 'scale(' + (.9 + focus * .12).toFixed(3) + ')';
                card.style.opacity = (.48 + focus * .52).toFixed(3);
                if (distance < closestDistance) {
                    closestDistance = distance;
                    closest = card;
                }
            });
            showcaseCards.forEach(function (card) { card.classList.toggle('is-focus', card === closest); });
        }
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
});
</script>
@endsection

@section('content')
@php if ($isWebsiteService): @endphp
<main class="juki-web-landing">
    <section class="jw-hero">
        <div class="jw-container jw-hero-inner">
            <div>
                <span class="jw-eyebrow">Juki Web Developer Solo</span>
                <h1>Vendor / Jasa Pembuatan Website Profesional untuk UMKM dan Perusahaan di Solo</h1>
                <p class="jw-lead">Jasa pembuatan website di Solo dan Surakarta untuk membangun kepercayaan, mendapatkan inquiry, dan mengubah pencarian Google menjadi peluang bisnis.</p>
                <div class="jw-service-area">
                    <strong>Wilayah Soloraya:</strong> Melayani semua klien di wilayah Surakarta, Karanganyar, Sragen, Wonogiri, Boyolali, Klaten, Sukoharjo dan sekitarnya.
                </div>
                <div class="jw-actions">
                    <a class="jw-btn jw-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi jasa pembuatan website untuk bisnis saya.') }}">Konsultasi via WhatsApp <span aria-hidden="true">→</span></a>
                    <a class="jw-btn jw-btn-ghost" href="{{ url('/contact') }}">Kirim brief proyek</a>
                </div>
            </div>
            <div class="jw-hero-visual" aria-label="Preview website profesional">
                <div class="jw-browser">
                    <div class="jw-browser-bar"><i></i><i></i><i></i><span></span></div>
                    <img src="{{ $heroImage }}" width="1200" height="750" loading="eager" fetchpriority="high" decoding="async" alt="Contoh desain website profesional Juki di Solo">
                </div>
                <div class="jw-visual-note"><strong>Designed to convert</strong><small>Struktur halaman, CTA, dan SEO dirancang untuk membantu bisnis mendapatkan inquiry.</small></div>
            </div>
        </div>
    </section>

    <section class="jw-proof" aria-label="Fokus layanan">
        <div class="jw-container jw-proof-grid">
            <div><strong>Mobile-first</strong><span>Nyaman di setiap layar</span></div>
            <div><strong>SEO-ready</strong><span>Struktur siap ditemukan</span></div>
            <div><strong>Conversion-led</strong><span>CTA menuju inquiry</span></div>
            <div><strong>Ownership penuh</strong><span>Aset tetap milik bisnis</span></div>
        </div>
    </section>

    <section class="jw-showcase" data-reveal>
        <div class="jw-container jw-showcase-grid">
            <div class="jw-showcase-sticky">
                <span class="jw-eyebrow">A different kind of website</span>
                <h2>Rapi ketika dilihat. Terarah ketika digunakan.</h2>
                <p class="jw-intro">Website profesional di Solo tidak harus terlihat ramai untuk terlihat bernilai. Kami menggunakan kontras, ruang, tipografi, dan urutan pesan untuk membuat keputusan terasa lebih mudah.</p>
                <a class="jw-btn jw-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin melihat konsep website profesional untuk bisnis saya.') }}">Lihat konsep untuk bisnis saya →</a>
                <div class="jw-showcase-progress" aria-hidden="true"><span></span></div>
            </div>
            <div>
                <article class="jw-showcase-card jw-reveal"><div class="jw-showcase-number"><span>01</span><span aria-hidden="true">↗</span></div><div><span class="jw-card-label">Attention</span><h3>Orang langsung tahu Anda menjual apa.</h3><p>Hero bukan tempat untuk jargon. Ia menyampaikan layanan utama, wilayah Solo, manfaat, dan CTA dalam satu pandangan pertama.</p></div></article>
                <article class="jw-showcase-card jw-reveal"><div class="jw-showcase-number"><span>02</span><span aria-hidden="true">↗</span></div><div><span class="jw-card-label">Interest</span><h3>Informasi disusun seperti percakapan.</h3><p>Manfaat, paket, proses, teknologi, dan FAQ menjawab pertanyaan calon pelanggan sebelum mereka mengirim pesan.</p></div></article>
                <article class="jw-showcase-card jw-reveal"><div class="jw-showcase-number"><span>03</span><span aria-hidden="true">↗</span></div><div><span class="jw-card-label">Desire → Action</span><h3>Kepercayaan berakhir pada langkah yang jelas.</h3><p>Harga awal dan CTA WhatsApp hadir ketika pengunjung sudah memahami nilai yang ditawarkan—bukan dipaksa sejak detik pertama.</p></div></article>
            </div>
        </div>
    </section>

    <section class="jw-section" data-reveal>
        <div class="jw-container jw-sticky-layout">
            <div class="jw-scroll-column">
                <div class="jw-scroll-card jw-reveal"><span class="jw-kicker">Masalah yang kami selesaikan</span><h3>Website terlihat ada, tetapi tidak menghasilkan percakapan.</h3><p>Calon pelanggan datang dari Google, iklan, atau rekomendasi lalu menemukan halaman yang lambat, membingungkan, atau tidak menjelaskan langkah berikutnya. Kami merapikan pesan dan alur halaman agar pengunjung cepat memahami nilai bisnis Anda.</p></div>
                <div class="jw-scroll-card jw-reveal"><span class="jw-kicker">Solusi yang dibangun</span><h3>Pesan yang tajam, visual yang meyakinkan, CTA yang jelas.</h3><p>Mulai dari headline, struktur layanan, benefit, harga awal, FAQ, sampai tombol WhatsApp—semuanya memiliki peran dalam membantu calon pelanggan mengambil keputusan.</p></div>
                <div class="jw-scroll-card jw-reveal"><span class="jw-kicker">Hasil yang dituju</span><h3>Lebih mudah dipercaya. Lebih mudah ditemukan. Lebih mudah dihubungi.</h3><p>Website Anda menjadi pusat informasi dan penjualan yang dapat dikembangkan dengan SEO, konten, otomasi, dan integrasi bisnis ketika kebutuhan bertambah.</p></div>
            </div>
            <aside class="jw-sticky-offer" aria-label="Penawaran website">
                <span class="jw-card-label">Paket paling populer</span>
                <h3>Website Bisnis</h3>
                <div class="jw-price">Mulai Rp 2,5 juta</div>
                <p>Company profile, landing page, atau toko online dengan desain responsive, struktur SEO-ready, dan support WhatsApp.</p>
                <div class="jw-check"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Estimasi pengerjaan maksimal 2 minggu</span></div>
                <div class="jw-check"><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Mobile-first dan SEO-friendly</span></div>
                <a class="jw-btn jw-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi Paket Website mulai Rp 2.500.000.') }}">Konsultasikan proyek →</a>
            </aside>
        </div>
    </section>

    <!-- PRICING / SOLUSI WEBSITE UNTUK KEBUTUHAN BERBEDA (RATA TENGAH & DETAIL) -->
    <section class="jw-section jw-section-pricing jw-section-alt">
        <div class="jw-container jw-section-center">
            <span class="jw-kicker">Pilih titik mulai Anda</span>
            <h2>Solusi website untuk kebutuhan yang berbeda.</h2>
            <p class="jw-intro">Setiap bisnis memiliki skala, anggaran, dan target operasional yang unik. Berikut rincian paket dan apa saja yang didapatkan secara transparan.</p>
            <div class="jw-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr)); margin-top: 48px; text-align: left;">
                
                <article class="jw-service jw-service-center">
                    <span class="jw-card-label">UMKM & Profesional</span>
                    <h3>Website Bisnis</h3>
                    <div class="jw-price-tag">Mulai Rp 2,5 juta</div>
                    <p>Company profile, landing page, atau toko online untuk bisnis di Solo yang ingin tampil kredibel dan mulai menerima inquiry via WhatsApp.</p>
                    <ul class="jw-pricing-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Domain & Hosting setup (opsional)</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Desain Responsive Mobile-First</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Struktur On-Page SEO-Ready</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Tombol & Alur Inquiry WhatsApp</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Revisi & Panduan Pengelolaan</li>
                    </ul>
                    <a class="jw-btn jw-btn-primary" style="width:100%; margin-top:auto;" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya tertarik Paket Website Bisnis mulai Rp 2.500.000.') }}">Tanya Paket Ini →</a>
                </article>

                <article class="jw-service jw-service-center" style="border: 2px solid var(--jw-gold);">
                    <span class="jw-card-label" style="color:var(--jw-gold-dark);">Perusahaan & Operasional</span>
                    <h3>Sistem Informasi</h3>
                    <div class="jw-price-tag">Mulai Rp 10 juta</div>
                    <p>CRM, ERP, inventory, POS, dashboard internal, atau aplikasi web custom yang dirancang mengikuti alur kerja perusahaan Anda.</p>
                    <ul class="jw-pricing-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Arsitektur Database & Backend Aman</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Manajemen Hak Akses (Role/Permission)</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> REST API & Integrasi Eksternal</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Dashboard & Reporting Real-time</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Pelatihan Tim & Garansi Maintenance</li>
                    </ul>
                    <a class="jw-btn jw-btn-primary" style="width:100%; margin-top:auto;" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi pembuatan Sistem Informasi / Aplikasi Web.') }}">Diskusikan Kebutuhan →</a>
                </article>

                <article class="jw-service jw-service-center">
                    <span class="jw-card-label">Skala Lanjutan</span>
                    <h3>Solusi Terintegrasi</h3>
                    <div class="jw-price-tag" style="font-size:1.3rem; margin:16px 0;">Custom / Enterprise</div>
                    <p>Kombinasi website, automated workflow, otomasi pemasaran, AI knowledge integration, dan optimasi SEO menyeluruh untuk korporasi.</p>
                    <ul class="jw-pricing-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Multi-channel & Programmatic SEO</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Otomasi WhatsApp / CRM Webhook</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Keamanan & Performa High-Traffic</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Dedicated Technical Partner</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Ruang Lingkup Sesuai Kebutuhan</li>
                    </ul>
                    <a class="jw-btn jw-btn-primary" style="width:100%; margin-top:auto;" href="{{ url('/contact') }}">Kirim Brief Proyek →</a>
                </article>

            </div>
        </div>
    </section>

    <!-- STICKY AIDA SECTION (SCROLL TERUS MUNCUL & PENJELASAN MASING-MASING) -->
    <section class="jw-section jw-section-aida" aria-label="Alur AIDA Interaktif">
        <div class="jw-container jw-section-center">
            <span class="jw-kicker">Psikologi konversi</span>
            <h2>Alur AIDA yang terasa natural, bukan teori di atas kertas.</h2>
            <p class="jw-intro">Setiap pengunjung melewati fase mental yang sama sebelum memutuskan menghubungi Anda. Halaman ini dirancang mengikuti alur AIDA agar transisi dari pembaca menjadi klien terasa mulus.</p>
            
            <div class="jw-sticky-aida-wrapper">
                <div class="jw-sticky-aida-nav">
                    <button type="button" class="jw-aida-nav-item is-active" data-aida-nav="0"><b>01 · Attention</b> Perhatian Pertama</button>
                    <button type="button" class="jw-aida-nav-item" data-aida-nav="1"><b>02 · Interest</b> Ketertarikan Kontekstual</button>
                    <button type="button" class="jw-aida-nav-item" data-aida-nav="2"><b>03 · Desire</b> Keinginan & Bukti Nilai</button>
                    <button type="button" class="jw-aida-nav-item" data-aida-nav="3"><b>04 · Action</b> Tindakan Nyata (Inquiry)</button>
                </div>
                <div class="jw-sticky-aida-content">
                    <div class="jw-aida-content-card is-active" data-aida-index="0">
                        <b>Tahap 01 — Attention (Perhatian)</b>
                        <h3>Masalah langsung terasa di detik pertama kunjungan</h3>
                        <p>Pengunjung yang datang dari pencarian Google di Solo langsung melihat headline yang relevan dengan kebutuhan bisnis mereka—tanpa basa-basi, tanpa animasi yang mengganggu, dan langsung menjawab apa yang Anda tawarkan.</p>
                    </div>
                    <div class="jw-aida-content-card" data-aida-index="1">
                        <b>Tahap 02 — Interest (Ketertarikan)</b>
                        <h3>Solusi dan nilai bisnis disusun seperti percakapan</h3>
                        <p>Alih-alih daftar fitur teknis yang membosankan, kami menjelaskan bagaimana website menyelesaikan masalah operasional, menambah kepercayaan klien, dan merapikan brand perusahaan Anda di mata calon pembeli.</p>
                    </div>
                    <div class="jw-aida-content-card" data-aida-index="2">
                        <b>Tahap 03 — Desire (Keinginan)</b>
                        <h3>Transparansi harga dan kredibilitas menghilangkan keraguan</h3>
                        <p>Calon pembeli sering ragu karena tidak tahu kisaran harga dan apa yang didapat. Kami memaparkan paket transparan, estimasi waktu, serta keunggulan kepemilikan penuh agar mereka yakin.</p>
                    </div>
                    <div class="jw-aida-content-card" data-aida-index="3">
                        <b>Tahap 04 — Action (Tindakan)</b>
                        <h3>Tombol WhatsApp & konsultasi hadir di saat yang tepat</h3>
                        <p>Ketika pengunjung sudah memahami nilai dan memutuskan untuk bergerak, tombol WhatsApp dan formulir ringkas langsung siap diklik tanpa perlu mencari-cari di menu atau footer.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- METODE FUNNELING PERKONTENAN (TOFU, MOFU, BOFU) -->
    <section class="jw-section jw-section-alt">
        <div class="jw-container jw-section-center">
            <span class="jw-kicker">Strategi SEO & Content Marketing</span>
            <h2>Metode Funneling Perkontenan untuk Menjemput Traffic Google.</h2>
            <p class="jw-intro">Website landing page utama tidak berdiri sendiri. Kami menghubungkannya dengan ekosistem konten artikel berstruktur <strong>TOFU, MOFU, BOFU</strong> agar otoritas SEO Anda tumbuh organik.</p>
            
            <div class="jw-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr)); text-align: left; margin-top: 36px;">
                <article class="jw-service">
                    <span class="jw-card-label">TOFU (Top of Funnel)</span>
                    <h3>Edukasi & Jangkauan Luas</h3>
                    <p>Artikel panduan, definisi, dan tips awal untuk menjaring pencari informasi di Solo yang baru menyadari kebutuhan digitalisasi bisnis.</p>
                    <ul class="jw-pricing-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Target: Kata kunci informasional</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Fokus: Edukasi & kesadaran brand</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Tautan ke halaman layanan utama</li>
                    </ul>
                </article>

                <article class="jw-service" style="border: 2px solid var(--jw-gold);">
                    <span class="jw-card-label" style="color:var(--jw-gold-dark);">MOFU (Middle of Funnel)</span>
                    <h3>Evaluasi & Perbandingan</h3>
                    <p>Artikel studi kasus, perbandingan platform (misal WordPress vs Custom), dan tips memilih vendor web development yang tepat.</p>
                    <ul class="jw-pricing-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Target: Kata kunci investigasi</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Fokus: Membangun pertimbangan</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Tautan ke studi kasus / LP spesifik</li>
                    </ul>
                </article>

                <article class="jw-service">
                    <span class="jw-card-label">BOFU (Bottom of Funnel)</span>
                    <h3>Keputusan & Konversi</h3>
                    <p>Artikel penentu, rincian biaya, dan panduan kerja sama yang langsung mengarah pada keputusan menggunakan jasa Anda.</p>
                    <ul class="jw-pricing-list">
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Target: Kata kunci transaksional</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Fokus: Mendorong inquiry WhatsApp</li>
                        <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg> Tautan langsung ke tombol order/LP</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="jw-section jw-section-seo">
        <div class="jw-container jw-columns">
            <div>
                <span class="jw-kicker">Dibangun untuk ditemukan dan dipercaya</span>
                <h2>Premium di mata pelanggan. Sehat di mata Google.</h2>
                <p class="jw-intro">Keindahan visual tidak berdiri sendiri. Setiap halaman disusun agar mudah dipindai, cepat digunakan, dan memiliki konteks SEO yang konsisten untuk wilayah Solo Raya.</p>
            </div>
            <ul class="jw-list">
                <li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Mobile-first, aksesibel, dan nyaman dibaca di berbagai perangkat.</span></li>
                <li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Meta title, schema service, sitemap, canonical, dan internal link.</span></li>
                <li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Struktur cepat, aman, dan siap dikembangkan kapan saja.</span></li>
                <li><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12 4 4L19 6"/></svg><span>Integrasi WhatsApp, formulir inquiry, API, dan sistem bisnis.</span></li>
            </ul>
        </div>
    </section>

    <section class="jw-section jw-section-faq jw-section-alt">
        <div class="jw-container jw-section-center">
            <span class="jw-kicker">Pertanyaan umum</span>
            <h2>Masih ingin memastikan sebelum mulai?</h2>
            <div class="jw-faq" style="text-align: left; margin-inline: auto;">
                <details><summary>Berapa harga pembuatan website?</summary><p>Paket website bisnis mulai Rp 2.500.000, sistem informasi custom mulai Rp 10.000.000, dan solusi enterprise disesuaikan dengan kebutuhan proyek Anda.</p></details>
                <details><summary>Berapa lama pengerjaannya?</summary><p>Website standar dapat diselesaikan maksimal dalam waktu dua minggu. Proyek custom atau aplikasi web menyesuaikan tingkat kompleksitas.</p></details>
                <details><summary>Apakah website sudah SEO-friendly?</summary><p>Ya. Struktur heading, metadata, schema, sitemap, performa, mobile-first, dan internal link disiapkan sejak awal agar mudah diindeks Google.</p></details>
                <details><summary>Apakah melayani klien di luar Solo?</summary><p>Ya. Meskipun berbasis di Solo dan melayani seluruh wilayah Soloraya (Surakarta, Karanganyar, Sragen, Wonogiri, Boyolali, Klaten, Sukoharjo), kami juga menangani klien dari berbagai kota di Indonesia.</p></details>
            </div>
        </div>
    </section>

    <section class="jw-final">
        <div class="jw-container">
            <span class="jw-kicker" style="color:#e8c982">Punya proyek dalam pikiran?</span>
            <h2>Website Anda seharusnya membantu menjual, bukan hanya terlihat ada.</h2>
            <p>Ceritakan kebutuhan Anda. Kami bantu menentukan solusi yang paling masuk akal untuk bisnis, anggaran, dan target pelanggan Anda. Mulai dengan percakapan singkat via WhatsApp tanpa komitmen.</p>
            <div class="jw-actions" style="justify-content:center">
                <a class="jw-btn jw-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi jasa pembuatan website untuk bisnis saya.') }}">WhatsApp Saya <span aria-hidden="true">→</span></a>
                <a class="jw-btn jw-btn-ghost" href="{{ url('/contact') }}">Kirim brief proyek</a>
            </div>
        </div>
    </section>
</main>
@php else: @endphp
@include('partials.juki-hero', [
    'badge' => $p['badge'] ?? 'Layanan Spesialisasi Digital',
    'heading' => $isWebsiteService ? 'Jasa Pembuatan Website Profesional untuk Bisnis Anda' : ($p['heading'] ?? $page->title),
    'subheadline' => $isWebsiteService ? 'Bangun website cepat, responsif, aman, dan siap mendukung SEO serta konversi bisnis di Solo, Surakarta, dan seluruh Indonesia.' : ($p['lead'] ?? $page->meta_description),
    'ctaPrimaryText' => 'Konsultasi via WhatsApp',
    'ctaPrimaryUrl' => 'https://wa.me/' . $waNumber . '?text=' . urlencode('Halo Tri Marzuki, saya tertarik konsultasi layanan ' . ($page->title)),
    'ctaSecondaryText' => 'Kirim Inquiry / RFQ',
    'ctaSecondaryUrl' => url('/contact'),
])
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2">
    <nav class="flex items-center text-xs sm:text-sm text-slate-500 gap-2 flex-wrap">
        <a href="{{ url('/') }}" class="hover:text-brand-indigo transition-colors">Beranda</a>
        <span>/</span>
        <a href="{{ url('/jasa') }}" class="hover:text-brand-indigo transition-colors">Layanan</a>
        @php if (!empty($p['parent_url']) && !empty($p['parent_title'])): @endphp
            <span>/</span>
            <a href="{{ url($p['parent_url']) }}" class="hover:text-brand-indigo transition-colors">{{ $p['parent_title'] }}</a>
        @php endif; @endphp
        <span>/</span>
        <span class="text-slate-900 font-medium">{{ $page->title }}</span>
    </nav>
</div>
@php if (!empty($p['value_props'])): @endphp
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php foreach ($p['value_props'] as $idx => $vp): @endphp
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="text-xs font-bold text-brand-indigo uppercase tracking-wider mb-1">0{{ $idx + 1 }} · {{ $vp['tag'] }}</div>
            <div class="font-bold text-slate-900 text-sm mb-1 font-outfit">{{ $vp['title'] }}</div>
            <div class="text-xs text-slate-500 leading-relaxed">{{ $vp['desc'] }}</div>
        </div>
        @php endforeach; @endphp
    </div>
</section>
@php endif; @endphp
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-16 text-slate-700 leading-relaxed font-sans">
    <div class="prose prose-slate prose-lg max-w-none">
        {!! $page->body_raw !!}
    </div>
</article>
@php endif; @endphp
@endsection
