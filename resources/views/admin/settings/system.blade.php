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
                'seo' => 'SEO Settings',
                'email' => 'Email SMTP',
                'storage' => 'Storage/S3',
                'ai' => 'AI Pipeline & Keys',
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
                    
                    <div class="space-y-6 max-w-3xl">
                        @if($key === 'general')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">App Name</label>
                                    <input type="text" name="app_name" value="{{ $settings['general']['app_name'] ?? 'SEOFAST' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
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
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Base URL Artikel / Blog</label>
                                    <div class="flex rounded-xl shadow-sm">
                                        <span class="inline-flex items-center rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 px-3 text-slate-500 text-base">{{ config('app.url') }}/</span>
                                        <input type="text" name="permalink_blog" value="{{ $settings['permalinks']['permalink_blog'] ?? 'blog' }}" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border-slate-300 focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="blog">
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">Bisa dikosongkan jika ingin artikel langsung di root: domain.com/judul-artikel</p>
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Footer Logo Text</label>
                                        <input type="text" name="footer_logo_text" value="{{ $settings['footer']['footer_logo_text'] ?? 'SF' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Footer Brand Name</label>
                                        <input type="text" name="footer_brand_name" value="{{ $settings['footer']['footer_brand_name'] ?? 'SEOFAST' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                    </div>
                                </div>
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
                        @elseif($key === 'seo')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Default Meta Title</label>
                                    <input type="text" name="seo_meta_title" value="{{ $settings['seo']['seo_meta_title'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="SEOFAST - Super CMS">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Default Meta Description</label>
                                    <textarea name="seo_meta_description" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="Platform CMS terbaik untuk otomatisasi konten...">{{ $settings['seo']['seo_meta_description'] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Default Open Graph (OG) Image URL</label>
                                    <input type="url" name="seo_og_image" value="{{ $settings['seo']['seo_og_image'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="https://domain.com/assets/og-default.jpg">
                                    <p class="text-xs text-slate-500 mt-1">Gambar default jika postingan tidak memiliki thumbnail saat dibagikan ke media sosial.</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Google Analytics ID</label>
                                        <input type="text" name="google_analytics_id" value="{{ $settings['seo']['google_analytics_id'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="G-XXXXXXXXXX">
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Google Tag Manager ID</label>
                                        <input type="text" name="gtm_id" value="{{ $settings['seo']['gtm_id'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="GTM-XXXXXXX">
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Google Site Verification</label>
                                        <input type="text" name="google_site_verification" value="{{ $settings['seo']['google_site_verification'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="Token verifikasi...">
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Facebook Pixel ID</label>
                                        <input type="text" name="fb_pixel_id" value="{{ $settings['seo']['fb_pixel_id'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="XXXXXXXXXXXXXX">
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
                        @elseif($key === 'ai')
                            <div class="space-y-6 max-w-4xl">
                                <h3 class="text-lg font-bold border-b pb-2">AI Pipeline Configuration</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Default AI Provider</label>
                                        <select name="ai_provider" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                            @foreach(['openai', 'gemini', 'claude', 'custom'] as $provider)
                                                <option value="{{ $provider }}" {{ ($settings['ai']['ai_provider'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint (OpenAI Compatible)' : ucfirst($provider) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 1: Keyword/Silo</label>
                                        <select name="ai_provider_keyword" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                            @foreach(['openai', 'gemini', 'claude', 'custom'] as $provider)
                                                <option value="{{ $provider }}" {{ ($settings['ai']['ai_provider_keyword'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 1: Header & Subheading</label>
                                        <select name="ai_provider_1" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                            @foreach(['openai', 'gemini', 'claude', 'custom'] as $provider)
                                                <option value="{{ $provider }}" {{ ($settings['ai']['ai_provider_1'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 2: Full Body Content</label>
                                        <select name="ai_provider_2" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                            @foreach(['openai', 'gemini', 'claude', 'custom'] as $provider)
                                                <option value="{{ $provider }}" {{ ($settings['ai']['ai_provider_2'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 3: Markdown to HTML</label>
                                        <select name="ai_provider_3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                            @foreach(['openai', 'gemini', 'claude', 'custom'] as $provider)
                                                <option value="{{ $provider }}" {{ ($settings['ai']['ai_provider_3'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 4: Schema & Meta SEO</label>
                                        <select name="ai_provider_4" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                            @foreach(['openai', 'gemini', 'claude', 'custom'] as $provider)
                                                <option value="{{ $provider }}" {{ ($settings['ai']['ai_provider_4'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Image Prompt Generator</label>
                                        <select name="ai_provider_image_prompt" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                            @foreach(['openai', 'gemini', 'claude', 'custom'] as $provider)
                                                <option value="{{ $provider }}" {{ ($settings['ai']['ai_provider_image_prompt'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <h3 class="text-lg font-bold border-b pb-2 mt-8">AI Prompt Settings (SEO & Meta)</h3>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Meta Title Prompt Template</label>
                                        <textarea name="ai_prompt_meta_title" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="Generate a highly click-worthy SEO Title...">{{ $settings['ai']['ai_prompt_meta_title'] ?? 'Generate a highly click-worthy SEO Title for the keyword "{keyword}". Maximum 60 characters. Return ONLY the title text, nothing else.' }}</textarea>
                                        <p class="text-xs text-slate-500 mt-1">Variables allowed: <code class="bg-slate-100 px-1 rounded">{keyword}</code>, <code class="bg-slate-100 px-1 rounded">{content_title}</code></p>
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Meta Description Prompt Template</label>
                                        <textarea name="ai_prompt_meta_description" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="Generate an engaging SEO Meta Description...">{{ $settings['ai']['ai_prompt_meta_description'] ?? 'Generate an engaging SEO Meta Description for the keyword "{keyword}". Must be between 150-160 characters. Include a call to action. Return ONLY the description text.' }}</textarea>
                                        <p class="text-xs text-slate-500 mt-1">Variables allowed: <code class="bg-slate-100 px-1 rounded">{keyword}</code>, <code class="bg-slate-100 px-1 rounded">{content_title}</code></p>
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Article Content Prompt Template</label>
                                        <textarea name="ai_prompt_article_body" rows="4" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="Write a comprehensive SEO article...">{{ $settings['ai']['ai_prompt_article_body'] ?? 'Write a comprehensive, SEO-optimized article about "{keyword}". Use markdown formatting, H2 and H3 tags, lists, and bold text. Ensure the tone is professional yet engaging.' }}</textarea>
                                        <p class="text-xs text-slate-500 mt-1">Variables allowed: <code class="bg-slate-100 px-1 rounded">{keyword}</code>, <code class="bg-slate-100 px-1 rounded">{outline}</code></p>
                                    </div>
                                </div>
                                <h3 class="text-lg font-bold border-b pb-2 mt-8">API Keys & Custom Endpoints</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">OpenAI API Key</label>
                                        <input type="password" name="openai_api_key" value="{{ $settings['ai']['openai_api_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="sk-...">
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Google Gemini API Key</label>
                                        <input type="password" name="gemini_api_key" value="{{ $settings['ai']['gemini_api_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="AIza...">
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">Anthropic Claude API Key</label>
                                        <input type="password" name="claude_api_key" value="{{ $settings['ai']['claude_api_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="sk-ant-...">
                                    </div>
                                    <div>
                                        <label class="block text-base font-semibold text-slate-800 mb-1.5">9Router API Key</label>
                                        <input type="password" name="9router_api_key" value="{{ $settings['ai']['9router_api_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="sk-or-...">
                                    </div>
                                    <div class="md:col-span-2 border-t border-slate-100 pt-4 mt-2">
                                        <h4 class="text-sm font-bold text-slate-700 mb-3">Custom Endpoint Configuration (Local LLM / LM Studio / Ollama)</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 mb-1">Custom API Base URL</label>
                                                <input type="text" name="custom_api_base" value="{{ $settings['ai']['custom_api_base'] ?? 'http://localhost:20128/v1' }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-3 py-2" placeholder="http://localhost:20128/v1">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 mb-1">Custom API Key (Optional)</label>
                                                <input type="password" name="custom_api_key" value="{{ $settings['ai']['custom_api_key'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-3 py-2" placeholder="kosongkan jika tidak ada">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 mb-1">Custom Model Name</label>
                                                <input type="text" name="custom_model" value="{{ $settings['ai']['custom_model'] ?? 'custom-model' }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-3 py-2" placeholder="contoh: llama-3-8b">
                                            </div>
                                        </div>
                                    </div>
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
