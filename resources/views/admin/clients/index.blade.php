@extends('layouts.admin')

@section('header', 'Manajemen Klien')

@section('admin_content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">Klien / Tenant</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola akses CMS SEOFAST untuk klien B2B Anda.</p>
    </div>
    <div>
        <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-indigo text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Klien Baru
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-brand-indigo">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Klien</p>
                <p class="text-2xl font-bold font-outfit text-slate-900">{{ $clients->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Klien Aktif</p>
                <p class="text-2xl font-bold font-outfit text-slate-900">{{ $clients->where('is_active', true)->count() ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total User</p>
                <p class="text-2xl font-bold font-outfit text-slate-900">{{ \App\Models\User::count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Pendapatan MRR</p>
                <p class="text-lg font-bold font-outfit text-slate-900">Rp {{ number_format(\App\Models\Tenant::where('is_active', true)->sum('monthly_rate'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 font-semibold">Nama Klien</th>
                    <th class="px-6 py-4 font-semibold">Domain</th>
                    <th class="px-6 py-4 font-semibold">Paket</th>
                    <th class="px-6 py-4 font-semibold">Konten</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($clients as $client)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $client->name }}</div>
                        <div class="text-slate-500 text-xs">{{ $client->contact_email ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $client->domain }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs font-semibold uppercase">{{ $client->subscription_plan }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ number_format($client->contents_count) }} Post
                    </td>
                    <td class="px-6 py-4">
                        @if($client->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-green-50 text-green-700 text-xs font-medium border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-red-50 text-red-700 text-xs font-medium border border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Suspended
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <form action="{{ route('admin.clients.login_as', $client) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-brand-indigo hover:underline font-medium" title="Login sebagai klien ini">
                                Impersonate
                            </button>
                        </form>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('admin.clients.show', $client) }}" class="text-xs text-slate-600 hover:text-slate-900 font-medium">
                            Kelola
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Belum ada klien/tenant.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $clients->links() }}
    </div>
    @endif
</div>
@endsection
