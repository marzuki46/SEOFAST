@extends('buyer.layouts.app')

@section('header')
Order #{{ $order->order_number }}
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('buyer.orders.index') }}" class="text-sm text-slate-500 hover:text-slate-900 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Riwayat
        </a>
        <div>
            {!! $order->status_badge !!}
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
            <h3 class="text-lg font-bold font-outfit text-slate-900">Detail Pesanan</h3>
        </div>
        <div class="p-6">
            <div class="flex gap-6 mb-6 pb-6 border-b border-slate-100">
                <div class="w-20 h-20 bg-slate-100 rounded-lg flex-shrink-0">
                    @if($order->product->image_url)
                        <img src="{{ $order->product->image_url }}" class="w-full h-full object-cover rounded-lg" alt="" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>
                <div>
                    <h4 class="text-xl font-bold text-slate-900">{{ $order->product->name }}</h4>
                    <p class="text-sm text-slate-500 mt-1">{{ $order->product->description }}</p>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Tanggal Order</span>
                    <span class="font-medium text-slate-900">{{ $order->created_at->format('d F Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Harga Produk</span>
                    <span class="font-medium text-slate-900">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Kode Unik</span>
                    <span class="font-medium text-slate-900 text-brand-indigo">+ Rp {{ number_format($order->unique_amount, 0, ',', '.') }}</span>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-base font-bold text-slate-900">Total Pembayaran</span>
                    <span class="text-2xl font-bold font-outfit text-brand-indigo">Rp {{ number_format($order->amount + $order->unique_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($order->status === 'pending')
    <div class="bg-white rounded-xl shadow-sm border border-brand-indigo overflow-hidden">
        <div class="px-6 py-5 border-b border-indigo-100 bg-indigo-50/50">
            <h3 class="text-lg font-bold font-outfit text-brand-indigo">Instruksi Pembayaran</h3>
        </div>
        <div class="p-6">
            <p class="text-sm text-slate-600 mb-4">Silakan transfer tepat sejumlah <strong class="text-slate-900">Rp {{ number_format($order->amount + $order->unique_amount, 0, ',', '.') }}</strong> (pastikan 3 digit terakhir sama) ke rekening berikut:</p>
            
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 mb-6 font-mono text-center">
                <p class="text-sm text-slate-500 uppercase tracking-wider mb-1">Bank BCA</p>
                <p class="text-2xl font-bold text-slate-900">8821 3456 78</p>
                <p class="text-sm text-slate-500 mt-1">a.n. PT SEOFAST Teknologi</p>
            </div>

            <form action="{{ route('buyer.orders.upload_proof', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="block text-sm font-medium text-slate-700 mb-2">Upload Bukti Transfer (JPG/PNG)</label>
                <input type="file" name="proof" required accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-brand-indigo hover:file:bg-indigo-100 border border-slate-300 rounded-lg p-2">
                <button type="submit" class="w-full mt-4 flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand-indigo hover:bg-indigo-700">
                    Kirim Bukti Pembayaran
                </button>
            </form>
        </div>
    </div>
    @elseif($order->status === 'paid')
    <div class="bg-amber-50 rounded-xl shadow-sm border border-amber-200 p-6 text-center">
        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-amber-900">Pembayaran Sedang Diverifikasi</h3>
        <p class="mt-2 text-sm text-amber-700">Terima kasih telah melakukan pembayaran. Tim kami sedang melakukan verifikasi maksimal 1x24 jam kerja.</p>
    </div>
    @elseif($order->status === 'verified')
    <div class="bg-green-50 rounded-xl shadow-sm border border-green-200 p-6 text-center">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-green-900">Pembayaran Berhasil Diverifikasi</h3>
        <p class="mt-2 text-sm text-green-700 mb-4">Akses produk Anda telah dibuka.</p>
        <a href="{{ route('buyer.products.index') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">Buka Produk Saya</a>
    </div>
    @elseif($order->status === 'rejected')
    <div class="bg-red-50 rounded-xl shadow-sm border border-red-200 p-6">
        <h3 class="text-lg font-bold text-red-900 mb-2">Pembayaran Ditolak</h3>
        <p class="text-sm text-red-700">Mohon maaf, pembayaran Anda tidak dapat diverifikasi.</p>
        @if($order->admin_note)
            <div class="mt-4 p-4 bg-white/50 rounded border border-red-100 text-sm text-red-800">
                <strong>Catatan Admin:</strong> {{ $order->admin_note }}
            </div>
        @endif
    </div>
    @endif
</div>
@endsection
