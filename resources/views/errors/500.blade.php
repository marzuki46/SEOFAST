@extends('layouts.frontend')

@section('title', 'Terjadi Gangguan | ' . config('app.name'))
@section('meta_description', 'Terjadi gangguan sementara. Silakan kembali ke beranda dan coba lagi.')

@section('content')
<section class="min-h-[70vh] bg-slate-50 py-24 flex items-center">
    <div class="mx-auto max-w-2xl px-4 text-center">
        <p class="font-mono text-sm font-bold tracking-widest text-brand-indigo">500</p>
        <h1 class="mt-3 font-outfit text-4xl font-extrabold text-slate-900">Terjadi gangguan sementara</h1>
        <p class="mt-4 text-lg leading-relaxed text-slate-600">Halaman sedang mengalami kendala teknis. Silakan coba lagi beberapa saat atau kembali ke beranda.</p>
        <a href="{{ url('/') }}" class="mt-8 inline-flex rounded-xl bg-slate-900 px-6 py-3 font-semibold text-white hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-indigo focus:ring-offset-2">Kembali ke Beranda</a>
    </div>
</section>
@endsection
