@extends('layouts.admin')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.clients.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
    </a>
    Tenant: {{ $client->name }}
</div>
@endsection

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Tenant Detail -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Informasi Klien</h3>
                <div>
                    <a href="{{ route('admin.clients.edit', $client) }}" class="text-sm font-medium text-brand-indigo hover:text-indigo-700">Edit Data</a>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-y-6 gap-x-4">
                    <div>
                        <p class="text-sm text-slate-500">Domain / Website</p>
                        <p class="font-medium text-slate-900 mt-1"><a href="https://{{ $client->domain }}" target="_blank" class="text-brand-indigo hover:underline">{{ $client->domain }}</a></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Slug ID</p>
                        <p class="font-mono text-slate-900 mt-1 text-sm">{{ $client->slug }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Email Kontak</p>
                        <p class="font-medium text-slate-900 mt-1">{{ $client->contact_email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Paket Langganan</p>
                        <p class="mt-1"><span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs font-semibold uppercase">{{ $client->subscription_plan }}</span></p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Tanggal Bergabung</p>
                        <p class="font-medium text-slate-900 mt-1">{{ $client->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Status</p>
                        <p class="mt-1">
                            @if($client->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-green-50 text-green-700 text-xs font-medium border border-green-200">Aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-red-50 text-red-700 text-xs font-medium border border-red-200">Suspended</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Pengguna / Admin Tenant</h3>
            </div>
            <div class="p-0">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($client->users as $user)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs {{ $user->role === 'admin' ? 'bg-indigo-50 text-brand-indigo' : 'bg-slate-100 text-slate-600' }}">{{ $user->role }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-slate-500">Tidak ada pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Actions & Stats -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center">
            <h3 class="text-lg font-bold font-outfit text-slate-900 mb-4">Statistik Konten</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Total Post</p>
                    <p class="text-2xl font-bold font-outfit text-brand-indigo">{{ number_format($stats['total_contents']) }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Silo / Pilar</p>
                    <p class="text-2xl font-bold font-outfit text-slate-900">{{ number_format($stats['total_silos']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold font-outfit text-slate-900 mb-4">Tindakan Cepat</h3>
            <div class="space-y-3">
                <form action="{{ route('admin.clients.login_as', $client) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-indigo-50 text-brand-indigo text-sm font-medium rounded-lg hover:bg-indigo-100 transition-colors border border-indigo-100">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Impersonate (Masuk sebagai klien)
                    </button>
                </form>

                @if($client->is_active)
                    <form action="{{ route('admin.clients.suspend', $client) }}" method="POST" onsubmit="return confirm('Suspend akun klien ini? Mereka tidak akan bisa login atau mengakses layanan.')">
                        @csrf
                        <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors border border-red-100">
                            Suspend Akun Klien
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.clients.reactivate', $client) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-lg hover:bg-green-100 transition-colors border border-green-100">
                            Aktifkan Kembali Akun
                        </button>
                    </form>
                @endif
                
                <hr class="border-slate-100 my-4">

                <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('HAPUS PERMANEN klien ini beserta seluruh datanya? TINDAKAN INI TIDAK BISA DIBATALKAN.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-white text-slate-500 hover:text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition-colors border border-slate-200 hover:border-red-200">
                        Hapus Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
