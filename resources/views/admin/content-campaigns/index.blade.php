@extends('layouts.admin')

@section('title', 'Content Campaigns - ' . config('app.name'))
@section('page_title', 'Content Campaigns')

@section('admin_content')
<div class="space-y-6" x-data="{ showCreate: false }">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ $errors->first() }}</div>
    @endif

    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Kelola topic bank, funnel TOFU/MOFU/BOFU, dan jadwal artikel per produk.</p>
        </div>
        <button type="button" @click="showCreate = true" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Buat Campaign</button>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr><th class="px-5 py-3">Campaign</th><th class="px-5 py-3">Produk / Silo</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Topics / Artikel</th><th class="px-5 py-3">Aksi</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($campaigns as $campaign)
                <tr>
                    <td class="px-5 py-4"><div class="font-semibold text-slate-900">{{ $campaign->name }}</div><div class="text-xs text-slate-500">{{ strtoupper($campaign->funnel_mode) }} · {{ $campaign->posts_per_day }}/hari</div></td>
                    <td class="px-5 py-4"><div>{{ $campaign->product?->name ?? '—' }}</div><div class="text-xs text-slate-500">{{ $campaign->siloBlueprint?->silo_name ?? '—' }}</div><div class="mt-1 text-xs text-slate-500">{{ $campaign->targets->count() }} landing target</div></td>
                    <td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold">{{ ucfirst($campaign->status) }}</span></td>
                    <td class="px-5 py-4">{{ $campaign->topics_count }} / {{ $campaign->contents_count }}</td>
                    <td class="px-5 py-4"><div class="flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('admin.content-campaigns.research', $campaign) }}">@csrf <input type="hidden" name="count" value="{{ $campaign->research_batch_size }}"><button class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold">Cari topik</button></form>
                        <form method="POST" action="{{ route('admin.content-campaigns.toggle', $campaign) }}">@csrf <button class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white">{{ $campaign->status === 'active' ? 'Jeda' : 'Aktifkan' }}</button></form>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">Belum ada campaign.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $campaigns->links() }}

    <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 p-4" @keydown.escape.window="showCreate = false">
        <div class="mx-auto max-w-3xl rounded-2xl bg-white p-6 shadow-xl" @click.outside="showCreate = false">
            <div class="mb-5 flex items-center justify-between"><h2 class="text-lg font-bold text-slate-900">Campaign baru</h2><button type="button" @click="showCreate = false" class="text-slate-500">✕</button></div>
            <form method="POST" action="{{ route('admin.content-campaigns.store') }}" class="grid gap-4 md:grid-cols-2">@csrf
                <label class="text-sm font-medium">Nama campaign<input name="name" required maxlength="120" class="mt-1 w-full rounded-lg border-slate-300"></label>
                <label class="text-sm font-medium">Produk<select name="product_id" required class="mt-1 w-full rounded-lg border-slate-300"><option value="">Pilih produk</option>@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach</select></label>
                <label class="text-sm font-medium">Silo<select name="silo_blueprint_id" required class="mt-1 w-full rounded-lg border-slate-300"><option value="">Pilih silo</option>@foreach($silos as $silo)<option value="{{ $silo->id }}">{{ $silo->silo_name }}</option>@endforeach</select></label>
                <label class="text-sm font-medium">Bahasa<select name="language" class="mt-1 w-full rounded-lg border-slate-300"><option value="id">Indonesia</option><option value="en">English</option></select></label>
                <label class="text-sm font-medium">Mode funnel<select name="funnel_mode" class="mt-1 w-full rounded-lg border-slate-300"><option value="balanced">Balanced</option><option value="tofu">TOFU</option><option value="mofu">MOFU</option><option value="bofu">BOFU</option></select></label>
                <label class="text-sm font-medium">Artikel per hari<input type="number" name="posts_per_day" value="1" min="1" max="24" required class="mt-1 w-full rounded-lg border-slate-300"></label>
                <label class="text-sm font-medium md:col-span-2">Keyword target landing page <span class="font-normal text-slate-500">(satu per baris atau koma)</span><textarea name="target_keywords" required maxlength="5000" rows="3" placeholder="software akuntansi&#10;aplikasi laporan keuangan" class="mt-1 w-full rounded-lg border-slate-300"></textarea></label>
                <label class="text-sm font-medium md:col-span-2">Entity seed / topik utama<input name="entity_seed" maxlength="500" class="mt-1 w-full rounded-lg border-slate-300"></label>
                <label class="text-sm font-medium md:col-span-2">Data bisnis untuk AI (fitur, audiens, batasan)<textarea name="business_data" maxlength="10000" rows="3" class="mt-1 w-full rounded-lg border-slate-300"></textarea></label>
                <label class="text-sm font-medium">Tipe jadwal<select name="schedule_type" class="mt-1 w-full rounded-lg border-slate-300"><option value="random">Acak natural</option><option value="fixed">Jam tetap</option></select></label>
                <label class="text-sm font-medium">Jam tetap (opsional)<input name="fixed_hours" placeholder="09:00,14:00" class="mt-1 w-full rounded-lg border-slate-300"></label>
                <input type="hidden" name="research_batch_size" value="5"><input type="hidden" name="publish_status" value="draft"><input type="hidden" name="timezone" value="Asia/Jakarta"><input type="hidden" name="auto_research" value="1">
                <div class="md:col-span-2 flex justify-end gap-3"><button type="button" @click="showCreate = false" class="rounded-lg border px-4 py-2 text-sm">Batal</button><button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Simpan campaign</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
