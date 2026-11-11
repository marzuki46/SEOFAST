@extends('layouts.admin')

@section('title', 'Topic Bank - ' . $campaign->name)
@section('page_title', 'Topic Bank')

@section('admin_content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.content-campaigns.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">← Kembali ke Campaign</a>
            <h1 class="mt-2 text-2xl font-bold text-slate-900">Topic Bank: {{ $campaign->name }}</h1>
            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-2.5 py-1 font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    Kategori Silo: {{ $campaign->siloBlueprint?->silo_name ?? 'Umum (Tanpa Silo)' }}
                </span>
                <span>•</span>
                <span>Produk: <strong class="text-slate-700">{{ $campaign->product?->name ?? 'Default' }}</strong></span>
                <span>•</span>
                <span>Target Bahasa: <strong class="text-slate-700 uppercase">{{ $campaign->language }}</strong></span>
                <span>•</span>
                <span>Mode Funnel: <strong class="text-slate-700 uppercase">{{ $campaign->funnel_mode }}</strong></span>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.content-campaigns.research', $campaign) }}" class="flex items-center gap-2">
            @csrf
            <input type="number" name="count" value="{{ $campaign->research_batch_size }}" min="1" max="20" class="w-20 rounded-lg border-slate-300 text-sm">
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition">Riset Topik Baru</button>
        </form>
    </div>

    @if(session('success'))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-5 py-3.5">Judul / Keyword</th>
                    <th class="px-5 py-3.5">Kategori (Silo)</th>
                    <th class="px-5 py-3.5">Target Landing</th>
                    <th class="px-5 py-3.5">Funnel</th>
                    <th class="px-5 py-3.5">Intent</th>
                    <th class="px-5 py-3.5">Entity Keywords</th>
                    <th class="px-5 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($topics as $topic)
                <tr class="align-top hover:bg-slate-50/50 transition">
                    <td class="px-5 py-4">
                        <div class="font-semibold text-slate-900">{{ $topic->title }}</div>
                        <div class="mt-1 font-mono text-xs text-indigo-700">{{ $topic->primary_keyword }}</div>
                        <div class="mt-1 max-w-md text-xs text-slate-500">{{ $topic->angle ?: '—' }}</div>
                        @if($topic->content_id)
                        <div class="mt-2">
                            <a href="{{ route('admin.content.show', $topic->content_id) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                                Buka Artikel Hasil Publikasi
                            </a>
                        </div>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">
                            <svg class="h-3.5 w-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25z" />
                            </svg>
                            {{ $topic->siloBlueprint?->silo_name ?? $campaign->siloBlueprint?->silo_name ?? 'Umum' }}
                        </div>
                    </td>
                    <td class="px-5 py-4 text-xs text-slate-600">
                        {{ $topic->campaignTarget?->keyword ?? '—' }}
                    </td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $topic->funnel_stage === 'tofu' ? 'bg-sky-100 text-sky-700' : ($topic->funnel_stage === 'mofu' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ strtoupper($topic->funnel_stage) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-xs text-slate-700">
                        {{ ucfirst($topic->intent) }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex max-w-xs flex-wrap gap-1">
                            @foreach(($topic->entity_keywords ?? []) as $entity)
                            <span class="rounded bg-slate-100 px-2 py-0.5 text-[11px] text-slate-600">{{ $entity }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-5 py-4 text-xs">
                        @if($topic->status === 'published' || $topic->status === 'completed' || $topic->status === 'used')
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Dipublikasi</span>
                        @elseif($topic->status === 'processing')
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-600/20 animate-pulse">Diproses AI...</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 font-medium text-slate-700">{{ ucfirst($topic->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center text-slate-500">Topic bank masih kosong. Klik “Riset Topik Baru”.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $topics->links() }}
</div>
@endsection
