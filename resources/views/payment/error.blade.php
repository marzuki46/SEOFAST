@extends('layouts.frontend')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="max-w-2xl mx-auto py-20 px-4 text-center">
    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </div>
    <h1 class="text-3xl font-bold font-outfit text-slate-900 mb-4">Pembayaran Gagal</h1>
    <p class="text-slate-600 mb-2">Maaf, pembayaran Anda tidak dapat diproses.</p>
    <p class="text-slate-500 mb-8">Silakan coba lagi dengan metode pembayaran yang berbeda atau hubungi support kami.</p>
    <div class="flex justify-center gap-4">
        <a href="{{ route('products.catalog') }}" class="bg-gradient-to-r from-brand-indigo to-brand-purple text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all shadow-md inline-block">
            Kembali ke Produk
        </a>
        <a href="{{ route('buyer.orders.index') }}" class="bg-slate-100 text-slate-700 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-all inline-block">
            Cek Pesanan
        </a>
    </div>
</div>
@endsection
