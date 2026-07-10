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

        $siteName    = SystemSetting::get('site_name', config('app.name'));
        $siteVerif   = SystemSetting::get('seo_global_google_site_verification');
        $bingVerif   = SystemSetting::get('seo_indexing_bing_verification');
        $keywords    = SystemSetting::get('homepage_meta_keywords');
        $faviconUrl  = SystemSetting::get('favicon_url', asset('favicon.ico'));
        $logoUrl     = SystemSetting::get('logo_url');
        $logoAlt     = SystemSetting::get('logo_alt', $siteName);
        $logoText    = substr($siteName, 0, 2);
        $brandName   = $siteName;
        $brandShort  = substr($brandName, 0, 3);
        $brandRest   = substr($brandName, 3);
    @endphp

    <!-- SEO Meta Tags (SSR) -->
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription ?: SystemSetting::get('seo_global_meta_description', '') }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    @if($keywords)
    <meta name="keywords" content="{{ $keywords }}">
    @endif

    {{-- Hreflang — hanya untuk route yang punya EN version (homepage & blog) --}}
    @php
        $multiLang = \App\Models\SystemSetting::get('enable_auto_translate_en', '0') === '1';
        $blogPrefix = \App\Models\SystemSetting::get('permalink_blog', 'blog');
        $productPrefix = \App\Models\SystemSetting::get('permalink_product', 'produk');
        $projectPrefix = \App\Models\SystemSetting::get('permalink_project', 'projeku');
        $currentPath = request()->path();
        $normalizedPath = ltrim($currentPath, '/');
        $isOnEn = app()->getLocale() === 'en';

        // Normalize: remove leading /en/ to compare
        $pathWithoutEn = preg_replace('/^en\//', '', $normalizedPath);
        $pathWithoutEn = preg_replace('/^en$/', '', $pathWithoutEn);

        // Cek apakah path ini punya EN version
        $hasEnVersion = false;
        if (empty($pathWithoutEn)) {
            // Homepage (/ or /en)
            $hasEnVersion = true;
        } elseif (str_starts_with($pathWithoutEn, $blogPrefix . '/') || $pathWithoutEn === $blogPrefix) {
            // Blog routes (/blog/... or /en/blog/...)
            $hasEnVersion = true;
        } elseif (str_starts_with($pathWithoutEn, $blogPrefix . '/category/')) {
            // Category pages
            $hasEnVersion = true;
        } elseif (str_starts_with($pathWithoutEn, $blogPrefix . '/preview/')) {
            // Preview
            $hasEnVersion = true;
        } elseif (str_starts_with($pathWithoutEn, $productPrefix . '/') || $pathWithoutEn === $productPrefix) {
            // Product routes (/produk/... or /en/produk/...)
            $hasEnVersion = true;
        } elseif (str_starts_with($pathWithoutEn, $projectPrefix . '/') || $pathWithoutEn === $projectPrefix) {
            // Project routes (/projeku/... or /en/projeku/...)
            $hasEnVersion = true;
        } elseif (!empty($pathWithoutEn) && !str_starts_with($pathWithoutEn, 'admin') && !str_starts_with($pathWithoutEn, 'buyer') && !str_starts_with($pathWithoutEn, 'auth') && $pathWithoutEn !== 'sitemap.xml' && $pathWithoutEn !== 'robots.txt' && !str_starts_with($pathWithoutEn, 'g/')) {
            // Static pages (catch-all route) have EN version
            $hasEnVersion = true;
        }

        // Self-referencing ID
        $idUrl = url($pathWithoutEn ?: '/');
        $enUrl = url('en/' . ltrim($pathWithoutEn, '/'));
    @endphp
    <link rel="alternate" hreflang="id" href="{{ $idUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $idUrl }}">
    @if($multiLang && $hasEnVersion)
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}">
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
    <meta property="og:locale" content="{{ app()->getLocale() === 'en' ? 'en_US' : 'id_ID' }}">
    <meta property="og:locale:alternate" content="{{ app()->getLocale() === 'en' ? 'id_ID' : 'en_US' }}">

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

    <!-- Compiled CSS & JS -->
    @php
        $manifestPath = public_path('build/manifest.json');
        $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
        $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
    @endphp
    @if($cssFile)<link rel="stylesheet" href="{{ asset('build/'.$cssFile) }}">@endif
    @if($jsFile)<script type="module" src="{{ asset('build/'.$jsFile) }}"></script>@endif

    <style>
        [x-cloak] { display: none !important; }
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

    <!-- WebSite Schema + Sitelinks Search Box -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "{{ $siteName }}",
        "url": "{{ config('app.url') }}",
        "inLanguage": "{{ app()->getLocale() === 'en' ? 'en-US' : 'id-ID' }}",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": {
                "@@type": "EntryPoint",
                "urlTemplate": "{{ route('search', ['q' => '{search_term_string}']) }}"
            },
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <!-- Organization Schema -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "{{ $siteName }}",
        "url": "{{ config('app.url') }}",
        @if($logoUrl)
        "logo": "{{ $logoUrl }}",
        @endif
        "description": "{{ SystemSetting::get('seo_global_meta_description', '') }}",
        "foundingDate": "2025",
        @if($logoUrl)
        "image": "{{ $logoUrl }}",
        @endif
        "sameAs": [
            {{-- Sosial media links bisa ditambah via settings nanti --}}
        ]
    }
    </script>

    <!-- Google Analytics / GTM / FB Pixel (SSR, no JS required for crawlers) -->
    {!! \App\Services\SeoHelper::trackingHeadScripts() !!}

    <!-- Advanced SEO Head Code (custom per-site) -->
    {!! \App\Models\SystemSetting::get('seo_advanced_head_code') !!}

    <!-- Per-page head extras (pagination, alternate links, etc.) -->
    @yield('head_extra')
