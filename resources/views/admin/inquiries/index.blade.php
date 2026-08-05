@extends('layouts.admin')

@section('header', 'Pesan Masuk')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">Contact Form Inquiries</h1>
        <p class="text-sm text-slate-500 mt-1">Pesan yang dikirim pengunjung melalui form kontak, live chat, dan paket enterprise.</p>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('admin.inquiries.index') }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'all' && !$spam ? 'border-indigo-400 ring-1 ring-indigo-400' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow text-center">
        <p class="text-sm font-medium text-slate-500">Semua</p>
        <p class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ array_sum($stats) }}</p>
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'unread']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'unread' ? 'border-red-400 ring-1 ring-red-400' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow text-center">
        <p class="text-sm font-medium text-slate-500">Belum Dibaca</p>
        <p class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ $stats['unread'] }}</p>
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'read']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'read' ? 'border-blue-400 ring-1 ring-blue-400' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow text-center">
        <p class="text-sm font-medium text-slate-500">Sudah Dibaca</p>
        <p class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ $stats['read'] }}</p>
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'replied']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'replied' ? 'border-green-400 ring-1 ring-green-400' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow text-center">
        <p class="text-sm font-medium text-slate-500">Sudah Dibalas</p>
        <p class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ $stats['replied'] }}</p>
    </a>
</div>

<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.inquiries.index', ['spam' => 1]) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold border {{ $spam ? 'bg-amber-50 border-amber-300 text-amber-800 ring-1 ring-amber-300' : 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50' }}">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Spam ({{ $stats['spam'] }})
        </a>
        @if($spam)
        <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Keluar dari mode spam
        </a>
        @endif
    </div>
    <form method="GET" action="{{ route('admin.inquiries.index') }}" class="flex items-center gap-2">
        @if($status !== 'all')<input type="hidden" name="status" value="{{ $status }}">@endif
        @if($spam)<input type="hidden" name="spam" value="1">@endif
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/email/subjek/pesan..." class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-indigo w-72 max-w-full">
        <button type="submit" class="px-3 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg text-sm font-semibold hover:bg-slate-50">Cari</button>
    </form>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm font-semibold">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-semibold">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <form method="POST" action="{{ route('admin.inquiries.bulk') }}" id="bulkForm">
        @csrf
        <input type="hidden" name="action" value="" id="bulkAction">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-300 text-brand-indigo focus:ring-brand-indigo" aria-label="Pilih semua">
                        </th>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Nama</th>
                        <th class="px-6 py-4 font-semibold">Kontak</th>
                        <th class="px-6 py-4 font-semibold">Subjek</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inquiries as $inquiry)
                    <tr class="hover:bg-slate-50 transition-colors {{ $inquiry->status === 'unread' && !$inquiry->is_spam ? 'bg-red-50/50' : '' }} {{ $inquiry->is_spam ? 'bg-amber-50/40' : '' }}">
                        <td class="px-4 py-4">
                            <input type="checkbox" name="ids[]" value="{{ $inquiry->id }}" class="row-check w-4 h-4 rounded border-slate-300 text-brand-indigo focus:ring-brand-indigo" aria-label="Pilih {{ $inquiry->name }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-900">{{ $inquiry->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-slate-400">{{ $inquiry->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">{{ $inquiry->name }}</div>
                            @if($inquiry->source)
                                <div class="text-xs text-slate-400">{{ $inquiry->sourceLabel() }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-600">{{ $inquiry->email ?: '-' }}</div>
                            @if($inquiry->phone)
                                <div class="text-xs text-slate-400">{{ $inquiry->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-900 max-w-[200px] truncate">{{ $inquiry->subject }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($inquiry->is_spam)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Spam</span>
                            @endif
                            {!! $inquiry->statusBadge() !!}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 rounded-md text-xs font-medium text-slate-700 hover:bg-slate-50">
                                    Detail
                                </a>
                                <form action="{{ route('admin.inquiries.whatsapp', $inquiry) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-[#25D366]/10 border border-[#25D366]/40 rounded-md text-xs font-medium text-[#128C7E] hover:bg-[#25D366]/20" title="Kirim ke WhatsApp via Fonnte">
                                        WA
                                    </button>
                                </form>
                                <form action="{{ route('admin.inquiries.toggle_spam', $inquiry) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 rounded-md text-xs font-medium text-amber-700 hover:bg-amber-50" title="{{ $inquiry->is_spam ? 'Keluarkan dari spam' : 'Tandai sebagai spam' }}">
                                        {{ $inquiry->is_spam ? 'Unspam' : 'Spam' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" class="inline" onsubmit="return confirm('Hapus inquiry ini? Tindakan tidak bisa dibatalkan.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-red-200 rounded-md text-xs font-medium text-red-600 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            @if($spam)
                                Tidak ada inquiry bertanda spam.
                            @else
                                Belum ada pesan masuk.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inquiries->count() > 0)
        <div class="px-6 py-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-500" id="selectedCount">0 dipilih</span>
                <div class="flex items-center gap-2">
                    <button type="submit" data-action="whatsapp" class="bulk-btn inline-flex items-center px-4 py-2 bg-[#25D366] text-white rounded-lg text-sm font-semibold hover:bg-[#1FBD5A] disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                        Kirim ke WhatsApp (Fonnte)
                    </button>
                    <button type="submit" data-action="spam" class="bulk-btn inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-semibold hover:bg-amber-600 disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                        Tandai Spam
                    </button>
                    <button type="submit" data-action="unspam" class="bulk-btn inline-flex items-center px-4 py-2 bg-slate-500 text-white rounded-lg text-sm font-semibold hover:bg-slate-600 disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                        Unspam
                    </button>
                    <button type="submit" data-action="delete" data-confirm="1" class="bulk-btn inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                        Hapus Terpilih
                    </button>
                </div>
            </div>
        </div>
        @endif
    </form>

    @if($inquiries->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $inquiries->appends(['status' => $status, 'spam' => $spam ? '1' : null, 'q' => request('q')])->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var selectAll = document.getElementById('selectAll');
        var rows = document.querySelectorAll('.row-check');
        var bulkBtns = document.querySelectorAll('.bulk-btn');
        var countLabel = document.getElementById('selectedCount');
        var form = document.getElementById('bulkForm');

        function update() {
            var checked = Array.prototype.filter.call(rows, function (r) { return r.checked; }).length;
            if (selectAll) {
                selectAll.checked = rows.length > 0 && checked === rows.length;
                selectAll.indeterminate = checked > 0 && checked < rows.length;
            }
            if (countLabel) countLabel.textContent = checked + ' dipilih';
            bulkBtns.forEach(function (b) { b.disabled = checked === 0; });
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                rows.forEach(function (r) { r.checked = selectAll.checked; });
                update();
            });
        }

        rows.forEach(function (r) { r.addEventListener('change', update); });

        bulkBtns.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var checked = Array.prototype.filter.call(rows, function (r) { return r.checked; }).length;
                if (checked === 0) return;

                if (btn.dataset.confirm) {
                    if (!confirm('Hapus ' + checked + ' inquiry terpilih? Tindakan tidak bisa dibatalkan.')) return;
                }

                document.getElementById('bulkAction').value = btn.dataset.action;
                form.submit();
            });
        });
    })();
</script>
@endpush
