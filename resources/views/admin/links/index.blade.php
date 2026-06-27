@extends('layouts.admin')

@section('title', 'Internal Link Mapping - SEOFAST')
@section('page_title', 'Rekayasa Internal Link (Deterministic)')

@section('admin_content')
<div class="space-y-6">
    <!-- Top Bar: Silo Selection -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-900 font-outfit mb-1">Select Silo</h3>
            <p class="text-sm text-slate-500">Choose a Silo Blueprint to configure its internal linking</p>
        </div>
        <div class="w-full md:w-96">
            <select name="silo_id" onchange="window.location.href='?silo_id='+this.value"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none bg-slate-50">
                @foreach($silos as $silo)
                    <option value="{{ $silo->id }}" {{ $selectedSilo == $silo->id ? 'selected' : '' }}>
                        {{ $silo->silo_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Form & Table Container -->
    <div class="space-y-6">
        
        <!-- Form: Map New Link -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 gap-4">
                <h3 class="text-lg font-bold text-slate-900 font-outfit">Map New Link</h3>
                @if($selectedSilo)
                <form action="{{ route('admin.links.generate_ai') }}" method="POST">
                    @csrf
                    <input type="hidden" name="silo_id" value="{{ $selectedSilo }}">
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-indigo-700 transition shadow-sm flex items-center gap-2 text-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        Generate High-CTR Anchors with AI
                    </button>
                </form>
                @endif
            </div>
            
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
                                <option value="{{ $c->id }}" data-hierarchy="{{ $c->hierarchy_level }}" data-parent="{{ $c->parent_id }}">
                                    [{{ ucfirst(str_replace('_', ' ', $c->hierarchy_level)) }}] {{ $c->target_keyword }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="target_content_id" class="block text-sm font-semibold text-slate-700 mb-1">Target (To)</label>
                        <select name="target_content_id" id="target_content_id" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 outline-none bg-slate-50">
                            <option value="">-- Select Target --</option>
                            @foreach($contents as $c)
                                <option value="{{ $c->id }}" data-hierarchy="{{ $c->hierarchy_level }}" data-parent="{{ $c->parent_id }}">
                                    [{{ ucfirst(str_replace('_', ' ', $c->hierarchy_level)) }}] {{ $c->target_keyword }}
                                </option>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sourceSelect = document.getElementById('source_content_id');
        const targetSelect = document.getElementById('target_content_id');
        if (!sourceSelect || !targetSelect) return;

        // Store all original target options
        const originalTargetOptions = Array.from(targetSelect.options);

        sourceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (!selectedOption.value) {
                // Restore all if nothing selected
                targetSelect.innerHTML = '';
                originalTargetOptions.forEach(opt => targetSelect.appendChild(opt));
                return;
            }

            const sourceHierarchy = selectedOption.getAttribute('data-hierarchy');
            const sourceId = selectedOption.value;
            const sourceParent = selectedOption.getAttribute('data-parent');

            // Clear target options
            targetSelect.innerHTML = '';
            targetSelect.appendChild(originalTargetOptions[0]); // Keep '-- Select Target --'

            originalTargetOptions.forEach(opt => {
                if (!opt.value || opt.value === sourceId) return; // Skip placeholder and self

                const targetHierarchy = opt.getAttribute('data-hierarchy');
                const targetParent = opt.getAttribute('data-parent');
                const targetId = opt.value;
                
                let shouldShow = false;

                if (sourceHierarchy === 'pillar') {
                    // Pillar -> Sub Cluster (based on user rules)
                    if (targetHierarchy === 'sub_cluster') shouldShow = true;
                } 
                else if (sourceHierarchy === 'cluster') {
                    // Child(cluster) -> Sesama child (other clusters), Sub Cluster (its own)
                    if (targetHierarchy === 'cluster') shouldShow = true;
                    if (targetHierarchy === 'sub_cluster' && targetParent === sourceId) shouldShow = true;
                }
                else if (sourceHierarchy === 'sub_cluster') {
                    // Sub Cluster -> Child(cluster_utama/parent), antar sub_cluster(siblings)
                    if (targetHierarchy === 'cluster' && targetId === sourceParent) shouldShow = true;
                    if (targetHierarchy === 'sub_cluster' && targetParent === sourceParent) shouldShow = true;
                }

                if (shouldShow) {
                    targetSelect.appendChild(opt.cloneNode(true));
                }
            });
        });
    });
</script>
@endsection
