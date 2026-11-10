@extends('layouts.admin')

@section('title', 'Topic Bank - ' . $campaign->name)
@section('page_title', 'Topic Bank')

@section('admin_content')
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div><a href="{{ route('admin.content-campaigns.index') }}" class="text-sm font-semibold text-indigo-600">← Kembali ke Campaign</a><h1 class="mt-2 text-2xl font-bold text-slate-900">Topic Bank: {{ $campaign->name }}</h1><p class="mt-1 text-sm text-slate-500">Topik hasil riset AI tersimpan di sini sebelum dipakai scheduler.</p></div>
        <form method="POST" action="{{ route('admin.content-campaigns.research', $campaign) }}" class="flex items-center gap-2">@csrf<input type="number" name="count" value="{{ $campaign->research_batch_size }}" min="1" max="20" class="w-20 rounded-lg border-slate-300 text-sm"><button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Riset Topik Baru</button></form>
    </div>
    @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm"><table class="min-w-full text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3">Judul / Keyword</th><th class="px-5 py-3">Target Landing</th><th class="px-5 py-3">Funnel</th><th class="px-5 py-3">Intent</th><th class="px-5 py-3">Entity Keywords</th><th class="px-5 py-3">Status</th></tr></thead><tbody class="divide-y divide-slate-100">
    @forelse($topics as $topic)<tr class="align-top"><td class="px-5 py-4"><div class="font-semibold text-slate-900">{{ $topic->title }}</div><div class="mt-1 font-mono text-xs text-indigo-700">{{ $topic->primary_keyword }}</div><div class="mt-1 max-w-md text-xs text-slate-500">{{ $topic->angle ?: '—' }}</div></td><td class="px-5 py-4 text-xs text-slate-600">{{ $topic->campaignTarget?->keyword ?? '—' }}</td><td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $topic->funnel_stage === 'tofu' ? 'bg-sky-100 text-sky-700' : ($topic->funnel_stage === 'mofu' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">{{ strtoupper($topic->funnel_stage) }}</span></td><td class="px-5 py-4 text-xs">{{ ucfirst($topic->intent) }}</td><td class="px-5 py-4"><div class="flex max-w-xs flex-wrap gap-1">@foreach(($topic->entity_keywords ?? []) as $entity)<span class="rounded bg-slate-100 px-2 py-1 text-xs text-slate-600">{{ $entity }}</span>@endforeach</div></td><td class="px-5 py-4 text-xs font-semibold">{{ ucfirst($topic->status) }}</td></tr>@empty<tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">Topic bank masih kosong. Klik “Riset Topik Baru”.</td></tr>@endforelse
    </tbody></table></div>
    {{ $topics->links() }}
</div>
@endsection
