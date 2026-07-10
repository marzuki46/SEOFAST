@extends('layouts.frontend')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="max-w-2xl mx-auto py-20 px-4 text-center">
    @if($order->status === 'verified')
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold font-outfit text-slate-900 mb-4">Pembayaran Berhasil!</h1>
        <p class="text-slate-600 mb-2">Terima kasih, pembayaran Anda untuk <strong>{{ $order->product->name }}</strong> telah diterima dan diverifikasi.</p>
        <p class="text-slate-500 mb-8">Akses produk Anda telah aktif. Silakan buka dashboard untuk mengunduh atau mengakses materi.</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('buyer.products.access', $order->access) }}" class="bg-gradient-to-r from-brand-indigo to-brand-purple text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all shadow-md">
                Akses Produk
            </a>
            <a href="{{ route('buyer.dashboard') }}" class="bg-slate-100 text-slate-700 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-all">
                Dashboard
            </a>
        </div>
    @elseif($order->status === 'pending')
        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold font-outfit text-slate-900 mb-4">Pembayaran Diproses</h1>
        <p class="text-slate-600 mb-2">Pembayaran Anda sedang diproses oleh sistem.</p>
        <p class="text-slate-500 mb-8">Kami akan memperbarui status pesanan secara otomatis. Silakan cek dashboard secara berkala.</p>
        <a href="{{ route('buyer.orders.show', $order) }}" class="bg-gradient-to-r from-brand-indigo to-brand-purple text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all shadow-md inline-block">
            Lihat Status Pesanan
        </a>
    @endif
</div>
@endsection
