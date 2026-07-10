@extends('layouts.admin')

@section('title', 'Products & Midtrans Checkout - ' . config('app.name'))
@section('page_title', 'Products & Checkout Shortcodes')

@section('admin_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500 mt-1">Create pricing tiers and get the shortcode to embed stunning checkout forms in your articles.</p>
        </div>
        <button onclick="document.getElementById('createProductModal').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Product
        </button>
    </div>

    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
        <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 flex flex-col h-full relative overflow-hidden group">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold text-slate-900 font-outfit">{{ $product->name }}</h3>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-slate-400 hover:text-indigo-500 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-rose-500 transition">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 6l-1.5 14.5a2 2 0 01-2 1.9H8a2 2 0 01-2-1.9L4.5 6m15 0H4.5m10.5 0V4.5a2 2 0 00-2-2h-3a2 2 0 00-2 2V6" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-3xl font-bold text-slate-900 tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $product->description ?? 'One-time payment' }}</p>
            </div>

            @if($product->categories->isNotEmpty())
            <div class="flex flex-wrap gap-1.5 mb-3">
                @foreach($product->categories as $cat)
                    <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100">{{ $cat->name }}</span>
                @endforeach
            </div>
            @endif

            <div class="flex-1 space-y-2 mb-6">
                @if($product->features)
                    @foreach($product->features as $feature)
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-slate-600">{{ $feature }}</span>
                    </div>
                    @endforeach
                @endif
            </div>

            <div class="mt-auto bg-slate-50 p-3 rounded-xl border border-slate-200">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Content Shortcode</p>
                <code class="text-sm font-mono text-indigo-600 font-bold break-all select-all">{{ $product->shortcode }}</code>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
            <h3 class="text-lg font-bold text-slate-900 font-outfit mb-2">No Products Configured</h3>
            <p class="text-sm text-slate-500 mb-6">Create your first product to generate embeddable Midtrans checkout widgets.</p>
            <button onclick="document.getElementById('createProductModal').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">
                Create First Product
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Create Product Modal -->
<div id="createProductModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('createProductModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-6 pt-6 pb-6 space-y-5">
                    <div>
                        <h3 class="text-xl font-bold font-outfit text-slate-900">Create New Product</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Product Name</label>
                        <input type="text" name="name" id="name" placeholder="e.g. SEO Masterclass" required
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-semibold text-slate-700 mb-1">Price (IDR)</label>
                        <input type="number" name="price" id="price" placeholder="e.g. 500000" required
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Description (Optional)</label>
                        <input type="text" name="description" id="description" placeholder="e.g. Lifetime access to the portal"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label for="image_url" class="block text-sm font-semibold text-slate-700 mb-1">Image URL (Thumbnail)</label>
                        <input type="url" name="image_url" id="image_url" placeholder="e.g. https://via.placeholder.com/400"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label for="download_url" class="block text-sm font-semibold text-slate-700 mb-1">Digital Product URL / External Link</label>
                        <input type="url" name="download_url" id="download_url" placeholder="e.g. https://drive.google.com/..."
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                        <p class="text-xs text-slate-400 mt-1.5">Link access this digital product (Google Drive, Notion, dll).</p>
                    </div>

                    <div>
                        <label for="download_file" class="block text-sm font-semibold text-slate-700 mb-1">Atau Upload File Langsung</label>
                        <input type="file" name="download_file" id="download_file"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                        <p class="text-xs text-slate-400 mt-1.5">Max 10MB. File akan bisa didownload pembeli setelah diverifikasi.</p>
                    </div>

                    <div>
                        <label for="features" class="block text-sm font-semibold text-slate-700 mb-1">Features</label>
                        <textarea name="features" id="features" rows="3" placeholder="e.g. Full Course Access, Private Community, Monthly Q&A"
                                  class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"></textarea>
                        <p class="text-xs text-slate-400 mt-1.5">Separate each feature with a comma (,)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Categories</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach(\App\Models\ProductCategory::active()->orderBy('order')->orderBy('name')->get() as $cat)
                            <label class="inline-flex items-center gap-1.5 text-sm text-slate-700">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="rounded border-slate-300 text-indigo-600">
                                {{ $cat->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('createProductModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
