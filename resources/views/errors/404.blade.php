@extends('layouts.frontend')

@section('title', '404 - Halaman Tidak Ditemukan | SEOFAST')
@section('meta_description', 'Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan.')

@section('content')
<section class="py-24 bg-slate-50 min-h-[70vh] flex items-center">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-8">
            <h1 class="text-9xl font-extrabold text-slate-200 tracking-tighter mb-4 font-outfit">404</h1>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4 font-outfit">Oops! Halaman Tidak Ditemukan</h2>
            <p class="text-lg text-slate-600 mb-8">
                Halaman yang Anda cari mungkin telah dihapus, namanya diubah, atau tidak tersedia sementara.
            </p>
        </div>

        <!-- Search Bar untuk fallback -->
        <div class="max-w-xl mx-auto mb-12">
            <form action="{{ url('/') }}" method="GET" class="relative">
                <input type="text" name="q" placeholder="Cari konten lainnya..." class="w-full px-6 py-4 rounded-2xl border border-slate-200 shadow-sm focus:ring-2 focus:ring-brand-indigo focus:border-brand-indigo text-slate-900 outline-none transition-all">
                <button type="submit" class="absolute right-3 top-3 p-2 bg-brand-indigo text-white rounded-xl hover:bg-brand-purple transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="{{ url('/') }}" class="px-8 py-3 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition-colors shadow-md">
                Kembali ke Beranda
            </a>
            <a href="{{ route('blog.index') }}" class="px-8 py-3 bg-white text-slate-700 font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors shadow-sm">
                Lihat Artikel Terbaru
            </a>
        </div>
    </div>
</section>
@endsection
