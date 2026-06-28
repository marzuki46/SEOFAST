@extends('layouts.admin')

@section('title', 'AI & App Settings - SEOFAST')
@section('page_title', 'AI & Application Settings')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold font-outfit text-slate-900 tracking-tight">Platform Settings</h1>
        <p class="text-sm text-slate-500 mt-1">Konfigurasi AI provider, API keys, dan integrasi utama.</p>
    </div>
    @if(auth()->user()->role === 'admin')
    <div>
        <a href="{{ route('admin.settings.system') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            System Settings
        </a>
    </div>
    @endif
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ activeTab: 'pipeline' }">
    <!-- Tabs Header -->
    <div class="border-b border-slate-200 bg-slate-50/50 flex overflow-x-auto no-scrollbar">
        @php
            $tabs = [
                'pipeline' => 'AI Pipeline',
                'api_keys' => 'API Keys',
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
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <!-- AI Pipeline Tab -->
            <div x-show="activeTab === 'pipeline'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <div class="space-y-6 max-w-4xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Default AI Provider</label>
                            <select name="ai_provider" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                @foreach(['openai', 'gemini', 'claude', 'openrouter', 'custom'] as $provider)
                                    <option value="{{ $provider }}" {{ ($settings['ai_provider'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint (OpenAI Compatible)' : ucfirst($provider) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 1: Keyword/Silo Architecture</label>
                            <select name="ai_provider_keyword" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                @foreach(['openai', 'gemini', 'claude', 'openrouter', 'custom'] as $provider)
                                    <option value="{{ $provider }}" {{ ($settings['ai_provider_keyword'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 1 (Content): Header & Subheading</label>
                            <select name="ai_provider_1" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                @foreach(['openai', 'gemini', 'claude', 'openrouter', 'custom'] as $provider)
                                    <option value="{{ $provider }}" {{ ($settings['ai_provider_1'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 2: Full Body Content</label>
                            <select name="ai_provider_2" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                @foreach(['openai', 'gemini', 'claude', 'openrouter', 'custom'] as $provider)
                                    <option value="{{ $provider }}" {{ ($settings['ai_provider_2'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 3: Markdown to HTML</label>
                            <select name="ai_provider_3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                @foreach(['openai', 'gemini', 'claude', 'openrouter', 'custom'] as $provider)
                                    <option value="{{ $provider }}" {{ ($settings['ai_provider_3'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Phase 4: Schema & Meta SEO</label>
                            <select name="ai_provider_4" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                @foreach(['openai', 'gemini', 'claude', 'openrouter', 'custom'] as $provider)
                                    <option value="{{ $provider }}" {{ ($settings['ai_provider_4'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Image Prompt Generator</label>
                            <select name="ai_provider_image_prompt" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                @foreach(['openai', 'gemini', 'claude', 'openrouter', 'custom'] as $provider)
                                    <option value="{{ $provider }}" {{ ($settings['ai_provider_image_prompt'] ?? '') == $provider ? 'selected' : '' }}>{{ $provider === 'custom' ? 'Custom Endpoint' : ucfirst($provider) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Keys Tab -->
            <div x-show="activeTab === 'api_keys'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <div class="space-y-6 max-w-4xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">OpenAI API Key</label>
                            <input type="password" name="openai_api_key" value="{{ $settings['openai_api_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="sk-...">
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Google Gemini API Key</label>
                            <input type="password" name="gemini_api_key" value="{{ $settings['gemini_api_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="AIza...">
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Anthropic Claude API Key</label>
                            <input type="password" name="claude_api_key" value="{{ $settings['claude_api_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="sk-ant-...">
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">OpenRouter API Key</label>
                            <input type="password" name="openrouter_api_key" value="{{ $settings['openrouter_api_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="sk-or-...">
                        </div>
                        <div class="md:col-span-2 border-t border-slate-200 pt-4 mt-2">
                            <h4 class="text-sm font-bold text-slate-700 mb-3">Custom Endpoint Configuration (Local LLM / LM Studio / Ollama)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Custom API Base URL</label>
                                    <input type="text" name="custom_api_base" value="{{ $settings['custom_api_base'] ?? 'http://localhost:20128/v1' }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-3 py-2" placeholder="http://localhost:20128/v1">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Custom API Key (Optional)</label>
                                    <input type="password" name="custom_api_key" value="{{ $settings['custom_api_key'] ?? '' }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-3 py-2" placeholder="kosongkan jika tidak ada">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Custom Model Name</label>
                                    <input type="text" name="custom_model" value="{{ $settings['custom_model'] ?? 'custom-model' }}" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-3 py-2" placeholder="contoh: llama-3-8b">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Tab -->
            <div x-show="activeTab === 'payment'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <div class="space-y-6 max-w-4xl">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Midtrans Environment</label>
                            <select name="midtrans_is_production" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2">
                                <option value="false" {{ ($settings['midtrans_is_production'] ?? false) == false ? 'selected' : '' }}>Sandbox (Testing)</option>
                                <option value="true" {{ ($settings['midtrans_is_production'] ?? false) == true ? 'selected' : '' }}>Production (Live)</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-base font-semibold text-slate-800 mb-1.5">Midtrans Merchant ID</label>
                                <input type="text" name="midtrans_merchant_id" value="{{ $settings['midtrans_merchant_id'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="G...">
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-slate-800 mb-1.5">Midtrans Client Key</label>
                                <input type="text" name="midtrans_client_key" value="{{ $settings['midtrans_client_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="SB-Mid-client-...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-1.5">Midtrans Server Key</label>
                            <input type="password" name="midtrans_server_key" value="{{ $settings['midtrans_server_key'] ?? '' }}" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-brand-indigo focus:ring-brand-indigo text-sm px-4 py-2" placeholder="SB-Mid-server-...">
                            <p class="text-xs text-slate-500 mt-1">Gunakan Server Key untuk menerima pembayaran produk digital di website Anda secara otomatis.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200 mt-8 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-brand-indigo text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-indigo">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
