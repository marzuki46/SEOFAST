@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;
    $siteName = SystemSetting::get('site_name', config('app.name', 'Juki'));
    $logoUrl = SystemSetting::get('logo_url', 'https://juki.eu.org/storage/content-images/xjuki-website-developer-solo-landscape-1785293658.webp.pagespeed.ic.ehTKUU0dbo.webp');
    $logoAlt = SystemSetting::get('logo_alt', 'Juki Website Developer Solo');
    $waNumber = SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
    $canonicalUrl = url('/' . ltrim($page->slug, '/'));
    $p = $serviceParams ?? [];
@endphp

@section('title', ($page->meta_title ?: $page->title) . ' — ' . $siteName)
@section('meta_description', $page->meta_description ?: ($p['lead'] ?? ''))
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
    "description": "{{ $page->meta_description }}",
    "url": "{{ $canonicalUrl }}"
}
</script>
@endsection

@section('content')
<!-- Hero Section (Konsisten dengan Juki Hero + Produk) -->
@include('partials.juki-hero', [
    'badge' => $p['badge'] ?? 'Layanan Spesialisasi Digital',
    'heading' => $p['heading'] ?? $page->title,
    'subheadline' => $p['lead'] ?? $page->meta_description,
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
        {!! $page->html_content ?: $page->content !!}
    </div>

    <!-- Related Services Nav -->
    @if(!empty($p['related_links']))
    <div class="mt-12 pt-8 border-t border-slate-200">
        <h3 class="text-xl font-bold text-slate-900 font-outfit mb-4">Layanan Terkait Lainnya</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($p['related_links'] as $rl)
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
