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
                <h3 class="text-lg font-bold font-outfit text-slate-900">Tindakan</h3>
            </div>
            <div class="p-6 space-y-3">
                <form action="{{ route('admin.inquiries.whatsapp', $inquiry) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#25D366] text-white text-sm font-bold rounded-lg hover:bg-[#1FBD5A] transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Kirim ke WhatsApp (Fonnte)
                    </button>
                </form>
                <form action="{{ route('admin.inquiries.toggle_spam', $inquiry) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 {{ $inquiry->is_spam ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-amber-500 hover:bg-amber-600' }} text-white text-sm font-bold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ $inquiry->is_spam ? 'Keluarkan dari Spam' : 'Tandai sebagai Spam' }}
                    </button>
                </form>
                <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Hapus inquiry ini? Tindakan tidak bisa dibatalkan.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus Inquiry
                    </button>
                </form>
            </div>
        </div>

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
                @if($inquiry->source)
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Sumber</p>
                    <p class="text-sm text-slate-900 font-medium mt-1">{{ $inquiry->sourceLabel() }}</p>
                </div>
                @endif
                @if($inquiry->is_spam)
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Status Spam</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mt-1">Spam (deteksi otomatis)</span>
                </div>
                @endif
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
