@extends('layouts.admin')

@section('header', 'Edit Klien: ' . $client->name)

@section('admin_content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center">
        <a href="{{ route('admin.clients.show', $client) }}" class="text-slate-400 hover:text-slate-600 mr-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">Edit Klien</h1>
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

    <form action="{{ route('admin.clients.update', $client) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold font-outfit text-slate-900">Data Organisasi</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Perusahaan / Klien <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $client->name) }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Lengkap PT/CV</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $client->company_name) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kontak Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $client->contact_email) }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kontak Telepon / WA</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $client->contact_phone) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Catatan Internal Admin</label>
                    <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">{{ old('notes', $client->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                <h3 class="font-bold font-outfit text-slate-900">Informasi Langganan</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Paket Langganan <span class="text-red-500">*</span></label>
                    <select name="subscription_plan" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                        <option value="free" {{ old('subscription_plan', $client->subscription_plan) == 'free' ? 'selected' : '' }}>Free</option>
                        <option value="starter" {{ old('subscription_plan', $client->subscription_plan) == 'starter' ? 'selected' : '' }}>Starter</option>
                        <option value="pro" {{ old('subscription_plan', $client->subscription_plan) == 'pro' ? 'selected' : '' }}>Pro</option>
                        <option value="enterprise" {{ old('subscription_plan', $client->subscription_plan) == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Harga Tagihan Bulanan (Rp)</label>
                    <input type="number" name="monthly_rate" value="{{ old('monthly_rate', (int)$client->monthly_rate) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kontrak Mulai</label>
                    <input type="date" name="contract_start_at" value="{{ old('contract_start_at', $client->contract_start_at ? $client->contract_start_at->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kontrak Berakhir (Opsional)</label>
                    <input type="date" name="contract_end_at" value="{{ old('contract_end_at', $client->contract_end_at ? $client->contract_end_at->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo sm:text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.clients.show', $client) }}" class="px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">Batal</a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-brand-indigo hover:bg-indigo-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
