@extends('layouts.admin')

@section('title', 'Redirect Manager - ' . config('app.name'))
@section('page_title', 'Redirect Manager')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <p class="text-slate-500 text-sm">Manage 301/302 redirects for changed or removed URLs.</p>
    <a href="{{ route('admin.redirects.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow font-bold text-sm hover:bg-indigo-500 transition">
        + Add Redirect
    </a>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm font-semibold">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Old URL</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">New URL</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Hits</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($redirects as $redirect)
            <tr>
                <td class="px-6 py-4 text-sm font-mono text-gray-800">/<span class="break-all">{{ $redirect->old_url }}</span></td>
                <td class="px-6 py-4 text-sm font-mono text-gray-600"><span class="break-all">{{ $redirect->new_url }}</span></td>
                <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $redirect->status_code === 301 ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $redirect->status_code }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $redirect->hits }}</td>
                <td class="px-6 py-4">
                    @if($redirect->active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('admin.redirects.edit', $redirect) }}" class="text-sm text-amber-600 hover:text-amber-900 font-semibold">Edit</a>
                    <span class="text-gray-300">|</span>
                    <form action="{{ route('admin.redirects.destroy', $redirect) }}" method="POST" class="inline" onsubmit="return confirm('Delete this redirect?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-900 font-semibold">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    No redirects configured yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $redirects->links() }}
</div>
@endsection
