@extends('layouts.admin')

@section('title', 'Internal Link Mapping - SEOFAST')
@section('page_title', 'Rekayasa Internal Link (Deterministic)')

@section('admin_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 font-outfit mb-4">Map New Link</h3>
            
            <form action="{{ route('admin.links.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Select Silo</label>
                    <select name="silo_id" onchange="window.location.href='?silo_id='+this.value"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                        @foreach($silos as $silo)
                            <option value="{{ $silo->id }}" {{ $selectedSilo == $silo->id ? 'selected' : '' }}>
                                {{ $silo->silo_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2">
                    <label for="source_content_id" class="block text-sm font-semibold text-slate-700 mb-1">Source Content (Link From)</label>
                    <select name="source_content_id" id="source_content_id" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                        <option value="">-- Select Source --</option>
                        @foreach($contents as $c)
                            <option value="{{ $c->id }}">[{{ $c->hierarchy_level }}] {{ $c->target_keyword }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="target_content_id" class="block text-sm font-semibold text-slate-700 mb-1">Target Content (Link To)</label>
                    <select name="target_content_id" id="target_content_id" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                        <option value="">-- Select Target --</option>
                        @foreach($contents as $c)
                            <option value="{{ $c->id }}">[{{ $c->hierarchy_level }}] {{ $c->target_keyword }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="anchor_text" class="block text-sm font-semibold text-slate-700 mb-1">Anchor Text</label>
                    <input type="text" name="anchor_text" id="anchor_text" required placeholder="e.g. panduan lengkap SEO"
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">
                        Save Link Mapping
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-lg font-bold text-slate-900 font-outfit">Mapped Links for Silo</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-white border-b border-slate-200 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Source (From)</th>
                            <th class="px-6 py-4 font-semibold">Target (To)</th>
                            <th class="px-6 py-4 font-semibold">Anchor Text</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($links as $link)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $link->source->target_keyword ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-indigo-600 font-medium">&rarr; {{ $link->target->target_keyword ?? 'N/A' }}</td>
                            <td class="px-6 py-4"><span class="bg-slate-100 px-2 py-1 rounded text-slate-600 border border-slate-200">{{ $link->mandatory_anchor_text }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                No internal links mapped yet for this silo.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
