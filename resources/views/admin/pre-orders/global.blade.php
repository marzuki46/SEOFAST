@extends('layouts.admin')

@section('title', 'Pre-Orders - ' . config('app.name'))
@section('page_title', 'All Pre-Orders')

@section('admin_content')
<div class="space-y-6">
    @if($products->isEmpty())
    <div class="text-center py-16 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
        <h3 class="text-lg font-bold text-slate-700 font-outfit">No pre-orders yet</h3>
        <p class="text-slate-500 text-sm mt-2">Products with pre-orders will appear here.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
            <h3 class="text-lg font-bold text-slate-900 font-outfit mb-2">{{ $product->name }}</h3>
            <div class="flex items-center gap-4 text-sm text-slate-500 mb-4">
                <span>{{ $product->pre_orders_count }} pre-order(s)</span>
                @if($product->isLaunched())
                <span class="text-emerald-600 font-semibold">✓ Launched</span>
                @else
                <span class="text-amber-600 font-semibold">Pending</span>
                @endif
            </div>
            <a href="{{ route('admin.pre-orders.index', $product) }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 gap-1">
                Manage Pre-Orders
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
