@extends('buyer.layouts.app')

@section('header', 'Akses Produk: ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('buyer.products.index') }}" class="text-sm text-slate-500 hover:text-slate-900 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Produk Saya
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div class="p-8 border-b border-slate-200 bg-slate-50/50 flex flex-col md:flex-row gap-8 items-center">
            <div class="w-32 h-32 rounded-xl bg-white shadow-sm border border-slate-200 flex-shrink-0">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="w-full h-full object-cover rounded-xl" alt="" loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-extrabold font-outfit text-slate-900 tracking-tight">{{ $product->name }}</h1>
                <p class="text-slate-500 mt-2 max-w-2xl">{{ $product->description }}</p>
                <div class="mt-4 inline-flex items-center gap-4 text-xs font-medium bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                    <span class="text-slate-500">Diakses: {{ $access->access_count }}x</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-green-600">Aktif Sejak: {{ $access->granted_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <h3 class="text-lg font-bold font-outfit text-slate-900 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-brand-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                File & Link Download
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if($product->download_url)
                <a href="{{ $product->download_url }}" target="_blank" class="flex items-center p-4 rounded-xl border border-slate-200 hover:border-brand-indigo hover:shadow-md transition-all group bg-white">
                    <div class="w-12 h-12 bg-indigo-50 text-brand-indigo rounded-lg flex items-center justify-center group-hover:bg-brand-indigo group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-bold text-slate-900 group-hover:text-brand-indigo">Akses Link Materi</p>
                        <p class="text-xs text-slate-500 mt-1">Buka di tab baru</p>
                    </div>
                </a>
                @endif

                @if($product->download_file)
                <a href="{{ asset('storage/' . $product->download_file) }}" download class="flex items-center p-4 rounded-xl border border-slate-200 hover:border-brand-indigo hover:shadow-md transition-all group bg-white">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="font-bold text-slate-900 group-hover:text-green-600">Download File Pendukung</p>
                        <p class="text-xs text-slate-500 mt-1">Simpan ke perangkat Anda</p>
                    </div>
                </a>
                @endif
                
                @if(!$product->download_url && !$product->download_file)
                <div class="col-span-2 text-center p-8 border border-dashed border-slate-200 rounded-xl bg-slate-50">
                    <p class="text-slate-500">Materi belum diunggah oleh Admin. Silakan hubungi support.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
