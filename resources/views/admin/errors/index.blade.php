@extends('layouts.admin')

@section('title', '404 Error Tracker - ' . config('app.name'))
@section('page_title', '404 Error Tracker')

@section('admin_content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <p class="text-slate-500 text-sm">Analitik akses 4xx: IP, kategori aktor, lokasi, URL, dan frekuensi.</p>
    <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('admin.errors.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="URL/path..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 w-48">
            <input type="text" name="ip" value="{{ request('ip') }}" placeholder="IP address" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 w-36">
            <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">Semua kategori</option>
                @foreach(['person' => 'Orang', 'search_engine_crawler' => 'Search crawler', 'bot' => 'Bot', 'hacker' => 'Hacker', 'other' => 'Lainnya'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status_code" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">Semua 4xx</option>
                @foreach([400, 401, 403, 404, 405, 408, 429] as $status)
                    <option value="{{ $status }}" @selected((string) request('status_code') === (string) $status)>{{ $status }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50">Search</button>
        </form>
        <a href="{{ route('admin.errors.export', request()->query()) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export CSV
        </a>
        <form action="{{ route('admin.errors.clear_all') }}" method="POST" onsubmit="return confirm('Clear all 404 entries? This cannot be undone.');">
            @csrf
            <button type="submit" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-50">Clear All</button>
        </form>
    </div>
</div>

<form method="GET" action="{{ route('admin.errors.index') }}" class="mb-6 flex flex-wrap items-center gap-2 rounded-xl border bg-white p-4 shadow-sm">
    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rentang tanggal</span>
    <input type="date" name="from" value="{{ request('from') }}" class="rounded-lg border-gray-300 text-sm">
    <input type="date" name="to" value="{{ request('to') }}" class="rounded-lg border-gray-300 text-sm">
    @foreach(request()->except(['from', 'to', 'page']) as $key => $value)<input type="hidden" name="{{ $key }}" value="{{ $value }}">@endforeach
    <button class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white">Terapkan filter</button>
</form>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm font-semibold">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border rounded-xl p-4 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Unique URLs</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalCount) }}</p>
    </div>
    <div class="bg-white border rounded-xl p-4 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total 404 Hits</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalHits) }}</p>
    </div>
    <div class="bg-white border rounded-xl p-4 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Redir. Potential</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($pageErrors->where('count', '>', 10)->count()) }}</p>
    </div>
    <div class="bg-white border rounded-xl p-4 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">IP Terpantau</p>
        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($ipSummary->count()) }}</p>
    </div>
</div>

<div class="mb-6 overflow-hidden rounded-xl border bg-white shadow-sm">
    <div class="border-b px-5 py-3"><h2 class="font-bold text-slate-900">Ringkasan IP</h2><p class="text-xs text-slate-500">Klik cek untuk melihat lokasi dan semua path yang diakses IP tersebut.</p></div>
    <div class="overflow-x-auto"><table class="w-full text-left"><thead class="bg-gray-50 border-b"><tr><th class="px-5 py-3 text-xs uppercase text-gray-500">IP</th><th class="px-5 py-3 text-xs uppercase text-gray-500">Kategori</th><th class="px-5 py-3 text-xs uppercase text-gray-500">Total akses 4xx</th><th class="px-5 py-3 text-xs uppercase text-gray-500">Terakhir</th><th></th></tr></thead>
    <tbody class="divide-y divide-gray-100">@forelse($ipSummary as $summary)<tr><td class="px-5 py-3 font-mono text-sm">{{ $summary->ip_address }}</td><td class="px-5 py-3 text-sm">{{ str_replace('_', ' ', ucfirst($summary->category)) }}</td><td class="px-5 py-3 text-sm font-bold">{{ number_format($summary->hits) }}</td><td class="px-5 py-3 text-sm text-gray-500">{{ $summary->last_seen ? \Carbon\Carbon::parse($summary->last_seen)->diffForHumans() : '-' }}</td><td class="px-5 py-3 text-right"><button type="button" onclick="showIpDetails(@js($summary->ip_address))" class="text-sm font-semibold text-indigo-600">Cek lokasi & path</button></td></tr>@empty<tr><td colspan="5" class="px-5 py-6 text-center text-sm text-gray-500">Belum ada data IP.</td></tr>@endforelse</tbody></table></div>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">URL</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">IP / Kategori</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Referer</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Hits</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">First Seen</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Last Seen</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pageErrors as $error)
            <tr>
                <td class="px-6 py-4 text-sm font-mono text-gray-800 max-w-xs break-all">
                    <a href="{{ $error->url }}" target="_blank" rel="noopener" class="hover:text-indigo-600">{{ $error->url }}</a>
                </td>
                <td class="px-6 py-4 text-sm"><span class="rounded-full bg-red-100 px-2 py-1 text-xs font-bold text-red-700">{{ $error->status_code }}</span></td>
                <td class="px-6 py-4 text-sm">@if($error->last_ip_address)<button type="button" onclick="showIpDetails(@js($error->last_ip_address))" class="font-mono text-indigo-600 hover:underline">{{ $error->last_ip_address }}</button>@else<span>-</span>@endif<div class="text-xs text-gray-500">{{ str_replace('_', ' ', ucfirst($error->category)) }}</div></td>
                <td class="px-6 py-4 text-sm text-gray-500 max-w-[200px] truncate">{{ $error->referer ?: '-' }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $error->count > 10 ? 'bg-red-100 text-red-800' : ($error->count > 3 ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ $error->count }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $error->first_seen->format('d M Y, H:i') }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $error->last_seen->diffForHumans() }}</td>
                <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('modal-{{ $error->id }}').classList.remove('hidden');" class="text-sm text-emerald-600 hover:text-emerald-900 font-semibold">Redirect</a>
                    <span class="text-gray-300">|</span>
                    <form action="{{ route('admin.errors.destroy', $error) }}" method="POST" class="inline" onsubmit="return confirm('Clear this entry?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-900 font-semibold">Ignore</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    No 404 errors tracked yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $pageErrors->links() }}
