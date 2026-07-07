@extends('layouts.admin')

@section('title', $redirect ? 'Edit Redirect - SEOFAST' : 'Add Redirect - SEOFAST')
@section('page_title', $redirect ? 'Edit Redirect' : 'Add Redirect')

@section('admin_content')
<div class="max-w-2xl">
    <form action="{{ $redirect ? route('admin.redirects.update', $redirect) : route('admin.redirects.store') }}" method="POST" class="space-y-6 bg-white border rounded-xl shadow-sm p-8">
        @csrf
        @if($redirect) @method('PUT') @endif

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Old URL <span class="text-red-500">*</span></label>
            <div class="flex items-center gap-2">
                <span class="text-gray-500 text-sm font-mono shrink-0">{{ url('/') }}/</span>
                <input type="text" name="old_url" value="{{ old('old_url', $redirect?->old_url) }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm"
                    placeholder="old-page-slug">
            </div>
            <p class="text-xs text-gray-400 mt-1">Enter the path only (without domain). E.g. <code>old-page</code> or <code>blog/post-title</code></p>
            @error('old_url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">New URL <span class="text-red-500">*</span></label>
            <input type="text" name="new_url" value="{{ old('new_url', $redirect?->new_url) }}" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm"
                placeholder="https://example.com/new-page or /new-path">
            @error('new_url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Redirect Type</label>
            <select name="status_code" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                <option value="301" {{ old('status_code', $redirect?->status_code) === 301 ? 'selected' : '' }}>301 — Moved Permanently</option>
                <option value="302" {{ old('status_code', $redirect?->status_code) === 302 ? 'selected' : '' }}>302 — Found (Temporary)</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" name="active" value="1" id="active"
                {{ old('active', $redirect?->active ?? true) ? 'checked' : '' }}
                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            <label for="active" class="text-sm font-semibold text-gray-700">Active</label>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-500 transition shadow-sm">
                {{ $redirect ? 'Update Redirect' : 'Create Redirect' }}
            </button>
            <a href="{{ route('admin.redirects.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
