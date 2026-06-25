@extends('layouts.admin')

@section('title', 'Dashboard - SEOFAST V3')
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
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Content Status Distribution -->
        <div class="bg-white shadow-sm rounded-xl border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-base font-semibold text-slate-900 font-outfit">Status Konten</h3>
            </div>
            <div class="px-6 py-5">
                @if(count($stats['content_by_status']) > 0)
                <div class="space-y-4">
                    @foreach($stats['content_by_status'] as $status => $count)
                    <div class="flex items-center justify-between">
                        @php
                            $colors = [
                                'published'  => 'bg-green-100 text-green-800',
                                'draft'      => 'bg-gray-100 text-gray-800',
                                'scheduled'  => 'bg-blue-100 text-blue-800',
                                'archived'   => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-purple-100 text-purple-800',
                                'blueprint'  => 'bg-indigo-100 text-indigo-800',
                            ];
                            $badgeColor = $colors[$status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold {{ $badgeColor }}">
                            {{ ucfirst($status) }}
                        </span>
                        <span class="text-sm font-bold text-slate-900">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-slate-500">Belum ada data konten.</p>
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