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
            <button id="startAiWorker" class="flex-1 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-8 py-3.5 rounded-xl font-bold shadow-lg shadow-emerald-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                <span id="startAiWorkerText"><span class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Start AI Worker ({{ $activeJobs->whereIn('status', ['pending', 'processing'])->count() }} jobs)</span></span>
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

    <!-- ═══ Terminal Log Window ═══ -->
    <div class="bg-slate-950 rounded-2xl border border-slate-700 shadow-xl overflow-hidden hidden" id="terminalContainer">
        <div class="px-4 py-2.5 border-b border-slate-700 flex items-center justify-between bg-slate-800">
            <h3 class="font-bold text-slate-300 text-xs tracking-wider uppercase flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" id="terminalDot"></span>
                <span id="terminalTitle">Live AI Pipeline Log</span>
            </h3>
            <div class="flex items-center gap-4">
                <span class="text-xs text-slate-500 font-mono" id="terminalCounter">0 lines</span>
                <button onclick="clearLogs()" class="text-xs text-slate-500 hover:text-white transition">Clear</button>
            </div>
        </div>
        <div class="p-3 h-72 overflow-y-auto font-mono text-xs bg-black space-y-0.5 leading-5" id="terminalLog">
            <div class="text-slate-600">&gt;&gt; Ready. Press Start to begin...</div>
        </div>
    </div>

    <!-- ═══ Jobs Table Card ═══ -->
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
                        <th class="px-6 py-3.5">Retry</th>
                        <th class="px-6 py-3.5">Submitted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($activeJobs as $job)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-mono text-slate-500 text-xs">#{{ $job->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900">{{ $job->content?->target_keyword ?? 'Unknown' }}</span>
                                <span class="text-xs text-slate-400 mt-0.5">{{ $job->content?->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                {{ str_replace('_', ' ', ucwords($job->job_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin h-3.5 w-3.5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                @php
                                    $phaseColors = [
                                        'pending'    => 'bg-slate-100 text-slate-600',
                                        'phase_1'    => 'bg-blue-100 text-blue-700',
                                        'phase_2'    => 'bg-purple-100 text-purple-700',
                                        'phase_3'    => 'bg-amber-100 text-amber-700',
                                        'phase_4'    => 'bg-indigo-100 text-indigo-700',
                                        'completed'  => 'bg-emerald-100 text-emerald-700',
                                        'failed'     => 'bg-rose-100 text-rose-700',
                                        'failed_cqi' => 'bg-orange-100 text-orange-700',
                                    ];
                                    $phaseName = [
                                        'pending'    => 'Pending',
                                        'phase_1'    => '📝 Phase 1: Draft',
                                        'phase_2'    => '🔍 Phase 2: CQI Check',
                                        'phase_3'    => '✍️ Phase 3: Expand',
                                        'phase_4'    => '🎨 Phase 4: Polish',
                                        'completed'  => '✅ Completed',
                                        'failed'     => '❌ Failed',
                                        'failed_cqi' => '⚠️ CQI Retry',
                                    ];
                                    $color = $phaseColors[$job->status] ?? 'bg-slate-100 text-slate-600';
                                    $label = $phaseName[$job->status] ?? strtoupper($job->status);
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $color }}">
                                    {{ $label }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-xs font-mono">{{ $job->retry_count ?? 0 }}/2</td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $job->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            Tidak ada proses antrean AI yang sedang berjalan.<br>
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
        let isWorking   = sessionStorage.getItem('ai_worker_running') === 'true';
        let logLineCount = 0;
        const MAX_LINES  = 50;

        const btn          = document.getElementById('startAiWorker');
        const btnText      = document.getElementById('startAiWorkerText');
        const termContainer = document.getElementById('terminalContainer');
        const termLog      = document.getElementById('terminalLog');
        const termDot      = document.getElementById('terminalDot');
        const termTitle    = document.getElementById('terminalTitle');
        const termCounter  = document.getElementById('terminalCounter');

        // ─── Colour palette per log level ────────────────────────────────────
        const LEVEL_STYLE = {
            info:    { cls: 'text-cyan-400',    icon: '●' },
            success: { cls: 'text-emerald-400', icon: '✔' },
            error:   { cls: 'text-rose-400',    icon: '✘' },
            warn:    { cls: 'text-amber-400',   icon: '⚠' },
            running: { cls: 'text-indigo-400',  icon: '↻' },
            check:   { cls: 'text-violet-400',  icon: '⚡' },
        };

        function esc(s) {
            return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        window.clearLogs = function () {
            termLog.innerHTML = '<div class="text-slate-600">&gt;&gt; Logs cleared.</div>';
            logLineCount = 0;
            termCounter.textContent = '0 lines';
        };

        function appendLog(level, message) {
            termContainer.classList.remove('hidden');
            const s   = LEVEL_STYLE[level] || LEVEL_STYLE.info;
            const ts  = new Date().toLocaleTimeString('id-ID', { hour12: false });
            const row = document.createElement('div');
            row.className = 'flex gap-2 items-start';
            row.innerHTML = `<span class="text-slate-600 shrink-0 select-none">${ts}</span>`
                          + `<span class="${s.cls} shrink-0">${s.icon}</span>`
                          + `<span class="${s.cls} break-words min-w-0">${esc(message)}</span>`;
            termLog.appendChild(row);
            logLineCount++;

            while (termLog.children.length > MAX_LINES) {
                termLog.removeChild(termLog.firstChild);
            }
            termLog.scrollTop = termLog.scrollHeight;
            termCounter.textContent = logLineCount + ' lines';
        }

        function setWorkingUI(active) {
            if (!btn) return;
            if (active) {
                btn.className = btn.className.replace('from-emerald-500 to-emerald-600', 'from-rose-500 to-rose-600');
                btn.className = btn.className.replace('hover:from-emerald-600 hover:to-emerald-700', 'hover:from-rose-600 hover:to-rose-700');
                btn.className = btn.className.replace('shadow-emerald-500/20', 'shadow-rose-500/20');
                btnText.innerHTML = '<span class="flex items-center gap-2"><svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Pause / Stop Worker</span>';
                termTitle.textContent = 'Live AI Pipeline Log — Running';
            } else {
                btn.className = btn.className.replace('from-rose-500 to-rose-600', 'from-emerald-500 to-emerald-600');
                btn.className = btn.className.replace('hover:from-rose-600 hover:to-rose-700', 'hover:from-emerald-600 hover:to-emerald-700');
                btn.className = btn.className.replace('shadow-rose-500/20', 'shadow-emerald-500/20');
                
                // Fallback for previous amber classes just in case
                btn.className = btn.className.replace('from-amber-500 to-amber-600', 'from-emerald-500 to-emerald-600');
                
                let pendingCount = (typeof jobQueue !== 'undefined') ? jobQueue.length : {{ $activeJobs->whereIn('status', ['pending', 'processing'])->count() }};
                btnText.innerHTML = `<span class="flex items-center gap-2"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Start AI Worker (${pendingCount} jobs)</span>`;
                termTitle.textContent = 'AI Pipeline Log — Idle';
                termDot.classList.replace('bg-emerald-400','bg-slate-500');
                termDot.classList.remove('animate-pulse');
            }
        }

        async function refreshTable() {
            try {
                const res = await fetch(window.location.href);
                const html = await res.text();
                const doc  = new DOMParser().parseFromString(html, 'text/html');
                const nb   = doc.querySelector('tbody');
                if (nb) document.querySelector('tbody').innerHTML = nb.innerHTML;
            } catch (_) {}
        }
        
        // Auto-refresh the UI table periodically if AI Worker is running
        setInterval(() => {
            if (isWorking) {
                refreshTable();
            }
        }, 5000);

        /**
         * Cek koneksi ke AI provider sebelum batch dimulai.
         * Return true jika koneksi OK, false jika gagal.
         */
        async function checkAiConnection() {
            appendLog('check', 'Mengecek koneksi ke AI provider...');
            try {
                const res = await fetch('{{ route("admin.content.check_connection") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN':     '{{ csrf_token() }}',
                        'Content-Type':     'application/json',
                        'Accept':           'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({})
                });

                if (!res.ok) {
                    appendLog('error', `Koneksi check HTTP ${res.status} — ${res.statusText}`);
                    return false;
                }

                const data = await res.json();

                // Render per-provider diagnostics (always, for visibility)
                if (data.diagnostics && data.diagnostics.length > 0) {
                    data.diagnostics.forEach(d => {
                        const icon    = d.status === 'success' ? '✔' : (d.status === 'skipped' ? '⊘' : '✘');
                        const timing  = d.elapsed_ms != null ? ` [${d.elapsed_ms}ms]` : '';
                        const fmt     = d.response_format && d.response_format !== 'unknown' ? ` fmt:${d.response_format}` : '';
                        const http    = d.http_status != null ? ` HTTP ${d.http_status}` : '';
                        const len     = d.content_length != null ? ` ${d.content_length}chars` : '';
                        let line = `  ${icon} [${d.provider}] ${d.model}${timing}${http}${fmt}${len}`;
                        if (d.error) line += ` → ${d.error}`;
                        const lvl = d.status === 'success' ? 'success' : (d.status === 'skipped' ? 'warn' : 'error');
                        appendLog(lvl, line);
                        if (d.raw_snippet) appendLog('warn', `     RAW: ${d.raw_snippet.substring(0, 200)}`);
                    });
                }

                if (data.ok) {
                    appendLog('success', `✅ Koneksi AI OK — Provider: [${data.provider}] | Model: [${data.model}]`);
                    appendLog('info', 'Semua phase (1-4) menggunakan provider yang sama.');
                    return true;
                } else {
                    appendLog('error', `❌ Koneksi AI GAGAL — ${data.error}`);
                    appendLog('warn', 'Periksa API Key & Base URL di Settings → AI Configuration.');
                    return false;
                }
            } catch (err) {
                appendLog('error', 'Koneksi check exception: ' + err.message);
                return false;
            }
        }


        if (btn) {
            btn.addEventListener('click', async function () {
                if (isWorking) {
                    isWorking = false;
                    sessionStorage.setItem('ai_worker_running', 'false');
                    setWorkingUI(false);
                    appendLog('warn', '⏸️ Worker di-pause. Proses yang sedang berjalan saat ini akan diselesaikan, lalu berhenti.');
                    return;
                }

                // Tampilkan terminal dulu sebelum cek koneksi
                termContainer.classList.remove('hidden');
                isWorking = true;
                sessionStorage.setItem('ai_worker_running', 'true');
                setWorkingUI(true);
                appendLog('info', 'Memulai AI Worker...');

                // ── Cek koneksi dulu sebelum mulai ──────────────────
                const connectionOk = await checkAiConnection();
                if (!connectionOk) {
                    isWorking = false;
                    sessionStorage.setItem('ai_worker_running', 'false');
                    setWorkingUI(false);
                    appendLog('error', '⛔ Worker dihentikan karena koneksi AI gagal.');
                    return;
                }

                appendLog('info', `Mulai memproses ${jobQueue.length} job secara berurutan...`);
                await processNextJob();
            });
        }

        // Auto-resume jika halaman di-refresh saat sedang berjalan
        if (isWorking) {
            setWorkingUI(true);
            setTimeout(() => {
                appendLog('info', 'Melanjutkan dari sesi sebelumnya...');
                processNextJob();
            }, 800);
        }

        // Convert PHP active jobs to JS array
        @php
            $queueArray = $activeJobs->map(function($job) {
                return [
                    'job_id' => $job->id,
                    'content_id' => $job->content_id,
                    'keyword' => $job->content->target_keyword ?? 'Unknown',
                    'target_status' => is_array($job->error_log) ? ($job->error_log['target_status'] ?? 'draft') : 'draft',
                    'status' => $job->status
                ];
            })->filter(fn($j) => in_array($j['status'], ['pending', 'processing']))->values()->toArray();
        @endphp
        let jobQueue = {!! json_encode($queueArray) !!};

        async function processNextJob() {
            if (!isWorking) return;
            
            if (jobQueue.length === 0) {
                isWorking = false;
                sessionStorage.setItem('ai_worker_running', 'false');
                setWorkingUI(false);
                appendLog('success', '═══ Semua job AI selesai! Cek halaman Draft. ═══');
                return;
            }

            const currentJob = jobQueue.shift(); // Get first job
            
            if (!currentJob.resumed) {
                appendLog('info', `▶ Memulai proses untuk: [${currentJob.keyword}]...`);
            }

            try {
                const res = await fetch('{{ route("admin.content.generate_single") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN':     '{{ csrf_token() }}',
                        'Content-Type':     'application/json',
                        'Accept':           'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        content_id: currentJob.content_id,
                        job_id: currentJob.job_id,
                        target_status: currentJob.target_status
                    })
                });

                if (!res.ok) {
                    if (res.status === 524 || res.status === 520) {
                        appendLog('warn', `Cloudflare Timeout ${res.status}. Server masih bekerja di belakang layar...`);
                        currentJob.resumed = true;
                        jobQueue.unshift(currentJob); // Kembalikan ke antrean
                        setTimeout(processNextJob, 10000); // Polling lagi 10 detik kemudian
                        return;
                    }
                    appendLog('error', `HTTP ${res.status} — Kemungkinan server kehabisan memory/timeout.`);
                }

                let data = {};
                try {
                    data = await res.json();
                } catch (e) {
                    if (res.status === 524 || res.status === 520) return;
                    throw new Error(`Gagal membaca respons dari server. Status: ${res.status}`);
                }

                // Render structured log entries
                if (data.logs && Array.isArray(data.logs) && data.logs.length > 0) {
                    data.logs.forEach(e => appendLog(e.level || 'info', e.message || ''));
                }
                
                if (!data.success) {
                    appendLog('error', data.error || data.message || `Server Error ${res.status}`);
                } else if (data.status === 'continue') {
                    // Masukkan kembali ke antrean paling depan untuk lanjut ke phase berikutnya
                    currentJob.resumed = true;
                    jobQueue.unshift(currentJob);
                }

                await refreshTable();

                // Continue to next job after a short delay
                setTimeout(processNextJob, 500);

            } catch (err) {
                isWorking = false;
                sessionStorage.setItem('ai_worker_running', 'false');
                setWorkingUI(false);
                appendLog('error', 'FATAL: ' + err.message);
                // We don't auto-reload here so user can see the error
                btnText.textContent = 'Worker Error (Refresh Page)';
            }
        }
    @else
        sessionStorage.setItem('ai_worker_running', 'false');
    @endif
</script>
@endsection

