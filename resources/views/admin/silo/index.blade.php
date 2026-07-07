@extends('layouts.admin')

@section('title', 'Silo Blueprints & Keywords - SEOFAST')
@section('page_title', 'Topical Maps & Keywords')

@section('admin_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500 mt-1">Design your site's topical map by defining seed keywords and creating architectural silos.</p>
        </div>
        <button onclick="document.getElementById('createSiloModal').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Keyword Silo
        </button>
    </div>

    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 p-4 border border-emerald-200">
        <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Silos Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($silos as $silo)
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200 p-6 flex flex-col h-full">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                    </svg>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                        {{ strtoupper($silo->target_language) }}-{{ strtoupper($silo->target_country) }}
                    </span>
                    @if($silo->is_locked)
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                        Locked
                    </span>
                    @endif
                </div>
            </div>

            <div class="flex-1">
                <h3 class="text-lg font-bold text-slate-900 font-outfit mb-1">
                    <a href="{{ route('admin.silo.show', $silo->id) }}" class="hover:text-indigo-600 transition">{{ $silo->silo_name }}</a>
                </h3>
                <p class="text-sm font-semibold text-slate-500 mb-4">Seed: <span class="text-indigo-600">{{ $silo->seed_keyword }}</span></p>

                <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-4 mt-auto">
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Total Contents</p>
                        <p class="text-xl font-bold text-slate-800">{{ $silo->contents_count ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Published</p>
                        <p class="text-xl font-bold text-emerald-600">{{ $silo->published_contents }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.silo.show', $silo->id) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">View Map &rarr;</a>
                <form action="{{ route('admin.silo.destroy', $silo->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Silo Map? All associated content references will be affected.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-rose-500 hover:text-rose-700 transition">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
            <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.866 8.21 8.21 0 003 2.48z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 font-outfit mb-2">No Keywords / Silos Found</h3>
            <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">Start building your SEO topical authority by defining a new Keyword Silo.</p>
            <button onclick="document.getElementById('createSiloModal').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">
                Create First Keyword Silo
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Create Modal -->
<div id="createSiloModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('createSiloModal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
            <form action="{{ route('admin.silo.store') }}" method="POST">
                @csrf
                <div class="bg-white px-6 pt-6 pb-6 space-y-5">
                    <div>
                        <h3 class="text-xl font-bold font-outfit text-slate-900" id="modal-title">Create Keyword Silo</h3>
                        <p class="text-sm text-slate-500 mt-1">Define the topical boundary for your new content map.</p>
                    </div>

                    <div>
                        <label for="silo_name" class="block text-sm font-semibold text-slate-700 mb-1">Silo Name / Category</label>
                        <input type="text" name="silo_name" id="silo_name" placeholder="e.g. Laravel Architecture" required
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    </div>

                    <div>
                        <label for="seed_keyword" class="block text-sm font-semibold text-slate-700 mb-1">Seed Keyword</label>
                        <input type="text" name="seed_keyword" id="seed_keyword" placeholder="e.g. laravel tips" required
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                        <p class="text-xs text-slate-400 mt-1.5">This root keyword will be used to generate sub-cluster and KGR topics.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="target_language" class="block text-sm font-semibold text-slate-700 mb-1">Language</label>
                            <select name="target_language" id="target_language" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 bg-white focus:border-indigo-500 outline-none">
                                <option value="id">Indonesian (id)</option>
                                <option value="en">English (en)</option>
                            </select>
                        </div>
                        <div>
                            <label for="target_country" class="block text-sm font-semibold text-slate-700 mb-1">Country</label>
                            <select name="target_country" id="target_country" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 bg-white focus:border-indigo-500 outline-none">
                                <option value="ID">Indonesia (ID)</option>
                                <option value="US">United States (US)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="content_framework" class="block text-sm font-semibold text-slate-700 mb-1">Content Framework</label>
                        <select name="content_framework" id="content_framework" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-800 bg-white focus:border-indigo-500 outline-none">
                            <option value="default">Default (AI Bebas)</option>
                            <option value="aida">AIDA — Attention → Interest → Desire → Action</option>
                            <option value="pas">PAS — Problem → Agitate → Solution</option>
                            <option value="how_to">How-To — Panduan Langkah demi Langkah</option>
                            <option value="listicle">Listicle — Daftar / Top X</option>
                        </select>
                        <p class="text-xs text-slate-400 mt-1.5">Menentukan struktur outline konten yang akan diikuti AI saat menulis.</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('createSiloModal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">Save & Generate Map</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
