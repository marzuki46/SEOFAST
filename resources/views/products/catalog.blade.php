@extends('layouts.frontend')

@php
    use App\Models\ProductCategory;
    use App\Models\SystemSetting;
    $page = request('page', 1);
    $canonicalUrl = url()->current() . ($page > 1 ? '?page=' . $page : '');
    $currentCategory = request('category');
    $currentSort = request('sort', 'latest');
    $currentQ = request('q');
    $siteName = SystemSetting::get('site_name', config('app.name'));
    $currentCategoryName = $currentCategory ? optional(ProductCategory::where('slug', $currentCategory)->first())->name : null;
@endphp
@section('title', $currentCategoryName ? $currentCategoryName . ' — ' . $siteName : 'Katalog Produk Digital — ' . $siteName)
@section('meta_description', $currentCategoryName ? 'Belanja produk digital kategori ' . $currentCategoryName . ' di ' . $siteName : 'Jelajahi produk digital ' . $siteName . ' untuk optimasi SEO, konten AI, dan perangkat otomasi pemasaran.')
@section('canonical_url', $canonicalUrl)

@section('head_extra')
@if($products->hasMorePages())
    <link rel="next" href="{{ $products->nextPageUrl() }}">
@endif
@if(!$products->onFirstPage())
    <link rel="prev" href="{{ $products->previousPageUrl() }}">
@endif
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ItemList",
    "itemListElement": [
        @foreach($products as $i => $product)
        {
            "@@type": "ListItem",
            "position": {{ $i + 1 + (($products->currentPage() - 1) * $products->perPage()) }},
            "item": {
                "@@type": "Product",
                "name": {{ Js::from($product->name) }},
                "url": {{ Js::from(route('products.show', $product->slug)) }},
                "description": {{ Js::from(strip_tags($product->description)) }},
                "offers": {
                    "@@type": "Offer",
                    "price": "{{ $product->price }}",
                    "priceCurrency": "IDR"
                }
            }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <h1 class="text-3xl font-bold text-slate-900 font-outfit">Digital Products</h1>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <form method="GET" action="{{ route('products.catalog') }}" class="flex items-center w-full sm:w-auto">
                @if($currentCategory)
                    <input type="hidden" name="category" value="{{ $currentCategory }}">
                @endif
                <div class="relative w-full sm:w-auto">
                    <input type="text" name="q" value="{{ $currentQ }}" placeholder="Search products..."
                           class="w-full sm:w-48 lg:w-56 rounded-xl border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-brand-indigo focus:ring-1 focus:ring-brand-indigo outline-none">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>

            <form method="GET" action="{{ route('products.catalog') }}" id="sortForm">
                @if($currentCategory)<input type="hidden" name="category" value="{{ $currentCategory }}">@endif
                @if($currentQ)<input type="hidden" name="q" value="{{ $currentQ }}">@endif
                <select name="sort" onchange="this.form.submit()"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-indigo outline-none bg-white w-full sm:w-auto">
                    <option value="latest" {{ $currentSort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ $currentSort === 'price_asc' ? 'selected' : '' }}>Harga: Rendah - Tinggi</option>
                    <option value="price_desc" {{ $currentSort === 'price_desc' ? 'selected' : '' }}>Harga: Tinggi - Rendah</option>
                    <option value="name_asc" {{ $currentSort === 'name_asc' ? 'selected' : '' }}>Nama: A - Z</option>
                    <option value="name_desc" {{ $currentSort === 'name_desc' ? 'selected' : '' }}>Nama: Z - A</option>
                </select>
            </form>
        </div>
    </div>

    @if($categories->isNotEmpty())
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('products.catalog') }}{{ $currentQ ? '?q='.$currentQ : '' }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ !$currentCategory ? 'bg-brand-indigo text-white' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-900' }}">
            All Products
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('products.catalog', array_filter(['category' => $cat->slug, 'q' => $currentQ, 'sort' => $currentSort !== 'latest' ? $currentSort : null])) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $currentCategory === $cat->slug ? 'bg-brand-indigo text-white' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-900' }}">
            {{ $cat->name }}
            <span class="text-xs opacity-70 ml-1">({{ $cat->products_count }})</span>
        </a>
        @endforeach
    </div>
    @endif

    <div>
        @if($products->isEmpty())
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <h3 class="text-lg font-bold text-slate-700 font-outfit">No products found</h3>
            <p class="text-slate-500 text-sm mt-2">Try adjusting your search or filter criteria.</p>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($products as $product)
                @include('products.partials.card')
            @endforeach
        </div>
        <div class="mt-10">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
