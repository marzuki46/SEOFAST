@extends('layouts.admin')

@section('header', 'Tambah Klien Baru')

@section('admin_content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center">
        <a href="{{ route('admin.clients.index') }}" class="text-slate-400 hover:text-slate-600 mr-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">Setup Tenant Baru</h1>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.clients.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold font-outfit text-slate-900">1. Data Organisasi / Klien</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Perusahaan / Klien <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Slug (ID Unik) <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm text-slate-500 lowercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Domain Publik <span class="text-red-500">*</span></label>
                    <input type="text" name="domain" value="{{ old('domain') }}" required placeholder="klien.com" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kontak Email Klien <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold font-outfit text-slate-900">2. Langganan & Limitasi</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Paket <span class="text-red-500">*</span></label>
                    <select name="subscription_plan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                        <option value="free">Free</option>
                        <option value="starter">Starter</option>
                        <option value="pro" selected>Pro</option>
                        <option value="enterprise">Enterprise</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Harga Tagihan Bulanan (Rp)</label>
                    <input type="number" name="monthly_rate" value="{{ old('monthly_rate') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold font-outfit text-slate-900">3. Akun Admin Tenant</h3>
                <p class="text-xs text-slate-500 mt-1">Akun ini akan digunakan oleh klien untuk login ke CMS mereka.</p>
            </div>
            <div class="p-6 grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Admin <span class="text-red-500">*</span></label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Email Admin (Login) <span class="text-red-500">*</span></label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="admin_password" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.clients.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">Batal</a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand-indigo hover:bg-indigo-700">Buat Tenant & Admin</button>
        </div>
    </form>
</div>
@endsection
