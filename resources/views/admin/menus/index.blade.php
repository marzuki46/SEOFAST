@extends('layouts.admin')

@section('page_title', 'Menus')

@section('admin_content')
<div class="mb-6">
    <h2 class="text-2xl font-bold font-outfit">Menu Management</h2>
    <p class="text-sm text-slate-500">Manage your website's navigation menus.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Available Pages/Links Box -->
    <div class="bg-white border rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold mb-4 border-b pb-2">Add Menu Items</h3>
        
        <div class="mb-4">
            <h4 class="font-semibold text-sm mb-2 text-slate-700">Pages</h4>
            <div class="space-y-2 max-h-48 overflow-y-auto border p-2 rounded">
                @foreach($pages as $page)
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="page-{{ $page->id }}" class="page-checkbox" data-title="{{ $page->title }}" data-url="/{{ $page->slug }}">
                        <label for="page-{{ $page->id }}" class="text-sm cursor-pointer">{{ $page->title }}</label>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addSelectedPages()" class="mt-3 text-xs bg-indigo-50 text-indigo-600 font-semibold px-3 py-1.5 rounded-lg border border-indigo-200 hover:bg-indigo-100 transition w-full">
                Add Selected Pages to Menu
            </button>
        </div>

        <div>
            <h4 class="font-semibold text-sm mb-2 text-slate-700">Custom Link</h4>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-slate-500 mb-1">URL</label>
                    <input type="text" id="custom-url" class="w-full text-sm border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="https://..." value="https://">
                </div>
                <div>
                    <label class="block text-xs text-slate-500 mb-1">Link Text</label>
                    <input type="text" id="custom-title" class="w-full text-sm border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Menu Item">
                </div>
                <button type="button" onclick="addCustomLink()" class="text-xs bg-indigo-50 text-indigo-600 font-semibold px-3 py-1.5 rounded-lg border border-indigo-200 hover:bg-indigo-100 transition w-full">
                    Add to Menu
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Structure Box -->
    <div class="lg:col-span-2 bg-white border rounded-xl shadow-sm p-6">
        @if($menus->isNotEmpty())
            @php $currentMenu = $menus->first(); @endphp
            <div class="flex items-center justify-between mb-4 border-b pb-4">
                <div>
                    <h3 class="text-lg font-bold">Menu Structure</h3>
                    <p class="text-xs text-slate-500">Drag each item into the order you prefer.</p>
                </div>
                <div class="text-sm">
                    <span class="font-semibold text-slate-700">Current Menu:</span> 
                    <span class="bg-slate-100 px-2 py-1 rounded border">{{ $currentMenu->name }} ({{ $currentMenu->location }})</span>
                </div>
            </div>

            <form action="{{ route('admin.menus.items.store', $currentMenu) }}" method="POST" id="menu-form">
                @csrf
                <div id="menu-structure" class="space-y-3 min-h-[200px] bg-slate-50 p-4 rounded-lg border border-dashed">
                    @foreach($currentMenu->items as $index => $item)
                        <div class="menu-item bg-white border rounded shadow-sm p-3 flex flex-col gap-2 cursor-move" data-index="{{ $index }}">
                            <div class="flex justify-between items-center">
                                <div class="font-semibold text-sm item-title-display">{{ $item->title }}</div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-slate-400">Custom Link</span>
                                    <button type="button" class="text-red-500 hover:text-red-700" onclick="this.closest('.menu-item').remove(); updateIndices();">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 mt-2 pt-2 border-t border-slate-100 hidden edit-panel">
                                <div>
                                    <label class="text-xs text-slate-500 block">URL</label>
                                    <input type="text" name="items[{{ $index }}][url]" class="w-full border rounded px-2 py-1 text-sm item-url" value="{{ $item->url }}">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-500 block">Title</label>
                                    <input type="text" name="items[{{ $index }}][title]" class="w-full border rounded px-2 py-1 text-sm item-title" value="{{ $item->title }}" onkeyup="this.closest('.menu-item').querySelector('.item-title-display').innerText = this.value">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-500 block">Parent Item (Index)</label>
                                    <input type="number" min="0" name="items[{{ $index }}][parent_index]" class="w-full border rounded px-2 py-1 text-sm item-parent" value="{{ $item->parent_id ? $currentMenu->items->search(function($i) use ($item) { return $i->id == $item->parent_id; }) : '' }}" placeholder="Root (empty)">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <button type="button" onclick="document.querySelectorAll('.edit-panel').forEach(el => el.classList.toggle('hidden'))" class="text-sm text-indigo-600 hover:underline">
                        Toggle Details
                    </button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                        Save Menu
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-8 text-slate-500">
                No menus found.
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuStructure = document.getElementById('menu-structure');
        if (menuStructure) {
            new Sortable(menuStructure, {
                animation: 150,
                ghostClass: 'bg-indigo-50',
                onEnd: function() {
                    updateIndices();
                }
            });
        }
    });

    function updateIndices() {
        const items = document.querySelectorAll('.menu-item');
        items.forEach((item, index) => {
            item.setAttribute('data-index', index);
            item.querySelector('.item-url').name = `items[${index}][url]`;
            item.querySelector('.item-title').name = `items[${index}][title]`;
            const parentInput = item.querySelector('.item-parent');
            if (parentInput) parentInput.name = `items[${index}][parent_index]`;
        });
    }

    function createMenuItemHTML(title, url) {
        const index = document.querySelectorAll('.menu-item').length;
        return `
            <div class="menu-item bg-white border rounded shadow-sm p-3 flex flex-col gap-2 cursor-move" data-index="${index}">
                <div class="flex justify-between items-center">
                    <div class="font-semibold text-sm item-title-display">${title}</div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-400">New Item</span>
                        <button type="button" class="text-red-500 hover:text-red-700" onclick="this.closest('.menu-item').remove(); updateIndices();">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-2 pt-2 border-t border-slate-100 hidden edit-panel">
                    <div>
                        <label class="text-xs text-slate-500 block">URL</label>
                        <input type="text" name="items[${index}][url]" class="w-full border rounded px-2 py-1 text-sm item-url" value="${url}">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 block">Title</label>
                        <input type="text" name="items[${index}][title]" class="w-full border rounded px-2 py-1 text-sm item-title" value="${title}" onkeyup="this.closest('.menu-item').querySelector('.item-title-display').innerText = this.value">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 block">Parent Item (Index)</label>
                        <input type="number" min="0" name="items[${index}][parent_index]" class="w-full border rounded px-2 py-1 text-sm item-parent" value="" placeholder="Root (empty)">
                    </div>
                </div>
            </div>
        `;
    }

    function addSelectedPages() {
        const checkboxes = document.querySelectorAll('.page-checkbox:checked');
        const container = document.getElementById('menu-structure');
        
        checkboxes.forEach(cb => {
            const title = cb.getAttribute('data-title');
            const url = cb.getAttribute('data-url');
            container.insertAdjacentHTML('beforeend', createMenuItemHTML(title, url));
            cb.checked = false; // uncheck after adding
        });
        
        updateIndices();
    }

    function addCustomLink() {
        const titleInput = document.getElementById('custom-title');
        const urlInput = document.getElementById('custom-url');
        
        if (!titleInput.value) {
            alert('Please enter a link text');
            return;
        }
        
        const container = document.getElementById('menu-structure');
        container.insertAdjacentHTML('beforeend', createMenuItemHTML(titleInput.value, urlInput.value));
        
        titleInput.value = '';
        urlInput.value = 'https://';
        updateIndices();
    }
</script>
@endsection
