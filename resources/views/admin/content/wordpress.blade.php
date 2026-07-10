@extends('layouts.admin')

@section('header', 'WordPress Import / Export')

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- IMPORT --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 flex items-center gap-3">
            <svg class="w-5 h-5 text-brand-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"></path></svg>
            <h3 class="text-lg font-bold font-outfit text-slate-900">Import dari WordPress</h3>
        </div>

        <form action="{{ route('admin.wordpress.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">File WXR XML</label>
                <input type="file" name="wxr_file" required accept=".xml"
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-brand-indigo hover:file:bg-indigo-100 border border-slate-300 rounded-lg p-2">
                <p class="text-xs text-slate-500 mt-1">Export dari WordPress Tools &rarr; Export &rarr; All Content</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Konten yang Diimport</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="post_types[]" value="post" checked class="rounded border-slate-300 text-brand-indigo">
                        <span class="text-sm text-slate-700">Post</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="post_types[]" value="page" class="rounded border-slate-300 text-brand-indigo">
                        <span class="text-sm text-slate-700">Page</span>
                    </label>
                </div>
            </div>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="skip_existing" value="1" checked class="rounded border-slate-300 text-brand-indigo">
                <span class="text-sm text-slate-700">Lewati konten yang slug-nya sudah ada</span>
            </label>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="import_media_library" value="1" class="rounded border-slate-300 text-brand-indigo">
                <span class="text-sm text-slate-700">Import seluruh Media Library (semua gambar dari WordPress)</span>
            </label>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
                <strong>Proses import:</strong>
                <ul class="list-disc list-inside mt-1 space-y-0.5 text-amber-700">
                    <li>Kategori WordPress akan dijadikan SiloBlueprint</li>
                    <li>Gambar dalam konten akan didownload & dikompres ke WebP</li>
                    <li>Featured image akan diimport sebagai gambar unggulan</li>
                    <li>SEO meta (Yoast) akan dipertahankan</li>
                    <li>Ceklis "Import seluruh Media Library" untuk mendownload semua gambar dari WP (tidak hanya yang dipakai di konten)</li>
                </ul>
            </div>

            <button type="submit" class="w-full py-2.5 bg-brand-indigo text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                Import WordPress
            </button>
        </form>
    </div>

    {{-- EXPORT --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 11l5-5 5 5M12 4v11"></path></svg>
            <h3 class="text-lg font-bold font-outfit text-slate-900">Export ke WordPress</h3>
        </div>

        <form action="{{ route('admin.wordpress.export') }}" method="GET" class="p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori (opsional)</label>
                <select name="silo_id" class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20">
                    <option value="">Semua Kategori</option>
                    @foreach($blueprints as $bp)
                        <option value="{{ $bp->id }}">{{ $bp->silo_name }} ({{ $bp->contents_count ?? 0 }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 mt-1">Kosongkan untuk mengexport semua konten published.</p>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 text-sm text-slate-600">
                <strong>Yang akan diexport:</strong>
                <ul class="list-disc list-inside mt-1 space-y-0.5 text-slate-500">
                    <li>Konten dengan status <strong>published</strong></li>
                    <li>Kategori sebagai WordPress category</li>
                    <li>SEO meta (Yoast-compatible)</li>
                    <li>URL featured image (referensi, file tidak disertakan)</li>
                </ul>
            </div>

            <button type="submit" class="w-full py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                Download WXR XML
            </button>
        </form>
    </div>

</div>

{{-- CATATAN --}}
<div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <h3 class="text-base font-bold text-slate-900 mb-2">Catatan Penting</h3>
    <ul class="space-y-1 text-sm text-slate-600 list-disc list-inside">
        <li>Import hanya mendukung format <strong>WXR XML</strong> (WordPress eXtended RSS) versi 1.2</li>
        <li>Gambar akan dikompres ke WebP dan disimpan di storage lokal</li>
        <li>Kategori yang sudah ada dengan nama sama tidak akan diduplikasi</li>
        <li>Opsi "Import seluruh Media Library" akan mendownload SEMUA gambar dari WordPress (tidak hanya yang dipakai di konten), berguna untuk membackup media library</li>
        <li>Export hanya mencakup konten <strong>published</strong> — draft tidak diexport</li>
        <li>File gambar tidak disertakan dalam export, hanya URL referensi</li>
    </ul>
</div>
@endsection
