@extends('layouts.admin')

@section('title', 'Edit Product - SEOFAST')
@section('page_title', 'Edit Product: ' . $product->name)

@section('admin_content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-lg font-bold text-slate-900 font-outfit">Product Details</h3>
            <span class="text-xs text-slate-500 font-mono bg-slate-200 px-2 py-1 rounded">Shortcode: {{ $product->shortcode }}</span>
        </div>
        
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Product Name</label>
                    <input type="text" name="name" required value="{{ old('name', $product->name) }}"
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Price (IDR)</label>
                    <input type="number" name="price" required min="0" value="{{ old('price', $product->price) }}"
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Image URL (Thumbnail)</label>
                <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="e.g. https://via.placeholder.com/400"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Digital Product URL / External Link</label>
                <input type="url" name="download_url" value="{{ old('download_url', $product->download_url) }}" placeholder="e.g. https://drive.google.com/..."
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                <p class="text-xs text-slate-500 mt-1">If set, users can access this link after their order is verified.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Upload File (Timpa file lama jika ada)</label>
                @if($product->download_file)
                    <div class="mb-2 text-sm text-indigo-600 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> File sudah terupload.
                    </div>
                @endif
                <input type="file" name="download_file"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none bg-white">
                <p class="text-xs text-slate-500 mt-1">Max 10MB.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Features (Comma separated)</label>
                <textarea name="features" rows="2" placeholder="Feature 1, Feature 2, Feature 3"
                          class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">{{ old('features', is_array($product->features) ? implode(', ', $product->features) : '') }}</textarea>
                <p class="text-xs text-slate-500 mt-1">Separate each feature with a comma (,). These will be displayed as bullet points.</p>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_active" class="text-sm font-semibold text-slate-700">Product is active and purchasable</label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancel</a>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
