@extends('layouts.admin')

@section('title', 'Dashboard - ' . config('app.name'))
@section('page_title', 'Dashboard')

@section('admin_content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

        <!-- Total Konten -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-500">Total Konten</div>
                        <div class="text-2xl font-bold text-slate-900 font-outfit">{{ $stats['total_content'] }}</div>
                        <div class="text-xs text-green-600 font-semibold mt-0.5">{{ $stats['published_content'] }} published</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User Pembantu -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-500">User Pembantu</div>
                        <div class="text-2xl font-bold text-slate-900 font-outfit">{{ $stats['total_users'] }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">Akun pengelola aktif</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-500">Total Revenue</div>
                        <div class="text-2xl font-bold text-slate-900 font-outfit">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $stats['total_orders'] }} total orders</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Server Health -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-500">Server Health</div>
                        <div class="text-2xl font-bold text-slate-900 font-outfit">PHP {{ explode('-', $stats['server']['php_version'])[0] }}</div>
                        <div class="text-xs mt-0.5">
                            OPCache:
                            @if($stats['server']['opcache'])
                                <span class="text-emerald-600 font-bold">ON</span>
                            @else
                                <span class="text-rose-600 font-bold">OFF</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Queue Worker -->
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $stats['queue']['status'] === 'stuck' ? 'bg-rose-50 text-rose-600' : ($stats['queue']['status'] === 'running' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-400') }} flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3.75h7.5M8.25 3.75v.75m0-.75v.75m7.5-.75v.75m-7.5 3.75h7.5m-7.5 3.75H12m-8.25 0h.008v.008H3.75V12zm0 3.75h.008v.008H3.75V15.75zm0 3.75h.008v.008H3.75V19.5zM12 15.75h.008v.008H12V15.75zm0 3.75h.008v.008H12V19.5zM16.5 15.75h.008v.008H16.5V15.75zm0 3.75h.008v.008H16.5V19.5zM20.25 15.75h.008v.008H20.25V15.75zm0 3.75h.008v.008H20.25V19.5z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-slate-500">Queue Worker</div>
                        <div class="text-2xl font-bold text-slate-900 font-outfit">
                            @if($stats['queue']['status'] === 'stuck')
                                <span class="text-rose-600">Stuck</span>
                            @elseif($stats['queue']['status'] === 'running')
                                <span class="text-emerald-600">Running</span>
                            @else
                                <span class="text-slate-400">Idle</span>
                            @endif
                        </div>
                        <div class="text-xs mt-0.5 text-slate-500">
                            {{ $stats['queue']['pending'] }} pending &middot; {{ $stats['queue']['active'] }} active
                        </div>
                    </div>
                    <form action="{{ route('admin.dashboard.queue-restart') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all
                            @if($stats['queue']['status'] === 'stuck' || $stats['queue']['pending'] > 0)
                                bg-rose-500 text-white hover:bg-rose-600 shadow-sm
                            @else
                                bg-slate-100 text-slate-400 cursor-not-allowed
                            @endif"
                            @unless($stats['queue']['status'] === 'stuck' || $stats['queue']['pending'] > 0) disabled @endunless>
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                            </svg>
                            Restart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── SEO Stats Cards ─── -->
    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

        <!-- GSC Total Clicks -->
        <div class="bg-gradient-to-br from-white to-orange-50/50 overflow-hidden shadow-sm rounded-xl border border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zm-7.518-.267A8.25 8.25 0 1120.25 10.5M8.288 14.212A5.25 5.25 0 1117.25 10.5"/></svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-500">Clicks (28 hari)</div>
                        <div class="text-2xl font-bold text-slate-900 font-outfit">{{ number_format($stats['gsc']['clicks']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GSC Total Impressions -->
        <div class="bg-gradient-to-br from-white to-blue-50/50 overflow-hidden shadow-sm rounded-xl border border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-500">Impressions (28 hari)</div>
                        <div class="text-2xl font-bold text-slate-900 font-outfit">{{ number_format($stats['gsc']['impressions']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GSC Avg CTR -->
        <div class="bg-gradient-to-br from-white to-emerald-50/50 overflow-hidden shadow-sm rounded-xl border border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-500">Avg CTR</div>
                        <div class="text-2xl font-bold text-slate-900 font-outfit">{{ $stats['gsc']['avg_ctr'] }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GSC Avg Position -->
        <div class="bg-gradient-to-br from-white to-purple-50/50 overflow-hidden shadow-sm rounded-xl border border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3h11.25M9 3v8.25m0 0l-3-3m3 3l3-3m-3 3V21M3.75 21h16.5"/></svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-slate-500">Avg Position</div>
                        <div class="text-2xl font-bold text-slate-900 font-outfit">{{ $stats['gsc']['avg_position'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── Quick Tools ─── -->
    <div class="mt-8">
        <h3 class="text-base font-semibold text-slate-900 font-outfit mb-4">Quick Tools</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('admin.competitor-analysis.index') }}" class="flex flex-col items-center gap-2 p-5 bg-white border border-slate-200 rounded-xl hover:border-brand-indigo hover:shadow-sm transition-all group">
                <svg class="w-8 h-8 text-slate-400 group-hover:text-brand-indigo transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/></svg>
                <span class="text-xs font-semibold text-slate-600 group-hover:text-brand-indigo text-center transition-colors">Competitor Analysis</span>
            </a>
        </div>
    </div>

    <!-- ─── Middle Row: Index Coverage + Content Health + Top Redirects ─── -->
    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Index Coverage -->
        <div class="bg-white shadow-sm rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-900 font-outfit">Index Coverage</h3>
                <a href="{{ route('admin.gsc.index') }}" class="text-xs font-semibold text-brand-indigo hover:text-brand-purple">Details</a>
            </div>
            <div class="px-6 py-5">
                @if(count($stats['index_coverage']) > 0)
                <div class="space-y-3">
                    @foreach($stats['index_coverage'] as $state => $count)
                    @php
                        $stateColors = [
                            'Submitted and indexed' => 'bg-green-100 text-green-800',
                            'Crawled - currently not indexed' => 'bg-amber-100 text-amber-800',
                            'Discovered - currently not indexed' => 'bg-blue-100 text-blue-800',
                            'Page with redirect' => 'bg-purple-100 text-purple-800',
                            'Not found (404)' => 'bg-red-100 text-red-800',
                            'Couldn\'t fetch' => 'bg-rose-100 text-rose-800',
                            'Excluded' => 'bg-gray-100 text-gray-800',
                            'Duplicate' => 'bg-yellow-100 text-yellow-800',
                        ];
                        $badgeColor = $stateColors[$state] ?? 'bg-gray-100 text-gray-800';
                        $shortState = match($state) {
                            'Submitted and indexed' => 'Indexed',
                            'Crawled - currently not indexed' => 'Crawled',
                            'Discovered - currently not indexed' => 'Discovered',
                            'Page with redirect' => 'Redirect',
                            'Not found (404)' => '404',
                            'Couldn\'t fetch' => 'Fetch err',
                            default => $state,
                        };
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold {{ $badgeColor }}">{{ $shortState }}</span>
                        <span class="text-sm font-bold text-slate-900">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-slate-500">Sync GSC URL Inspection dulu.</p>
                @endif
            </div>
        </div>

        <!-- Content Freshness & Readability -->
        <div class="bg-white shadow-sm rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-base font-semibold text-slate-900 font-outfit">Content Health</h3>
            </div>
            <div class="px-6 py-5 space-y-5">
                <!-- Freshness -->
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Freshness</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Last 7 days</span>
                            <span class="text-sm font-bold text-emerald-600">{{ $stats['content_freshness']['last_7'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Last 30 days</span>
                            <span class="text-sm font-bold text-blue-600">{{ $stats['content_freshness']['last_30'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Last 90 days</span>
                            <span class="text-sm font-bold text-amber-600">{{ $stats['content_freshness']['last_90'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">90+ days</span>
                            <span class="text-sm font-bold text-red-600">{{ $stats['content_freshness']['older'] }}</span>
                        </div>
                    </div>
                </div>
                <!-- Readability -->
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Readability</h4>
                    @if(array_sum($stats['readability']) > 0)
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Low (&lt;40)</span>
                            <span class="text-sm font-bold text-red-600">{{ $stats['readability']['low'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Medium (40-70)</span>
                            <span class="text-sm font-bold text-amber-600">{{ $stats['readability']['medium'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">High (&gt;70)</span>
                            <span class="text-sm font-bold text-emerald-600">{{ $stats['readability']['high'] }}</span>
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-slate-500">Belum ada data readability.</p>
                    @endif
                </div>
                <!-- Content Status -->
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">By Status</h4>
                    @if(count($stats['content_by_status']) > 0)
                    <div class="space-y-2">
                        @foreach($stats['content_by_status'] as $status => $count)
                        <div class="flex items-center justify-between">
                            @php
                                $colors = [
                                    'published'  => 'text-green-600',
                                    'draft'      => 'text-gray-600',
                                    'scheduled'  => 'text-blue-600',
                                    'archived'   => 'text-yellow-600',
                                    'processing' => 'text-purple-600',
                                    'blueprint'  => 'text-indigo-600',
                                ];
                            @endphp
                            <span class="text-sm text-slate-600 capitalize">{{ $status }}</span>
                            <span class="text-sm font-bold {{ $colors[$status] ?? 'text-slate-900' }}">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-slate-500">Belum ada data konten.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Redirects -->
        <div class="bg-white shadow-sm rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-900 font-outfit">Top Redirects</h3>
                <a href="{{ route('admin.redirects.index') }}" class="text-xs font-semibold text-brand-indigo hover:text-brand-purple">Manage</a>
            </div>
            <div class="px-6 py-5">
                @if($stats['top_redirects']->count() > 0)
                <ul role="list" class="divide-y divide-slate-100">
                    @foreach($stats['top_redirects'] as $r)
                    <li class="py-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-mono text-slate-900 truncate">/{{ $r->old_url }} <span class="text-xs text-slate-400">&rarr;</span> <span class="truncate">{{ $r->new_url }}</span></p>
                            </div>
                            <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $r->status_code === 301 ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $r->status_code }}
                            </span>
                            <span class="shrink-0 text-xs font-bold text-slate-500">{{ $r->hits }} hits</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-slate-500">Belum ada redirect.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- ─── Bottom Row: Top Queries + Top Products ─── -->
    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Top Search Queries -->
        <div class="bg-white shadow-sm rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-base font-semibold text-slate-900 font-outfit">Top Search Queries (28 hari)</h3>
            </div>
            <div class="px-6 py-5">
                @if($stats['top_queries']->count() > 0)
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs font-semibold text-slate-500 uppercase">
                            <th class="pb-2 pr-2">Query</th>
                            <th class="pb-2 pr-2 text-right">Clicks</th>
                            <th class="pb-2 pr-2 text-right">Impr.</th>
                            <th class="pb-2 text-right">Pos.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($stats['top_queries'] as $q)
                        <tr>
                            <td class="py-2 pr-2 font-medium text-slate-900 truncate max-w-[200px]">{{ $q->query }}</td>
                            <td class="py-2 pr-2 text-right font-bold text-emerald-600">{{ $q->clicks }}</td>
                            <td class="py-2 pr-2 text-right text-slate-600">{{ $q->impressions }}</td>
                            <td class="py-2 text-right text-slate-600">{{ $q->avg_pos }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-sm text-slate-500">Sync GSC Search Analytics dulu.</p>
                @endif
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white shadow-sm rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-base font-semibold text-slate-900 font-outfit">Produk Terlaris</h3>
            </div>
            <div class="px-6 py-5">
                @if($stats['top_products']->count() > 0)
                <ul role="list" class="divide-y divide-slate-100">
                    @foreach($stats['top_products'] as $product)
                    <li class="py-3 flex items-center justify-between">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-900 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-800">
                            {{ $product->invoices_count }} orders
                        </span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-slate-500">Belum ada produk.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Content Chart -->
    <div class="mt-8 bg-white shadow-sm rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-base font-semibold text-slate-900 font-outfit">Konten Baru per Bulan — {{ now()->year }}</h3>
        </div>
        <div class="px-6 py-5">
            @if(array_sum($stats['monthly_content']) > 0)
            <div class="flex items-end space-x-2 h-40">
                @foreach($stats['monthly_content'] as $month => $count)
                @php
                    $maxCount  = max($stats['monthly_content']) ?: 1;
                    $height    = ($count / $maxCount) * 100;
                    $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                @endphp
                <div class="flex-1 flex flex-col items-center">
                    <span class="text-xs font-medium text-slate-700 mb-1">{{ $count }}</span>
                    <div class="w-full bg-gradient-to-t from-brand-indigo to-brand-purple rounded-t" style="height: {{ $height }}%; min-height: {{ $count > 0 ? '4px' : '0' }}"></div>
                    <span class="text-xs text-slate-500 mt-1">{{ $monthNames[$month] }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-slate-500">Belum ada data konten tahun ini.</p>
            @endif
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="mt-8 bg-white shadow-sm rounded-xl border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-base font-semibold text-slate-900 font-outfit">Aktivitas Terakhir</h3>
        </div>
        <div class="px-6 py-5">
            @if($stats['recent_activities']->count() > 0)
            <ul role="list" class="divide-y divide-slate-100">
                @foreach($stats['recent_activities'] as $activity)
                <li class="py-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-sm font-semibold text-slate-600">
                            {{ strtoupper(substr($activity->causer?->name ?? 'S', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm text-slate-900">{{ $activity->description }}</p>
                            <p class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-sm text-slate-500">Belum ada aktivitas.</p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Dashboard-specific JS
</script>
@endpush