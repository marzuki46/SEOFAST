@extends('layouts.frontend')

@section('title', $product->name . ' — Beli Produk SEOFAST')
@section('meta_description', Str::limit(strip_tags($product->description), 150))
@section('og_image', asset($product->image_url ?? 'assets/og-default.jpg'))
@section('canonical_url', url()->current())

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4">
    <div class="bg-white border rounded-lg p-8 shadow-sm">
        <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
        <div class="text-2xl font-bold text-indigo-600 mb-6">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        <div class="prose max-w-none">
            {{ $product->description }}
        </div>
        <form action="{{ route('products.order', $product->id) }}" method="POST" class="mt-8">
            @csrf
            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700">Order Now</button>
        </form>
    </div>
</div>

<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->name }}",
  "image": "{{ asset($product->image_url ?? 'assets/og-default.jpg') }}",
  "description": "{{ Str::limit(strip_tags($product->description), 150) }}",
  "sku": "PROD-{{ $product->id }}",
  "brand": {
    "@type": "Brand",
    "name": "SEOFAST"
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
