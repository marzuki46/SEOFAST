<div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group">
    <a href="{{ route('products.show', $product->slug) }}" class="block mb-4 overflow-hidden rounded-xl bg-slate-100">
        <img src="{{ $product->image_url ?: asset('images/seofast-placeholder.svg') }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">
    </a>
    <h2 class="text-xl font-bold text-slate-900 font-outfit group-hover:text-brand-indigo transition-colors">
        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
    </h2>
    <p class="text-slate-600 my-3 text-sm leading-relaxed line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 100) }}</p>
    @if($product->features && count($product->features) > 0)
    <div class="flex flex-wrap gap-1.5 mb-3">
        @foreach(array_slice($product->features, 0, 4) as $feature)
        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100">{{ $feature }}</span>
        @endforeach
        @if(count($product->features) > 4)
        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-500">+{{ count($product->features) - 4 }}</span>
        @endif
    </div>
    @endif
    @if($product->isDevelopment())
    <div class="mb-4">
        <div class="text-lg font-bold text-emerald-600">{{ $product->displayPriceFormatted() }}</div>
        <div class="text-xs text-slate-400">
            <span class="line-through">{{ $product->originalPriceFormatted() }}</span>
            <span class="ml-1.5 inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 uppercase">Early Bird</span>
        </div>
    </div>
    @else
    <div class="text-lg font-bold text-brand-indigo mb-4">{{ $product->displayPriceFormatted() }}</div>
    @endif
    <div class="flex gap-2">
        <a href="{{ route('products.show', $product->slug) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 text-sm font-semibold text-brand-indigo border border-brand-indigo hover:bg-brand-indigo hover:text-white transition-all px-4 py-2.5 rounded-xl">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Lihat Detail
        </a>
        <a href="{{ route('products.show', $product->slug) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 text-sm font-bold text-white bg-brand-indigo hover:opacity-90 transition-all px-4 py-2.5 rounded-xl shadow-sm">
            Beli
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
        </a>
    </div>
</div>
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $product->name }}",
  "image": "{{ asset($product->image_url ?: 'images/seofast-placeholder.svg') }}",
  "description": "{{ strip_tags($product->description) }}",
  "sku": "PROD-{{ $product->id }}",
  "offers": {
    "@@type": "Offer",
    "priceCurrency": "IDR",
    "price": "{{ $product->displayPrice() }}",
    "availability": "https://schema.org/InStock",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>