</head>
<body class="min-h-screen relative overflow-x-hidden antialiased">
    <!-- GTM noscript body open -->
    {!! \App\Services\SeoHelper::trackingBodyStart() !!}
    <!-- Background Glow Orbs -->
    <div class="glow-orb w-96 h-96 bg-brand-purple top-10 left-10"></div>
    <div class="glow-orb w-[500px] h-[500px] bg-brand-blue bottom-20 right-10"></div>
    
    <!-- Navbar -->
    <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 glass-nav">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-12">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group" aria-label="{{ $siteName }}">
                    @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $logoAlt }}" loading="lazy" class="h-10 w-auto group-hover:scale-105 transition-transform">
                    @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-indigo to-brand-purple flex items-center justify-center font-outfit font-extrabold text-white text-xl shadow-lg shadow-brand-indigo/10 group-hover:scale-105 transition-transform">
                        {{ $logoText }}
                    </div>
                    <span class="font-outfit font-extrabold text-2xl tracking-tight text-slate-900">
                        {{ $brandShort }}<span class="bg-gradient-to-r from-brand-indigo to-brand-purple bg-clip-text text-transparent">{{ $brandRest }}</span>
                    </span>
                    @endif
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

            <div class="hidden md:flex items-center gap-4">
                <!-- Search Toggle -->
                <div x-data="{ searchOpen: false }" class="relative" @click.away="searchOpen = false">
                    <button @click="searchOpen = !searchOpen" aria-label="Search" class="flex items-center justify-center w-10 h-10 text-slate-500 hover:text-slate-900 hover:bg-slate-100/60 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    <div x-show="searchOpen" x-transition class="absolute right-0 mt-2 w-96" style="display:none;">
                        <form method="GET" action="{{ route('search') }}" class="bg-white border border-slate-200 rounded-xl shadow-lg p-3">
                            <div class="relative">
                                <input type="text" name="q" placeholder="Search everything..." autofocus
                                       class="w-full rounded-lg border border-slate-300 pl-10 pr-4 py-2.5 text-sm focus:border-brand-indigo focus:ring-1 focus:ring-brand-indigo outline-none">
                                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </form>
                    </div>
                </div>

                @if(\App\Models\SystemSetting::get('enable_auto_translate_en', '0') === '1' && $hasEnVersion)
                    <div class="relative" x-data="{ langOpen: false }" @click.away="langOpen = false">
                        <button @click="langOpen = !langOpen" aria-label="Toggle Language" aria-expanded="false" :aria-expanded="langOpen.toString()" class="flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors bg-white/50 px-2 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                            <span class="uppercase">{{ app()->getLocale() }}</span>
                            <svg class="w-3.5 h-3.5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="langOpen" x-transition class="absolute right-0 mt-2 w-32 bg-white border border-slate-200 rounded-xl shadow-lg py-1 z-50" style="display: none;">
                            <a href="{{ $idUrl }}" class="flex items-center justify-between px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors {{ !$isOnEn ? 'font-semibold text-brand-indigo bg-slate-50' : '' }}">
                                Indonesia
                                @if(!$isOnEn)<svg class="w-4 h-4 text-brand-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>@endif
                            </a>
                            <a href="{{ $enUrl }}" class="flex items-center justify-between px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors {{ $isOnEn ? 'font-semibold text-brand-indigo bg-slate-50' : '' }}">
                                English
                                @if($isOnEn)<svg class="w-4 h-4 text-brand-indigo" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>@endif
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

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <div class="flex items-center gap-4">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle mobile menu" class="text-slate-600 hover:text-slate-900 focus:outline-none p-2 rounded-lg bg-slate-100/50">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="mobileMenuOpen" style="display:none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" x-transition.opacity style="display:none;" class="md:hidden absolute top-20 left-0 w-full bg-white border-b border-slate-200 shadow-xl">
            <div class="px-6 pt-4 pb-6 space-y-1 max-h-[calc(100vh-5rem)] overflow-y-auto">
                @if($primaryMenu && $primaryMenu->items->isNotEmpty())
                    @foreach($primaryMenu->items as $item)
                        @if($item->children->count() > 0)
                            <div x-data="{ childOpen: false }" class="py-1">
                                <button @click="childOpen = !childOpen" class="flex items-center justify-between w-full px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-brand-indigo rounded-lg transition-colors">
                                    {{ $item->title }}
                                    <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': childOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="childOpen" x-collapse class="mt-1 pl-4 space-y-1">
                                    @foreach($item->children as $child)
                                        <a href="{{ url($child->url) }}" class="block px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-indigo rounded-lg transition-colors">{{ $child->title }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ url($item->url) }}" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-brand-indigo rounded-lg transition-colors">{{ $item->title }}</a>
                        @endif
                    @endforeach
                @else
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-brand-indigo rounded-lg transition-colors">Home</a>
                    <a href="{{ route('blog.index') }}" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 hover:text-brand-indigo rounded-lg transition-colors">Blog</a>
                @endif
                
                <div class="pt-6 mt-6 border-t border-slate-200/60 flex flex-col gap-3">
                    @auth('web')
                        <a href="{{ route('dashboard') }}" class="w-full text-center text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-5 py-3 rounded-xl transition-all">
                            Admin Panel
                        </a>
                    @endauth
                    @auth('buyer')
                        <a href="{{ route('buyer.dashboard') }}" class="w-full text-center text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-5 py-3 rounded-xl transition-all">
                            Dashboard
                        </a>
                    @endauth
                    @if(!auth('web')->check() && !auth('buyer')->check())
                        <a href="{{ route('buyer.login') }}" class="w-full text-center text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-5 py-3 rounded-xl transition-all">
                            Sign In
                        </a>
                        <a href="{{ route('buyer.register') }}" class="w-full text-center text-sm font-semibold text-white bg-gradient-to-r from-brand-indigo to-brand-purple hover:opacity-90 px-5 py-3 rounded-xl shadow-md">
                            Get Started
                        </a>
                    @endif
                </div>
            </div>
        </div>
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
                            {{ substr($siteName, 0, 2) }}
                        </div>
                        <span class="font-outfit font-extrabold text-xl tracking-tight text-slate-900">
                            {{ $brandShort }}<span class="bg-gradient-to-r from-brand-indigo to-brand-purple bg-clip-text text-transparent">{{ $brandRest }}</span>
                        </span>
                    </a>
                    <p class="text-slate-600 text-sm max-w-sm leading-relaxed mb-6">
                        {{ \App\Models\SystemSetting::get('footer_description', 'The ultimate SEO Operating System for modern marketing. Zero manual refresh, zero soft failures, and seamless closed-loop Google Search Console synchronization.') }}
                    </p>
                    <div class="text-xs text-slate-400 font-mono">
                        {{ \App\Models\SystemSetting::get('footer_subtext', 'System Architecture V3') }}
                    </div>
                </div>

                <div>
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">
                        {{ \App\Models\SystemSetting::get('footer_col1_title', 'Platform') }}
                    </h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        @php
                            $col1LinksText = \App\Models\SystemSetting::get('footer_col1_links', "Integrations|/\nAI Content Generator|/\nSilo Builder|/\nPricing Plans|/#pricing");
                            $col1Lines = explode("\n", str_replace("\r", "", $col1LinksText));
                        @endphp
                        @foreach($col1Lines as $line)
                            @if(trim($line))
                                @php
                                    $parts = explode('|', $line, 2);
                                    $text = trim($parts[0] ?? '');
                                    $url = trim($parts[1] ?? '#');
                                @endphp
                                <li><a href="{{ $url }}" class="hover:text-slate-900 transition-colors">{{ $text }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">
                        {{ \App\Models\SystemSetting::get('footer_col2_title', 'Resources') }}
                    </h3>
                    <ul class="space-y-3 text-sm text-slate-600">
                        @php
                            $col2LinksText = \App\Models\SystemSetting::get('footer_col2_links', "Blog Feed|/blog\nDocumentation|/\nChangelog|/\nSupport Center|/");
                            $col2Lines = explode("\n", str_replace("\r", "", $col2LinksText));
                        @endphp
                        @foreach($col2Lines as $line)
                            @if(trim($line))
                                @php
                                    $parts = explode('|', $line, 2);
                                    $text = trim($parts[0] ?? '');
                                    $url = trim($parts[1] ?? '#');
                                @endphp
                                <li><a href="{{ $url }}" class="hover:text-slate-900 transition-colors">{{ $text }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-200/60 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} {{ $brandName }} Inc. All rights reserved. Made for speed & high performance.
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
