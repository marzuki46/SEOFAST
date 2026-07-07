@extends('layouts.frontend')

@section('title', 'Detail Proyek — ' . \App\Models\SystemSetting::get('site_name', 'SEOFAST'))
@section('meta_description', 'Pelajari detail proyek SEO dan konten AI yang telah dikerjakan oleh tim SEOFAST.')
@section('canonical_url', url()->current())

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4">
    <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-8">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('projects.index') }}" class="hover:text-slate-900 transition-colors">Projects</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-800 font-semibold">{{ $slug }}</span>
    </nav>
    <h1 class="text-3xl font-bold mb-4 text-slate-900 font-outfit">{{ $slug }}</h1>
    <div class="text-slate-600">
        <p>Halaman detail proyek sedang dalam pengembangan.</p>
    </div>
</div>
@endsection
