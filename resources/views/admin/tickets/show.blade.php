@extends('layouts.admin')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.tickets.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    Ticket #{{ $ticket->ticket_number }}
</div>
@endsection

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold font-outfit text-slate-900">{{ $ticket->subject }}</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Dibuat {{ $ticket->created_at->format('d M Y H:i') }}
                        @if($ticket->category)
                            &middot; <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ ucfirst($ticket->category) }}</span>
                        @endif
                    </p>
                </div>
                {!! $ticket->statusBadge() !!}
            </div>
            <div class="p-6">
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $ticket->message }}</div>
            </div>
        </div>

        <div class="space-y-4">
            <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Riwayat Percakapan ({{ $ticket->replies->count() }})</h4>

            @foreach($ticket->replies as $reply)
            <div class="flex gap-4 {{ $reply->isFromAdmin() ? '' : 'flex-row-reverse' }}">
                <div class="flex-shrink-0">
                    @if($reply->isFromAdmin())
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-sm font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-brand-indigo text-sm font-bold">
                            {{ substr($ticket->buyer->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="{{ $reply->isFromAdmin() ? 'bg-slate-50 border-slate-200' : 'bg-indigo-50 border-indigo-100' }} rounded-xl px-5 py-4 border max-w-[80%]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold {{ $reply->isFromAdmin() ? 'text-slate-700' : 'text-brand-indigo' }}">
                            {{ $reply->isFromAdmin() ? ($reply->user->name ?? 'Admin') : $ticket->buyer->name }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-sm text-slate-700 whitespace-pre-wrap">{{ $reply->message }}</div>
                </div>
            </div>
            @endforeach
        </div>

        @if(!in_array($ticket->status, ['solved', 'closed']))
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Balas Ticket</h3>
            </div>
            <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" class="p-6">
                @csrf
                <textarea name="message" rows="4" required
                    class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20 transition-colors"
                    placeholder="Tulis balasan...">{{ old('message') }}</textarea>
                @error('message') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-indigo text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Informasi Buyer</h3>
            </div>
            <div class="p-6 text-center">
                <img src="{{ $ticket->buyer->avatar_url }}" class="w-20 h-20 rounded-full mx-auto mb-3" alt="">
                <h4 class="font-bold text-slate-900 text-lg">{{ $ticket->buyer->name }}</h4>
                <p class="text-slate-500 text-sm">{{ $ticket->buyer->email }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Ubah Status</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.tickets.status', $ticket) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                        <select name="status" required
                            class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="wait_response" {{ $ticket->status === 'wait_response' ? 'selected' : '' }}>Menunggu Respon</option>
                            <option value="on_progress" {{ $ticket->status === 'on_progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="solved" {{ $ticket->status === 'solved' ? 'selected' : '' }}>Solved</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan Internal</label>
                        <textarea name="admin_notes" rows="3"
                            class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20"
                            placeholder="Catatan untuk admin (tidak terlihat buyer)">{{ $ticket->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        @if($ticket->solved_at)
        <div class="bg-green-50 rounded-xl shadow-sm border border-green-200 p-6">
            <h3 class="text-sm font-bold text-green-900 uppercase tracking-wider mb-2">Solved</h3>
            <p class="text-sm text-green-700">Ticket ini diselesaikan pada {{ $ticket->solved_at->format('d M Y H:i') }}.</p>
        </div>
        @endif

        @if($ticket->closed_by && $ticket->closedBy)
        <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">Ditutup Oleh</h3>
            <p class="text-sm text-slate-600">{{ $ticket->closedBy->name }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
