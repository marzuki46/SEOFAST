@extends('layouts.admin')

@section('title', 'Billing & Upgrades - SEOFAST')
@section('page_title', 'Billing & Subscriptions')

@section('admin_content')
<div class="space-y-8">
    <!-- Order Overview Header -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden">
        <div class="max-w-md relative z-10">
            <h2 class="text-3xl font-extrabold font-outfit tracking-tight text-white mb-2">
                Digital Products & Orders
            </h2>
            <p class="text-indigo-200 text-sm leading-relaxed">
                Kelola penjualan produk digital Anda, pantau transaksi masuk, dan verifikasi pembayaran.
            </p>
        </div>
    </div>

    <!-- Legacy Pricing Options Grid removed for Single Ownership -->

    <!-- Invoice List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-900 font-outfit">Manajemen Order & Invoices</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pantau status order dan lakukan verifikasi transfer manual (Khusus Admin).</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase border-b border-slate-200">
                        <th class="px-6 py-3">Invoice Number</th>
                        <th class="px-6 py-3">Description</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Amount</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($invoices as $invoice)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $invoice->notes }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">IDR {{ number_format($invoice->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($invoice->status === 'paid')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/10">Paid</span>
                            @elseif($invoice->status === 'pending')
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/10">Pending</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-50 px-2 py-1 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-600/10">{{ ucfirst($invoice->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($invoice->status === 'pending')
                                <form action="{{ route('admin.billing.verify', $invoice->id) }}" method="POST" onsubmit="return confirm('Verify this payment?');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
                                        Verify Payment
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-emerald-600 font-medium">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
