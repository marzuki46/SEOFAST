@extends('layouts.admin')

@section('title', 'Content Pipeline - ' . config('app.name'))
@section('page_title', 'Content Pipeline')

@section('admin_content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Pilih Metode Content Production</h1>
        <p class="mt-1 text-sm text-slate-500">Dua workflow berjalan terpisah. Pilih sesuai kebutuhan tanpa mengubah konten dari metode lainnya.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-2xl border border-indigo-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div><span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">METODE 1</span><h2 class="mt-3 text-xl font-bold text-slate-900">Silo / Blueprint Lama</h2><p class="mt-2 text-sm text-slate-500">Workflow SEOFAST yang sudah ada untuk membuat pillar, cluster, sub-cluster, internal link, dan generate AI manual.</p></div>
                <span class="text-3xl">🧱</span>
            </div>
            <ol class="mb-6 space-y-3 text-sm text-slate-600"><li><b>1.</b> Riset keyword dan buat Silo.</li><li><b>2.</b> Generate pillar/cluster blueprint.</li><li><b>3.</b> Review di Pra Post.</li><li><b>4.</b> Jalankan AI Queue dan internal linking.</li></ol>
            <div class="flex flex-wrap gap-3"><a href="{{ route('admin.silo.index') }}" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white">Mulai Metode Lama</a><a href="{{ route('admin.content.prapost') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Pra Post</a><a href="{{ route('admin.links.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Internal Link</a><a href="{{ route('admin.content.create') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">AI Queue</a><a href="{{ route('admin.content.drafts') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Drafts</a><a href="{{ route('admin.content.calendar') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Calendar</a></div>
        </section>

        <section class="rounded-2xl border border-emerald-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">METODE 2</span><h2 class="mt-3 text-xl font-bold text-slate-900">Auto Post Content</h2><p class="mt-2 text-sm text-slate-500">Workflow seperti plugin Auto Post Content: target landing page lebih dulu, lalu topic bank, entity, funnel, dan jadwal artikel.</p></div>
                <span class="text-3xl">⚡</span>
            </div>
            <ol class="mb-6 space-y-3 text-sm text-slate-600"><li><b>1.</b> Input beberapa keyword target.</li><li><b>2.</b> Buat landing page per keyword.</li><li><b>3.</b> Riset entity dan cegah duplicate keyword.</li><li><b>4.</b> Distribusikan TOFU/MOFU/BOFU.</li><li><b>5.</b> Jadwalkan artikel dan arahkan internal link ke landing page.</li></ol>
            <a href="{{ route('admin.content-campaigns.index') }}" class="inline-flex rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white">Buka Auto Post Content</a>
        </section>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-600"><b class="text-slate-900">Catatan:</b> Konten Metode 1 dan Metode 2 memiliki relasi campaign/topic yang berbeda, sehingga Anda dapat menjalankan keduanya tanpa mencampur jadwal atau target landing page.</div>
</div>
@endsection
