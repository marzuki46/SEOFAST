@extends('layouts.admin')

@section('title', 'Phase 4: Select Image - ' . config('app.name'))
@section('page_title', 'Phase 4: Image Processing & Selection')

@section('admin_content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-5">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold text-slate-900 font-outfit mb-2">Select Featured Image</h3>
                <p class="text-sm text-slate-500">
                    Find the perfect high-quality image from Unsplash. The AI will inject this image and its contextual ALT text into the article during Phase 4 of content generation.
                </p>
            </div>
            
            <div class="w-full md:w-auto flex items-center gap-2">
                <form id="searchForm" class="flex items-center gap-2">
                    <input type="text" id="searchQuery" value="{{ $content->target_keyword }}" placeholder="Search images..." 
                           class="w-full md:w-64 rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-semibold hover:bg-slate-800 transition shadow-sm">Search</button>
                </form>
                <button id="generateAiBtn" type="button" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl text-sm font-semibold hover:from-purple-700 hover:to-indigo-700 transition shadow-sm whitespace-nowrap">
                    <span id="aiBtnText">Generate AI Illus</span>
                    <svg id="aiSpinner" class="hidden animate-spin h-4 w-4 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                </button>
            </div>
        </div>

        <div class="mt-6 p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex items-start gap-3">
            <svg class="h-5 w-5 text-indigo-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <div>
                <h4 class="text-sm font-semibold text-indigo-900">Target Content Context</h4>
                <ul class="text-sm text-indigo-800 mt-1 space-y-1">
                    <li><strong>Keyword:</strong> {{ $content->target_keyword }}</li>
                    <li><strong>Type:</strong> {{ ucfirst($content->hierarchy_level) }} Page</li>
                    <li><strong>Status:</strong> <span class="capitalize">{{ str_replace('_', ' ', $content->status) }}</span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Image Results Grid -->
    <div id="loadingIndicator" class="hidden py-12 text-center">
        <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p id="loadingText" class="text-slate-500 font-medium">Fetching images...</p>
    </div>

    <div id="imageResults" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Results will be injected here via JS -->
    </div>
</div>

<!-- Form to submit selected image -->
<form id="selectImageForm" action="{{ route('admin.content.images.select', $content->id) }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="image_url" id="selectedImageUrl">
    <input type="hidden" name="alt_text" id="selectedAltText">
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const queryInput = document.getElementById('searchQuery');
        const resultsContainer = document.getElementById('imageResults');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const selectForm = document.getElementById('selectImageForm');
        const generateAiBtn = document.getElementById('generateAiBtn');
        const aiBtnText = document.getElementById('aiBtnText');
        const aiSpinner = document.getElementById('aiSpinner');
        
        // Initial search based on keyword
        performSearch(queryInput.value);

        generateAiBtn.addEventListener('click', function() {
            const prompt = queryInput.value.trim() || '{{ $content->target_keyword }}';
            aiBtnText.textContent = 'Generating...';
            aiSpinner.classList.remove('hidden');
            generateAiBtn.disabled = true;
            loadingIndicator.classList.remove('hidden');
            document.getElementById('loadingText').textContent = 'AI sedang membuat ilustrasi...';

            fetch('{{ route('admin.content.images.ai-generate', $content->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(res => res.json())
            .then(data => {
                aiBtnText.textContent = 'Generate AI Illus';
                aiSpinner.classList.add('hidden');
                generateAiBtn.disabled = false;
                loadingIndicator.classList.add('hidden');

                if (data.success && data.image) {
                    resultsContainer.innerHTML = '';
                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm group relative cursor-pointer hover:ring-2 hover:ring-indigo-500 transition-all';
                    card.innerHTML = `
                        <div class="aspect-w-16 aspect-h-12 w-full bg-slate-100">
                            <img src="${data.image.url}" alt="${data.image.alt_text}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-4 absolute bottom-0 inset-x-0 bg-gradient-to-t from-slate-900/90 to-transparent translate-y-full group-hover:translate-y-0 transition duration-300">
                            <p class="text-white text-xs font-medium truncate">${data.image.author}</p>
                            <button type="button" class="mt-2 w-full py-1.5 bg-purple-500 hover:bg-purple-400 text-white text-xs font-bold rounded-lg shadow select-btn" data-url="${data.image.url}" data-alt="${data.image.alt_text}">
                                Pilih Gambar Ini
                            </button>
                        </div>
                    `;
                    resultsContainer.appendChild(card);
                    attachSelectButtons();
                } else {
                    resultsContainer.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-rose-500">' + (data.message || 'Gagal generate ilustrasi.') + '</p></div>';
                }
            })
            .catch(err => {
                aiBtnText.textContent = 'Generate AI Illus';
                aiSpinner.classList.add('hidden');
                generateAiBtn.disabled = false;
                loadingIndicator.classList.add('hidden');
                resultsContainer.innerHTML = '<div class="col-span-full text-center py-12 text-rose-500">Error: ' + err.message + '</div>';
            });
        });

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (queryInput.value.trim() !== '') {
                performSearch(queryInput.value);
            }
        });

        function attachSelectButtons() {
            document.querySelectorAll('.select-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('selectedImageUrl').value = this.getAttribute('data-url');
                    document.getElementById('selectedAltText').value = this.getAttribute('data-alt');
                    
                    if (confirm('Gunakan gambar ini untuk artikel? AI akan memproses dan menyuntikkannya ke konten.')) {
                        selectForm.submit();
                    }
                });
            });
        }

        function performSearch(query) {
            resultsContainer.innerHTML = '';
            loadingIndicator.classList.remove('hidden');
            document.getElementById('loadingText').textContent = 'Fetching images from Openverse & Wikimedia...';

            fetch(`{{ route('admin.content.images.search', $content->id) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ query: query })
            })
            .then(res => res.json())
            .then(data => {
                loadingIndicator.classList.add('hidden');
                
                if (data.images && data.images.length > 0) {
                    data.images.forEach(img => {
                        const card = document.createElement('div');
                        card.className = 'bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm group relative cursor-pointer hover:ring-2 hover:ring-indigo-500 transition-all';
                        
                        card.innerHTML = `
                            <div class="aspect-w-16 aspect-h-12 w-full bg-slate-100">
                                <img src="${img.thumb}" alt="${img.alt_text}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            <div class="p-4 absolute bottom-0 inset-x-0 bg-gradient-to-t from-slate-900/90 to-transparent translate-y-full group-hover:translate-y-0 transition duration-300">
                                <p class="text-white text-xs font-medium truncate">Photo by ${img.author}</p>
                                <button type="button" class="mt-2 w-full py-1.5 bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-bold rounded-lg shadow select-btn" data-url="${img.url}" data-alt="${img.alt_text}">
                                    Pilih Gambar Ini
                                </button>
                            </div>
                        `;
                        
                        resultsContainer.appendChild(card);
                    });

                    attachSelectButtons();
                } else {
                    resultsContainer.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-slate-500">No images found for this query. Try a different keyword.</p></div>';
                }
            })
            .catch(err => {
                console.error(err);
                loadingIndicator.classList.add('hidden');
                resultsContainer.innerHTML = '<div class="col-span-full text-center py-12 text-rose-500">Failed to fetch images from API. Please try again later.</div>';
            });
        }
    });
</script>
@endpush
