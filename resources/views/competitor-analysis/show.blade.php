@extends('layouts.admin')

@section('title', 'Hasil Analisis: ' . $analysis->keyword)
@section('page_title', 'Hasil Analisis: ' . $analysis->keyword)

@section('admin_content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-6">
        <a href="{{ route('admin.competitor-analysis.index') }}" class="hover:text-slate-900 transition-colors">Competitor Analysis</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 font-semibold">{{ $analysis->keyword }}</span>
    </nav>

    @if($analysis->status === 'processing')
    <div class="text-center py-20">
        <div class="w-10 h-10 border-4 border-brand-indigo border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
        <p class="text-slate-500">Sedang menganalisis...</p>
        <meta http-equiv="refresh" content="3">
    </div>
    @elseif($analysis->status === 'failed')
    <div class="text-center py-16">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <h2 class="font-outfit font-bold text-xl text-slate-900 mb-2">Analisis Gagal</h2>
        <p class="text-slate-500 text-sm mb-4">{{ $analysis->error_message ?? 'Terjadi kesalahan saat menganalisis.' }}</p>
        <a href="{{ route('admin.competitor-analysis.index') }}" class="text-sm font-semibold text-brand-indigo hover:underline">Coba lagi</a>
    </div>
    @elseif($analysis->status === 'completed' && $analysis->results)
    @php $results = $analysis->results; @endphp

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-outfit font-bold text-2xl md:text-3xl text-slate-900">Analisis: {{ $analysis->keyword }}</h1>
            <p class="text-sm text-slate-400 mt-1">{{ $analysis->created_at->format('d M Y, H:i') }}</p>
        </div>
        <form action="{{ route('admin.competitor-analysis.destroy', $analysis) }}" method="POST" onsubmit="return confirm('Hapus analisis ini?')">
            @csrf @method('DELETE')
            <button class="text-xs text-red-500 hover:text-red-700 font-semibold">Hapus</button>
        </form>
    </div>

    <div class="space-y-8">
        @if(!empty($results['key_findings']))
        <section class="bg-white border border-slate-200 rounded-2xl p-6">
            <h2 class="font-outfit font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                Key Findings
            </h2>
            <ul class="space-y-2">
                @foreach($results['key_findings'] as $finding)
                <li class="flex items-start gap-3 text-sm text-slate-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 mt-1.5 shrink-0"></span>
                    {{ $finding }}
                </li>
                @endforeach
            </ul>
        </section>
        @endif

        @if(!empty($results['common_topics']))
        <section class="bg-white border border-slate-200 rounded-2xl p-6">
            <h2 class="font-outfit font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                Topik Umum (banyak dibahas competitor)
            </h2>
            <div class="space-y-4">
                @foreach($results['common_topics'] as $topic)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-semibold text-slate-900">{{ $topic['topic'] }}</span>
                        <span class="text-xs text-slate-400">{{ $topic['mentioned_by'] }}/10 competitor</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ ($topic['mentioned_by'] ?? 0) * 10 }}%"></div>
                    </div>
                    @if(!empty($topic['description']))
                    <p class="text-xs text-slate-500 mt-1">{{ $topic['description'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if(!empty($results['gap_topics']))
        <section class="bg-white border border-amber-200 rounded-2xl p-6">
            <h2 class="font-outfit font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                Opportunity Gap (jarang dibahas)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($results['gap_topics'] as $topic)
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-100">
                    <h4 class="font-semibold text-sm text-slate-900 mb-1">{{ $topic['topic'] }}</h4>
                    @if(!empty($topic['description']))
                    <p class="text-xs text-slate-500">{{ $topic['description'] }}</p>
                    @endif
                    <span class="inline-block mt-2 text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-200 text-amber-800">Hanya {{ $topic['mentioned_by'] }} competitor</span>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if(!empty($results['content_recommendations']))
        <section class="bg-white border border-slate-200 rounded-2xl p-6">
            <h2 class="font-outfit font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                Rekomendasi Konten
            </h2>
            <div class="space-y-3">
                @foreach($results['content_recommendations'] as $rec)
                <div class="p-4 rounded-xl bg-purple-50 border border-purple-100">
                    <h4 class="font-semibold text-sm text-slate-900">{{ $rec['title'] ?? '' }}</h4>
                    @if(!empty($rec['rationale']))
                    <p class="text-xs text-slate-500 mt-1">{{ $rec['rationale'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        @if(!empty($results['competitor_insights']))
        <section class="bg-white border border-slate-200 rounded-2xl p-6">
            <h2 class="font-outfit font-bold text-lg text-slate-900 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-slate-600"></span>
                Detail Per Competitor
            </h2>
            <div class="space-y-4">
                @foreach($results['competitor_insights'] as $insight)
                <details class="smooth border border-slate-200 rounded-xl overflow-hidden">
                    <summary class="flex items-center justify-between px-5 py-3.5 bg-slate-50 cursor-pointer text-sm font-semibold text-slate-900 hover:bg-slate-100 transition-colors list-none">
                        <span>#{{ $insight['rank'] }} — {{ $insight['title'] ?? 'Unknown' }}</span>
                        <svg class="faq-chevron w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </summary>
                    <div class="px-5 py-4 text-sm text-slate-600 space-y-1.5">
                        @foreach($insight['main_points'] ?? [] as $point)
                        <div class="flex items-start gap-2">
                            <span class="text-emerald-500 mt-0.5">•</span>
                            <span>{{ $point }}</span>
                        </div>
                        @endforeach
                    </div>
                </details>
                @endforeach
            </div>
        </section>
        @endif

        @if(!empty($results['raw_contents']))
        <details class="smooth border border-slate-200 rounded-2xl overflow-hidden">
            <summary class="flex items-center justify-between px-6 py-4 bg-slate-50 cursor-pointer text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors list-none">
                <span>Lihat data mentah ({{ count($results['raw_contents']) }} halaman)</span>
                <svg class="faq-chevron w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </summary>
            <div class="px-6 py-4 space-y-3">
                @foreach($results['raw_contents'] as $rc)
                <div>
                    <a href="{{ $rc['url'] }}" target="_blank" class="text-sm font-semibold text-brand-indigo hover:underline">#{{ $rc['rank'] }} {{ $rc['title'] }}</a>
                    <p class="text-xs text-slate-400 truncate">{{ $rc['url'] }}</p>
                </div>
                @endforeach
            </div>
        </details>
        @endif
    </div>
    @endif
</div>
@endsection
