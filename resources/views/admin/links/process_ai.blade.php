@extends('layouts.admin')

@section('title', 'Processing AI Anchors - SEOFAST')
@section('page_title', 'Generating High-CTR Anchors')

@section('admin_content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200 p-8 shadow-sm text-center mt-12">
    <div class="mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 mb-4 shadow-sm border border-indigo-100">
            <svg class="h-8 w-8 animate-spin" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 font-outfit mb-2">Generating AI Anchor Texts</h2>
        <p class="text-slate-500">Please wait while our AI analyzes and generates optimal anchor texts for your internal links. Do not close this page.</p>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-slate-100 rounded-full h-4 mb-2 overflow-hidden border border-slate-200">
        <div id="progress-bar" class="bg-gradient-to-r from-purple-600 to-indigo-600 h-4 rounded-full transition-all duration-500 ease-out flex items-center justify-center" style="width: 0%"></div>
    </div>
    
    <div class="flex justify-between items-center text-sm font-medium">
        <span class="text-slate-500" id="progress-text">Processing 0 of {{ $totalCount }} links...</span>
        <span class="text-indigo-600" id="progress-percentage">0%</span>
    </div>

    <div id="error-container" class="mt-6 text-left hidden">
        <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 text-sm flex gap-3">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <strong>Notice:</strong> <span id="error-message"></span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalCount = {{ $totalCount }};
    const initialPending = {{ $pendingCount }};
    const siloId = {{ $silo->id }};
    
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const progressPercentage = document.getElementById('progress-percentage');
    const errorContainer = document.getElementById('error-container');
    const errorMessage = document.getElementById('error-message');
    
    function updateProgress(remaining) {
        let processed = totalCount - remaining;
        let percentage = Math.min(100, Math.round((processed / totalCount) * 100));
        
        progressBar.style.width = percentage + '%';
        progressPercentage.innerText = percentage + '%';
        progressText.innerText = `Processing ${processed} of ${totalCount} links...`;
    }
    
    // Initial display
    updateProgress(initialPending);
    
    function processNextChunk() {
        fetch('{{ route('admin.links.process_ai_chunk') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                silo_id: siloId 
                @if(isset($clusterId))
                , cluster_id: {{ $clusterId }}
                @endif
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'done') {
                updateProgress(0);
                setTimeout(() => {
                    let redirectUrl = '{{ route('admin.links.index') }}?silo_id=' + siloId;
                    @if(isset($clusterId))
                        redirectUrl += '&cluster_id={{ $clusterId }}';
                    @endif
                    window.location.href = redirectUrl;
                }, 1000);
            } else {
                if (data.status === 'error_fallback') {
                    errorContainer.classList.remove('hidden');
                    errorMessage.innerText = "Beberapa API request timeout. Fallback digunakan untuk mencegah loop tak terbatas.";
                }
                
                updateProgress(data.remaining);
                
                // Sleep slightly to prevent rate limits
                setTimeout(processNextChunk, 1500);
            }
        })
        .catch(err => {
            errorContainer.classList.remove('hidden');
            errorMessage.innerText = "Koneksi terputus. Mencoba ulang dalam 5 detik...";
            setTimeout(processNextChunk, 5000);
        });
    }
    
    // Start processing
    processNextChunk();
});
</script>
@endsection
