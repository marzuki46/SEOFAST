@extends('layouts.admin')

@section('header', 'Advanced SEO Settings')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">Enterprise SEO Settings</h1>
        <p class="text-sm text-slate-500 mt-1">Konfigurasi pusat untuk semua parameter SEO, Schema Markup, dan AI Pipeline SEOFAST.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ activeTab: '{{ session('active_tab', 'global') }}' }">
    <!-- Tabs Header -->
    <div class="border-b border-slate-200 bg-slate-50/50 flex overflow-x-auto no-scrollbar">
        @php
            $tabs = [
                'global' => 'Global SEO',
                'multilingual' => 'Multilingual (Bilingual)',
                'ai_prompt' => 'AI Prompt Settings',
                'schema' => 'Schema Markup',
                'indexing' => 'Indexing & Crawler',
                'redirect' => 'Redirects',
                'advanced' => 'Advanced Tools',
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
                <form action="{{ route('admin.seo.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="group" value="seo_{{ $key }}">
                    
                    <div class="space-y-6 max-w-3xl">
                        @if($key === 'global')
                            {{-- SITE IDENTITY --}}
                            <div class="border-b border-slate-100 pb-6">
                                <h4 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2"><span class="text-brand-indigo">①</span> Site Identity</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Website Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="site_name" value="{{ $settings['seo_global']['site_name'] ?? \App\Models\SystemSetting::get('site_name', config('app.name')) }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="SEOFAST">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Alternate Site Name</label>
                                        <input type="text" name="site_alt_name" value="{{ $settings['seo_global']['site_alt_name'] ?? \App\Models\SystemSetting::get('site_alt_name') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="SEO Operating System">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tagline</label>
                                        <input type="text" name="site_tagline" value="{{ $settings['seo_global']['site_tagline'] ?? \App\Models\SystemSetting::get('site_tagline') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="Platform AI Content SEO Terbaik">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Default Title Separator</label>
                                        <select name="title_separator" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo text-sm px-4 py-2.5">
                                            @foreach(['|' => 'Pipa |', '-' => 'Dash -', '•' => 'Bullet •', '»' => 'Arrow »', ':' => 'Colon :'] as $val => $lbl)
                                                <option value="{{ $val }}" {{ ($settings['seo_global']['title_separator'] ?? \App\Models\SystemSetting::get('title_separator', '|')) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Default Language</label>
                                        <input type="text" name="site_language" value="{{ $settings['seo_global']['site_language'] ?? \App\Models\SystemSetting::get('site_language', 'id-ID') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="id-ID">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Country Targeting</label>
                                        <input type="text" name="site_country" value="{{ $settings['seo_global']['site_country'] ?? \App\Models\SystemSetting::get('site_country', 'ID') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="ID">
                                    </div>
                                </div>
                            </div>

                            {{-- TITLE TEMPLATE SYSTEM --}}
                            <div class="border-b border-slate-100 pb-6">
                                <h4 class="text-base font-bold text-slate-900 mb-2 flex items-center gap-2"><span class="text-brand-indigo">②</span> Dynamic Title Templates</h4>
                                <p class="text-xs text-slate-500 mb-4">Gunakan variabel: <code class="bg-slate-100 px-1 rounded">{site_name}</code> <code class="bg-slate-100 px-1 rounded">{page_title}</code> <code class="bg-slate-100 px-1 rounded">{category_name}</code> <code class="bg-slate-100 px-1 rounded">{year}</code> <code class="bg-slate-100 px-1 rounded">{separator}</code></p>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Template Title Artikel</label>
                                        <input type="text" name="title_template_post" value="{{ $settings['seo_global']['title_template_post'] ?? \App\Models\SystemSetting::get('title_template_post', '{page_title} {separator} {site_name}') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5 font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Template Title Kategori</label>
                                        <input type="text" name="title_template_category" value="{{ $settings['seo_global']['title_template_category'] ?? \App\Models\SystemSetting::get('title_template_category', '{category_name} Articles {separator} {site_name}') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5 font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Template Title Halaman Statis</label>
                                        <input type="text" name="title_template_page" value="{{ $settings['seo_global']['title_template_page'] ?? \App\Models\SystemSetting::get('title_template_page', '{page_title} {separator} {site_name}') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5 font-mono">
                                    </div>
                                </div>
                            </div>

                            {{-- HOMEPAGE SEO --}}
                            <div class="border-b border-slate-100 pb-6">
                                <h4 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2"><span class="text-brand-indigo">③</span> Homepage SEO</h4>
                                <div class="grid grid-cols-1 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Homepage Title</label>
                                        <input type="text" name="seo_global_meta_title" value="{{ $settings['seo_global']['seo_global_meta_title'] ?? \App\Models\SystemSetting::get('seo_global_meta_title') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="SEOFAST V3 — Platform SEO Terbaik Indonesia">
                                        <p class="text-xs text-slate-400 mt-1">Kosongkan untuk menggunakan template: <em>{site_name}</em></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Homepage H1 (Hero Headline)</label>
                                        <input type="text" name="homepage_h1" value="{{ $settings['seo_global']['homepage_h1'] ?? \App\Models\SystemSetting::get('homepage_h1') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="Platform SEO Terbaik di Indonesia">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Homepage Meta Description</label>
                                        <textarea name="seo_global_meta_description" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="Deskripsi homepage Anda (150-160 karakter)...">{{ $settings['seo_global']['seo_global_meta_description'] ?? \App\Models\SystemSetting::get('seo_global_meta_description') }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Homepage Meta Keywords <span class="text-slate-400 font-normal">(opsional)</span></label>
                                        <input type="text" name="homepage_meta_keywords" value="{{ $settings['seo_global']['homepage_meta_keywords'] ?? \App\Models\SystemSetting::get('homepage_meta_keywords') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="seo, cms, laravel, content marketing">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Homepage OG Image</label>
                                        <input type="url" name="seo_global_og_image" value="{{ $settings['seo_global']['seo_global_og_image'] ?? \App\Models\SystemSetting::get('seo_global_og_image') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="https://domain.com/assets/og-default.jpg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Homepage Canonical URL</label>
                                        <input type="url" name="homepage_canonical" value="{{ $settings['seo_global']['homepage_canonical'] ?? \App\Models\SystemSetting::get('homepage_canonical', url('/')) }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="{{ url('/') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- TRACKING IDs --}}
                            <div>
                                <h4 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2"><span class="text-brand-indigo">④</span> Tracking & Verification</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Google Analytics ID</label>
                                        <input type="text" name="seo_global_google_analytics_id" value="{{ $settings['seo_global']['seo_global_google_analytics_id'] ?? \App\Models\SystemSetting::get('seo_global_google_analytics_id') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="G-XXXXXXXXXX">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Google Tag Manager ID</label>
                                        <input type="text" name="seo_global_gtm_id" value="{{ $settings['seo_global']['seo_global_gtm_id'] ?? \App\Models\SystemSetting::get('seo_global_gtm_id') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="GTM-XXXXXXX">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Google Site Verification</label>
                                        <input type="text" name="seo_global_google_site_verification" value="{{ $settings['seo_global']['seo_global_google_site_verification'] ?? \App\Models\SystemSetting::get('seo_global_google_site_verification') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="verification token">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Facebook Pixel ID</label>
                                        <input type="text" name="seo_global_fb_pixel_id" value="{{ $settings['seo_global']['seo_global_fb_pixel_id'] ?? \App\Models\SystemSetting::get('seo_global_fb_pixel_id') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="XXXXXXXXXXXXXX">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Bing Webmaster Verification</label>
                                        <input type="text" name="seo_indexing_bing_verification" value="{{ $settings['seo_global']['seo_indexing_bing_verification'] ?? \App\Models\SystemSetting::get('seo_indexing_bing_verification') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="Bing verification code">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Favicon URL</label>
                                        <input type="url" name="favicon_url" value="{{ $settings['seo_global']['favicon_url'] ?? \App\Models\SystemSetting::get('favicon_url') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2.5" placeholder="https://domain.com/favicon.ico">
                                    </div>
                                </div>
                            </div>
                            
                        @elseif($key === 'multilingual')
                            <div class="border-b border-slate-100 pb-6">
                                <h4 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2"><span class="text-brand-indigo">①</span> Bilingual Configuration</h4>
                                <div class="grid grid-cols-1 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Enable Auto-Translate to English</label>
                                        <select name="enable_auto_translate_en" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo text-sm px-4 py-2.5">
                                            <option value="1" {{ (\App\Models\SystemSetting::get('enable_auto_translate_en', '0') === '1') ? 'selected' : '' }}>Yes, automatically translate AI generated content</option>
                                            <option value="0" {{ (\App\Models\SystemSetting::get('enable_auto_translate_en', '0') === '0') ? 'selected' : '' }}>No, only use default language</option>
                                        </select>
                                        <p class="text-xs text-slate-500 mt-1">If enabled, Phase 6 of the AI generation process will translate all content (including metadata and slugs) to English.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Translation Prompt</label>
                                        <textarea name="ai_prompt_translation" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3" placeholder="You are a professional Translator...">{{ $settings['seo_multilingual']['ai_prompt_translation'] ?? \App\Models\SystemSetting::get('ai_prompt_translation', 'You are a professional Translator. Translate the following Indonesian text to English. Maintain the exact same formatting, markdown syntax, and tone. CRITICAL RULE: DO NOT translate any URLs inside href attributes or markdown links. Leave the URLs exactly as they are.') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                        @elseif($key === 'ai_prompt')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Meta Title Prompt Template</label>
                                    <textarea name="ai_prompt_meta_title" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3" placeholder="Generate a highly click-worthy SEO Title...">{{ $settings['seo_ai_prompt']['ai_prompt_meta_title'] ?? \App\Models\SystemSetting::get('ai_prompt_meta_title', 'Generate a highly click-worthy SEO Title for the keyword "{keyword}". Maximum 60 characters. Return ONLY the title text, nothing else.') }}</textarea>
                                    <p class="text-xs text-slate-500 mt-1">Variables allowed: <code class="bg-slate-100 px-1 rounded">{keyword}</code>, <code class="bg-slate-100 px-1 rounded">{content_title}</code></p>
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Meta Description Prompt Template</label>
                                    <textarea name="ai_prompt_meta_description" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3" placeholder="Generate an engaging SEO Meta Description...">{{ $settings['seo_ai_prompt']['ai_prompt_meta_description'] ?? \App\Models\SystemSetting::get('ai_prompt_meta_description', 'Generate an engaging SEO Meta Description for the keyword "{keyword}". Must be between 150-160 characters. Include a call to action. Return ONLY the description text.') }}</textarea>
                                </div>
                                <div class="border-t border-slate-200 pt-6">
                                    <h4 class="text-lg font-bold mb-4">Phase 1: Draft Generation</h4>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">System Prompt</label>
                                    <textarea name="ai_prompt_phase1_sys" rows="3" class="mt-1 mb-4 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3">{{ $settings['seo_ai_prompt']['ai_prompt_phase1_sys'] ?? \App\Models\SystemSetting::get('ai_prompt_phase1_sys', "You are a professional SEO Content Writer. Write an initial draft for an article about the target keyword: '{keyword}' using seed keyword hints '{seed_keyword}' in language '{lang}' for country '{country}'. Format in clean Markdown with appropriate headers (H2, H3).") }}</textarea>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">User Prompt</label>
                                    <textarea name="ai_prompt_phase1_user" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3">{{ $settings['seo_ai_prompt']['ai_prompt_phase1_user'] ?? \App\Models\SystemSetting::get('ai_prompt_phase1_user', "Write a comprehensive 800-word draft about: {keyword}. Include an introduction, key concepts, and actionable tips.{image_context}") }}</textarea>
                                </div>
                                <div class="border-t border-slate-200 pt-6">
                                    <h4 class="text-lg font-bold mb-4">Phase 2: Editor Critique</h4>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">System Prompt</label>
                                    <textarea name="ai_prompt_phase2_sys" rows="3" class="mt-1 mb-4 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3">{{ $settings['seo_ai_prompt']['ai_prompt_phase2_sys'] ?? \App\Models\SystemSetting::get('ai_prompt_phase2_sys', "You are a senior SEO Editor. Critique the following content draft to identify structural gaps, missing topical depth, or readability improvements. You MUST return your findings ONLY in JSON format containing: {'cqi_score': integer (0-100), 'gaps': array, 'improvements': array}.") }}</textarea>
                                </div>
                                <div class="border-t border-slate-200 pt-6">
                                    <h4 class="text-lg font-bold mb-4">Phase 3: Content Expansion</h4>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">System Prompt</label>
                                    <textarea name="ai_prompt_phase3_sys" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3">{{ $settings['seo_ai_prompt']['ai_prompt_phase3_sys'] ?? \App\Models\SystemSetting::get('ai_prompt_phase3_sys', "You are an SEO Content Expander. Expand the draft by incorporating the following critique and improvements. Make the content richer, add bullet points, and structure the sections cleanly in Markdown.") }}</textarea>
                                </div>
                                <div class="border-t border-slate-200 pt-6">
                                    <h4 class="text-lg font-bold mb-4">Phase 4: HTML Conversion</h4>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">System Prompt</label>
                                    <textarea name="ai_prompt_phase4_sys" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3">{{ $settings['seo_ai_prompt']['ai_prompt_phase4_sys'] ?? \App\Models\SystemSetting::get('ai_prompt_phase4_sys', "You are a Master HTML and Web Layout Editor. Convert the following markdown text into clean, structured, semantic HTML (containing <h2>, <h3>, <p>, <ul>, <li> tags). Do not return markdown, html wrapper body, or head tags. Just the raw inner content HTML.") }}</textarea>
                                </div>
                            </div>
                        @elseif($key === 'schema')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Default Schema Type</label>
                                    <select name="seo_schema_default_type" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3">
                                        <option value="Article" {{ ($settings['seo_schema']['seo_schema_default_type'] ?? '') === 'Article' ? 'selected' : '' }}>Article / BlogPosting</option>
                                        <option value="LocalBusiness" {{ ($settings['seo_schema']['seo_schema_default_type'] ?? '') === 'LocalBusiness' ? 'selected' : '' }}>Local Business</option>
                                        <option value="Organization" {{ ($settings['seo_schema']['seo_schema_default_type'] ?? '') === 'Organization' ? 'selected' : '' }}>Organization</option>
                                        <option value="Product" {{ ($settings['seo_schema']['seo_schema_default_type'] ?? '') === 'Product' ? 'selected' : '' }}>Product</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Organization / Local Business Name</label>
                                    <input type="text" name="seo_schema_org_name" value="{{ $settings['seo_schema']['seo_schema_org_name'] ?? \App\Models\SystemSetting::get('seo_schema_org_name') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3" placeholder="PT. Perusahaan Anda">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Organization Logo URL</label>
                                    <input type="url" name="seo_schema_org_logo" value="{{ $settings['seo_schema']['seo_schema_org_logo'] ?? \App\Models\SystemSetting::get('seo_schema_org_logo') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3" placeholder="https://domain.com/logo.png">
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Publisher / Author Name (Default)</label>
                                    <input type="text" name="seo_schema_author" value="{{ $settings['seo_schema']['seo_schema_author'] ?? \App\Models\SystemSetting::get('seo_schema_author') }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3" placeholder="Admin SEOFAST">
                                </div>
                            </div>
                        @elseif($key === 'indexing')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Global Robots Meta</label>
                                    <select name="seo_indexing_robots" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-base px-4 py-3">
                                        <option value="index, follow" {{ ($settings['seo_indexing']['seo_indexing_robots'] ?? '') === 'index, follow' ? 'selected' : '' }}>Index, Follow (Recommended)</option>
                                        <option value="noindex, nofollow" {{ ($settings['seo_indexing']['seo_indexing_robots'] ?? '') === 'noindex, nofollow' ? 'selected' : '' }}>Noindex, Nofollow</option>
                                    </select>
                                </div>
                                <div class="border-t border-slate-200 pt-6">
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Robots.txt Editor</label>
                                    @php
                                        $defaultRobots = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /login\nDisallow: /dashboard\nDisallow: /buyer/\nDisallow: /ghost/\n\nSitemap: " . url('/sitemap.xml') . "\n";
                                    @endphp
                                    <textarea name="robots_txt_content" rows="8" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm font-mono text-sm focus:border-brand-indigo focus:ring-brand-indigo px-4 py-3">{{ $settings['seo_indexing']['robots_txt_content'] ?? \App\Models\SystemSetting::get('robots_txt_content', $defaultRobots) }}</textarea>
                                    <p class="text-xs text-slate-500 mt-1">Kosongkan jika Anda ingin mengembalikan file <code class="bg-slate-100 px-1 rounded">robots.txt</code> ke versi default sistem.</p>
                                </div>
                            </div>
                        @elseif($key === 'redirect')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">301 Redirect Rules (JSON)</label>
                                    <textarea name="seo_redirect_rules" rows="6" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm font-mono text-sm focus:border-brand-indigo focus:ring-brand-indigo px-4 py-3" placeholder='{"/old-page": "/new-page"}'>{{ $settings['seo_redirect']['seo_redirect_rules'] ?? \App\Models\SystemSetting::get('seo_redirect_rules', '{}') }}</textarea>
                                    <p class="text-xs text-slate-500 mt-1">Format JSON key-value pair. Path awal menuju path tujuan.</p>
                                </div>
                            </div>
                        @elseif($key === 'advanced')
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Custom Headers in &lt;head&gt;</label>
                                    <textarea name="seo_advanced_head_code" rows="5" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm font-mono text-sm focus:border-brand-indigo focus:ring-brand-indigo px-4 py-3" placeholder="<script>...</script>">{{ $settings['seo_advanced']['seo_advanced_head_code'] ?? \App\Models\SystemSetting::get('seo_advanced_head_code') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-base font-semibold text-slate-800 mb-1.5">Custom Code before &lt;/body&gt;</label>
                                    <textarea name="seo_advanced_body_code" rows="5" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm font-mono text-sm focus:border-brand-indigo focus:ring-brand-indigo px-4 py-3" placeholder="<script>...</script>">{{ $settings['seo_advanced']['seo_advanced_body_code'] ?? \App\Models\SystemSetting::get('seo_advanced_body_code') }}</textarea>
                                </div>
                            </div>
                        @endif
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-indigo text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-indigo">
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
