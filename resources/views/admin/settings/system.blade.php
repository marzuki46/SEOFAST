@extends('layouts.admin')

@section('header', 'System Settings')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">System Settings</h1>
        <p class="text-sm text-slate-500 mt-1">Konfigurasi global untuk Super Admin (Berlaku untuk semua tenant).</p>
    </div>
    <div>
        <form action="{{ route('admin.settings.clear_cache') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Clear System Cache
            </button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ activeTab: '{{ session('active_tab', 'general') }}' }">
    <!-- Tabs Header -->
    <div class="border-b border-slate-200 bg-slate-50/50 flex overflow-x-auto no-scrollbar">
        @php
            $tabs = [
                'general' => 'General',
                'permalinks' => 'Permalinks / URL',
                'footer' => 'Footer Settings',
                'auth' => 'Auth & OAuth',
                'email' => 'Email SMTP',
                'storage' => 'Storage/S3',
                'payment' => 'Payment Gateway',
            ];
        @endphp

        @foreach($tabs as $key => $label)
            <button @click="activeTab = '{{ $key }}'" 
                    :class="{'border-brand-indigo text-brand-indigo bg-white': activeTab === '{{ $key }}', 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100': activeTab !== '{{ $key }}'}"
                    class="px-6 py-4 border-b-2 font-medium text-sm whitespace-nowrap transition-colors outline-none focus:outline-none">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Tabs Content -->
    <div class="p-6">
        @foreach($tabs as $key => $label)
            <div x-show="activeTab === '{{ $key }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="group" value="{{ $key }}">
                    
                    <div class="space-y-6 w-full">
                        @if($key === 'general')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Site Name (Brand)</label>
                                    <input type="text" name="site_name" value="{{ $settings['general']['site_name'] ?? 'SEOFAST' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="SEOFAST">
                                    <p class="text-xs text-slate-500 mt-1">Nama brand yang tampil di header, title tag, dan Open Graph.</p>
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Logo URL</label>
                                    <input type="url" name="logo_url" value="{{ $settings['general']['logo_url'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="https://domain.com/logo.png">
                                    <p class="text-xs text-slate-500 mt-1">URL gambar logo. Biarkan kosong untuk pakai teks brand.</p>
                                    @if(!empty($settings['general']['logo_url']))
                                    <img src="{{ $settings['general']['logo_url'] }}" alt="Preview Logo" class="mt-2 h-10 rounded-lg border border-slate-200 p-1">
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Logo Alt Text</label>
                                    <input type="text" name="logo_alt" value="{{ $settings['general']['logo_alt'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="Nama Brand Anda">
                                    <p class="text-xs text-slate-500 mt-1">Teks alternatif untuk logo (penting untuk SEO dan aksesibilitas).</p>
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">App URL</label>
                                    <input type="url" name="app_url" value="{{ $settings['general']['app_url'] ?? config('app.url') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Favicon / Icon Website URL</label>
                                    <input type="url" name="favicon_url" value="{{ $settings['general']['favicon_url'] ?? asset('favicon.ico') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="https://domain.com/favicon.ico">
                                    <p class="text-xs text-slate-500 mt-1">Masukkan URL gambar untuk icon tab browser (Favicon).</p>
                                </div>
                            </div>
                            <div class="border-t border-slate-200 pt-6 mt-6">
                                <h4 class="text-md font-bold text-slate-900 mb-4">Contact / Inquiry Settings</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Inquiry Email Tujuan</label>
                                        <input type="email" name="contact_inquiry_email" value="{{ $settings['general']['contact_inquiry_email'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="hello@domain.com">
                                        <p class="text-xs text-slate-500 mt-1">Email tujuan saat ada inquiry masuk dari form Contact Us.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Inquiry WhatsApp Number</label>
                                        <input type="text" name="contact_inquiry_whatsapp" value="{{ $settings['general']['contact_inquiry_whatsapp'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="6282213028718">
                                        <p class="text-xs text-slate-500 mt-1">Nomor WhatsApp (dengan kode negara, tanpa +) untuk menerima notifikasi inquiry.</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat (Contact Page)</label>
                                    <input type="text" name="contact_address" value="{{ $settings['general']['contact_address'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="Parangjoro, Grogol, Sukoharjo">
                                    <p class="text-xs text-slate-500 mt-1">Alamat yang ditampilkan di halaman Contact Us.</p>
                                </div>
                            </div>
                        @elseif($key === 'permalinks')
                            <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 mb-6 text-sm">
                                <strong>Penting:</strong> Setelah mengubah permalink, Anda mungkin perlu melakukan "Clear System Cache" agar rute baru terbaca oleh sistem. Jangan gunakan spasi atau karakter khusus.
                            </div>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Base URL Produk Digital</label>
                                    <div class="flex rounded-xl shadow-sm">
                                        <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 px-3 text-slate-500 text-base">{{ config('app.url') }}/</span>
                                        <input type="text" name="permalink_product" value="{{ $settings['permalinks']['permalink_product'] ?? 'produk' }}" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border-slate-300 focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="produk">
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">Contoh hasil: domain.com/produk/nama-produk</p>
                                </div>
                                <div x-data="{ permalinkType: '{{ ($settings['permalinks']['permalink_blog'] ?? 'blog') === '' ? 'post_name' : (($settings['permalinks']['permalink_blog'] ?? 'blog') === 'blog' ? 'default' : 'custom') }}' }">
                                    <label class="block text-base font-semibold text-slate-800 mb-2">Pola Permalink Artikel / Blog</label>
                                    
                                    <div class="space-y-3 mb-4">
                                        <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors" :class="permalinkType === 'default' ? 'border-brand-indigo bg-indigo-50/30' : 'border-slate-200 hover:bg-slate-50'">
                                            <input type="radio" x-model="permalinkType" value="default" class="w-4 h-4 text-brand-indigo bg-gray-100 border-gray-300 focus:ring-brand-indigo">
                                            <div class="ml-3">
                                                <span class="block text-sm font-medium text-slate-900">Default (Bawaan)</span>
                                                <span class="block text-xs text-slate-500 font-mono mt-0.5">{{ config('app.url') }}/blog/judul-artikel</span>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors" :class="permalinkType === 'post_name' ? 'border-brand-indigo bg-indigo-50/30' : 'border-slate-200 hover:bg-slate-50'">
                                            <input type="radio" x-model="permalinkType" value="post_name" class="w-4 h-4 text-brand-indigo bg-gray-100 border-gray-300 focus:ring-brand-indigo">
                                            <div class="ml-3">
                                                <span class="block text-sm font-medium text-slate-900">Post Name (Gaya WordPress)</span>
                                                <span class="block text-xs text-slate-500 font-mono mt-0.5">{{ config('app.url') }}/judul-artikel</span>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-3 border rounded-xl cursor-pointer transition-colors" :class="permalinkType === 'custom' ? 'border-brand-indigo bg-indigo-50/30' : 'border-slate-200 hover:bg-slate-50'">
                                            <input type="radio" x-model="permalinkType" value="custom" class="w-4 h-4 text-brand-indigo bg-gray-100 border-gray-300 focus:ring-brand-indigo">
                                            <div class="ml-3">
                                                <span class="block text-sm font-medium text-slate-900">Custom Prefix</span>
                                                <span class="block text-xs text-slate-500 mt-0.5">Tentukan awalan URL sendiri</span>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Actual Input Sent to Server -->
                                    <div x-show="permalinkType === 'custom'" class="flex rounded-xl shadow-sm" style="display: none;" x-transition>
                                        <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 px-3 text-slate-500 text-base">{{ config('app.url') }}/</span>
                                        <input type="text" name="permalink_blog_custom" value="{{ $settings['permalinks']['permalink_blog'] ?? 'blog' }}" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border-slate-300 focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="berita">
                                    </div>
                                    
                                    <!-- Hidden input to handle the final logic -->
                                    <input type="hidden" name="permalink_blog" :value="permalinkType === 'default' ? 'blog' : (permalinkType === 'post_name' ? '' : document.querySelector('input[name=permalink_blog_custom]').value)">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Base URL Project / Portofolio</label>
                                    <div class="flex rounded-xl shadow-sm">
                                        <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 px-3 text-slate-500 text-base">{{ config('app.url') }}/</span>
                                        <input type="text" name="permalink_project" value="{{ $settings['permalinks']['permalink_project'] ?? 'projeku' }}" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border-slate-300 focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="projeku">
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">Contoh hasil: domain.com/projeku/nama-project</p>
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Base URL Silo / Kategori</label>
                                    <div class="flex rounded-xl shadow-sm">
                                        <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 px-3 text-slate-500 text-base">{{ config('app.url') }}/</span>
                                        <input type="text" name="permalink_silo" value="{{ $settings['permalinks']['permalink_silo'] ?? 'kategori' }}" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border-slate-300 focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="kategori">
                                    </div>
                                </div>
                            </div>
                        @elseif($key === 'footer')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Footer Description</label>
                                    <textarea name="footer_description" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">{{ $settings['footer']['footer_description'] ?? 'The ultimate SEO Operating System for modern marketing. Zero manual refresh, zero soft failures, and seamless closed-loop Google Search Console synchronization.' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Footer Sub-text / Architecture Info</label>
                                    <input type="text" name="footer_subtext" value="{{ $settings['footer']['footer_subtext'] ?? 'System Architecture V3' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                
                                <div class="border-t border-slate-200 pt-6">
                                    <h4 class="text-md font-bold text-slate-900 mb-4">Column 1 Links</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 mb-1.5">Column 1 Title</label>
                                            <input type="text" name="footer_col1_title" value="{{ $settings['footer']['footer_col1_title'] ?? 'Platform' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 mb-1.5">Column 1 Links (Format: Link Text | URL per baris)</label>
                                            <textarea name="footer_col1_links" rows="5" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm font-mono px-4 py-2">{{ $settings['footer']['footer_col1_links'] ?? "Integrations|/\nAI Content Generator|/\nSilo Builder|/\nPricing Plans|/#pricing" }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-slate-200 pt-6">
                                    <h4 class="text-md font-bold text-slate-900 mb-4">Column 2 Links</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 mb-1.5">Column 2 Title</label>
                                            <input type="text" name="footer_col2_title" value="{{ $settings['footer']['footer_col2_title'] ?? 'Resources' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-800 mb-1.5">Column 2 Links (Format: Link Text | URL per baris)</label>
                                            <textarea name="footer_col2_links" rows="5" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm font-mono px-4 py-2">{{ $settings['footer']['footer_col2_links'] ?? "Blog Feed|/blog\nDocumentation|/\nChangelog|/\nSupport Center|/" }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($key === 'auth')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Google OAuth Client ID (Admin)</label>
                                    <input type="text" name="google_oauth_client_id" value="{{ $settings['auth']['google_oauth_client_id'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Google OAuth Secret</label>
                                    <input type="password" name="google_oauth_client_secret" value="{{ $settings['auth']['google_oauth_client_secret'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                            </div>
                            </div>
                        @elseif($key === 'email')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">SMTP Host</label>
                                    <input type="text" name="smtp_host" value="{{ $settings['email']['smtp_host'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="smtp.mailtrap.io">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">SMTP Port</label>
                                    <input type="text" name="smtp_port" value="{{ $settings['email']['smtp_port'] ?? '2525' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">SMTP Username</label>
                                    <input type="text" name="smtp_username" value="{{ $settings['email']['smtp_username'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">SMTP Password</label>
                                    <input type="password" name="smtp_password" value="{{ $settings['email']['smtp_password'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Mail From Address</label>
                                    <input type="email" name="mail_from_address" value="{{ $settings['email']['mail_from_address'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="noreply@domain.com">
                                </div>
                            </div>
                        @elseif($key === 'storage')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Storage Driver</label>
                                    <select name="storage_driver" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                        <option value="local" {{ ($settings['storage']['storage_driver'] ?? 'local') == 'local' ? 'selected' : '' }}>Local Server</option>
                                        <option value="s3" {{ ($settings['storage']['storage_driver'] ?? 'local') == 's3' ? 'selected' : '' }}>Amazon S3 / R2 / Wasabi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">S3 Bucket Name</label>
                                    <input type="text" name="s3_bucket" value="{{ $settings['storage']['s3_bucket'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">S3 Region</label>
                                    <input type="text" name="s3_region" value="{{ $settings['storage']['s3_region'] ?? 'us-east-1' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">S3 Access Key</label>
                                    <input type="text" name="s3_access_key" value="{{ $settings['storage']['s3_access_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">S3 Secret Key</label>
                                    <input type="password" name="s3_secret_key" value="{{ $settings['storage']['s3_secret_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">S3 Endpoint (Optional for Cloudflare R2 / Wasabi)</label>
                                    <input type="text" name="s3_endpoint" value="{{ $settings['storage']['s3_endpoint'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="https://...">
                                </div>
                            </div>
                        @elseif($key === 'payment')
                            <div class="space-y-6 max-w-4xl">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Midtrans Environment</label>
                                        <select name="midtrans_is_production" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                            <option value="false" {{ ($settings['payment']['midtrans_is_production'] ?? 'false') == 'false' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                            <option value="true" {{ ($settings['payment']['midtrans_is_production'] ?? 'false') == 'true' ? 'selected' : '' }}>Production (Live)</option>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Midtrans Merchant ID</label>
                                            <input type="text" name="midtrans_merchant_id" value="{{ $settings['payment']['midtrans_merchant_id'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="G...">
                                        </div>
                                        <div>
                                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Midtrans Client Key</label>
                                            <input type="text" name="midtrans_client_key" value="{{ $settings['payment']['midtrans_client_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="SB-Mid-client-...">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Midtrans Server Key</label>
                                        <input type="password" name="midtrans_server_key" value="{{ $settings['payment']['midtrans_server_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="SB-Mid-server-...">
                                        <p class="text-xs text-slate-500 mt-1">Gunakan Server Key untuk menerima pembayaran produk digital di website Anda secara otomatis.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-8 text-center text-slate-500 bg-slate-50 rounded-xl border border-slate-200 border-dashed">
                                Konfigurasi {{ $label }} tidak tersedia atau sudah dipindahkan.
                            </div>
                        @endif
                        
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-brand-indigo text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-indigo">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
