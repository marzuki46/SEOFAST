@extends('layouts.admin')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.inquiries.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    Pesan dari {{ $inquiry->name }}
</div>
@endsection

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold font-outfit text-slate-900">{{ $inquiry->subject }}</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Diterima {{ $inquiry->created_at->format('d M Y H:i') }}
                    </p>
                </div>
                {!! $inquiry->statusBadge() !!}
            </div>
            <div class="p-6">
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $inquiry->message }}</div>
            </div>
        </div>

        @if($inquiry->status !== 'replied')
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Tandai Sudah Dibalas</h3>
            </div>
            <form action="{{ route('admin.inquiries.replied', $inquiry) }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan (opsional)</label>
                    <textarea name="admin_note" rows="3"
                        class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20 transition-colors"
                        placeholder="Catatan tentang bagaimana pesan ini ditindaklanjuti...">{{ old('admin_note') }}</textarea>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    Tandai Sudah Dibalas
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Informasi Pengirim</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Nama</p>
                    <p class="text-sm text-slate-900 font-medium mt-1">{{ $inquiry->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Email</p>
                    <a href="mailto:{{ $inquiry->email }}" class="text-sm text-brand-indigo font-medium mt-1 block hover:underline">{{ $inquiry->email }}</a>
                </div>
                @if($inquiry->phone)
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">WhatsApp</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inquiry->phone) }}" target="_blank" class="text-sm text-green-600 font-medium mt-1 block hover:underline">{{ $inquiry->phone }}</a>
                </div>
                @endif
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Subjek</p>
                    <p class="text-sm text-slate-900 font-medium mt-1">{{ $inquiry->subject }}</p>
                </div>
            </div>
        </div>

        @if($inquiry->status === 'replied' && $inquiry->repliedBy)
        <div class="bg-green-50 rounded-xl shadow-sm border border-green-200 p-6">
            <h3 class="text-sm font-bold text-green-900 uppercase tracking-wider mb-2">Sudah Dibalas</h3>
            <p class="text-sm text-green-700">
                Oleh <strong>{{ $inquiry->repliedBy->name }}</strong> pada {{ $inquiry->replied_at->format('d M Y H:i') }}
            </p>
            @if($inquiry->admin_note)
                <div class="mt-3 p-3 bg-white/50 rounded border border-green-100 text-sm text-green-800">
                    <strong>Catatan:</strong> {{ $inquiry->admin_note }}
                </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
