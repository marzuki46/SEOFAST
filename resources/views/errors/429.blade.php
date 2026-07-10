@extends('layouts.frontend')

@section('title', 'Terlalu Banyak Permintaan | ' . config('app.name'))
@section('meta_description', 'Maaf, Anda terlalu sering mengirim permintaan. Silakan coba lagi dalam beberapa menit.')

@section('content')
<section class="py-24 bg-slate-50 min-h-[70vh] flex items-center">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-8">
            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path></svg>
            </div>
            <h1 class="text-6xl font-extrabold text-slate-200 tracking-tighter mb-4 font-outfit">429</h1>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4 font-outfit">Terlalu Banyak Permintaan</h2>
            <p class="text-lg text-slate-600 mb-2">
                Mohon maaf, Anda terlalu sering mengirim permintaan dalam waktu singkat.
            </p>
            <p class="text-base text-slate-500 mb-8">
                Silakan tunggu beberapa menit sebelum mencoba lagi. Langkah ini kami terapkan untuk menjaga keamanan sistem.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="{{ url()->previous() }}" class="px-8 py-3 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 transition-colors shadow-md">
                Kembali ke Halaman Sebelumnya
            </a>
            <a href="{{ url('/') }}" class="px-8 py-3 bg-white text-slate-700 font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors shadow-sm">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</section>
@endsection
