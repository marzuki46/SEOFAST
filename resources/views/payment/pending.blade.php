@extends('layouts.frontend')

@section('title', 'Pembayaran Tertunda')

@section('content')
<div class="max-w-2xl mx-auto py-20 px-4 text-center">
    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    <h1 class="text-3xl font-bold font-outfit text-slate-900 mb-4">Pembayaran Tertunda</h1>
    <p class="text-slate-600 mb-2">Pembayaran Anda masih dalam proses dan menunggu konfirmasi.</p>
    <p class="text-slate-500 mb-8">Silakan selesaikan pembayaran Anda melalui metode yang dipilih. Status akan diperbarui secara otomatis.</p>
    <a href="{{ route('buyer.orders.index') }}" class="bg-gradient-to-r from-brand-indigo to-brand-purple text-white px-6 py-3 rounded-xl font-bold hover:opacity-90 transition-all shadow-md inline-block">
        Cek Pesanan Saya
    </a>
</div>
@endsection
