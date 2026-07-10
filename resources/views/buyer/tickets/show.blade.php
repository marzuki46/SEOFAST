@extends('buyer.layouts.app')

@section('header', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('buyer.tickets.index') }}" class="text-sm text-slate-500 hover:text-slate-900 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
        <div class="flex items-center gap-2">
            {!! $ticket->statusBadge() !!}
            @if($ticket->priority === 'urgent')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Urgent</span>
            @elseif($ticket->priority === 'high')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Tinggi</span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-xl font-bold font-outfit text-slate-900">{{ $ticket->subject }}</h3>
                    <div class="flex items-center gap-3 mt-1.5 text-sm text-slate-500">
                        <span>Dibuat {{ $ticket->created_at->format('d M Y H:i') }}</span>
                        @if($ticket->category)
                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $ticket->category }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="prose prose-sm max-w-none text-slate-700">
                {{ nl2br(e($ticket->message)) }}
            </div>
        </div>
    </div>

    @if($replies->count())
    <div class="space-y-4 mb-6">
        <h4 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Riwayat Percakapan ({{ $replies->count() }})</h4>

        @foreach($replies as $reply)
        <div class="flex gap-4 {{ $reply->isFromBuyer() ? '' : 'flex-row-reverse' }}">
            <div class="flex-shrink-0">
                @if($reply->isFromBuyer())
                    <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-brand-indigo text-sm font-bold">
                        {{ substr($ticket->buyer->name, 0, 1) }}
                    </div>
                @else
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-sm font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                @endif
            </div>
            <div class="{{ $reply->isFromBuyer() ? 'bg-indigo-50 border-indigo-100' : 'bg-slate-50 border-slate-200' }} rounded-xl px-5 py-4 border max-w-[80%]">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold {{ $reply->isFromBuyer() ? 'text-brand-indigo' : 'text-slate-700' }}">
                        {{ $reply->isFromBuyer() ? 'Anda' : ($reply->user->name ?? 'Admin') }}
                    </span>
                    <span class="text-xs text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
                <div class="text-sm text-slate-700">
                    {{ nl2br(e($reply->message)) }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if(!in_array($ticket->status, ['solved', 'closed']))
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
            <h3 class="text-lg font-bold font-outfit text-slate-900">Balas</h3>
        </div>
        <form action="{{ route('buyer.tickets.reply', $ticket) }}" method="POST" class="p-6" onsubmit="disableButton(this)">
            @csrf
            <div>
                <textarea name="message" rows="4" required
                    class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20 transition-colors"
                    placeholder="Tulis balasan Anda...">{{ old('message') }}</textarea>
                @error('message') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-brand-indigo text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Kirim Balasan
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="bg-slate-50 rounded-xl border border-slate-200 p-6 text-center">
        <p class="text-sm text-slate-500">Ticket ini sudah {{ $ticket->status === 'solved' ? 'selesai (solved)' : 'ditutup' }}. Jika ada kendala baru, silakan buat ticket baru.</p>
        <a href="{{ route('buyer.tickets.create') }}" class="inline-flex items-center mt-3 px-4 py-2 bg-brand-indigo text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
            Buat Ticket Baru
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function disableButton(form) {
    var btn = form.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';
    }
}
</script>
@endpush
