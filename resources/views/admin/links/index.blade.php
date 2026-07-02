@extends('layouts.admin')

@section('title', 'Internal Link Mapping - SEOFAST')
@section('page_title', 'Rekayasa Internal Link (Deterministic)')

@section('admin_content')
<div class="space-y-6">
    <!-- Header & AI Generation Button -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h3 class="text-2xl font-bold text-slate-900 font-outfit mb-2">Automated Internal Linking</h3>
            <p class="text-slate-500 text-sm max-w-xl">
                Sistem akan memetakan tautan secara otomatis berdasarkan hierarki (Pillar &rarr; Cluster &rarr; Sub-cluster). AI akan menganalisis konten dan menghasilkan variasi Anchor Text natural (Exact, Partial, dan Long Tail).
            </p>
        </div>
        
        @if($selectedSilo)
            <div class="flex items-center gap-3 shrink-0">
                <form action="{{ route('admin.links.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua pemetaan anchor untuk Cluster ini? Anda harus meng-generate-nya ulang dari nol.');">
                    @csrf
                    <input type="hidden" name="silo_id" value="{{ $selectedSilo }}">
                    @if(isset($selectedCluster))
                        <input type="hidden" name="cluster_id" value="{{ $selectedCluster }}">
                    @endif
                    <button type="submit" class="px-5 py-3.5 bg-white border-2 border-red-100 text-red-600 hover:bg-red-50 hover:border-red-200 font-bold rounded-xl transition-all flex items-center gap-2 active:scale-95 shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Kosongkan Anchor
                    </button>
                </form>
                
                <form action="{{ route('admin.links.generate_ai') }}" method="POST">
                    @csrf
                    <input type="hidden" name="silo_id" value="{{ $selectedSilo }}">
                    @if(isset($selectedCluster))
                        <input type="hidden" name="cluster_id" value="{{ $selectedCluster }}">
                    @endif
                    <button type="submit" class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 text-white font-extrabold rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-3 active:scale-95">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                        </svg>
                        Generate AI Anchor Text
                    </button>
                </form>
            </div>
        @else
            <div class="px-6 py-3 bg-slate-100 text-slate-500 font-semibold rounded-xl text-sm border border-slate-200">
                Silakan pilih Silo dari menu utama terlebih dahulu.
            </div>
        @endif
    </div>

    <!-- Grouped Links by Source -->
    <div class="space-y-6">
        @forelse($links->groupBy('source_content_id') as $sourceId => $sourceLinks)
            @php $sourceContent = $sourceLinks->first()->source; @endphp
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 block">
                            {{ $sourceContent->hierarchy_level }}
                        </span>
                        <h3 class="text-base font-bold text-slate-900">
                            {{ $sourceContent->target_keyword ?? 'Unknown Source' }}
                        </h3>
                    </div>
                    <div class="text-sm font-semibold text-slate-500">
                        {{ $sourceLinks->count() }} Kewajiban Tautan
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white border-b border-slate-200 text-slate-500 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold w-2/5">Target (Ke Artikel)</th>
                                <th class="px-6 py-4 font-semibold w-3/5">Anchor Text (Wajib Disertakan)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($sourceLinks as $link)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-indigo-600 font-medium align-top">
                                    &rarr; {{ $link->target->target_keyword ?? 'N/A' }}
                                    <div class="text-[10px] text-slate-400 mt-1 uppercase">{{ $link->target->hierarchy_level }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-slate-100 text-sm">
                                    @if($link->mandatory_anchor_text === '[PENDING_AI]')
                                        <span class="inline-flex items-center gap-2 text-amber-600 font-semibold bg-amber-50 px-3 py-1 rounded-full text-xs">
                                            <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Menunggu AI...
                                        </span>
                                    @else
                                        <div class="flex items-center">
                                            <textarea rows="2" class="anchor-input w-full border-transparent hover:border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm sm:text-sm px-3 py-2 bg-slate-50 hover:bg-white transition-colors resize-none font-medium text-emerald-700" data-id="{{ $link->id }}">{{ $link->mandatory_anchor_text }}</textarea>
                                            <span class="save-indicator ml-3 text-emerald-500 opacity-0 transition-opacity flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4 shadow-inner">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                    </svg>
                </div>
                <p class="text-slate-600 font-bold text-lg mb-1">Belum Ada Pemetaan Link</p>
                <p class="text-slate-500 text-sm">Tekan tombol "Generate AI Anchor Text" di atas untuk memetakan otomatis sesuai hierarki Silo Anda.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inline Editing for Anchor Text
        const anchorInputs = document.querySelectorAll('.anchor-input');
        anchorInputs.forEach(input => {
            let timeout = null;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                const indicator = this.nextElementSibling;
                indicator.style.opacity = '0';
                
                timeout = setTimeout(() => {
                    const linkId = this.getAttribute('data-id');
                    const newValue = this.value;
                    
                    fetch(`/admin/links/${linkId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ mandatory_anchor_text: newValue })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            indicator.style.opacity = '1';
                            setTimeout(() => {
                                indicator.style.opacity = '0';
                            }, 2000);
                        }
                    });
                }, 800);
            });
        });
    });
</script>
@endsection
