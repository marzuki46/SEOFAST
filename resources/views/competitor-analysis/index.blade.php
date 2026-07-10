@extends('layouts.admin')

@section('title', 'Competitor Analysis')
@section('page_title', 'Competitor Analysis')

@section('admin_content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-slate-500 text-sm">Masukkan target keyword untuk menganalisis konten competitor di 10 besar Google.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.competitor-analysis.store') }}" method="POST" class="mb-12">
        @csrf
        <div class="flex gap-3">
            <input type="text" name="keyword" placeholder="Masukkan target keyword..." value="{{ old('keyword') }}" required
                class="flex-1 px-5 py-3.5 rounded-xl border border-slate-300 text-sm focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20 outline-none transition-all">
            <button type="submit" class="px-6 py-3.5 bg-brand-indigo text-white font-bold rounded-xl hover:opacity-90 transition-all shadow-md shadow-brand-indigo/20 text-sm whitespace-nowrap">
                Analisis
            </button>
        </div>
        @error('keyword')
        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </form>

    @if($analyses->count() > 0)
    <h2 class="font-outfit font-bold text-xl text-slate-900 mb-4">Riwayat Analisis</h2>
    <div class="space-y-3">
        @foreach($analyses as $a)
        <a href="{{ route('admin.competitor-analysis.show', $a) }}" class="block bg-white border border-slate-200 rounded-xl p-5 hover:border-slate-300 hover:shadow-sm transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <span class="font-semibold text-slate-900">{{ $a->keyword }}</span>
                    <span class="ml-3 text-xs text-slate-400">{{ $a->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                        @if($a->status === 'completed') bg-emerald-100 text-emerald-700
                        @elseif($a->status === 'failed') bg-red-100 text-red-700
                        @elseif($a->status === 'processing') bg-amber-100 text-amber-700
                        @else bg-slate-100 text-slate-500 @endif">
                        {{ ucfirst($a->status) }}
                    </span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-6">{{ $analyses->links() }}</div>
    @else
    <div class="text-center py-16 text-slate-400">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/></svg>
        <p class="text-sm">Belum ada analisis. Coba masukkan keyword di atas.</p>
    </div>
    @endif
</div>
@endsection
