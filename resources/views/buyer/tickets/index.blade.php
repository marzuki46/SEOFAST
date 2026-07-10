@extends('buyer.layouts.app')

@section('header', 'Customer Service')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-slate-500">Kelola pertanyaan dan kendala Anda di sini.</p>
    <a href="{{ route('buyer.tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-indigo text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
        Buat Ticket Baru
    </a>
</div>

@if($tickets->count())
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="divide-y divide-slate-100">
            @foreach($tickets as $ticket)
            <a href="{{ route('buyer.tickets.show', $ticket) }}" class="block p-6 hover:bg-slate-50 transition-colors">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="text-xs font-mono text-slate-400">#{{ $ticket->ticket_number }}</span>
                            <span class="text-xs text-slate-400">&middot;</span>
                            <span class="text-xs text-slate-500">{{ $ticket->created_at->diffForHumans() }}</span>
                            @if($ticket->category)
                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $ticket->category }}</span>
                            @endif
                        </div>
                        <h4 class="text-base font-bold text-slate-900 truncate">{{ $ticket->subject }}</h4>
                        <p class="text-sm text-slate-500 mt-1 line-clamp-1">{{ $ticket->message }}</p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="text-right">
                            {!! $ticket->statusBadge() !!}
                            <p class="text-xs text-slate-400 mt-1">{{ $ticket->replies_count }} balasan</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
        <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-brand-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </div>
        <h3 class="text-lg font-bold font-outfit text-slate-900">Belum Ada Ticket</h3>
        <p class="mt-2 text-sm text-slate-500 max-w-md mx-auto">Tidak ada ticket support yang ditemukan. Jika ada kendala, silakan buat ticket baru dan tim kami akan merespon secepatnya.</p>
        <a href="{{ route('buyer.tickets.create') }}" class="inline-flex items-center mt-6 px-6 py-2.5 bg-brand-indigo text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
            Buat Ticket Baru
        </a>
    </div>
@endif
@endsection
