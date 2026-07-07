@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;
    $siteName = SystemSetting::get('site_name', 'SEOFAST');
@endphp
@section('title', $product->name . ' — ' . $siteName)
@section('meta_description', strip_tags($product->description))
@section('og_image', $product->image_url ? asset($product->image_url) : SystemSetting::get('seo_global_og_image', asset('assets/og-default.jpg')))
@section('og_title', $product->name)
@section('og_description', strip_tags($product->description))
@section('canonical_url', url()->current())

@section('schema_markup')
@php
    $crumbs = [
        ['name' => 'Home', 'url' => route('home')],
        ['name' => 'Products', 'url' => route('products.catalog')],
        ['name' => $product->name, 'url' => request()->url()],
    ];
@endphp
{!! \App\Services\SeoHelper::breadcrumbSchema($crumbs) !!}
{!! \App\Services\SeoHelper::renderSchema($product) !!}
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $product->name }}",
  "image": "{{ $product->image_url ? asset($product->image_url) : SystemSetting::get('seo_global_og_image', asset('assets/og-default.jpg')) }}",
  "description": "{{ strip_tags($product->description) }}",
  "sku": "PROD-{{ $product->id }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $siteName }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "IDR",
    "price": "{{ $product->price }}",
    "availability": "https://schema.org/InStock",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4">
    <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-8">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.catalog') }}" class="hover:text-slate-900 transition-colors">Products</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 font-semibold">{{ $product->name }}</span>
    </nav>

    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
        <h1 class="text-3xl font-bold mb-4 text-slate-900 font-outfit">{{ $product->name }}</h1>
        <div class="text-2xl font-bold text-brand-indigo mb-6">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        <div class="prose max-w-none text-slate-700">
            {!! nl2br(e($product->description)) !!}
        </div>
        <form action="{{ route('products.order', $product->id) }}" method="POST" class="mt-8">
            @csrf
            <button type="submit" class="bg-gradient-to-r from-brand-indigo to-brand-purple text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all shadow-md">Order Now</button>
        </form>
    </div>
</div>
@endsection
