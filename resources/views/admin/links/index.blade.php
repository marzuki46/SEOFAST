@extends('layouts.admin')

@section('title', 'Internal Link Mapping - SEOFAST')
@section('page_title', 'Rekayasa Internal Link (Deterministic)')

@section('admin_content')
<div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
    <!-- Left Sidebar: Silo List -->
    <div class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm sticky top-6">
            <h3 class="text-xs font-bold text-slate-500 font-outfit mb-3 uppercase tracking-wider">Select Silo</h3>
            <div class="space-y-1">
                @foreach($silos as $silo)
                    <a href="?silo_id={{ $silo->id }}" 
                       class="block px-3 py-2 rounded-lg text-sm font-medium transition {{ $selectedSilo == $silo->id ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 border border-transparent' }}">
                        {{ $silo->silo_name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Side: Form & Table -->
    <div class="flex-1 space-y-6 min-w-0">
        
        <!-- Form: Map New Link -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 font-outfit mb-4">Map New Link</h3>
            
            <form action="{{ route('admin.links.store') }}" method="POST">
                @csrf
                <input type="hidden" name="silo_id" value="{{ $selectedSilo }}">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="source_content_id" class="block text-sm font-semibold text-slate-700 mb-1">Source (From)</label>
                        <select name="source_content_id" id="source_content_id" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none bg-slate-50">
                            <option value="">-- Select Source --</option>
                            @foreach($contents as $c)
                                <option value="{{ $c->id }}">[{{ $c->hierarchy_level }}] {{ $c->target_keyword }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="target_content_id" class="block text-sm font-semibold text-slate-700 mb-1">Target (To)</label>
                        <select name="target_content_id" id="target_content_id" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none bg-slate-50">
                            <option value="">-- Select Target --</option>
                            @foreach($contents as $c)
                                <option value="{{ $c->id }}">[{{ $c->hierarchy_level }}] {{ $c->target_keyword }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="anchor_text" class="block text-sm font-semibold text-slate-700 mb-1">Anchor Text</label>
                        <input type="text" name="anchor_text" id="anchor_text" required placeholder="e.g. panduan lengkap SEO"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none bg-slate-50">
                    </div>
                </div>

                <div class="mt-5 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Save Link Mapping
                    </button>
                </div>
            </form>
        </div>

        <!-- Table: Mapped Links -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="text-lg font-bold text-slate-900 font-outfit">Mapped Links for Selected Silo</h3>
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
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 px-3 py-1.5 rounded-lg text-slate-700 border border-slate-200 font-medium text-xs">
                                    {{ $link->mandatory_anchor_text }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                    </svg>
                                </div>
                                <p class="text-slate-500 font-medium">No internal links mapped yet for this silo.</p>
                                <p class="text-slate-400 text-xs mt-1">Use the form above to manually map links or generate them from the Silo Builder.</p>
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
