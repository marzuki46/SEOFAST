@extends('layouts.admin')

@section('title', 'Media Library')

@section('admin_content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Media Library</h1>
        <p class="text-slate-500 mt-1">Manage and upload multiple images for your content.</p>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-3">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 flex items-center gap-3">
    <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0 text-rose-600">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </div>
    <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100">
    <ul class="list-disc pl-5 text-sm font-medium text-rose-800">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Upload Form -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-8 overflow-hidden">
    <div class="p-6">
        <form id="upload-form" action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4" onsubmit="uploadMedia(event)">
            @csrf
            
            <div class="w-full">
                <label for="files" class="flex justify-center w-full h-32 px-4 transition bg-slate-50 border-2 border-slate-300 border-dashed rounded-xl appearance-none cursor-pointer hover:border-brand-indigo hover:bg-indigo-50 focus:outline-none">
                    <span class="flex flex-col items-center justify-center space-y-2">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span class="font-medium text-slate-600">
                            Drop files here to upload, or
                            <span class="text-brand-indigo underline">browse</span>
                        </span>
                    </span>
                    <input type="file" name="files[]" id="files" class="hidden" multiple accept="image/*" onchange="updateFileList(this)" />
                </label>
            </div>
            
            <div id="file-list" class="hidden text-sm text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-200"></div>

            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-brand-indigo text-white font-semibold rounded-xl hover:bg-brand-purple transition shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload Media
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Media Grid (10 per row on lg) -->
<div id="media-grid" class="grid grid-cols-2 md:grid-cols-5 lg:grid-cols-10 gap-4 mb-8">
    @forelse($media as $item)
        <div id="media-item-{{ $item->id }}" 
             class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden group flex flex-col cursor-pointer hover:ring-2 hover:ring-brand-indigo transition-all"
             data-id="{{ $item->id }}"
             data-url="{{ $item->url }}"
             data-filename="{{ $item->filename }}"
             data-title="{{ $item->title }}"
             data-alt="{{ $item->alt_text }}"
             data-desc="{{ $item->description }}"
             data-size="{{ $item->size }}"
             data-date="{{ $item->created_at }}"
             onclick="openMediaModalFromEl(this)">
            <!-- Image Thumbnail -->
            <div class="aspect-square bg-slate-100 relative group">
                <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover">
            </div>
        </div>
    @empty
        <div class="col-span-full py-20 text-center border-2 border-slate-200 border-dashed rounded-2xl">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-slate-900">No media files</h3>
            <p class="mt-1 text-sm text-slate-500">Upload images using the form above to get started.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mb-12">
    {{ $media->links() }}
</div>

<!-- WordPress-like Media Modal -->
<div id="media-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" onclick="closeMediaModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl flex flex-col md:flex-row h-[80vh]">
                
                <!-- Left: Image Preview -->
                <div class="w-full md:w-2/3 bg-slate-100 flex items-center justify-center p-8 relative border-r border-slate-200">
                    <button onclick="closeMediaModal()" class="absolute top-4 right-4 p-2 text-slate-500 hover:text-slate-800 bg-white rounded-lg shadow-sm md:hidden">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    <img id="modal-img" src="" class="max-w-full max-h-full object-contain shadow-sm rounded-lg" alt="">
                </div>

                <!-- Right: Image Details & Edit Form -->
                <div class="w-full md:w-1/3 bg-white flex flex-col overflow-y-auto">
                    <!-- Header -->
                    <div class="p-5 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
                        <h3 class="text-lg font-bold text-slate-900">Attachment Details</h3>
                        <button onclick="closeMediaModal()" class="hidden md:block p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-5 flex-1">
                        <!-- File Info -->
                        <div class="text-xs text-slate-500 space-y-1 mb-6 pb-6 border-b border-slate-100">
                            <p class="font-medium text-slate-900 truncate" id="modal-filename"></p>
                            <p id="modal-date"></p>
                            <p id="modal-size"></p>
                        </div>

                        <!-- Update Form -->
                        <form id="modal-form" method="POST" action="" onsubmit="saveMedia(event)">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Title</label>
                                    <input type="text" id="modal-title-input" name="title" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-brand-indigo focus:border-brand-indigo text-sm">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alt Text</label>
                                    <input type="text" id="modal-alt-input" name="alt_text" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-brand-indigo focus:border-brand-indigo text-sm">
                                    <p class="text-[10px] text-slate-500 mt-1">Describe the purpose of the image. Leave empty if the image is purely decorative.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Description</label>
                                    <textarea id="modal-desc-input" name="description" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-brand-indigo focus:border-brand-indigo text-sm"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">File URL</label>
                                    <div class="flex">
                                        <input type="text" id="modal-url-input" readonly class="w-full px-3 py-2 border border-slate-300 rounded-l-lg bg-slate-50 text-sm text-slate-500 font-mono">
                                        <button type="button" onclick="copyModalUrl()" class="px-4 py-2 bg-slate-100 border border-l-0 border-slate-300 rounded-r-lg text-slate-600 hover:bg-slate-200 text-sm font-medium transition-colors">
                                            Copy
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                                <button type="button" onclick="deleteMedia()" id="btn-delete" class="text-sm font-medium text-rose-600 hover:text-rose-800">Delete permanently</button>
                                <button type="submit" id="btn-save" class="px-4 py-2 bg-brand-indigo text-white font-medium rounded-lg hover:bg-brand-purple transition-colors text-sm">
                                    Save Changes
                                </button>
                            </div>
                        </form>

                        <!-- Hidden Delete Form -->
                        <form id="delete-form" method="POST" action="{{ route('admin.media.destroy') }}" class="hidden">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" id="delete-id">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateFileList(input) {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            fileList.classList.remove('hidden');
            let html = '<strong class="block mb-2 text-slate-800">Selected Files (' + input.files.length + '):</strong><ul class="list-disc pl-5 space-y-1">';
            
            for (let i = 0; i < input.files.length; i++) {
                html += `<li>${input.files[i].name} <span class="text-slate-400 text-xs ml-2">(${(input.files[i].size / 1024).toFixed(1)} KB)</span></li>`;
            }
            
            html += '</ul>';
            fileList.innerHTML = html;
        } else {
            fileList.classList.add('hidden');
        }
    }

    function openMediaModalFromEl(el) {
        document.getElementById('media-modal').classList.remove('hidden');
        
        document.getElementById('modal-img').src = el.getAttribute('data-url');
        document.getElementById('modal-filename').textContent = el.getAttribute('data-filename');
        
        const dateStr = el.getAttribute('data-date');
        const date = new Date(dateStr);
        document.getElementById('modal-date').textContent = date.toLocaleDateString() + ' at ' + date.toLocaleTimeString();
        document.getElementById('modal-size').textContent = (parseInt(el.getAttribute('data-size')) / 1024).toFixed(1) + ' KB';
        
        document.getElementById('modal-title-input').value = el.getAttribute('data-title') || '';
        document.getElementById('modal-alt-input').value = el.getAttribute('data-alt') || '';
        document.getElementById('modal-desc-input').value = el.getAttribute('data-desc') || '';
        document.getElementById('modal-url-input').value = el.getAttribute('data-url');
        
        document.getElementById('modal-form').action = '{{ url('/admin/media') }}/' + el.getAttribute('data-id');
        document.getElementById('delete-id').value = el.getAttribute('data-id');
    }

    function closeMediaModal() {
        document.getElementById('media-modal').classList.add('hidden');
    }

    function copyModalUrl() {
        const input = document.getElementById('modal-url-input');
        input.select();
        document.execCommand('copy');
        
        const btn = event.target;
        const originalText = btn.textContent;
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = originalText, 2000);
    }

    function deleteMedia() {
        if (confirm('Are you sure you want to delete this media file? This action cannot be undone.')) {
            const id = document.getElementById('delete-id').value;
            const btn = document.getElementById('btn-delete');
            const originalText = btn.textContent;
            btn.textContent = 'Deleting...';
            btn.disabled = true;

            fetch('{{ url('/admin/media') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    _method: 'DELETE',
                    id: id
                })
            }).then(response => {
                if (!response.ok && response.status !== 422) {
                    throw new Error('Network response was ' + response.status);
                }
                return response.json();
            }).then(data => {
                if(data.success) {
                    const el = document.getElementById('media-item-' + id);
                    if(el) el.remove();
                    closeMediaModal();
                } else {
                    alert('Failed to delete media: ' + (data.message || 'Unknown error'));
                    btn.textContent = originalText;
                    btn.disabled = false;
                }
            }).catch(err => {
                console.error(err);
                alert('An error occurred. Check browser console.');
                btn.textContent = originalText;
                btn.disabled = false;
            });
        }
    }

    function saveMedia(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('btn-save');
        const originalText = btn.textContent;
        
        btn.textContent = 'Saving...';
        btn.disabled = true;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (!response.ok && response.status !== 422) {
                throw new Error('Network response was ' + response.status);
            }
            return response.json();
        }).then(data => {
            if(data.success) {
                btn.textContent = 'Saved!';
                btn.classList.replace('bg-brand-indigo', 'bg-emerald-600');
                
                // Update the DOM element dataset and image Alt tag immediately
                const el = document.getElementById('media-item-' + data.media.id);
                if (el) {
                    el.setAttribute('data-title', data.media.title || '');
                    el.setAttribute('data-alt', data.media.alt_text || '');
                    el.setAttribute('data-desc', data.media.description || '');
                    
                    const img = el.querySelector('img');
                    if (img) {
                        img.alt = data.media.alt_text || '';
                    }
                }

                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.classList.replace('bg-emerald-600', 'bg-brand-indigo');
                    btn.disabled = false;
                }, 2000);
            } else {
                let errorMsg = data.message || 'Unknown error';
                if (data.errors) {
                    errorMsg += '\n' + Object.values(data.errors).map(e => e.join(', ')).join('\n');
                }
                alert('Failed to update details: ' + errorMsg);
                btn.textContent = originalText;
                btn.disabled = false;
            }
        }).catch(err => {
            console.error('Save error:', err);
            alert('An error occurred while saving: ' + err.message);
            btn.textContent = originalText;
            btn.disabled = false;
        });
    }

    function uploadMedia(e) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        const fileInput = document.getElementById('files');

        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Please select at least one image to upload.');
            return;
        }

        btn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Uploading...
        `;
        btn.disabled = true;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (!response.ok) {
                throw new Error('Network response was ' + response.status);
            }
            return response.json();
        }).then(data => {
            if (data.success && data.uploaded) {
                // Clear file input
                fileInput.value = '';
                document.getElementById('file-list').classList.add('hidden');
                
                // Get the grid container
                const grid = document.getElementById('media-grid');
                
                // If there's a "No media files" placeholder, remove it
                const placeholder = grid.querySelector('.col-span-full');
                if (placeholder) {
                    grid.innerHTML = '';
                }

                // Prepend each uploaded media to the grid
                data.uploaded.forEach(item => {
                    const div = document.createElement('div');
                    div.id = 'media-item-' + item.id;
                    div.className = 'bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden group flex flex-col cursor-pointer hover:ring-2 hover:ring-brand-indigo transition-all';
                    
                    // Set all dataset attributes
                    div.setAttribute('data-id', item.id);
                    div.setAttribute('data-url', item.url);
                    div.setAttribute('data-filename', item.filename);
                    div.setAttribute('data-title', item.title || '');
                    div.setAttribute('data-alt', item.alt_text || '');
                    div.setAttribute('data-desc', item.description || '');
                    div.setAttribute('data-size', item.size);
                    div.setAttribute('data-date', item.created_at);
                    
                    div.onclick = function() { openMediaModalFromEl(this); };

                    div.innerHTML = `
                        <div class="aspect-square bg-slate-100 relative group">
                            <img src="${item.url}" alt="${item.alt_text || ''}" class="w-full h-full object-cover">
                        </div>
                    `;
                    
                    // Prepend to grid
                    grid.insertBefore(div, grid.firstChild);
                });

                alert(data.uploaded.length + ' image(s) processed and uploaded successfully.');
            } else {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
            btn.innerHTML = originalText;
            btn.disabled = false;
        }).catch(err => {
            console.error('Upload error:', err);
            alert('An error occurred during upload: ' + err.message);
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>
@endpush
