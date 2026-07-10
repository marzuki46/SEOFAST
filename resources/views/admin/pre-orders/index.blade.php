@extends('layouts.admin')

@section('title', 'Pre-Orders: ' . $product->name . ' - ' . config('app.name'))
@section('page_title', 'Pre-Orders: ' . $product->name)

@section('admin_content')
<div class="space-y-6">
    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
        <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-xl bg-rose-50 p-4 border border-rose-200">
        <p class="text-sm font-semibold text-rose-800">{{ session('error') }}</p>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-2xl p-5">
        <div>
            <p class="text-sm text-slate-500">Status: 
                @if($product->isLaunched())
                <span class="text-emerald-600 font-semibold">Launched ({{ $product->launched_at->isoFormat('D MMM YYYY') }})</span>
                @else
                <span class="text-amber-600 font-semibold">Not yet launched</span>
                @endif
            </p>
            <p class="text-sm text-slate-500 mt-1">{{ $preOrders->count() }} pre-order(s)</p>
        </div>
        @if(!$product->isLaunched() && $preOrders->isNotEmpty())
        <form action="{{ route('admin.pre-orders.launch', $product) }}" method="POST" onsubmit="return confirm('Launch product and notify all pre-order customers?')">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Launch Product & Notify All
            </button>
        </form>
        @endif
    </div>

    @if($preOrders->isEmpty())
    <div class="text-center py-16 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
        <h3 class="text-lg font-bold text-slate-700 font-outfit">No pre-orders yet</h3>
        <p class="text-slate-500 text-sm mt-2">Waiting for customers to pre-order this product.</p>
    </div>
    @else
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Name</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Email</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Phone</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Notes</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Date</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Notified</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preOrders as $po)
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="px-5 py-3 font-medium text-slate-900">{{ $po->name }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $po->email }}</td>
                    <td class="px-5 py-3 text-slate-600">{{ $po->phone ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-600 max-w-xs truncate">{{ $po->notes ?? '—' }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $po->created_at->isoFormat('D MMM YYYY') }}</td>
                    <td class="px-5 py-3">
                        @if($po->notified_at)
                        <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Yes</span>
                        @else
                        <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-1 rounded-full">No</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
