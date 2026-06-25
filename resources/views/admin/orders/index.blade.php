@extends('layouts.admin')

@section('header', 'Manajemen Order Buyer')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">Order Masuk</h1>
        <p class="text-sm text-slate-500 mt-1">Verifikasi pembayaran dan kelola akses produk buyer.</p>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'pending' ? 'border-brand-indigo ring-1 ring-brand-indigo' : 'border-slate-200' }} p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Menunggu (Pending)</p>
                <p class="text-2xl font-bold font-outfit text-slate-900">{{ $stats['pending'] }}</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'paid' ? 'border-brand-indigo ring-1 ring-brand-indigo' : 'border-slate-200' }} p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Bukti Diupload (Paid)</p>
                <p class="text-2xl font-bold font-outfit text-slate-900">{{ $stats['paid'] }}</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.orders.index', ['status' => 'verified']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'verified' ? 'border-brand-indigo ring-1 ring-brand-indigo' : 'border-slate-200' }} p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Selesai (Verified)</p>
                <p class="text-2xl font-bold font-outfit text-slate-900">{{ $stats['verified'] }}</p>
            </div>
        </div>
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 font-semibold">Order ID / Tanggal</th>
                    <th class="px-6 py-4 font-semibold">Buyer</th>
                    <th class="px-6 py-4 font-semibold">Produk</th>
                    <th class="px-6 py-4 font-semibold">Total Pembayaran</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $order->order_number }}</div>
                        <div class="text-slate-500 text-xs mt-1">{{ $order->created_at->format('d M Y H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $order->buyer->avatar_url }}" class="w-8 h-8 rounded-full bg-slate-200" alt="">
                            <div>
                                <div class="font-medium text-slate-900">{{ $order->buyer->name }}</div>
                                <div class="text-slate-500 text-xs">{{ $order->buyer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-slate-900">{{ $order->product->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">Rp {{ number_format($order->amount + $order->unique_amount, 0, ',', '.') }}</div>
                        <div class="text-xs text-brand-indigo mt-0.5">Unik: {{ $order->unique_code }}</div>
                    </td>
                    <td class="px-6 py-4">
                        {!! $order->status_badge !!}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 rounded-md text-xs font-medium text-slate-700 hover:bg-slate-50">
                            Detail / Verifikasi
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Belum ada order dengan status ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $orders->appends(['status' => $status])->links() }}
    </div>
    @endif
</div>
@endsection
