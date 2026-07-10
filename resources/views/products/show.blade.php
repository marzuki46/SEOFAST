@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;
    $siteName = SystemSetting::get('site_name', config('app.name'));
    $sections = $product->display_sections ?? ['description', 'features', 'specifications', 'faq', 'changelog', 'documentation'];
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
  "brand": { "@type": "Brand", "name": "{{ $siteName }}" },
  "offers": {
    "@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "IDR",
    "price": "{{ $product->displayPrice() }}",
    "availability": "https://schema.org/InStock",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-6">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('products.catalog') }}" class="hover:text-slate-900 transition-colors">Products</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 font-semibold">{{ $product->name }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8 mb-10">
        <!-- Left: Gallery -->
        <div class="w-full lg:w-1/2">
            <div x-data="{ active: 0, images: {{ Js::from(array_filter(array_merge([$product->image_url], $product->gallery_images ?? []))) }} }" class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden aspect-[4/3] flex items-center justify-center p-4">
                    <img :src="images[active]" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain">
                </div>
                <template x-if="images.length > 1">
                    <div class="flex gap-2 overflow-x-auto pb-2">
                        <template x-for="(img, i) in images" :key="i">
                            <button @click="active = i" :class="active === i ? 'border-indigo-500 ring-2 ring-indigo-200' : 'border-slate-200 hover:border-slate-400'" class="flex-shrink-0 w-16 h-16 border-2 rounded-lg overflow-hidden bg-white p-1 transition-all">
                                <img :src="img" alt="" class="w-full h-full object-contain">
                            </button>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <!-- Right: Info -->
        <div class="w-full lg:w-1/2 space-y-6">
            @if($product->categories->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach($product->categories as $cat)
                    <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">{{ $cat->name }}</span>
                @endforeach
            </div>
            @endif

            <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 font-outfit">{{ $product->name }}</h1>

            <div class="text-3xl font-bold {{ $product->isDevelopment() ? 'text-emerald-600' : 'text-brand-indigo' }}">{{ $product->displayPriceFormatted() }}</div>
            @if($product->isDevelopment())
            <div class="flex items-center gap-2 text-sm text-slate-400">
                <span class="line-through">{{ $product->originalPriceFormatted() }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 uppercase">Early Bird — Harga Naik Setelah Launch</span>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                @if($product->enable_buy_button)
                <form action="{{ route('products.order', $product->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-brand-indigo text-white px-6 py-3.5 rounded-xl font-bold hover:opacity-90 transition-all shadow-md shadow-brand-indigo/20 text-sm">
                        Beli Sekarang — {{ $product->displayPriceFormatted() }}
                    </button>
                </form>
                @endif
                @if($product->enable_inquiry_button)
                <button type="button" @click="$dispatch('open-preorder')"
                   class="flex-1 flex items-center justify-center gap-2 border-2 border-slate-300 text-slate-700 px-6 py-3.5 rounded-xl font-bold hover:border-brand-indigo hover:text-brand-indigo transition-all text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    {{ $product->inquiry_label ?: 'Pre-Order' }}
                </button>
                @endif
            </div>

            @if(in_array('features', $sections) && $product->features)
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                <h3 class="font-bold text-slate-900 font-outfit mb-3">Fitur Produk</h3>
                <ul class="space-y-2">
                    @foreach($product->features as $feature)
                    <li class="flex items-start gap-3 text-sm text-slate-700">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    <!-- Tabs -->
    @php
        $tabs = [];
        if (in_array('description', $sections) && $product->description) $tabs['description'] = 'Deskripsi';
        if (in_array('specifications', $sections) && $product->specifications) $tabs['specifications'] = 'Spesifikasi';
        if (in_array('faq', $sections) && $product->faq) $tabs['faq'] = 'FAQ';
        if (in_array('changelog', $sections) && $product->changelog) $tabs['changelog'] = 'Changelog';
        if (in_array('documentation', $sections) && $product->documentation) $tabs['documentation'] = 'Dokumentasi';
    @endphp

    @if(count($tabs) > 0)
    <div x-data="{ tab: '{{ array_key_first($tabs) }}' }" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="flex border-b border-slate-200 bg-slate-50 overflow-x-auto">
            @foreach($tabs as $key => $label)
            <button @click="tab = '{{ $key }}'" :class="tab === '{{ $key }}' ? 'border-brand-indigo text-brand-indigo bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3.5 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap">{{ $label }}</button>
            @endforeach
        </div>
        <div class="p-6 min-h-[300px]">
            @foreach($tabs as $key => $label)
            <div x-show="tab === '{{ $key }}'" x-cloak>
                @if($key === 'description')
                <div class="prose max-w-none text-slate-700">
                    {!! nl2br(e($product->description)) !!}
                </div>
                @elseif($key === 'specifications')
                <table class="w-full text-sm">
                    <tbody>
                        @foreach($product->specifications as $spec)
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-4 font-semibold text-slate-700 w-1/3 align-top">{{ $spec['key'] }}</td>
                            <td class="py-3 text-slate-600">{{ $spec['value'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @elseif($key === 'faq')
                <div class="space-y-4">
                    @foreach($product->faq as $faq)
                    <div x-data="{ open: false }" class="border border-slate-200 rounded-xl overflow-hidden">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-4 text-left font-semibold text-slate-900 hover:bg-slate-50 transition-colors text-sm">
                            <span>{{ $faq['question'] }}</span>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="px-5 pb-4 text-sm text-slate-600 leading-relaxed">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @elseif($key === 'changelog')
                <div class="space-y-8">
                    @foreach($product->changelog as $version)
                    <div class="relative pl-6 border-l-2 border-slate-200">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white shadow-sm
                            @if(($version['color'] ?? '') === 'emerald') bg-emerald-500
                            @elseif(($version['color'] ?? '') === 'amber') bg-amber-500
                            @else bg-indigo-500 @endif">
                        </div>
                        <div class="mb-3">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold
                                @if(($version['color'] ?? '') === 'emerald') bg-emerald-100 text-emerald-700
                                @elseif(($version['color'] ?? '') === 'amber') bg-amber-100 text-amber-700
                                @else bg-indigo-100 text-indigo-700 @endif">
                                v{{ $version['version'] }}
                                @if(!empty($version['label']))
                                <span class="ml-1">— {{ $version['label'] }}</span>
                                @endif
                            </span>
                            @if(!empty($version['date']))
                            <span class="ml-2 text-xs text-slate-400">{{ $version['date'] }}</span>
                            @endif
                        </div>
                        @if(!empty($version['changes']))
                            @foreach($version['changes'] as $group)
                                @if(!empty($group['items']))
                                <ul class="space-y-1.5 mb-3 last:mb-0">
                                    @foreach($group['items'] as $item)
                                    <li class="flex items-start gap-2 text-sm text-slate-600">
                                        <span class="shrink-0 mt-0.5
                                            @if(($group['type'] ?? '') === 'added') text-emerald-500
                                            @elseif(($group['type'] ?? '') === 'fixed') text-amber-500
                                            @else text-indigo-500 @endif font-bold">+</span>
                                        {{ $item }}
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    @endforeach
                </div>
                @elseif($key === 'documentation')
                <div class="prose max-w-none">
                    {!! $product->documentation !!}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Pre-Order Modal -->
<div x-data="{ open: false }" @open-preorder.window="open = true" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="open = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-100">
            <form action="{{ route('products.pre-order', $product->id) }}" method="POST">
                @csrf
                <div class="px-6 pt-6 pb-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold font-outfit text-slate-900">{{ $product->inquiry_label ?: 'Pre-Order' }}</h3>
                        <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <p class="text-sm text-slate-500">Isi data kamu, kami akan memberitahu saat <strong>{{ $product->name }}</strong> diluncurkan.</p>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-indigo focus:ring-1 focus:ring-brand-indigo outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-indigo focus:ring-1 focus:ring-brand-indigo outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No. WhatsApp (opsional)</label>
                        <input type="text" name="phone" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-indigo focus:ring-1 focus:ring-brand-indigo outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Catatan (opsional)</label>
                        <textarea name="notes" rows="2" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-indigo focus:ring-1 focus:ring-brand-indigo outline-none"></textarea>
                    </div>
                </div>
                <div class="px-6 pb-6 flex gap-3">
                    <button type="button" @click="open = false" class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-brand-indigo to-brand-purple rounded-xl hover:opacity-90 transition shadow-md">
                        {{ $product->inquiry_label ?: 'Pre-Order Sekarang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
