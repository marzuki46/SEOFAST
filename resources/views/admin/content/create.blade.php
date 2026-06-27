@extends('layouts.admin')

@section('title', 'Proses Antrean AI - SEOFAST')
@section('page_title', 'Proses Antrean AI')

@section('admin_content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">AI Queue Monitor</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau proses pembuatan konten yang sedang berjalan oleh AI di latar belakang.</p>
        </div>
        <a href="{{ route('admin.content.prapost') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
            Kembali ke Pra Post
        </a>
    </div>

    <!-- Sessions Alert -->
    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
        <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Jobs Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="font-bold text-slate-800 text-sm">Active AI Generation Jobs</h3>
            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-800">
                {{ $activeJobs->count() }} In Queue
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase border-b border-slate-200">
                        <th class="px-6 py-3.5">Job ID</th>
                        <th class="px-6 py-3.5">Target Keyword</th>
                        <th class="px-6 py-3.5">Job Type</th>
                        <th class="px-6 py-3.5">Phase Status</th>
                        <th class="px-6 py-3.5">Tokens Used</th>
                        <th class="px-6 py-3.5">Submitted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($activeJobs as $job)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-mono text-slate-500 text-xs">
                            #{{ $job->id }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900">{{ $job->content?->title ?? 'Unknown Content' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                {{ str_replace('_', ' ', ucwords($job->job_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-indigo-700 font-semibold text-xs">{{ strtoupper($job->status) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono text-xs">
                            {{ number_format($job->tokens_used) }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">
                            {{ $job->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            Tidak ada proses antrean AI yang sedang berjalan. <br>
                            Silakan kembali ke <a href="{{ route('admin.content.prapost') }}" class="text-indigo-600 font-semibold hover:underline">Pra Post</a> untuk memulai generasi konten.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Auto-refresh the page every 10 seconds if there are active jobs
    @if($activeJobs->count() > 0)
        setTimeout(() => {
            window.location.reload();
        }, 10000);
    @endif
</script>
@endsection
