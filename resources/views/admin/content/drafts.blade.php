@extends('layouts.admin')

@section('title', 'All Posts - SEOFAST')
@section('page_title', 'All Posts')

@section('admin_content')
<div class="space-y-6">
    <!-- Header Controls -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Drafts (AI Generated)</h1>
            <p class="text-sm text-slate-500 mt-1">Review dan edit konten yang telah digenerate oleh AI sebelum dipublish.</p>
        </div>
        <a href="{{ route('admin.content.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-tr from-brand-indigo to-brand-purple px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/10 hover:opacity-90 transition">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Write with AI
        </a>
    </div>

    <!-- Sessions Alert -->
    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Content Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <form action="{{ route('admin.content.bulk_generate') }}" method="POST">
            @csrf
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase border-b border-slate-200">
                        <th class="px-6 py-3.5 w-12 text-center">
                            <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="px-6 py-3.5">Title / Target Keyword</th>
                        <th class="px-6 py-3.5">Hierarchy</th>
                        <th class="px-6 py-3.5">Silo Category</th>
                        <th class="px-6 py-3.5">Target Anchor</th>
                        <th class="px-6 py-3.5">CQI Score</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($contents as $post)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" name="content_ids[]" value="{{ $post->id }}" class="content-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <a href="{{ route('admin.content.show', $post->id) }}" class="font-bold text-slate-900 hover:text-indigo-600 transition">
                                    {{ $post->title }}
                                </a>
                                @php
                                    $rawSlugDisp = $post->getTranslation('slug', app()->getLocale(), false) ?: $post->getTranslation('slug', 'id', false) ?: $post->slug;
                                    if (is_string($rawSlugDisp) && (str_starts_with($rawSlugDisp, '{') || str_starts_with($rawSlugDisp, '"{'))) {
                                        $decoded = json_decode(trim($rawSlugDisp, '"'), true);
                                        if (is_array($decoded)) $rawSlugDisp = $decoded;
                                    }
                                    while(is_array($rawSlugDisp)) {
                                        $rawSlugDisp = $rawSlugDisp[app()->getLocale()] ?? $rawSlugDisp['id'] ?? current($rawSlugDisp);
                                    }
                                    $slugDispStr = is_string($rawSlugDisp) ? $rawSlugDisp : 'invalid-slug';
                                @endphp
                                <span class="text-xs text-slate-400 font-mono mt-0.5">/blog/{{ $slugDispStr }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($post->hierarchy_level === 'pillar')
                                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-700/10">Pillar</span>
                            @elseif($post->hierarchy_level === 'cluster')
                                <span class="inline-flex items-center rounded-md bg-sky-50 px-2 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-700/10">Cluster</span>
                            @else
                                <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-700/10">Sub-Cluster</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $post->siloBlueprint?->silo_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-xs">
                            @php
                                $links = \App\Models\DeterministicLink::where('target_content_id', $post->id)->get();
                            @endphp
                            @if($links->count() > 0)
                                <div class="flex flex-col gap-1">
                                    @foreach($links as $link)
                                        <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                            {{ $link->mandatory_anchor_text }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 font-normal">--</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($post->cqi_score)
                                <div class="flex items-center gap-2">
                                    <div class="w-12 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $post->cqi_score >= 80 ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ $post->cqi_score }}%"></div>
                                    </div>
                                    <span class="font-bold text-slate-800">{{ round($post->cqi_score) }}%</span>
                                </div>
                            @else
                                <span class="text-slate-400">--</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($post->status === 'published')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Published</span>
                            @elseif($post->status === 'ai_processing')
                                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20 animate-pulse">AI Writing...</span>
                            @elseif($post->status === 'failed_cqi')
                                <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20">Failed CQI</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-50 px-2.5 py-0.5 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-600/20">{{ ucfirst($post->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @php
                                    $rawSlug = $post->getTranslation('slug', app()->getLocale(), false) ?: $post->getTranslation('slug', 'id', false) ?: $post->slug;
                                    if (is_string($rawSlug) && (str_starts_with($rawSlug, '{') || str_starts_with($rawSlug, '"{'))) {
                                        $decoded = json_decode(trim($rawSlug, '"'), true);
                                        if (is_array($decoded)) $rawSlug = $decoded;
                                    }
                                    while(is_array($rawSlug)) {
                                        $rawSlug = $rawSlug[app()->getLocale()] ?? $rawSlug['id'] ?? current($rawSlug);
                                    }
                                    $slugStr = is_string($rawSlug) ? $rawSlug : 'invalid-slug';
                                @endphp
                                <a href="{{ route('blog.show', ['slug' => $slugStr]) }}" target="_blank" class="p-1 text-slate-400 hover:text-indigo-600 transition" title="View on Website">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.content.edit', $post->id) }}" class="p-1 text-slate-400 hover:text-emerald-600 transition" title="Edit Content">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                @if($post->status === 'published')
                                <form action="{{ route('admin.content.update', $post->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="draft">
                                    <input type="hidden" name="target_keyword" value="{{ $post->target_keyword }}">
                                    <input type="hidden" name="hierarchy_level" value="{{ $post->hierarchy_level }}">
                                    <input type="hidden" name="silo_blueprint_id" value="{{ $post->silo_blueprint_id }}">
                                    <button type="submit" class="p-1 text-slate-400 hover:text-amber-600 transition" title="Move to Draft">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </button>
                                </form>
                                @elseif($post->status === 'blueprint' || $post->status === 'draft')
                                <form action="{{ route('admin.content.generate', $post->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="p-1 text-indigo-400 hover:text-indigo-600 transition" title="Generate with AI (Phase 5)">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l8.904-4.452L18 18l4.5-9-9 4.5 1.314-.657z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707-.707" />
                                        </svg>
                                    </button>
                                </form>
                                <a href="{{ route('admin.content.images', $post->id) }}" class="p-1 text-emerald-400 hover:text-emerald-600 transition inline-block" title="Pilih Gambar (Phase 4)">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </a>
                                @endif
                                <form action="{{ route('admin.content.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-rose-400 hover:text-rose-600 transition" title="Delete Post">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                            No blueprint/draft contents found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($contents->count() > 0)
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <label for="target_status" class="text-sm font-semibold text-slate-700">Simpan Sebagai:</label>
                <select name="target_status" id="target_status" class="rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 outline-none bg-white">
                    <option value="draft">Draft (Review Dulu)</option>
                    <option value="published">Langsung Publish</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-tr from-brand-indigo to-brand-purple px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-600/10 hover:opacity-90 transition">
                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
                Generate Konten (Selected)
            </button>
        </div>
        @endif
        @if($contents->hasPages())
        <div class="px-6 py-4 bg-white border-t border-slate-200">
            {{ $contents->links() }}
        </div>
        @endif
        
        </form>
    </div>
</div>

<script>
    document.getElementById('selectAll').addEventListener('change', function(e) {
        document.querySelectorAll('.content-checkbox').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endsection
