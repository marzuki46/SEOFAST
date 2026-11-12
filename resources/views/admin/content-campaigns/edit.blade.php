@extends('layouts.admin')

@section('title', 'Edit Campaign - '.$campaign->name)
@section('page_title', 'Edit Content Campaign')

@section('admin_content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.content-campaigns.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">← Kembali ke daftar campaign</a>
            <h1 class="mt-2 text-2xl font-bold text-slate-900">Edit Campaign</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui target dan jadwal tanpa mengubah artikel yang sudah terbit.</p>
        </div>
        <a href="{{ route('admin.content-campaigns.settings', $campaign) }}" class="rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700 hover:bg-indigo-100">Edit Prompt & AI</a>
    </div>

    @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ $errors->first() }}</div>@endif

    <form method="POST" action="{{ route('admin.content-campaigns.update', $campaign) }}" class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        @csrf
        @method('PUT')

        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-800">Nama Campaign <span class="text-red-500">*</span></label>
                <input name="name" required maxlength="120" value="{{ old('name', $campaign->name) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-800">Target Keyword</label>
                <textarea name="target_keywords" rows="5" maxlength="5000" placeholder="Satu keyword per baris atau pisahkan dengan koma" class="mt-1 w-full rounded-xl border border-slate-200 p-3.5 font-mono text-xs outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">{{ old('target_keywords', $campaign->targets->pluck('keyword')->implode("\n")) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Keyword lama akan disinkronkan dengan daftar ini. Topik dan artikel yang sudah ada tetap dipertahankan.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800">Status</label>
                <select name="status" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-indigo-500">
                    @foreach(['draft' => 'Draft', 'active' => 'Aktif', 'paused' => 'Dijeda'] as $value => $label)<option value="{{ $value }}" @selected(old('status', $campaign->status) === $value)>{{ $label }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800">URL Tujuan</label>
                <input type="url" name="destination_url" maxlength="2048" value="{{ old('destination_url', $campaign->destination_url) }}" placeholder="https://juki.eu.org/jasa/website" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800">Produk Terkait</label>
                <select name="product_id" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-indigo-500">
                    <option value="">Tanpa Produk / Bisnis Jasa</option>
                    @foreach($products as $product)<option value="{{ $product->id }}" @selected((string) old('product_id', $campaign->product_id) === (string) $product->id)>{{ $product->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800">Silo / Kategori</label>
                <select name="silo_blueprint_id" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-indigo-500">
                    <option value="">Tanpa Silo</option>
                    @foreach($silos as $silo)<option value="{{ $silo->id }}" @selected((string) old('silo_blueprint_id', $campaign->silo_blueprint_id) === (string) $silo->id)>{{ $silo->silo_name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800">Artikel per Hari</label>
                <input type="number" name="posts_per_day" min="1" max="24" required value="{{ old('posts_per_day', $campaign->posts_per_day) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800">Tipe Jadwal</label>
                <select name="schedule_type" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-indigo-500">
                    <option value="random" @selected(old('schedule_type', $campaign->schedule_type) === 'random')>Acak Natural</option>
                    <option value="fixed" @selected(old('schedule_type', $campaign->schedule_type) === 'fixed')>Jam Tetap</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-800">Jam Tetap</label>
                <input name="fixed_hours" value="{{ old('fixed_hours', $campaign->fixed_hours) }}" placeholder="09:00, 13:00, 18:00" class="mt-1 w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm outline-none focus:border-indigo-500">
            </div>
        </div>

        <details class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
            <summary class="cursor-pointer font-semibold text-slate-800">Parameter lanjutan</summary>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <input type="hidden" name="language" value="{{ old('language', $campaign->language ?: 'id') }}">
                <input type="hidden" name="funnel_mode" value="{{ old('funnel_mode', $campaign->funnel_mode ?: 'balanced') }}">
                <input type="hidden" name="publish_status" value="{{ old('publish_status', $campaign->publish_status ?: 'published') }}">
                <input type="hidden" name="timezone" value="{{ old('timezone', $campaign->timezone ?: 'Asia/Jakarta') }}">
                <div><label class="block text-sm font-semibold text-slate-800">Audience</label><input name="audience" value="{{ old('audience', $campaign->audience) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></div>
                <div><label class="block text-sm font-semibold text-slate-800">Brand Voice</label><input name="brand_voice" value="{{ old('brand_voice', $campaign->brand_voice) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></div>
                <div><label class="block text-sm font-semibold text-slate-800">Niche</label><input name="niche" value="{{ old('niche', $campaign->niche) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></div>
                <div><label class="block text-sm font-semibold text-slate-800">Entity Seed</label><input name="entity_seed" value="{{ old('entity_seed', $campaign->entity_seed) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></div>
                <div><label class="block text-sm font-semibold text-slate-800">Minimal Kata</label><input type="number" name="word_count_min" min="300" max="5000" required value="{{ old('word_count_min', $campaign->word_count_min ?: 900) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></div>
                <div><label class="block text-sm font-semibold text-slate-800">Maksimal Kata</label><input type="number" name="word_count_max" min="400" max="8000" required value="{{ old('word_count_max', $campaign->word_count_max ?: 1400) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></div>
                <div><label class="block text-sm font-semibold text-slate-800">Batch Riset</label><input type="number" name="research_batch_size" min="1" max="20" required value="{{ old('research_batch_size', $campaign->research_batch_size ?: 5) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></div>
                <div><label class="block text-sm font-semibold text-slate-800">Label CTA</label><input name="cta_label" value="{{ old('cta_label', $campaign->cta_label) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></div>
                <div><label class="block text-sm font-semibold text-slate-800">URL CTA</label><input type="url" name="cta_url" value="{{ old('cta_url', $campaign->cta_url) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm"></div>
                <div class="md:col-span-2"><label class="block text-sm font-semibold text-slate-800">Data Bisnis</label><textarea name="business_data" rows="4" class="mt-1 w-full rounded-xl border border-slate-200 p-3 text-sm">{{ old('business_data', $campaign->business_data) }}</textarea></div>
            </div>
        </details>

        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-5">
            <a href="{{ route('admin.content-campaigns.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
            <button class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
