@extends('layouts.admin')

@section('title', 'Product Categories - ' . config('app.name'))
@section('page_title', 'Product Categories')

@section('admin_content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-sm text-slate-500">Manage product categories for your digital marketplace.</p>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Category
        </button>
    </div>

    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
        <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="rounded-xl bg-rose-50 p-4 border border-rose-200">
        <ul class="text-sm font-semibold text-rose-800 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4 text-center">Products</th>
                    <th class="px-6 py-4 text-center">Order</th>
                    <th class="px-6 py-4 text-center">Active</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $cat)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $cat->name }}</td>
                    <td class="px-6 py-4 text-sm text-slate-500 font-mono">{{ $cat->slug }}</td>
                    <td class="px-6 py-4 text-center text-sm text-slate-600">{{ $cat->products_count ?? $cat->products()->count() }}</td>
                    <td class="px-6 py-4 text-center text-sm text-slate-600">{{ $cat->order }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($cat->is_active)
                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Yes</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold bg-slate-100 text-slate-500 rounded-full">No</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}', '{{ $cat->description }}', {{ $cat->order }}, {{ $cat->is_active ? 'true' : 'false' }})" class="text-slate-400 hover:text-indigo-500 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                            </button>
                            <form action="{{ route('admin.product-categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-500 transition">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 6l-1.5 14.5a2 2 0 01-2 1.9H8a2 2 0 01-2-1.9L4.5 6m15 0H4.5m10.5 0V4.5a2 2 0 00-2-2h-3a2 2 0 00-2 2V6"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm">No categories yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('createModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100 relative">
            <form action="{{ route('admin.product-categories.store') }}" method="POST">
                @csrf
                <div class="px-6 pt-6 pb-4 space-y-5">
                    <h3 class="text-xl font-bold font-outfit text-slate-900">New Category</h3>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Name</label>
                        <input type="text" name="name" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Sort Order</label>
                        <input type="number" name="order" value="0" min="0" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none">
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('editModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100 relative">
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="px-6 pt-6 pb-4 space-y-5">
                    <h3 class="text-xl font-bold font-outfit text-slate-900">Edit Category</h3>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Name</label>
                        <input type="text" name="name" id="edit_name" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="2" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Sort Order</label>
                        <input type="number" name="order" id="edit_order" min="0" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded border-slate-300 text-indigo-600">
                        <label for="edit_is_active" class="text-sm font-semibold text-slate-700">Active</label>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCategory(id, name, description, order, isActive) {
    document.getElementById('editForm').action = '/admin/product-categories/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_order').value = order;
    document.getElementById('edit_is_active').checked = isActive;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection
