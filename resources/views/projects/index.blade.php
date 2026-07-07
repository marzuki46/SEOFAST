@extends('layouts.frontend')

@section('title', 'Portofolio Proyek — ' . \App\Models\SystemSetting::get('site_name', 'SEOFAST'))
@section('meta_description', 'Jelajahi portofolio proyek SEOFAST: studi kasus nyata tentang implementasi SEO, konten AI, dan otomasi pemasaran digital.')
@section('canonical_url', url()->current())

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8 font-outfit text-slate-900">Projects / Portofolio</h1>
    <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center shadow-sm">
        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <h3 class="text-xl font-bold text-slate-900 mb-2 font-outfit">Halaman Sedang Dalam Pengembangan</h3>
        <p class="text-slate-500">Portofolio proyek akan segera hadir. Kami sedang menyusun case study terbaik kami.</p>
    </div>
</div>
@endsection
