@extends('layouts.admin')

@section('title', 'Infrastructure - SEOFAST V3')
@section('page_title', 'Infrastructure Settings')

@section('admin_content')
    <!-- Current Status -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-sm font-medium text-slate-500 mb-1">Queue Driver</div>
            <div class="text-2xl font-bold font-outfit {{ $queueDriver === 'redis' ? 'text-emerald-600' : 'text-amber-600' }}">
                {{ $queueDriver }}
            </div>
            <div class="text-xs text-slate-400 mt-0.5">QUEUE_CONNECTION</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-sm font-medium text-slate-500 mb-1">Cache Driver</div>
            <div class="text-2xl font-bold font-outfit {{ $cacheDriver === 'redis' ? 'text-emerald-600' : 'text-amber-600' }}">
                {{ $cacheDriver }}
            </div>
            <div class="text-xs text-slate-400 mt-0.5">CACHE_DRIVER</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-sm font-medium text-slate-500 mb-1">Redis Extension</div>
            <div class="text-2xl font-bold font-outfit {{ $redisInstalled ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $redisInstalled ? '✓ Tersedia' : '✕ Tidak Ada' }}
            </div>
            <div class="text-xs text-slate-400 mt-0.5">ext-redis</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="text-sm font-medium text-slate-500 mb-1">Redis Server</div>
            <div class="text-2xl font-bold font-outfit {{ $redisRunning ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $redisRunning ? '✓ Running' : '✕ Offline' }}
            </div>
            <div class="text-xs text-slate-400 mt-0.5">127.0.0.1:6379</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Queue Driver -->
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-base font-semibold text-slate-900 font-outfit">Queue Driver</h3>
                <p class="text-xs text-slate-500 mt-1">Saat ini: <strong>{{ $queueDriver }}</strong></p>
            </div>
            <div class="px-6 py-5">
                <form method="POST" action="{{ route('admin.infrastructure.update-queue') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Queue Driver</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer {{ $queueDriver === 'database' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-slate-300' }}">
                                <input type="radio" name="driver" value="database" {{ $queueDriver === 'database' ? 'checked' : '' }} class="text-indigo-600">
                                <div>
                                    <span class="text-sm font-medium text-slate-900">Database (MySQL)</span>
                                    <p class="text-xs text-slate-500">Cocok untuk development, tanpa Redis</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer {{ $queueDriver === 'redis' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-slate-300' }} {{ !$redisRunning ? 'opacity-50' : '' }}">
                                <input type="radio" name="driver" value="redis" {{ $queueDriver === 'redis' ? 'checked' : '' }} {{ !$redisRunning ? 'disabled' : '' }} class="text-indigo-600">
                                <div>
                                    <span class="text-sm font-medium text-slate-900">Redis</span>
                                    <p class="text-xs text-slate-500">Lebih cepat, support Horizon</p>
                                    @if(!$redisRunning)
                                        <p class="text-xs text-rose-500 mt-1">Redis server tidak aktif</p>
                                    @endif
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-800">
                        <strong>⚠️ Perhatian:</strong> Setelah ganti ke Redis, jangan lupa klik tombol <strong>"Restart Queue"</strong> di bawah agar perubahan diterapkan.
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-all">
                        Simpan Queue Driver
                    </button>
                </form>
            </div>
        </div>

        <!-- Cache Driver -->
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-base font-semibold text-slate-900 font-outfit">Cache Driver</h3>
                <p class="text-xs text-slate-500 mt-1">Saat ini: <strong>{{ $cacheDriver }}</strong></p>
            </div>
            <div class="px-6 py-5">
                <form method="POST" action="{{ route('admin.infrastructure.update-cache') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Cache Driver</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer {{ $cacheDriver === 'file' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-slate-300' }}">
                                <input type="radio" name="driver" value="file" {{ $cacheDriver === 'file' ? 'checked' : '' }} class="text-indigo-600">
                                <div>
                                    <span class="text-sm font-medium text-slate-900">File</span>
                                    <p class="text-xs text-slate-500">Simpan cache di storage/file</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer {{ $cacheDriver === 'redis' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-slate-300' }} {{ !$redisRunning ? 'opacity-50' : '' }}">
                                <input type="radio" name="driver" value="redis" {{ $cacheDriver === 'redis' ? 'checked' : '' }} {{ !$redisRunning ? 'disabled' : '' }} class="text-indigo-600">
                                <div>
                                    <span class="text-sm font-medium text-slate-900">Redis</span>
                                    <p class="text-xs text-slate-500">Cache lebih cepat, shared memory</p>
                                    @if(!$redisRunning)
                                        <p class="text-xs text-rose-500 mt-1">Redis server tidak aktif</p>
                                    @endif
                                </div>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition-all">
                        Simpan Cache Driver
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tools -->
    <div class="mt-8 bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-base font-semibold text-slate-900 font-outfit">⚙️ Tools</h3>
        </div>
        <div class="px-6 py-5 flex flex-wrap gap-4">
            <form method="POST" action="{{ route('admin.dashboard.cache-html') }}" onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='Memproses...'">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                    </svg>
                    Cache Ulang HTML Semua Konten
                </button>
            </form>
        </div>
    </div>

    <!-- Queue Controls -->
    <div class="mt-8 bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-base font-semibold text-slate-900 font-outfit">Queue Controls</h3>
        </div>
        <div class="px-6 py-5 flex flex-wrap gap-4">
            @if($horizonInstalled && $queueDriver === 'redis')
            <form method="POST" action="{{ route('admin.infrastructure.restart-queue') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-amber-700 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                    </svg>
                    Restart Queue
                </button>
            </form>
            <form method="POST" action="{{ route('admin.infrastructure.queue-status') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700 transition-all">
                    Cek Status Queue
                </button>
            </form>
            @else
            <p class="text-sm text-slate-500">
                @if(!$horizonInstalled)
                    Horizon belum terinstall.
                @else
                    Queue masih pake database. Ganti ke Redis dulu untuk akses kontrol Horizon.
                @endif
            </p>
            @endif
        </div>
    </div>

    <!-- Current .env Preview (read-only) -->
    <div class="mt-8 bg-white rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-base font-semibold text-slate-900 font-outfit">File .env</h3>
            <span class="text-xs text-slate-400">Read-only preview</span>
        </div>
        <div class="px-6 py-5">
            <pre class="bg-slate-900 text-slate-200 text-xs font-mono p-4 rounded-lg overflow-x-auto max-h-64 leading-relaxed">@php
                $lines = explode("\n", $envContents);
                foreach ($lines as $line) {
                    if (str_starts_with($line, 'DB_PASSWORD') || str_starts_with($line, 'APP_KEY') || str_starts_with($line, 'MIDTRANS_SERVER_KEY') || str_starts_with($line, 'GOOGLE_CLIENT_SECRET')) {
                        $parts = explode('=', $line, 2);
                        echo htmlspecialchars($parts[0]) . '=********' . "\n";
                    } else {
                        echo htmlspecialchars($line) . "\n";
                    }
                }
            @endphp</pre>
        </div>
    </div>
@endsection
