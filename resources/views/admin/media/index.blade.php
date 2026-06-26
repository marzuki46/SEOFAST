@extends('layouts.admin')

@section('title', 'Media Library')

@section('content')
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
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            @csrf
            
            <div class="w-full">
                <label for="files" class="flex justify-center w-full h-32 px-4 transition bg-white border-2 border-slate-300 border-dashed rounded-xl appearance-none cursor-pointer hover:border-brand-indigo focus:outline-none">
                    <span class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <span class="font-medium text-slate-600">
                            Drop files to Attach, or
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

<!-- Media Grid -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @forelse($media as $item)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden group flex flex-col">
            <!-- Image Thumbnail -->
            <div class="aspect-square bg-slate-100 relative group">
                <img src="{{ $item['url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    <!-- Copy URL Button -->
                    <button onclick="copyToClipboard('{{ $item['url'] }}')" class="p-2 bg-white/20 hover:bg-white text-white hover:text-slate-900 rounded-lg transition-colors backdrop-blur-sm" title="Copy URL">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                    <!-- Delete Button -->
                    <form action="{{ route('admin.media.destroy') }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this image? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="path" value="{{ $item['path'] }}">
                        <button type="submit" class="p-2 bg-rose-500/80 hover:bg-rose-600 text-white rounded-lg transition-colors backdrop-blur-sm" title="Delete">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Image Info -->
            <div class="p-3 bg-white border-t border-slate-100 flex-1 flex flex-col justify-between">
                <div class="truncate text-xs font-medium text-slate-700" title="{{ $item['name'] }}">
                    {{ $item['name'] }}
                </div>
                <div class="mt-1 flex justify-between items-center text-[10px] text-slate-500">
                    <span>{{ $item['size'] }}</span>
                    <span>{{ \Carbon\Carbon::createFromTimestamp($item['last_modified'])->format('M d') }}</span>
                </div>
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
@endsection

@push('scripts')
<script>
    function updateFileList(input) {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            fileList.classList.remove('hidden');
            let html = '<strong class="block mb-2 text-slate-800">Selected Files:</strong><ul class="list-disc pl-5 space-y-1">';
            
            for (let i = 0; i < input.files.length; i++) {
                html += `<li>${input.files[i].name} <span class="text-slate-400 text-xs ml-2">(${(input.files[i].size / 1024).toFixed(1)} KB)</span></li>`;
            }
            
            html += '</ul>';
            fileList.innerHTML = html;
        } else {
            fileList.classList.add('hidden');
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('URL copied to clipboard: ' + text);
        }, function(err) {
            console.error('Could not copy text: ', err);
            // Fallback
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert('URL copied to clipboard: ' + text);
        });
    }
</script>
@endpush
