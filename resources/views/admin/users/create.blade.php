@extends('layouts.admin')

@section('title', 'Tambah User - SEOFAST')
@section('page_title', 'Tambah User Pembantu')

@section('admin_content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
        @csrf
        
        @if($errors->any())
        <div class="rounded-xl bg-rose-50 p-4 border border-rose-200 text-rose-800 text-sm font-semibold">
            {{ $errors->first() }}
        </div>
        @endif

        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password (min. 8 karakter)</label>
            <input type="password" name="password" id="password" required minlength="8"
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
        </div>

        <div>
            <label for="role" class="block text-sm font-semibold text-slate-700 mb-1">Role</label>
            <select name="role" id="role" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User Pembantu (Kelola Konten)</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Super Admin (Akses Penuh)</option>
            </select>
            <p class="text-xs text-slate-400 mt-1">Super Admin memiliki akses penuh ke seluruh sistem.</p>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">
                Buat User
            </button>
        </div>
    </form>
</div>
@endsection
