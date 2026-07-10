@extends('layouts.frontend')

@php
    use App\Services\Payment\MidtransService;
    $midtrans = app(MidtransService::class);
    $clientKey = $midtrans->getClientKey();
    $isProduction = $midtrans->isProduction();
@endphp

@section('title', 'Checkout — ' . $order->product->name)

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900 font-outfit mb-6">Checkout</h1>

        <div class="flex items-center gap-4 pb-6 border-b border-slate-100 mb-6">
            @if($order->product->image_url)
                <img src="{{ $order->product->image_url }}" class="w-20 h-20 object-cover rounded-xl" alt="" loading="lazy">
            @endif
            <div>
                <h2 class="text-lg font-bold text-slate-900">{{ $order->product->name }}</h2>
                <p class="text-2xl font-bold text-brand-indigo mt-1">Rp {{ number_format($order->product->price, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="space-y-3 text-sm mb-8">
            <div class="flex justify-between">
                <span class="text-slate-500">Order Number</span>
                <span class="font-mono font-bold text-slate-800">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Status</span>
                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">Menunggu Pembayaran</span>
            </div>
        </div>

        <button id="pay-button" class="w-full bg-gradient-to-r from-brand-indigo to-brand-purple text-white px-6 py-4 rounded-xl font-bold hover:opacity-90 transition-all shadow-md text-lg">
            Bayar Sekarang — Rp {{ number_format($order->product->price, 0, ',', '.') }}
        </button>

        <p class="text-xs text-slate-400 text-center mt-4">Pembayaran diproses melalui Midtrans. Aman & terenkripsi.</p>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://app.{{ $isProduction ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                window.location.href = '{{ route('payment.finish') }}?order_id={{ $order->order_number }}';
            },
            onPending: function (result) {
                window.location.href = '{{ route('payment.pending') }}?order_id={{ $order->order_number }}';
            },
            onError: function (result) {
                window.location.href = '{{ route('payment.error') }}?order_id={{ $order->order_number }}';
            },
            onClose: function () {
                // User closed the popup without completing
                alert('Pembayaran dibatalkan. Silakan coba lagi kapan saja.');
            }
        });
    });

    // Auto-open Snap
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('pay-button').click();
    });
</script>
@endsection
