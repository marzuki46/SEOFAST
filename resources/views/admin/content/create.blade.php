@extends('layouts.admin')

@section('title', 'Proses Antrean AI - SEOFAST')
@section('page_title', 'Proses Antrean AI')

@section('admin_content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">AI Queue Monitor</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau proses pembuatan konten yang sedang berjalan oleh AI di latar belakang.</p>
        </div>
        <div class="flex items-center gap-3">
            @if($activeJobs->count() > 0)
            <button type="button" id="startAiWorker" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition shadow-lg shadow-emerald-500/20">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z" />
                </svg>
                <span id="startAiWorkerText">Start AI Worker ({{ $activeJobs->count() }} jobs)</span>
            </button>
            @endif
            <a href="{{ route('admin.content.prapost') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
                Kembali ke Pra Post
            </a>
        </div>
    </div>

    <!-- Sessions Alert -->
    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
        <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Terminal Log Window -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-sm overflow-hidden mb-6 hidden" id="terminalContainer">
        <div class="px-4 py-2 border-b border-slate-800 flex items-center justify-between bg-slate-800/50">
            <h3 class="font-bold text-slate-300 text-xs tracking-wider uppercase flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Worker Log
            </h3>
            <button onclick="clearLogs()" class="text-xs text-slate-500 hover:text-slate-300 transition">Clear</button>
        </div>
        <div class="p-4 h-64 overflow-y-auto font-mono text-xs text-emerald-400 bg-black space-y-1" id="terminalLog">
            <div><span class="text-slate-500">>></span> Ready. Waiting for worker to start...</div>
        </div>
    </div>

    <!-- Jobs Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="font-bold text-slate-800 text-sm">Active AI Generation Jobs</h3>
            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-800">
                {{ $activeJobs->count() }} In Queue
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase border-b border-slate-200">
                        <th class="px-6 py-3.5">Job ID</th>
                        <th class="px-6 py-3.5">Target Keyword</th>
                        <th class="px-6 py-3.5">Job Type</th>
                        <th class="px-6 py-3.5">Phase Status</th>
                        <th class="px-6 py-3.5">Tokens Used</th>
                        <th class="px-6 py-3.5">Submitted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($activeJobs as $job)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-mono text-slate-500 text-xs">
                            #{{ $job->id }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900">{{ $job->content?->title ?? 'Unknown Content' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                {{ str_replace('_', ' ', ucwords($job->job_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-indigo-700 font-semibold text-xs">{{ strtoupper($job->status) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono text-xs">
                            {{ number_format($job->tokens_used) }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">
                            {{ $job->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            Tidak ada proses antrean AI yang sedang berjalan. <br>
                            Silakan kembali ke <a href="{{ route('admin.content.prapost') }}" class="text-indigo-600 font-semibold hover:underline">Pra Post</a> untuk memulai generasi konten.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    @if($activeJobs->count() > 0)
        let isWorking = sessionStorage.getItem('ai_worker_running') === 'true';
        const btn = document.getElementById('startAiWorker');
        const btnText = document.getElementById('startAiWorkerText');
        const terminalContainer = document.getElementById('terminalContainer');
        const terminalLog = document.getElementById('terminalLog');

        function setWorkingUI() {
            if (!btn) return;
            btn.classList.remove('from-emerald-500', 'to-emerald-600');
            btn.classList.add('from-amber-500', 'to-amber-600');
            btnText.innerHTML = 'AI Worker Running (Do not close this page)...';
            btn.querySelector('svg').outerHTML = `<svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
        }

        window.clearLogs = function() {
            terminalLog.innerHTML = '<div><span class="text-slate-500">>></span> Logs cleared.</div>';
        }

        function appendLog(text) {
            terminalContainer.classList.remove('hidden');
            if(!text || text.trim() === '') return;
            
            // Clean up Laravel output
            const lines = text.split('\n').filter(l => l.trim() !== '');
            lines.forEach(line => {
                const div = document.createElement('div');
                div.innerHTML = `<span class="text-slate-500">>></span> ${line.replace(/</g, '&lt;').replace(/>/g, '&gt;')}`;
                terminalLog.appendChild(div);
            });
            
            // Keep max 30 lines
            while (terminalLog.children.length > 30) {
                terminalLog.removeChild(terminalLog.firstChild);
            }
            
            terminalLog.scrollTop = terminalLog.scrollHeight;
        }

        async function refreshTable() {
            try {
                const res = await fetch(window.location.href);
                const text = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(text, 'text/html');
                const newTbody = doc.querySelector('tbody');
                if (newTbody) {
                    document.querySelector('tbody').innerHTML = newTbody.innerHTML;
                }
            } catch (e) {
                console.error("Failed to refresh table", e);
            }
        }

        if (btn) {
            btn.addEventListener('click', async function() {
                if (isWorking) {
                    isWorking = false;
                    sessionStorage.setItem('ai_worker_running', 'false');
                    appendLog("Worker stopped manually.");
                    setTimeout(() => window.location.reload(), 500);
                    return;
                }
                
                isWorking = true;
                sessionStorage.setItem('ai_worker_running', 'true');
                setWorkingUI();
                appendLog("Starting background worker...");
                await processNextJob();
            });
        }

        if (isWorking) {
            setWorkingUI();
            setTimeout(() => {
                appendLog("Resuming background worker...");
                processNextJob();
            }, 1000);
        }

        async function processNextJob() {
            if (!isWorking) return;
            
            try {
                const response = await fetch('{{ route("admin.content.work_queue") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                
                if (result.output) {
                    appendLog(result.output);
                } else if (!result.success) {
                    appendLog("[ERROR] " + (result.error || "Unknown error"));
                }
                
                // Refresh table UI seamlessly
                await refreshTable();
                
                if (result.has_more) {
                    // Jeda sebentar sebelum next job agar UI bernafas
                    setTimeout(processNextJob, 1000);
                } else {
                    sessionStorage.setItem('ai_worker_running', 'false');
                    btnText.innerHTML = 'All Jobs Completed!';
                    btn.classList.remove('from-amber-500', 'to-amber-600');
                    btn.classList.add('from-emerald-500', 'to-emerald-600');
                    appendLog("All tasks completed successfully!");
                }
            } catch (err) {
                console.error(err);
                sessionStorage.setItem('ai_worker_running', 'false');
                appendLog("[FATAL ERROR] " + err.message);
                alert("Worker connection lost. Check the log.");
                setTimeout(() => window.location.reload(), 2000);
            }
        }
    @else
        sessionStorage.setItem('ai_worker_running', 'false');
    @endif
</script>
@endsection
