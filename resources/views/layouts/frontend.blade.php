<!DOCTYPE html>
<html lang="{{ \App\Models\SystemSetting::get('site_language', str_replace('_', '-', app()->getLocale())) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        use App\Services\SeoHelper;
        use App\Models\SystemSetting;

        // SSR: resolve title - child views may override via @section('title')
        $seoTitle       = trim($__env->yieldContent('title'));
        $seoDescription = trim($__env->yieldContent('meta_description'));
        $seoCanonical   = trim($__env->yieldContent('canonical_url', request()->url()));
        $seoOgImage     = trim($__env->yieldContent('og_image', SystemSetting::get('seo_global_og_image', asset('assets/og-default.jpg'))));
        $seoRobots      = trim($__env->yieldContent('robots_meta', SystemSetting::get('seo_indexing_robots', 'index, follow')));

        if (!$seoTitle) {
            $seoTitle = SeoHelper::homepageTitle();
        }

        $siteName    = SystemSetting::get('site_name', config('app.name', 'SEOFAST'));
        $siteVerif   = SystemSetting::get('seo_global_google_site_verification');
        $bingVerif   = SystemSetting::get('seo_indexing_bing_verification');
        $keywords    = SystemSetting::get('homepage_meta_keywords');
        $faviconUrl  = SystemSetting::get('favicon_url', asset('favicon.ico'));
    @endphp

    <!-- SEO Meta Tags (SSR) -->
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription ?: SystemSetting::get('seo_global_meta_description', '') }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    @if($keywords)
    <meta name="keywords" content="{{ $keywords }}">
    @endif

    <!-- Search Engine Verification (SSR) -->
    @if($siteVerif)
    <meta name="google-site-verification" content="{{ $siteVerif }}">
    @endif
    @if($bingVerif)
    <meta name="msvalidate.01" content="{{ $bingVerif }}">
    @endif

    <!-- Open Graph / Facebook (SSR) -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="{{ trim($__env->yieldContent('og_title', $seoTitle)) }}">
    <meta property="og:description" content="{{ trim($__env->yieldContent('og_description', $seoDescription ?: SystemSetting::get('seo_global_meta_description', ''))) }}">
    <meta property="og:image" content="{{ $seoOgImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter Card (SSR) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="{{ trim($__env->yieldContent('og_title', $seoTitle)) }}">
    <meta name="twitter:description" content="{{ trim($__env->yieldContent('og_description', $seoDescription ?: SystemSetting::get('seo_global_meta_description', ''))) }}">
    <meta name="twitter:image" content="{{ $seoOgImage }}">

    <!-- Favicon (SSR) -->
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Compiled CSS & JS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* slate-50 */
            color: #334155; /* slate-700 */
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(15, 23, 42, 0.06);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02);
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }

        /* Ambient Glow Effects (Calmed down for light bg) */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.05;
            pointer-events: none;
            z-index: 0;
        }

        /* Keyframes and Transitions */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @yield('styles')

    <!-- Schema Markup (SSR) -->
    @yield('schema_markup')

    <!-- Google Analytics / GTM / FB Pixel (SSR, no JS required for crawlers) -->
    {!! \App\Services\SeoHelper::trackingHeadScripts() !!}

    <!-- Advanced SEO Head Code (custom per-site) -->
    {!! \App\Models\SystemSetting::get('seo_advanced_head_code') !!}