</div>

<!-- Inline modals for "Redirect" action -->
@foreach($pageErrors as $error)
<div id="modal-{{ $error->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4 p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-1">Create Redirect</h3>
        <p class="text-sm text-slate-500 mb-4">From: <span class="font-mono text-slate-700">{{ $error->url }}</span></p>
        <form action="{{ route('admin.errors.create_redirect', $error) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">New URL <span class="text-red-500">*</span></label>
                <input type="text" name="new_url" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:ring-2 focus:ring-indigo-500" placeholder="https://example.com/new-page">
            </div>
            <div class="flex items-center gap-3">
                <label class="text-sm font-bold text-gray-700">Type</label>
                <select name="status_code" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="301">301 — Permanent</option>
                    <option value="302">302 — Temporary</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-500 transition">Create Redirect</button>
                <button type="button" onclick="document.getElementById('modal-{{ $error->id }}').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endforeach

<div id="ip-detail-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 p-4">
    <div class="max-h-[85vh] w-full max-w-3xl overflow-y-auto rounded-2xl bg-white p-6 shadow-xl">
        <div class="mb-4 flex items-center justify-between"><h3 class="text-lg font-bold text-slate-900">Detail IP</h3><button type="button" onclick="closeIpDetails()" class="text-xl text-slate-500">&times;</button></div>
        <div id="ip-detail-content" class="text-sm text-slate-600">Memuat...</div>
    </div>
</div>
<script>
const ipDetailsUrl = @json(url('/admin/errors/ip'));
function showIpDetails(ip) {
    const modal = document.getElementById('ip-detail-modal');
    const content = document.getElementById('ip-detail-content');
    modal.classList.remove('hidden'); modal.classList.add('flex'); content.textContent = 'Memuat...';
    fetch(ipDetailsUrl + '/' + encodeURIComponent(ip), {headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}})
        .then(response => response.ok ? response.json() : Promise.reject())
        .then(data => {
            const location = data.location || {};
            const locationText = [location.city, location.region, location.country].filter(Boolean).join(', ') || 'Tidak tersedia';
            const rows = (data.paths || []).map(row => `<tr><td class="border-b px-2 py-2 font-mono">${escapeHtml(row.path)}</td><td class="border-b px-2 py-2">${row.status_code}</td><td class="border-b px-2 py-2">${row.hits}</td></tr>`).join('');
            content.innerHTML = `<div class="mb-4 grid gap-3 md:grid-cols-3"><div><b>IP</b><br>${escapeHtml(data.ip)}</div><div><b>Lokasi</b><br>${escapeHtml(locationText)}</div><div><b>Total 4xx</b><br>${data.total_hits}</div></div><h4 class="mb-2 font-bold">Path yang diakses</h4><table class="w-full text-left text-xs"><thead><tr><th class="px-2 py-2">Path</th><th class="px-2 py-2">Status</th><th class="px-2 py-2">Hit</th></tr></thead><tbody>${rows || '<tr><td colspan="3" class="px-2 py-3">Tidak ada data.</td></tr>'}</tbody></table>`;
        }).catch(() => content.textContent = 'Lokasi/detail tidak tersedia.');
}
function closeIpDetails() { const modal = document.getElementById('ip-detail-modal'); modal.classList.add('hidden'); modal.classList.remove('flex'); }
function escapeHtml(value) { return String(value ?? '').replace(/[&<>'"]/g, character => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[character])); }
</script>
@endsection
