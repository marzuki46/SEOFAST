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
            <form action="{{ route('admin.links.generate_ai') }}" method="POST" class="shrink-0">
                @csrf
                <input type="hidden" name="silo_id" value="{{ $selectedSilo }}">
                <button type="submit" class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 text-white font-extrabold rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-3 active:scale-95">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                    Generate AI Anchor Text
                </button>
            </form>
        @else
            <div class="px-6 py-3 bg-slate-100 text-slate-500 font-semibold rounded-xl text-sm border border-slate-200">
                Silakan pilih Silo dari menu utama terlebih dahulu.
            </div>
        @endif
    </div>

    <!-- Table: Mapped Links -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-base font-bold text-slate-900 font-outfit uppercase tracking-wider">
                Daftar Internal Link: {{ $silos->firstWhere('id', $selectedSilo)?->silo_name ?? 'Silo' }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-white border-b border-slate-200 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-1/3">Source (Dari Artikel)</th>
                        <th class="px-6 py-4 font-semibold w-1/3">Target (Ke Artikel)</th>
                        <th class="px-6 py-4 font-semibold w-1/3">Anchor Text (Link Label)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($links as $link)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-slate-900">
                            {{ $link->source->target_keyword ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-indigo-600 font-medium">
                            &rarr; {{ $link->target->target_keyword ?? 'N/A' }}
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
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4 shadow-inner">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                </svg>
                            </div>
                            <p class="text-slate-600 font-bold text-lg mb-1">Belum Ada Pemetaan Link</p>
                            <p class="text-slate-500 text-sm">Tekan tombol "Generate AI Anchor Text" di atas untuk memetakan otomatis sesuai hierarki Silo Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
