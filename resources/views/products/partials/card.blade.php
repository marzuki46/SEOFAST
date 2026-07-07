<div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all group">
    @if($product->image_url)
    <a href="{{ route('products.show', $product->slug) }}" class="block mb-4 overflow-hidden rounded-xl">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">
    </a>
    @endif
    <h2 class="text-xl font-bold text-slate-900 font-outfit">{{ $product->name }}</h2>
    <p class="text-slate-600 my-3 text-sm leading-relaxed line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 120) }}</p>
    <div class="text-lg font-bold text-brand-indigo mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
    <a href="{{ route('products.show', $product->slug) }}" class="inline-flex items-center text-sm font-semibold text-brand-indigo hover:text-brand-purple transition-colors group-hover:underline">
        View Details
        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>
</div>
