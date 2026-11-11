@extends('layouts.admin')

@section('title', 'Content Campaigns - ' . config('app.name'))
@section('page_title', 'Content Campaigns (Auto Post Content)')

@section('admin_content')
<div class="space-y-6" x-data="{ showCreate: false, activeTab: '{{ request('tab', 'campaigns') }}' }">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ $errors->first() }}</div>
    @endif

    <!-- Tab Utama Navigasi (Persis Menu Plugin) -->
    <nav aria-label="Content campaign menu" class="flex flex-wrap gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
        <a href="{{ route('admin.content-campaigns.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request('tab', 'campaigns') === 'campaigns' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            📋 Daftar Campaign
        </a>
        <a href="{{ route('admin.content-campaigns.index', ['tab' => 'ai_settings']) }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request('tab') === 'ai_settings' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            ✨ 1. Konfigurasi AI & API Key
        </a>
        <a href="{{ route('admin.content-campaigns.index', ['tab' => 'brand_settings']) }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request('tab') === 'brand_settings' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            🏢 2. Niche, Brand Voice, & Data Bisnis
        </a>
        <a href="{{ route('admin.content-campaigns.index', ['tab' => 'prompt_settings']) }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ request('tab') === 'prompt_settings' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            ✍️ 3. Template Prompt SEO & E-E-A-T
        </a>
    </nav>

    <!-- TAB 1: PENGATURAN AI & API KEY (GLOBAL TERPUSAT) -->
    @if(request('tab') === 'ai_settings')
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="mb-6 border-b border-slate-100 pb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Pengaturan Terpusat</span>
                <h2 class="mt-1 text-2xl font-bold text-slate-900">Konfigurasi AI Engine & API Key</h2>
                <p class="mt-1 text-sm text-slate-500">Cukup simpan sekali di sini. Semua campaign otomatis memakai AI ini untuk meriset topik dan menulis konten.</p>
            </div>

            <form method="POST" action="{{ route('admin.content-campaigns.global_settings') }}" class="space-y-6" x-data="{ provider: '{{ $globalSettings['ai_provider'] }}' }">
                @csrf
                <!-- Pilihan Provider -->
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">Pilihan Provider AI Engine</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label :class="provider === 'gemini' ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-200' : 'border-slate-200 hover:bg-slate-50'" class="flex cursor-pointer items-center gap-3 rounded-2xl border p-4 transition">
                            <input type="radio" name="ai_provider" value="gemini" x-model="provider" class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <div class="font-bold text-slate-900">✨ Google Gemini Direct</div>
                                <div class="text-xs text-slate-500">Google AI Studio (Cepat & Stabil)</div>
                            </div>
                        </label>
                        <label :class="provider === '9router' ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-200' : 'border-slate-200 hover:bg-slate-50'" class="flex cursor-pointer items-center gap-3 rounded-2xl border p-4 transition">
                            <input type="radio" name="ai_provider" value="9router" x-model="provider" class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <div class="font-bold text-slate-900">🌐 9Router Gateway</div>
                                <div class="text-xs text-slate-500">Multi-Model LLM Gateway</div>
                            </div>
                        </label>
                        <label :class="provider === 'openai' ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-200' : 'border-slate-200 hover:bg-slate-50'" class="flex cursor-pointer items-center gap-3 rounded-2xl border p-4 transition">
                            <input type="radio" name="ai_provider" value="openai" x-model="provider" class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <div class="font-bold text-slate-900">⚡ OpenAI Official</div>
                                <div class="text-xs text-slate-500">GPT-4o & GPT-4o-mini</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Fields Gemini -->
                <div x-show="provider === 'gemini'" class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5 space-y-4">
                    <h3 class="font-bold text-slate-800 text-sm">Pengaturan Google Gemini</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Gemini API Key <span class="text-red-500">*</span></label>
                            <input type="password" name="gemini_api_key" value="" placeholder="{{ $globalSettings['gemini_api_key_configured'] ? 'Tersimpan — isi hanya untuk mengganti' : 'AIzaSy...' }}" autocomplete="new-password" class="w-full rounded-xl border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                            <p class="mt-1 text-xs text-slate-500">Dapatkan API Key gratis di Google AI Studio.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Model Gemini Default</label>
                            <input type="text" name="gemini_model" value="{{ $globalSettings['gemini_model'] }}" placeholder="gemini-2.0-flash" class="w-full rounded-xl border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                            <p class="mt-1 text-xs text-slate-500">Contoh: <code>gemini-2.5-pro</code>, <code>gemini-2.0-flash</code></p>
                        </div>
                    </div>
                </div>

                <!-- Fields 9Router -->
                <div x-show="provider === '9router'" x-cloak class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5 space-y-4">
                    <h3 class="font-bold text-slate-800 text-sm">Pengaturan 9Router Gateway</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Endpoint 9Router URL</label>
                            <input type="text" name="9router_api_base" value="{{ $globalSettings['9router_api_base'] }}" class="w-full rounded-xl border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">9Router API Key</label>
                            <input type="password" name="9router_api_key" value="" placeholder="{{ $globalSettings['9router_api_key_configured'] ? 'Tersimpan — isi hanya untuk mengganti' : 'nr-xxxx...' }}" autocomplete="new-password" class="w-full rounded-xl border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Model Writer 9Router</label>
                            <input type="text" name="9router_model" value="{{ $globalSettings['9router_model'] }}" placeholder="openai/gpt-4o" class="w-full rounded-xl border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Fields OpenAI -->
                <div x-show="provider === 'openai'" x-cloak class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5 space-y-4">
                    <h3 class="font-bold text-slate-800 text-sm">Pengaturan OpenAI</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">OpenAI API Key</label>
                        <input type="password" name="openai_api_key" value="" placeholder="{{ $globalSettings['openai_api_key_configured'] ? 'Tersimpan — isi hanya untuk mengganti' : 'sk-...' }}" autocomplete="new-password" class="w-full rounded-xl border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Model OpenAI</label>
                            <input type="text" name="openai_model" value="{{ $globalSettings['openai_model'] }}" placeholder="gpt-4o" class="w-full rounded-xl border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Model Role: Planner & Writer -->
                <div class="grid gap-4 sm:grid-cols-2 border-t border-slate-100 pt-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-1">Model Riset & Planner (Tahap 1)</label>
                        <input type="text" name="model_planner" value="{{ $globalSettings['model_planner'] }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                        <p class="mt-1 text-xs text-slate-500">Untuk riset keyword entitas, outline & landing structure.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-1">Model Penulis Artikel / Writer (Tahap 2)</label>
                        <input type="text" name="model_writer" value="{{ $globalSettings['model_writer'] }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                        <p class="mt-1 text-xs text-slate-500">Untuk menulis konten penuh dengan standar E-E-A-T.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-3">
                    <button class="rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Simpan Pengaturan AI</button>
                </div>
            </form>
        </div>

    <!-- TAB 2: NICHE, BRAND VOICE, & DATA BISNIS -->
    @elseif(request('tab') === 'brand_settings')
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="mb-6 border-b border-slate-100 pb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Pengaturan Terpusat</span>
                <h2 class="mt-1 text-2xl font-bold text-slate-900">Niche, Brand Voice, & Data Bisnis</h2>
                <p class="mt-1 text-sm text-slate-500">Informasi ini otomatis dipakai oleh semua campaign jasa / produk tanpa perlu diisi berulang-ulang.</p>
            </div>

            <form method="POST" action="{{ route('admin.content-campaigns.global_settings') }}" class="space-y-6">
                @csrf
                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-1">Niche / Industri Bisnis</label>
                        <input type="text" name="niche" value="{{ $globalSettings['niche'] }}" placeholder="Contoh: Jasa Pembuatan Website & SEO" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-1">Brand Voice / Gaya Bahasa</label>
                        <input type="text" name="brand_voice" value="{{ $globalSettings['brand_voice'] }}" placeholder="Otoritatif, Solutif, Informatif, Profesional" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-1">Target Audiens Utama</label>
                        <input type="text" name="audience" value="{{ $globalSettings['audience'] }}" placeholder="Pemilik Bisnis, UMKM, Calon Klien" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                    </div>
                </div>

                <!-- Basis Data Terkini Perusahaan -->
                <div class="rounded-2xl border border-blue-100 bg-blue-50/40 p-5 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">📊</span>
                        <h3 class="font-bold text-blue-900 text-sm">Bahan Pembuatan Konten & Basis Data Bisnis Terkini</h3>
                    </div>
                    <p class="text-xs leading-relaxed text-blue-800">
                        Masukkan data faktual layanan Anda (keunggulan jasa, paket harga, garansi, jangkauan kota, nomor kontak WA, dsb). <strong>AI akan otomatis memprioritaskan data ini saat meriset topik dan menyusun konten baru</strong>.
                    </p>
                    <textarea name="business_data" rows="6" placeholder="Contoh:&#10;- Layanan: Jasa Pembuatan Website Company Profile, Toko Online, dan Landing Page SEO.&#10;- Keunggulan: Loading ultra cepat, mobile friendly, include domain .com & hosting SSD.&#10;- Kontak WhatsApp resmi: 0812xxxxxx (Konsultasi Gratis)&#10;- Rute layanan: Solo, Jogja, Semarang, dan seluruh Indonesia." class="w-full rounded-xl border border-slate-200 bg-white p-3.5 text-sm shadow-sm outline-none focus:border-indigo-500 font-mono text-xs">{{ $globalSettings['business_data'] }}</textarea>
                </div>

                <!-- Default CTA -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5 space-y-4">
                    <h3 class="font-bold text-slate-800 text-sm">🎯 Target Call To Action (CTA) Bawaan</h3>
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe CTA</label>
                            <select name="cta_type" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm shadow-sm outline-none focus:border-indigo-500">
                                <option value="whatsapp" @selected($globalSettings['cta_type'] === 'whatsapp')>WhatsApp Direct</option>
                                <option value="link" @selected($globalSettings['cta_type'] === 'link')>Custom Link / Halaman Landing</option>
                                <option value="none" @selected($globalSettings['cta_type'] === 'none')>Tanpa CTA</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Teks Tombol CTA</label>
                            <input type="text" name="cta_label" value="{{ $globalSettings['cta_label'] }}" placeholder="Konsultasi via WhatsApp" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm shadow-sm outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Target Nomor WA / URL Link</label>
                            <input type="text" name="cta_url" value="{{ $globalSettings['cta_url'] }}" placeholder="628123456789 atau https://domain.com/kontak" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm shadow-sm outline-none focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Range Kata -->
                <div class="grid gap-4 sm:grid-cols-2 border-t border-slate-100 pt-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-1">Minimal Kata per Artikel</label>
                        <input type="number" name="word_count_min" value="{{ $globalSettings['word_count_min'] }}" min="300" max="5000" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-1">Maksimal Kata per Artikel</label>
                        <input type="number" name="word_count_max" value="{{ $globalSettings['word_count_max'] }}" min="400" max="8000" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm shadow-sm outline-none focus:border-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end pt-3">
                    <button class="rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Simpan Brand & Konteks</button>
                </div>
            </form>
        </div>

    <!-- TAB 3: TEMPLATE PROMPT SEO & E-E-A-T & TIM EDITORIAL -->
    @elseif(request('tab') === 'prompt_settings')
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="mb-6 border-b border-slate-100 pb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Pengaturan Terpusat</span>
                <h2 class="mt-1 text-2xl font-bold text-slate-900">Template Prompt SEO & Tim Editorial AI</h2>
                <p class="mt-1 text-sm text-slate-500">Formula prompt bawaan bebas batas karakter kaku, dilengkapi fitur Tim Editorial AI otomatis yang membaca seluruh artikel, menyempurnakan tulisan kaku, dan memperbarui tanggal revisi.</p>
            </div>

            <form method="POST" action="{{ route('admin.content-campaigns.global_settings') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1.5">Template Prompt Riset Kata Kunci Entitas (Entity SEO)</label>
                    <textarea name="keyword_prompt" rows="4" class="w-full rounded-xl border border-slate-200 p-3.5 text-sm shadow-sm outline-none focus:border-indigo-500 font-mono text-xs leading-relaxed">{{ $globalSettings['keyword_prompt'] }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Menginstruksikan AI menemukan keyword utama, sinonim, dan intent audiens (Maksimal 50.000 karakter).</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-1.5">Template Prompt Pengembangan Konten Utama & Gaya Alami</label>
                    <textarea name="content_prompt" rows="7" class="w-full rounded-xl border border-slate-200 p-3.5 text-sm shadow-sm outline-none focus:border-indigo-500 font-mono text-xs leading-relaxed">{{ $globalSettings['content_prompt'] }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Melarang klise robotik ("Di era digital", "Kesimpulan"), menghapus garis (---), dan mewajibkan transisi manusiawi (Maksimal 50.000 karakter).</p>
                </div>

                <!-- Modul Tim Editorial AI -->
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🧑‍💼</span>
                            <div>
                                <h3 class="font-bold text-emerald-950 text-base">Tim Editorial AI (Review & Poles Konten Pasca-Terbit)</h3>
                                <p class="text-xs text-emerald-800 mt-0.5">Membaca seluruh draft artikel yang sudah dibuat, mendeteksi keyword-nya, menyunting bagian yang kaku, lalu memperbarui artikel dengan tanggal revisi terakhir.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="editorial_enabled" value="1" @checked($globalSettings['editorial_enabled']) class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-emerald-950 mb-1.5">Instruksi Khusus untuk Tim Editorial AI</label>
                        <textarea name="editorial_prompt" rows="4" class="w-full rounded-xl border border-emerald-200 bg-white p-3.5 text-sm shadow-sm outline-none focus:border-emerald-500 font-mono text-xs leading-relaxed" placeholder="Masukkan arahan tim editorial...">{{ $globalSettings['editorial_prompt'] }}</textarea>
                        <p class="mt-1 text-xs text-emerald-700">Algoritma otomatis: Menjaga semua tautan markdown/HTML asli, menghaluskan flow kalimat, dan menyetel <code>revised_at</code> (+15 s/d 45 menit). Riwayat publikasi lama dihilangkan, hanya tanggal terakhir yang aktif di sitemap & Schema Google.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-3">
                    <button class="rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Simpan Template Prompt & Editorial</button>
                </div>
            </form>
        </div>

    <!-- TAB DEFAULT: LIST CAMPAIGN & BUAT CAMPAIGN BARU (SUPER RINGKAS) -->
    @else
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-slate-500">Buat campaign target keyword landing page, lalu scheduler AI otomatis memproduksi artikel pendukungnya.</p>
            </div>
            <button type="button" @click="showCreate = true" class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                ➕ Buat Campaign Baru
            </button>
        </div>

        <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr><th class="px-5 py-3.5">Campaign & Keyword Target</th><th class="px-5 py-3.5">Status</th><th class="px-5 py-3.5">Topik / Artikel</th><th class="px-5 py-3.5 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($campaigns as $campaign)
                    <tr>
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-900">{{ $campaign->name }}</div>
                            <div class="mt-1 flex flex-wrap gap-1 text-xs text-slate-500">
                                <span class="rounded bg-indigo-50 px-2 py-0.5 font-medium text-indigo-700">{{ $campaign->posts_per_day }} artikel/hari</span>
                                <span class="rounded bg-slate-100 px-2 py-0.5 font-medium">{{ strtoupper($campaign->funnel_mode) }}</span>
                                @if($campaign->product)
                                    <span class="rounded bg-emerald-50 px-2 py-0.5 font-medium text-emerald-700">Produk: {{ $campaign->product->name }}</span>
                                @endif
                            </div>
                            <div class="mt-1.5 text-xs text-slate-600">
                                Target: {{ $campaign->targets->pluck('keyword')->take(3)->implode(', ') }}
                                @if($campaign->targets->count() > 3) <span class="text-slate-400">+{{ $campaign->targets->count() - 3 }} lainnya</span> @endif
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $campaign->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-xs font-semibold text-slate-700">
                            <span class="font-medium text-slate-700">{{ $campaign->topics_count }} Topik Bank</span>
                            <span class="text-slate-300">•</span>
                            <span class="font-semibold text-emerald-700">{{ $campaign->published_contents_count }} Terbit</span>
                            @if(($campaign->draft_contents_count ?? 0) > 0 || ($campaign->processing_contents_count ?? 0) > 0)
                                <span class="text-slate-300">•</span>
                                <span class="text-xs text-amber-700 font-medium">
                                    {{ $campaign->draft_contents_count }} Draft
                                    @if(($campaign->processing_contents_count ?? 0) > 0)
                                        ({{ $campaign->processing_contents_count }} proses AI)
                                    @endif
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('admin.content-campaigns.trigger', $campaign) }}" onsubmit="return confirm('Jalankan trigger posting artikel sekarang?')">@csrf <button class="rounded-xl border border-emerald-300 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-100 flex items-center gap-1">⚡ Posting Sekarang</button></form>
                                <form method="POST" action="{{ route('admin.content-campaigns.research', $campaign) }}">@csrf <input type="hidden" name="count" value="{{ $campaign->research_batch_size }}"><button class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Riset Topik</button></form>
                                <a href="{{ route('admin.content-campaigns.topics', $campaign) }}" class="rounded-xl border border-indigo-200 bg-indigo-50/50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">Topic Bank ({{ $campaign->topics_count }})</a>
                                <form method="POST" action="{{ route('admin.content-campaigns.toggle', $campaign) }}">@csrf <button class="rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">{{ $campaign->status === 'active' ? 'Jeda' : 'Aktifkan' }}</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-12 text-center text-slate-500">Belum ada campaign. Klik “Buat Campaign Baru” untuk memulai.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $campaigns->links() }}

        <!-- MODAL CAMPAIGN BARU (RINGKAS: CUKUP TARGET KEYWORD & JADWAL) -->
        <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 p-4" @keydown.escape.window="showCreate = false">
            <div class="mx-auto max-w-2xl rounded-3xl bg-white p-6 shadow-2xl sm:p-8" @click.outside="showCreate = false">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Auto Post Content</span>
                        <h2 class="mt-1 text-2xl font-bold text-slate-900">Buat Campaign Baru</h2>
                    <p class="mt-1 text-xs text-slate-500">Nama hanya label campaign. Konten ditentukan oleh target keyword dan URL tujuan opsional.</p>
                    </div>
                    <button type="button" @click="showCreate = false" class="rounded-lg p-2 text-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.content-campaigns.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Campaign <span class="text-red-500">*</span></label>
                        <input name="name" required maxlength="120" placeholder="Contoh: Campaign SEO Website Solo" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                        <p class="mt-1 text-xs text-slate-500">Nama ini hanya label administrasi, bukan sumber topik atau tujuan konten.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Target Keyword <span class="font-normal text-xs text-slate-400">(Opsional, satu per baris atau koma)</span></label>
                        <textarea name="target_keywords" maxlength="5000" rows="3" placeholder="jasa pembuatan website solo&#10;web developer solo&#10;bikin landing page solo" class="w-full rounded-xl border border-slate-200 bg-slate-50 p-3.5 text-sm text-slate-900 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 font-mono text-xs"></textarea>
                        <p class="mt-1 text-xs text-slate-500">Jika kosong, AI meriset topik dari niche, produk, audiens, dan data bisnis. Nama campaign tidak dipakai sebagai topik.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">URL Tujuan Artikel <span class="font-normal text-xs text-slate-400">(Opsional)</span></label>
                        <input type="url" name="destination_url" maxlength="2048" placeholder="https://juki.eu.org/jasa-pembuatan-website-solo" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                        <p class="mt-1 text-xs text-slate-500">Jika diisi, artikel dapat menautkan URL ini dengan anchor berdasarkan keyword. Jika kosong, tidak ada URL tujuan campaign dan landing page tidak dibuat otomatis.</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Produk Terkait <span class="font-normal text-xs text-slate-400">(Opsional)</span></label>
                            <select name="product_id" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="">Tanpa Produk / Bisnis Jasa</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Silo / Kategori <span class="font-normal text-xs text-slate-400">(Opsional)</span></label>
                            <select name="silo_blueprint_id" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="">Tanpa Silo</option>
                                @foreach($silos as $silo)
                                    <option value="{{ $silo->id }}">{{ $silo->silo_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Artikel per Hari</label>
                            <input type="number" name="posts_per_day" value="1" min="1" max="24" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Jadwal Jam</label>
                            <select name="schedule_type" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100">
                                <option value="random">Acak Natural</option>
                                <option value="fixed">Jam Tetap</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="language" value="id">
                    <input type="hidden" name="funnel_mode" value="balanced">
                    <input type="hidden" name="research_batch_size" value="5">
                    <input type="hidden" name="publish_status" value="published">
                    <input type="hidden" name="timezone" value="Asia/Jakarta">
                    <input type="hidden" name="auto_research" value="1">

                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" @click="showCreate = false" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
                        <button class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Simpan Campaign</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
