@extends('layouts.admin')

@section('title', 'Google Search Console - SEOFAST V3')
@section('page_title', 'Google Search Console')

@section('admin_content')
    <!-- Top Action Buttons & Page Description -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-lg font-bold text-slate-900 font-outfit">Console Actions</h2>
            <p class="text-sm text-slate-500">Run manual synchronization jobs for indexation and performance monitoring.</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <form action="{{ route('admin.gsc.sync_inspections') }}" method="POST" class="flex-1 md:flex-none">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-600/10 hover:bg-indigo-500 transition duration-150">
                    Sync URL Inspections
                </button>
            </form>
            <form action="{{ route('admin.gsc.sync_analytics') }}" method="POST" class="flex-1 md:flex-none">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-slate-900/10 hover:bg-slate-800 transition duration-150">
                    Sync Analytics
                </button>
            </form>
        </div>
    </div>
        <!-- Toast Notifications -->
        @if (session('success'))
            <div class="rounded-md bg-emerald-50 p-4 mb-6 border border-emerald-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md bg-rose-50 p-4 mb-6 border border-rose-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div x-data="{ activeTab: 'indexation' }">
            
            <!-- Tabs Navigation -->
            <div class="border-b border-slate-200 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'indexation'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'indexation', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'indexation'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Indexation Health
                    </button>
                    <button @click="activeTab = 'logs'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'logs', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'logs'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Sync Logs
                    </button>
                    <button @click="activeTab = 'credentials'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'credentials', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'credentials'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Setup & Credentials
                    </button>
                </nav>
            </div>

            <!-- Tab Content: Indexation Health -->
            <div x-show="activeTab === 'indexation'" x-transition style="display: none;">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-xl">
                        <h2 class="text-base font-semibold leading-6 text-slate-900">URL Indexation & GSC Health Report</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Details on Google Search Console diagnostic verdict and crawler metrics.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr class="bg-slate-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">URL / Slug</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Verdict</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Coverage State</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Robots.txt</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Page Fetch</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Last Crawled</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($contents as $content)
                                    @php
                                        $insp = $content->latestUrlInspection;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                            <span class="block text-xs font-normal text-slate-400">/{{ $content->slug }}</span>
                                            <span class="truncate block max-w-xs">{{ $content->meta_title ?: ucfirst(str_replace('-', ' ', $content->slug)) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($insp)
                                                @php
                                                    $verdictColors = [
                                                        'GOOD' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                                                        'NEEDS_ATTENTION' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                                                        'BAD' => 'bg-rose-50 text-rose-700 ring-rose-600/10',
                                                    ];
                                                    $color = $verdictColors[$insp->verdict] ?? 'bg-slate-50 text-slate-700 ring-slate-600/10';
                                                @endphp
                                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $color }}">
                                                    {{ $insp->verdict }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-semibold text-slate-500 ring-1 ring-inset ring-slate-600/10">
                                                    NOT CHECKED
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ $insp?->coverage_state ?? 'No coverage data' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $insp?->robots_txt_state ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $insp?->page_fetch_state ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                            {{ $insp?->last_crawl_time ? $insp->last_crawl_time->format('Y-m-d H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('admin.gsc.submit_indexing') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="content_id" value="{{ $content->id }}">
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 rounded-md px-2.5 py-1.5 text-xs font-semibold transition duration-150">
                                                    Submit Indexing API
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-500">
                                            No published contents found. Please seed or add contents first.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Sync Logs -->
            <div x-show="activeTab === 'logs'" x-transition style="display: none;">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-xl">
                        <h2 class="text-base font-semibold leading-6 text-slate-900">Recent Sync Logs</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Monitoring logs of recent background sync events.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr class="bg-slate-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Sync Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">URLs</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Synced At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($syncLogs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                            {{ ucfirst(str_replace('_', ' ', $log->sync_type)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $statusColors = [
                                                    'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                                                    'running' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                                                    'failed' => 'bg-rose-50 text-rose-700 ring-rose-600/10',
                                                    'partial' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
                                                ];
                                                $color = $statusColors[$log->status] ?? 'bg-slate-50 text-slate-700 ring-slate-600/10';
                                            @endphp
                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $color }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $log->processed_urls }}/{{ $log->total_urls ?? 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-mono">
                                            {{ $log->duration_seconds ?? '-' }}s
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                            {{ $log->started_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                            No sync activities recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Setup & Credentials -->
            <div x-show="activeTab === 'credentials'" x-transition style="display: none;">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl border border-slate-200 max-w-3xl mx-auto">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 rounded-t-xl">
                        <h2 class="text-base font-semibold leading-6 text-slate-900">API Credentials</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Define your Search Console Property and OAuth2 tokens.</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <!-- Google OAuth Connection Status -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-center">
                            @if ($gscCred && $gscCred->access_token && !$gscCred->isExpired())
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/10 mb-2">Connected</span>
                                <p class="text-xs text-slate-600">Google Search Console is authorized.</p>
                                <p class="text-[10px] text-slate-400 mt-1">Expires: {{ $gscCred->token_expires_at?->format('Y-m-d H:i') }}</p>
                            @else
                                <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/10 mb-2">Disconnected</span>
                                <p class="text-xs text-slate-600 mb-3">Connect your account automatically via Google OAuth 2.0.</p>
                            @endif
                            
                            <a href="{{ route('admin.gsc.auth') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition duration-150 w-full">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                                </svg>
                                Connect with Google
                            </a>
                        </div>

                        <hr class="border-slate-200">

                        <form action="{{ route('admin.gsc.save_credentials') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label for="property_url" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">GSC Property URL</label>
                                <input type="url" name="property_url" id="property_url" 
                                       value="{{ $gscCred?->property_url ?? config('app.url') }}"
                                       required 
                                       class="mt-2 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-slate-800 placeholder-slate-400 bg-slate-50 border p-2">
                            </div>

                        <div>
                            <label for="access_token" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">OAuth Access Token</label>
                            <textarea name="access_token" id="access_token" rows="3"
                                      placeholder="Paste access token JSON or plain token string..."
                                      class="mt-2 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-slate-800 placeholder-slate-400 bg-slate-50 border p-2 font-mono text-xs">{{ $gscAccessToken }}</textarea>
                        </div>

                        <div>
                            <label for="refresh_token" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">OAuth Refresh Token</label>
                            <input type="text" name="refresh_token" id="refresh_token" 
                                   value="{{ $gscRefreshToken }}"
                                   placeholder="Offline refresh token..."
                                   class="mt-2 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-slate-800 placeholder-slate-400 bg-slate-50 border p-2 font-mono text-xs">
                        </div>

                        <div>
                            <label for="service_account_json" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Indexing API Service Account JSON</label>
                            <textarea name="service_account_json" id="service_account_json" rows="4"
                                      placeholder="Paste complete Google Service Account JSON contents here..."
                                      class="mt-2 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-slate-800 placeholder-slate-400 bg-slate-50 border p-2 font-mono text-xs">{{ $serviceAccountJson }}</textarea>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition duration-150">
                            Save API Credentials
                        </button>
                    </form>
                    </div>
            </div>

        </div>
@endsection
