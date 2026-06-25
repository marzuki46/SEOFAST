@extends('layouts.frontend')
@section('content')
<div class="max-w-7xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8">Digital Products</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white border rounded-lg p-6 shadow-sm">
                <h2 class="text-xl font-bold">{{ $product->name }}</h2>
                <p class="text-gray-600 my-4">{{ Str::limit($product->description, 100) }}</p>
                <div class="text-lg font-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <a href="{{ route('products.show', $product->slug) }}" class="text-indigo-600 hover:underline">View Details</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
