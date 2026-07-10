@extends('layouts.admin')

@section('header', 'Support Tickets')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">Ticket Support</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola pertanyaan dan laporan dari buyer.</p>
    </div>
</div>

<div class="grid grid-cols-5 gap-4 mb-8">
    <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'open' ? 'border-brand-indigo ring-1 ring-brand-indigo' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow text-center">
        <p class="text-sm font-medium text-slate-500">Open</p>
        <p class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ $stats['open'] }}</p>
    </a>
    <a href="{{ route('admin.tickets.index', ['status' => 'wait_response']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'wait_response' ? 'border-yellow-400 ring-1 ring-yellow-400' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow text-center">
        <p class="text-sm font-medium text-slate-500">Wait Response</p>
        <p class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ $stats['wait_response'] }}</p>
    </a>
    <a href="{{ route('admin.tickets.index', ['status' => 'on_progress']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'on_progress' ? 'border-indigo-400 ring-1 ring-indigo-400' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow text-center">
        <p class="text-sm font-medium text-slate-500">On Progress</p>
        <p class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ $stats['on_progress'] }}</p>
    </a>
    <a href="{{ route('admin.tickets.index', ['status' => 'solved']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'solved' ? 'border-green-400 ring-1 ring-green-400' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow text-center">
        <p class="text-sm font-medium text-slate-500">Solved</p>
        <p class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ $stats['solved'] }}</p>
    </a>
    <a href="{{ route('admin.tickets.index', ['status' => 'closed']) }}" class="bg-white rounded-xl shadow-sm border {{ $status === 'closed' ? 'border-slate-400 ring-1 ring-slate-400' : 'border-slate-200' }} p-4 hover:shadow-md transition-shadow text-center">
        <p class="text-sm font-medium text-slate-500">Closed</p>
        <p class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ $stats['closed'] }}</p>
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 font-semibold">Ticket</th>
                    <th class="px-6 py-4 font-semibold">Buyer</th>
                    <th class="px-6 py-4 font-semibold">Subjek</th>
                    <th class="px-6 py-4 font-semibold">Kategori</th>
                    <th class="px-6 py-4 font-semibold">Prioritas</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-mono text-xs font-bold text-slate-900">#{{ $ticket->ticket_number }}</div>
                        <div class="text-slate-500 text-xs mt-1">{{ $ticket->created_at->format('d M Y H:i') }}</div>
                        <div class="text-slate-400 text-xs">{{ $ticket->replies_count }} balasan</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $ticket->buyer->avatar_url }}" class="w-8 h-8 rounded-full bg-slate-200" alt="">
                            <div>
                                <div class="font-medium text-slate-900">{{ $ticket->buyer->name }}</div>
                                <div class="text-slate-500 text-xs">{{ $ticket->buyer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-slate-900 font-medium max-w-[200px] truncate">{{ $ticket->subject }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $ticket->category ? ucfirst($ticket->category) : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $priorityColors = [
                                'low' => 'bg-slate-100 text-slate-600',
                                'medium' => 'bg-blue-50 text-blue-600',
                                'high' => 'bg-orange-50 text-orange-600',
                                'urgent' => 'bg-red-50 text-red-600',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$ticket->priority] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {!! $ticket->statusBadge() !!}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-300 rounded-md text-xs font-medium text-slate-700 hover:bg-slate-50">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Belum ada ticket dengan status ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $tickets->appends(['status' => $status])->links() }}
    </div>
    @endif
</div>
@endsection
