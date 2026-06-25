@extends('layouts.admin')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.orders.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    Order #{{ $order->order_number }}
</div>
@endsection

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Detail & Bukti -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Detail Pembayaran</h3>
                {!! $order->status_badge !!}
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Tanggal Transaksi</p>
                        <p class="font-medium text-slate-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 mb-1">Total Dibayar</p>
                        <p class="text-xl font-bold font-outfit text-brand-indigo">Rp {{ number_format($order->amount + $order->unique_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <hr class="border-slate-100 mb-6">

                <h4 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Bukti Transfer</h4>
                @if($order->payment_proof)
                    <div class="border border-slate-200 rounded-lg p-2 bg-slate-50 inline-block">
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" class="max-w-full h-auto max-h-96 rounded object-contain" alt="Bukti Transfer">
                        </a>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Klik gambar untuk memperbesar</p>
                @else
                    <div class="p-6 text-center border border-dashed border-slate-300 rounded-lg bg-slate-50 text-slate-500">
                        Buyer belum mengunggah bukti pembayaran.
                    </div>
                @endif
            </div>
        </div>

        @if(in_array($order->status, ['pending', 'paid']))
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Tindakan Verifikasi</h3>
            </div>
            <div class="p-6">
                <div class="flex gap-4">
                    <form action="{{ route('admin.orders.verify', $order) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors" onclick="return confirm('Verifikasi pembayaran dan buka akses produk untuk buyer ini?')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Verifikasi & Buka Akses
                        </button>
                    </form>
                    
                    <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="flex justify-center items-center py-2.5 px-4 border border-red-200 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors font-medium text-sm">
                        Tolak
                    </button>
                </div>

                <div id="reject-form" class="mt-4 hidden border-t border-slate-100 pt-4">
                    <form action="{{ route('admin.orders.reject', $order) }}" method="POST">
                        @csrf
                        <label class="block text-sm font-medium text-slate-700 mb-1">Alasan Penolakan (Catatan untuk Buyer)</label>
                        <textarea name="note" rows="3" required class="block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"></textarea>
                        <button type="submit" class="mt-3 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">Submit Penolakan</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column: Buyer & Product Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Informasi Buyer</h3>
            </div>
            <div class="p-6 text-center">
                <img src="{{ $order->buyer->avatar_url }}" class="w-20 h-20 rounded-full mx-auto mb-3" alt="">
                <h4 class="font-bold text-slate-900 text-lg">{{ $order->buyer->name }}</h4>
                <p class="text-slate-500 text-sm">{{ $order->buyer->email }}</p>
                <div class="mt-4 pt-4 border-t border-slate-100 text-left text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Total Order</span>
                        <span class="font-medium text-slate-900">{{ $order->buyer->orders()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Produk Dimiliki</span>
                        <span class="font-medium text-slate-900">{{ $order->buyer->productAccesses()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Produk Dibeli</h3>
            </div>
            <div class="p-6">
                <div class="flex gap-4">
                    <div class="w-16 h-16 bg-slate-100 rounded flex-shrink-0">
                        @if($order->product->image_url)
                            <img src="{{ $order->product->image_url }}" class="w-full h-full object-cover rounded" alt="">
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900">{{ $order->product->name }}</h4>
                        <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $order->product->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($order->status === 'verified')
        <div class="bg-green-50 rounded-xl shadow-sm border border-green-200 p-6">
            <h3 class="text-sm font-bold text-green-900 uppercase tracking-wider mb-2">Status Akses</h3>
            <p class="text-sm text-green-700">Akses telah dibuka pada {{ $order->verified_at->format('d M Y H:i') }} oleh {{ $order->verifiedBy->name ?? 'System' }}.</p>
        </div>
        @endif
    </div>

</div>
@endsection
