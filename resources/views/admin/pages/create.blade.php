@extends('layouts.admin')

@section('page_title', 'Create Page')

@section('admin_content')
<div class="max-w-3xl">
    <div class="bg-white border rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Page Title</label>
                <input type="text" name="title" class="w-full border-gray-300 rounded-lg shadow-sm" required placeholder="e.g. About Us">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">URL Slug</label>
                <input type="text" name="slug" class="w-full border-gray-300 rounded-lg shadow-sm text-blue-600 font-mono" required placeholder="e.g. about-us">
                <p class="text-xs text-gray-500 mt-1">This will be the URL: {{ url('/') }}/<span class="font-bold">about-us</span></p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Title (SEO)</label>
                <input type="text" name="meta_title" class="w-full border-gray-300 rounded-lg shadow-sm" placeholder="Optional SEO title">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Meta Description (SEO)</label>
                <textarea name="meta_description" class="w-full border-gray-300 rounded-lg shadow-sm" rows="3" placeholder="Optional SEO description"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 text-gray-600 font-semibold hover:text-gray-900">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow font-bold hover:bg-indigo-500">Create & Start Building</button>
            </div>
        </form>
    </div>
</div>
@endsection
