@extends('layouts.admin')

@section('title', 'Edit Product - ' . config('app.name'))
@section('page_title', 'Edit Product: ' . $product->name)

@php
    $sections = $product->display_sections ?? ['description', 'features', 'specifications'];
@endphp

@section('admin_content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-lg font-bold text-slate-900 font-outfit">Product Details</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.pre-orders.index', $product) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">
                    Pre-Orders
                </a>
                @if($product->isLaunched())
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">✓ Launched {{ $product->launched_at->isoFormat('D MMM YYYY') }}</span>
                @endif
                <span class="text-xs text-slate-500 font-mono bg-slate-200 px-2 py-1 rounded">Shortcode: {{ $product->shortcode }}</span>
            </div>
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
                <label class="block text-sm font-semibold text-slate-700 mb-1">Thumbnail Image</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="URL"
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                    <input type="file" name="image" accept="image/*"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 outline-none bg-white">
                </div>
                @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="" class="mt-2 h-20 w-20 object-cover rounded-lg border border-slate-200">
                @endif
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Gallery Images</label>
                <p class="text-xs text-slate-400 mb-2">URL atau upload file. Keduanya bisa digabung.</p>
                <div id="gallery-wrapper">
                    @php $gallery = $product->gallery_images ?? []; @endphp
                    @forelse($gallery as $img)
                    <div class="flex gap-2 mb-2 gallery-row">
                        <input type="text" name="gallery_images[]" value="{{ $img }}" placeholder="URL or file name after upload"
                               class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none">
                        <button type="button" onclick="this.closest('.gallery-row').remove()" class="text-rose-500 hover:text-rose-700 px-2">✕</button>
                    </div>
                    @empty
                    <div class="flex gap-2 mb-2 gallery-row">
                        <input type="text" name="gallery_images[]" placeholder="https://..."
                               class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none">
                        <button type="button" onclick="this.closest('.gallery-row').remove()" class="text-rose-500 hover:text-rose-700 px-2">✕</button>
                    </div>
                    @endforelse
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="button" onclick="addGalleryRow()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">+ Add URL</button>
                    <label class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 cursor-pointer">
                        + Upload Images
                        <input type="file" name="gallery[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Digital Product URL / External Link</label>
                <input type="url" name="download_url" value="{{ old('download_url', $product->download_url) }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Upload File</label>
                @if($product->download_file)
                    <div class="mb-2 text-sm text-indigo-600 font-medium">✓ File sudah terupload.</div>
                @endif
                <input type="file" name="download_file"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none bg-white">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Features (Comma separated)</label>
                <textarea name="features" rows="2" placeholder="Feature 1, Feature 2, Feature 3"
                          class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">{{ old('features', is_array($product->features) ? implode(', ', $product->features) : '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Categories</label>
                <div class="flex flex-wrap gap-3">
                    @php $productCatIds = $product->categories->pluck('id')->toArray(); @endphp
                    @foreach(\App\Models\ProductCategory::active()->orderBy('order')->orderBy('name')->get() as $cat)
                    <label class="inline-flex items-center gap-1.5 text-sm text-slate-700">
                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}" {{ in_array($cat->id, $productCatIds) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        {{ $cat->name }}
                    </label>
                    @endforeach
                </div>
            </div>

            <hr class="border-slate-200">

            <div>
                <h4 class="text-base font-bold text-slate-900 font-outfit mb-3">Display Sections</h4>
                <p class="text-xs text-slate-500 mb-3">Pilih section mana yang tampil di halaman detail produk.</p>
                <div class="flex flex-wrap gap-4">
                    @php
                        $sectionLabels = [
                            'description' => 'Deskripsi',
                            'features' => 'Fitur',
                            'gallery' => 'Galeri Gambar',
                            'specifications' => 'Spesifikasi',
                            'faq' => 'FAQ',
                        ];
                    @endphp
                    @foreach($sectionLabels as $key => $label)
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="display_sections[]" value="{{ $key }}"
                               {{ in_array($key, $sections) ? 'checked' : '' }}
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div x-data="specsForm(@js($product->specifications ?? []))">
                <h4 class="text-base font-bold text-slate-900 font-outfit mb-3">Specifications</h4>
                <template x-for="(row, i) in rows" :key="i">
                    <div class="flex gap-2 mb-2">
                        <input x-model="row.key" :name="'specifications['+i+'][key]'" placeholder="Label"
                               class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none">
                        <input x-model="row.value" :name="'specifications['+i+'][value]'" placeholder="Value"
                               class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none">
                        <button type="button" @click="remove(i)" class="text-rose-500 hover:text-rose-700 px-2">✕</button>
                    </div>
                </template>
                <button type="button" @click="add()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">+ Add Row</button>
            </div>

            <div x-data="faqForm(@js($product->faq ?? []))">
                <h4 class="text-base font-bold text-slate-900 font-outfit mb-3">FAQ</h4>
                <template x-for="(row, i) in rows" :key="i">
                    <div class="mb-3 p-3 border border-slate-200 rounded-xl bg-slate-50">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-semibold text-slate-500 uppercase" x-text="'Question '+(i+1)"></span>
                            <button type="button" @click="remove(i)" class="text-rose-500 hover:text-rose-700 text-xs font-semibold">Remove</button>
                        </div>
                        <input x-model="row.question" :name="'faq['+i+'][question]'" placeholder="Question"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm mb-2 focus:border-indigo-500 outline-none">
                        <textarea x-model="row.answer" :name="'faq['+i+'][answer]'" placeholder="Answer" rows="2"
                                  class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 outline-none"></textarea>
                    </div>
                </template>
                <button type="button" @click="add()" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">+ Add FAQ</button>
            </div>

            <hr class="border-slate-200">

            <div>
                <h4 class="text-base font-bold text-slate-900 font-outfit mb-3">Action Buttons</h4>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 text-sm text-slate-700">
                        <input type="hidden" name="enable_buy_button" value="0">
                        <input type="checkbox" name="enable_buy_button" value="1" {{ $product->enable_buy_button ? 'checked' : '' }}
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="font-semibold">Enable "Beli Sekarang" button</span>
                    </label>
                    <label class="flex items-center gap-3 text-sm text-slate-700">
                        <input type="hidden" name="enable_inquiry_button" value="0">
                        <input type="checkbox" name="enable_inquiry_button" value="1" id="enable_inquiry" {{ $product->enable_inquiry_button ? 'checked' : '' }}
                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="font-semibold">Enable "Tanya Dulu / Pre Order" button</span>
                    </label>
                    <div class="grid grid-cols-2 gap-4 pl-8">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Button Label</label>
                            <input type="text" name="inquiry_label" value="{{ old('inquiry_label', $product->inquiry_label) }}" placeholder="Tanya Dulu"
                                   class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Inquiry URL (WhatsApp/Contact)</label>
                            <input type="url" name="inquiry_url" value="{{ old('inquiry_url', $product->inquiry_url) }}" placeholder="https://wa.me/..."
                                   class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}
                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_active" class="text-sm font-semibold text-slate-700">Product is active and purchasable</label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancel</a>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">Update Product</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function addGalleryRow() {
    var w = document.getElementById('gallery-wrapper');
    var d = document.createElement('div');
    d.className = 'flex gap-2 mb-2 gallery-row';
    d.innerHTML = '<input type="url" name="gallery_images[]" placeholder="https://..." class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none">' +
                  '<button type="button" onclick="this.closest(\'.gallery-row\').remove()" class="text-rose-500 hover:text-rose-700 px-2">✕</button>';
    w.appendChild(d);
}
</script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('specsForm', (initial) => ({
        rows: initial && initial.length ? initial : [{ key: '', value: '' }],
        add() { this.rows.push({ key: '', value: '' }); },
        remove(i) { this.rows.splice(i, 1); if (!this.rows.length) this.rows.push({ key: '', value: '' }); }
    }));
    Alpine.data('faqForm', (initial) => ({
        rows: initial && initial.length ? initial : [{ question: '', answer: '' }],
        add() { this.rows.push({ question: '', answer: '' }); },
        remove(i) { this.rows.splice(i, 1); if (!this.rows.length) this.rows.push({ question: '', answer: '' }); }
    }));
});
</script>
@endsection
