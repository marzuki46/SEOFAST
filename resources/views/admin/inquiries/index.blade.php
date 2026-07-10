@extends('layouts.admin')

@section('header', 'Pesan Masuk')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">Contact Form Inquiries</h1>
        <p class="text-sm text-slate-500 mt-1">Pesan yang dikirim pengunjung melalui form kontak.</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-8">
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

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
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
                <tr class="hover:bg-slate-50 transition-colors {{ $inquiry->status === 'unread' ? 'bg-red-50/50' : '' }}">
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-900">{{ $inquiry->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-slate-400">{{ $inquiry->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $inquiry->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-600">{{ $inquiry->email }}</div>
                        @if($inquiry->phone)
                            <div class="text-xs text-slate-400">{{ $inquiry->phone }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-slate-900 max-w-[200px] truncate">{{ $inquiry->subject }}</div>
                    </td>
                    <td class="px-6 py-4">
                        {!! $inquiry->statusBadge() !!}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 rounded-md text-xs font-medium text-slate-700 hover:bg-slate-50">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Belum ada pesan masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inquiries->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $inquiries->appends(['status' => $status])->links() }}
    </div>
    @endif
</div>
@endsection
