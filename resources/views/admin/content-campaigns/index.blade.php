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

    <nav aria-label="Content campaign menu" class="flex flex-wrap gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
        <a href="{{ route('admin.content-campaigns.index') }}" aria-current="{{ request('tab', 'campaigns') === 'campaigns' ? 'page' : 'false' }}" class="rounded-xl px-4 py-2 text-sm font-semibold {{ request('tab', 'campaigns') === 'campaigns' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">Campaign</a>
        <a href="{{ route('admin.content-campaigns.index', ['tab' => 'settings']) }}" aria-current="{{ request('tab') === 'settings' ? 'page' : 'false' }}" class="rounded-xl px-4 py-2 text-sm font-semibold {{ request('tab') === 'settings' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">Prompt & AI Settings</a>
    </nav>

    @if(request('tab') === 'settings')
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-5"><h2 class="text-xl font-bold text-slate-900">Prompt & AI Settings</h2><p class="mt-1 text-sm text-slate-500">Pilih campaign untuk mengatur prompt, data bisnis, gaya bahasa, dan output dalam satu tempat.</p></div>
            <form method="GET" action="{{ route('admin.content-campaigns.index') }}" class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end">
                <input type="hidden" name="tab" value="settings">
                <label class="flex-1 text-sm font-semibold">Campaign aktif<select name="campaign" required class="mt-1 w-full rounded-lg border-slate-300"><option value="">Pilih campaign</option>@foreach($campaignOptions as $campaignOption)<option value="{{ $campaignOption->id }}" @selected($selectedCampaign?->id === $campaignOption->id)>{{ $campaignOption->name }}</option>@endforeach</select></label>
                <button class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white">Buka Pengaturan</button>
            </form>
            @if($selectedCampaign)
                @include('admin.content-campaigns._settings-form', ['campaign' => $selectedCampaign])
            @else
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-12 text-center text-sm text-slate-500">Pilih campaign di atas untuk mulai mengatur prompt AI.</div>
            @endif
        </div>
    @else
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
                        <a href="{{ route('admin.content-campaigns.topics', $campaign) }}" class="rounded-lg border border-indigo-200 px-3 py-1.5 text-xs font-semibold text-indigo-700">Topic Bank ({{ $campaign->topics_count }})</a>
                        <a href="{{ route('admin.content-campaigns.settings', $campaign) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700">Prompt & AI Settings</a>
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
        <div class="mx-auto max-w-4xl rounded-3xl bg-white p-6 shadow-2xl sm:p-8" @click.outside="showCreate = false">
            <div class="mb-6 flex items-start justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-wider text-indigo-600">Auto Post Content</p><h2 class="mt-1 text-2xl font-bold text-slate-900">Campaign baru</h2><p class="mt-1 text-sm text-slate-500">Mulai dari keyword dan jadwal. Data AI dapat diatur setelah campaign dibuat.</p></div><button type="button" @click="showCreate = false" aria-label="Tutup form campaign baru" class="rounded-lg p-2 text-xl leading-none text-slate-400 hover:bg-slate-100 hover:text-slate-700">&times;</button></div>
            <form method="POST" action="{{ route('admin.content-campaigns.store') }}" class="grid gap-4 md:grid-cols-2">@csrf
                <p class="md:col-span-2 rounded-xl border border-indigo-100 bg-indigo-50 p-4 text-sm leading-6 text-indigo-900">Prompt, data bisnis, entity, gaya bahasa, dan target jumlah kata sudah memiliki default AI. Setelah campaign dibuat, semuanya bisa disesuaikan dari tab <b>Prompt & AI Settings</b>.</p>
                <label class="text-sm font-semibold text-slate-700">Nama campaign<input name="name" required maxlength="120" placeholder="Contoh: Jasa Pembuatan Website Solo" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"></label>
                <label class="text-sm font-semibold text-slate-700">Produk <span class="font-normal text-slate-400">(opsional)</span><select name="product_id" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"><option value="">Campaign jasa / tanpa produk</option>@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach</select><span class="mt-1 block text-xs font-normal text-slate-500">Pilih hanya jika campaign ingin mendorong produk tertentu.</span></label>
                <label class="text-sm font-semibold text-slate-700">Silo <span class="font-normal text-slate-400">(opsional)</span><select name="silo_blueprint_id" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"><option value="">Tanpa silo</option>@foreach($silos as $silo)<option value="{{ $silo->id }}">{{ $silo->silo_name }}</option>@endforeach</select><span class="mt-1 block text-xs font-normal text-slate-500">Gunakan silo bila artikel mengikuti struktur tertentu.</span></label>
                <label class="text-sm font-semibold text-slate-700">Bahasa<select name="language" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 shadow-sm outline-none transition focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"><option value="id">Indonesia</option><option value="en">English</option></select></label>
                <label class="text-sm font-semibold text-slate-700 md:col-span-2">Keyword target landing page <span class="font-normal text-slate-400">(satu per baris atau koma)</span><textarea name="target_keywords" required maxlength="5000" rows="3" placeholder="jasa pembuatan website solo&#10;web developer solo" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"></textarea></label>
                <label class="text-sm font-semibold text-slate-700">Artikel per hari<input type="number" name="posts_per_day" value="1" min="1" max="24" required class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"></label>
                <label class="text-sm font-semibold text-slate-700">Tipe jadwal<select name="schedule_type" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 shadow-sm outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"><option value="random">Acak natural</option><option value="fixed">Jam tetap</option></select></label>
                <label class="text-sm font-semibold text-slate-700">Jam tetap <span class="font-normal text-slate-400">(opsional)</span><input name="fixed_hours" placeholder="09:00,14:00" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-900 shadow-sm outline-none placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100"></label>
                <input type="hidden" name="funnel_mode" value="balanced"><input type="hidden" name="research_batch_size" value="5"><input type="hidden" name="publish_status" value="draft"><input type="hidden" name="timezone" value="Asia/Jakarta"><input type="hidden" name="auto_research" value="1"><input type="hidden" name="word_count_min" value="900"><input type="hidden" name="word_count_max" value="1400">
                <div class="md:col-span-2 mt-2 flex justify-end gap-3 border-t border-slate-100 pt-5"><button type="button" @click="showCreate = false" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button><button class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Simpan campaign</button></div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
