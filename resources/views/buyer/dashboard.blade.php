@extends('buyer.layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat Cards -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-brand-indigo">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Produk Dimiliki</p>
                <p class="text-2xl font-bold font-outfit text-slate-900">{{ count($ownedProducts) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Menunggu Pembayaran</p>
                <p class="text-2xl font-bold font-outfit text-slate-900">{{ $pendingOrders }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Products -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Owned Products -->
        @if(count($ownedProducts) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Akses Produk Anda</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($ownedProducts as $access)
                <div class="p-6 flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                    <div class="w-24 h-24 rounded-lg bg-slate-100 flex-shrink-0 flex items-center justify-center border border-slate-200">
                        @if($access->product->image_url)
                            <img src="{{ $access->product->image_url }}" class="w-full h-full object-cover rounded-lg" alt="" loading="lazy">
                        @else
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-slate-900">{{ $access->product->name }}</h4>
                        <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $access->product->description }}</p>
                        <div class="mt-3 flex gap-4 text-xs text-slate-500">
                            <span>Diakses: {{ $access->access_count }} kali</span>
                            @if($access->expires_at)
                                <span class="{{ $access->isExpired() ? 'text-red-500 font-medium' : '' }}">
                                    Kedaluwarsa: {{ $access->expires_at->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-green-600 font-medium border border-green-200 bg-green-50 px-2 py-0.5 rounded">Lifetime</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('buyer.products.access', $access) }}" class="inline-flex items-center px-4 py-2 bg-brand-indigo text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            Akses Produk
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <h3 class="mt-4 text-lg font-bold font-outfit text-slate-900">Belum Ada Produk</h3>
            <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">Anda belum memiliki produk digital apapun. Silakan cek penawaran produk kami di bawah.</p>
        </div>
        @endif

        <!-- Offers / All Products -->
        <h3 class="text-xl font-bold font-outfit text-slate-900 mb-4 pt-4">Penawaran Menarik</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @forelse($allProducts as $product)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="h-48 bg-slate-100 relative">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" class="w-full h-full object-cover" alt="" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 px-2 py-1 bg-white/90 backdrop-blur rounded text-xs font-bold text-slate-900 shadow-sm">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h4 class="font-bold text-slate-900 text-lg">{{ $product->name }}</h4>
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2 flex-1">{{ $product->description }}</p>
                        <a href="#" class="mt-4 block w-full py-2 text-center text-sm font-medium text-brand-indigo bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                            Beli Sekarang
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-slate-50 rounded-xl border border-slate-200 border-dashed p-8 text-center text-slate-500 text-sm">
                    Belum ada produk lain yang tersedia saat ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Right Column: Orders -->
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Transaksi Terakhir</h3>
                <a href="{{ route('buyer.orders.index') }}" class="text-sm text-brand-indigo hover:text-indigo-700 font-medium">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentOrders as $order)
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                            <p class="text-sm font-medium text-slate-900 mt-1">{{ $order->product->name }}</p>
                        </div>
                        <div>
                            {!! $order->status_badge !!}
                        </div>
                    </div>
                    <div class="flex justify-between items-end mt-4">
                        <p class="text-lg font-bold font-outfit text-brand-indigo">
                            Rp {{ number_format($order->amount + $order->unique_amount, 0, ',', '.') }}
                        </p>
                        <a href="{{ route('buyer.orders.show', $order) }}" class="text-xs font-medium text-slate-500 hover:text-slate-900 border border-slate-200 px-2 py-1 rounded">
                            Detail
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-500 text-sm">
                    Belum ada riwayat transaksi.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
