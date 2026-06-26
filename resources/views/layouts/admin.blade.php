<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - SEOFAST V3')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (CDN fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        dark: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            900: '#090d16',
                            950: '#030712',
                        },
                        brand: {
                            purple: '#8b5cf6',
                            blue: '#3b82f6',
                            indigo: '#6366f1',
                            violet: '#a78bfa',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
    </style>
    @yield('styles')
</head>
<body class="h-full font-sans antialiased text-slate-700">
    <div id="admin-layout-wrapper" class="flex h-full overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside id="admin-sidebar" class="w-64 bg-slate-900 text-slate-400 flex flex-col justify-between border-r border-slate-800 flex-shrink-0">
            <div class="flex flex-col flex-1 min-h-0">
                <!-- Brand Logo -->
                <div class="h-16 flex items-center px-6 border-b border-slate-800 gap-2.5 flex-shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-brand-indigo to-brand-purple flex items-center justify-center font-outfit font-extrabold text-white text-md shadow-lg shadow-brand-indigo/10">
                        SF
                    </div>
                    <span class="font-outfit font-extrabold text-lg tracking-tight text-white">
                        SEO<span class="bg-gradient-to-r from-brand-indigo to-brand-purple bg-clip-text text-transparent">FAST</span>
                    </span>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-2 flex-1 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #334155 transparent;">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/50 hover:text-slate-200' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                    
                    <!-- GROUP: Content Production -->
                    <div x-data="{ open: {{ request()->routeIs('admin.silo.*', 'admin.links.*', 'admin.content.*') ? 'true' : 'false' }} }" class="pt-2">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-slate-300 transition-colors">
                            <span>Content Pipeline</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="space-y-1 mt-1 pl-2 border-l border-slate-800 ml-3">
                            <a href="{{ route('admin.silo.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.silo.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                1. Riset Keyword (Silo)
                            </a>
                            <a href="{{ route('admin.links.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.links.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                2. Internal Link Map
                            </a>
                            <a href="{{ route('admin.content.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.content.index') || request()->routeIs('admin.content.show') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                3. All Posts
                            </a>
                            <a href="{{ route('admin.content.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.content.create') ? 'text-brand-violet font-semibold' : 'text-slate-400 hover:text-slate-200' }}">
                                4. Buat Konten AI
                            </a>
                        </div>
                    </div>

                    @if(auth()->user()->isAdmin())
                    <!-- GROUP: Website & CMS -->
                    <div x-data="{ open: {{ request()->routeIs('admin.pages.*', 'admin.menus.*', 'admin.media.*') ? 'true' : 'false' }} }" class="pt-2">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-slate-300 transition-colors">
                            <span>Website & CMS</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="space-y-1 mt-1 pl-2 border-l border-slate-800 ml-3">
                            <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.pages.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                Static Pages
                            </a>
                            <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.menus.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                Navigation Menu
                            </a>
                            <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.media.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                Media Library
                            </a>
                        </div>
                    </div>

                    <!-- GROUP: Sales & Products -->
                    <div x-data="{ open: {{ request()->routeIs('admin.products.*', 'admin.orders.*', 'admin.billing.*') ? 'true' : 'false' }} }" class="pt-2">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-slate-300 transition-colors">
                            <span>Commerce</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="space-y-1 mt-1 pl-2 border-l border-slate-800 ml-3">
                            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                Digital Products
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                Customer Orders
                            </a>
                            <a href="{{ route('admin.billing.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.billing.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                Billing Stats
                            </a>
                        </div>
                    </div>

                    <!-- GROUP: Configuration & Tools -->
                    <div x-data="{ open: {{ request()->routeIs('admin.settings.*', 'admin.users.*', 'admin.gsc.*') ? 'true' : 'false' }} }" class="pt-2">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-slate-300 transition-colors">
                            <span>System Admin</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="space-y-1 mt-1 pl-2 border-l border-slate-800 ml-3">
                            <a href="{{ route('admin.gsc.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.gsc.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                Google Search Console
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                User Management
                            </a>
                            <a href="{{ route('admin.seo.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.seo.settings.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                Enterprise SEO
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 {{ request()->routeIs('admin.settings.*') && !request()->routeIs('admin.seo.settings.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200' }}">
                                Global Settings
                            </a>
                        </div>
                    </div>
                    @endif
                </nav>
            </div>

            <!-- Sidebar Footer / Profile -->
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center justify-between mb-4 px-3">
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs text-slate-500 uppercase tracking-wider font-semibold">User</span>
                        <span class="text-sm font-medium text-slate-200 truncate">{{ Auth::user()->name }}</span>
                    </div>
                </div>
                
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-slate-800/50 hover:text-slate-200 transition duration-150 mb-1">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Front Page
                </a>

                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold text-rose-400 hover:bg-rose-950/20 hover:text-rose-300 transition duration-150">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" />
                    </svg>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden bg-slate-50">
            <!-- Topbar Header -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0">
                <h1 class="font-outfit font-bold text-xl text-slate-800">@yield('page_title', 'Dashboard')</h1>
                <div class="flex items-center gap-4">
                    <button id="toggle-sidebar-position" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition duration-150" title="Switch Sidebar Side">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-12L21 9m0 0l-4.5 4.5M21 9H7.5" />
                        </svg>
                    </button>
                    <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-700/10">Admin Panel</span>
                </div>
            </header>

            <!-- Scrollable Content View -->
            <main class="flex-1 overflow-y-auto p-8">
                @yield('admin_content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wrapper = document.getElementById('admin-layout-wrapper');
            const aside = document.getElementById('admin-sidebar');
            const toggleBtn = document.getElementById('toggle-sidebar-position');
            
            let position = localStorage.getItem('sidebar-position') || 'left';
            applyPosition(position);

            toggleBtn.addEventListener('click', function () {
                position = position === 'left' ? 'right' : 'left';
                localStorage.setItem('sidebar-position', position);
                applyPosition(position);
            });

            function applyPosition(pos) {
                if (pos === 'right') {
                    wrapper.classList.remove('flex-row');
                    wrapper.classList.add('flex-row-reverse');
                    aside.classList.remove('border-r');
                    aside.classList.add('border-l');
                } else {
                    wrapper.classList.remove('flex-row-reverse');
                    wrapper.classList.add('flex-row');
                    aside.classList.remove('border-l');
                    aside.classList.add('border-r');
                }
            }
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