</head>
<body class="min-h-screen relative overflow-x-hidden antialiased">
    <!-- GTM noscript body open -->
    {!! \App\Services\SeoHelper::trackingBodyStart() !!}
    <!-- Background Glow Orbs -->
    <div class="glow-orb w-96 h-96 bg-brand-purple top-10 left-10"></div>
    <div class="glow-orb w-[500px] h-[500px] bg-brand-blue bottom-20 right-10"></div>
    
    <!-- Navbar -->
    <header class="sticky top-0 z-50 glass-nav">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-12">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-indigo to-brand-purple flex items-center justify-center font-outfit font-extrabold text-white text-xl shadow-lg shadow-brand-indigo/10 group-hover:scale-105 transition-transform">
                        SF
                    </div>
                    <span class="font-outfit font-extrabold text-2xl tracking-tight text-slate-900">
                        SEO<span class="bg-gradient-to-r from-brand-indigo to-brand-purple bg-clip-text text-transparent">FAST</span>
                    </span>
                </a>
                
                <div class="hidden md:flex items-center gap-8 font-medium text-sm text-slate-600">
                    @php
                        $primaryMenu = \App\Models\Menu::where('location', 'primary')->with(['items' => function($q) {
                            $q->whereNull('parent_id')->with('children')->orderBy('order');
                        }])->first();
                    @endphp
                    @if($primaryMenu && $primaryMenu->items->isNotEmpty())
                        @foreach($primaryMenu->items as $item)
                            @if($item->children->count() > 0)
                                <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                    <button class="flex items-center gap-1 hover:text-slate-900 transition-colors {{ request()->is(ltrim($item->url, '/')) ? 'text-slate-900 font-semibold' : '' }}" aria-expanded="false" :aria-expanded="open.toString()">
                                        {{ $item->title }}
                                        <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" x-transition class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-lg py-2 z-50" style="display: none;">
                                        @foreach($item->children as $child)
                                            <a href="{{ url($child->url) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">{{ $child->title }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ url($item->url) }}" class="hover:text-slate-900 transition-colors {{ request()->is(ltrim($item->url, '/')) ? 'text-slate-900 font-semibold' : '' }}">{{ $item->title }}</a>
                            @endif
                        @endforeach
                    @else
                        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors {{ request()->routeIs('home') ? 'text-slate-900 font-semibold' : '' }}">Home</a>
                        <a href="{{ route('blog.index') }}" class="hover:text-slate-900 transition-colors {{ request()->routeIs('blog.*') ? 'text-slate-900 font-semibold' : '' }}">Blog</a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4">
                @if(\App\Models\SystemSetting::get('enable_auto_translate_en', '0') === '1')
                    <div class="relative" x-data="{ langOpen: false }" @click.away="langOpen = false">
                        <button @click="langOpen = !langOpen" aria-label="Toggle Language" aria-expanded="false" :aria-expanded="langOpen.toString()" class="flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors bg-white/50 px-2 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                            <span class="uppercase">{{ app()->getLocale() }}</span>
                            <svg class="w-3.5 h-3.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="langOpen" x-transition class="absolute right-0 mt-2 w-32 bg-white border border-slate-200 rounded-xl shadow-lg py-1 z-50" style="display: none;">
                            @php
                                $isEn = app()->getLocale() === 'en';
                                $currentPath = request()->path();
                                
                                // Generate ID Path (remove /en if exists)
                                $idPath = preg_replace('/^en(\/|$)/', '', $currentPath);
                                $idUrl = url($idPath ?: '/');
                                
                                // Generate EN Path
                                $enPath = $isEn ? $currentPath : 'en/' . ltrim($currentPath, '/');
                                $enUrl = url($enPath);
                            @endphp
                            <a href="{{ $idUrl }}" class="flex items-center justify-between px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors {{ !$isEn ? 'font-semibold text-brand-indigo bg-slate-50' : '' }}">
                                Indonesia
                                @if(!$isEn)<svg class="w-4 h-4 text-brand-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>@endif
                            </a>
                            <a href="{{ $enUrl }}" class="flex items-center justify-between px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors {{ $isEn ? 'font-semibold text-brand-indigo bg-slate-50' : '' }}">
                                English
                                @if($isEn)<svg class="w-4 h-4 text-brand-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>@endif
                            </a>
                        </div>
                    </div>
                @endif

                @auth('web')
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200/80 border border-slate-200 px-5 py-2.5 rounded-xl transition-all shadow-sm">
                        Admin Panel
                    </a>
                @endauth
                @auth('buyer')
                    <a href="{{ route('buyer.dashboard') }}" class="text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200/80 border border-slate-200 px-5 py-2.5 rounded-xl transition-all shadow-sm">
                        Dashboard
                    </a>
                @endauth
                @if(!auth('web')->check() && !auth('buyer')->check())
                    <a href="{{ route('buyer.login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('buyer.register') }}" class="text-sm font-semibold text-white bg-gradient-to-r from-brand-indigo to-brand-purple hover:opacity-90 px-5 py-2.5 rounded-xl transition-all shadow-md shadow-brand-indigo/10">
                        Get Started
                    </a>
                @endif
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
    <main class="relative z-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-50 border-t border-slate-200/80 py-16 relative z-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-brand-indigo to-brand-purple flex items-center justify-center font-outfit font-bold text-white text-md">
                            SF
                        </div>
                        <span class="font-outfit font-extrabold text-xl tracking-tight text-slate-900">
                            SEO<span class="bg-gradient-to-r from-brand-indigo to-brand-purple bg-clip-text text-transparent">FAST</span>
                        </span>
                    </a>
                    <p class="text-slate-600 text-sm max-w-sm leading-relaxed mb-6">
                        The ultimate SEO Operating System for modern marketing. Zero manual refresh, zero soft failures, and seamless closed-loop Google Search Console synchronization.
                    </p>
                    <div class="text-xs text-slate-400 font-mono">
                        System Architecture V3
                    </div>
                </div>

                <div>
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">Platform</h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li><a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Integrations</a></li>
                        <li><a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">AI Content Generator</a></li>
                        <li><a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Silo Builder</a></li>
                        <li><a href="{{ route('home') }}#pricing" class="hover:text-slate-900 transition-colors">Pricing Plans</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">Resources</h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li><a href="{{ route('blog.index') }}" class="hover:text-slate-900 transition-colors">Blog Feed</a></li>
                        <li><a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Documentation</a></li>
                        <li><a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Changelog</a></li>
                        <li><a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Support Center</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-200/60 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} SEOFAST Inc. All rights reserved. Made for speed & high performance.
                </p>
                <div class="flex gap-6 text-xs text-slate-500">
                    <a href="{{ url('/privacy-policy') }}" class="hover:text-slate-900 transition-colors">Privacy Policy</a>
                    <a href="{{ url('/terms-of-service') }}" class="hover:text-slate-900 transition-colors">Terms of Service</a>
                    <a href="{{ url('/sitemap.xml') }}" class="hover:text-slate-900 transition-colors" target="_blank">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
    @stack('scripts')
    
    <!-- Advanced SEO Body Code -->
    {!! \App\Models\SystemSetting::get('seo_advanced_body_code') !!}
</body>
</html>
