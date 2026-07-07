@extends('layouts.frontend')

@php
    $page = request('page', 1);
    $canonicalUrl = url()->current() . ($page > 1 ? '?page=' . $page : '');
@endphp
@section('title', 'Katalog Produk Digital — ' . \App\Models\SystemSetting::get('site_name', 'SEOFAST'))
@section('meta_description', 'Jelajahi produk digital SEOFAST untuk optimasi SEO, konten AI, dan perangkat otomasi pemasaran.')
@section('canonical_url', $canonicalUrl)

@section('head_extra')
@if($products->hasMorePages())
    <link rel="next" href="{{ $products->nextPageUrl() }}">
@endif
@if(!$products->onFirstPage())
    <link rel="prev" href="{{ $products->previousPageUrl() }}">
@endif
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8 text-slate-900 font-outfit">Digital Products</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
            @include('products.partials.card')
        @endforeach
    </div>
</div>
@endsection
