@extends('layouts.admin')

@section('title', 'Trash - ' . config('app.name'))
@section('page_title', 'Trash')

@section('admin_content')
<div class="space-y-6">
    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-semibold text-emerald-800">{!! session('success') !!}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase border-b border-slate-200">
                        <th class="px-6 py-3.5">Title / Target Keyword</th>
                        <th class="px-6 py-3.5">Silo Category</th>
                        <th class="px-6 py-3.5">Deleted At</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($contents as $post)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-600">{{ $post->title }}</span>
                                <span class="text-xs text-slate-400 font-mono mt-0.5">/blog/{{ $post->slug }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $post->siloBlueprint?->silo_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">
                            {{ $post->deleted_at?->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.content.restore', $post->id) }}" method="POST" onsubmit="return confirm('Restore this post?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition">
                                        Restore
                                    </button>
                                </form>
                                <form action="{{ route('admin.content.force_delete', $post->id) }}" method="POST" onsubmit="return confirm('Permanently delete this post? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition">
                                        Delete Forever
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <svg class="h-12 w-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            <p class="font-semibold">Trash is empty</p>
                            <p class="text-xs mt-1">Deleted posts will appear here for recovery.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($contents->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $contents->links() }}
        </div>
        @endif
    </div>

    <div class="flex justify-between items-center">
        <p class="text-xs text-slate-500">
            <span class="font-semibold">{{ $contents->total() }}</span> item(s) in trash
        </p>
        <a href="{{ route('admin.content.index') }}" class="text-sm text-brand-indigo hover:underline font-medium">
            &larr; Back to All Posts
        </a>
    </div>
</div>
@endsection
