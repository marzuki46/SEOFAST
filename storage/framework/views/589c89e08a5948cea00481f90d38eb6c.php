<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard - SEOFAST V3'); ?></title>

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
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body class="h-full font-sans antialiased text-slate-700" x-data="{ sidebarOpen: false }">
    <div id="admin-layout-wrapper" class="flex h-full overflow-hidden relative">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm md:hidden" style="display: none;"></div>

        <!-- Sidebar Navigation -->
        <aside id="admin-sidebar" 
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-400 flex flex-col justify-between border-r border-slate-800 flex-shrink-0 transition-transform duration-300 md:relative md:translate-x-0">
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
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition duration-150 <?php echo e(request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/50 hover:text-slate-200'); ?>">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>
                    
                    <!-- GROUP: Content Production -->
                    <div x-data="{ open: <?php echo e(request()->routeIs('admin.silo.*', 'admin.links.*', 'admin.content.*') ? 'true' : 'false'); ?> }" class="pt-2">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-slate-300 transition-colors">
                            <span>Content Pipeline</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="space-y-1 mt-1 pl-2 border-l border-slate-800 ml-3">
                            <a href="<?php echo e(route('admin.silo.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.silo.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                1. Riset Keyword (Silo)
                            </a>
                            <a href="<?php echo e(route('admin.links.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.links.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                2. Internal Link Map
                            </a>
                            <a href="<?php echo e(route('admin.content.prapost')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.content.prapost') ? 'text-white font-semibold' : 'text-slate-400 hover:text-slate-200'); ?>">
                                3. Pra Post (Blueprint)
                            </a>
                            <a href="<?php echo e(route('admin.content.create')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.content.create') ? 'text-white font-semibold' : 'text-slate-400 hover:text-slate-200'); ?>">
                                4. AI Queue Monitor
                            </a>
                            <a href="<?php echo e(route('admin.content.drafts')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.content.drafts') ? 'text-white font-semibold' : 'text-slate-400 hover:text-slate-200'); ?>">
                                5. AI Drafts
                            </a>
                            <a href="<?php echo e(route('admin.content.calendar')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.content.calendar') ? 'text-white font-semibold' : 'text-slate-400 hover:text-slate-200'); ?>">
                                6. Content Calendar
                            </a>
                            <a href="<?php echo e(route('admin.content.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.content.index') || request()->routeIs('admin.content.show') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                7. All Posts
                            </a>
                        </div>
                    </div>

                    <?php if(auth()->user()->isAdmin()): ?>
                    <!-- GROUP: Website & CMS -->
                    <div x-data="{ open: <?php echo e(request()->routeIs('admin.pages.*', 'admin.menus.*', 'admin.media.*') ? 'true' : 'false'); ?> }" class="pt-2">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-slate-300 transition-colors">
                            <span>Website & CMS</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="space-y-1 mt-1 pl-2 border-l border-slate-800 ml-3">
                            <a href="<?php echo e(route('admin.pages.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.pages.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                Static Pages
                            </a>
                            <a href="<?php echo e(route('admin.menus.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.menus.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                Navigation Menu
                            </a>
                            <a href="<?php echo e(route('admin.media.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.media.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                Media Library
                            </a>
                        </div>
                    </div>

                    <!-- GROUP: Sales & Products -->
                    <div x-data="{ open: <?php echo e(request()->routeIs('admin.products.*', 'admin.orders.*', 'admin.billing.*') ? 'true' : 'false'); ?> }" class="pt-2">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-slate-300 transition-colors">
                            <span>Commerce</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="space-y-1 mt-1 pl-2 border-l border-slate-800 ml-3">
                            <a href="<?php echo e(route('admin.products.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                Digital Products
                            </a>
                            <a href="<?php echo e(route('admin.orders.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.orders.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                Customer Orders
                            </a>
                            <a href="<?php echo e(route('admin.billing.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.billing.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                Billing Stats
                            </a>
                        </div>
                    </div>

                    <!-- GROUP: Configuration & Tools -->
                    <div x-data="{ open: <?php echo e(request()->routeIs('admin.settings.*', 'admin.seo.settings.*', 'admin.users.*', 'admin.gsc.*', 'admin.infrastructure.*', 'admin.redirects.*', 'admin.errors.*', 'admin.broken-links.*', 'admin.duplicates.*', 'admin.readability.*', 'admin.url-audit.*', 'admin.serp-rank.*') ? 'true' : 'false'); ?> }" class="pt-2">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-slate-300 transition-colors">
                            <span>System Admin</span>
                            <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="space-y-1 mt-1 pl-2 border-l border-slate-800 ml-3">
                            <a href="<?php echo e(route('admin.gsc.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.gsc.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                Google Search Console
                            </a>
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                User Management
                            </a>
                            <a href="<?php echo e(route('admin.seo.settings.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.seo.settings.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                Enterprise SEO
                            </a>
                            <a href="<?php echo e(route('admin.settings.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.settings.*') && !request()->routeIs('admin.seo.settings.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                Global Settings
                            </a>
                            <a href="<?php echo e(route('admin.errors.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.errors.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                404 Error Tracker
                            </a>
                            <a href="<?php echo e(route('admin.broken-links.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.broken-links.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.76a4.5 4.5 0 00-7.244 1.242l-4.5 4.5a4.5 4.5 0 006.364 6.364l1.757-1.757"/></svg>
                                Broken Link Checker
                            </a>
                            <a href="<?php echo e(route('admin.duplicates.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.duplicates.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/></svg>
                                Duplication Detector
                            </a>
                            <a href="<?php echo e(route('admin.readability.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.readability.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                Readability
                            </a>
                            <a href="<?php echo e(route('admin.url-audit.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.url-audit.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m6.121-1.516a4.5 4.5 0 01-1.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757"/></svg>
                                URL Audit
                            </a>
                            <a href="<?php echo e(route('admin.serp-rank.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.serp-rank.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                                SERP Rank
                            </a>
                            <a href="<?php echo e(route('admin.redirects.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.redirects.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                Redirect Manager
                            </a>
                            <a href="<?php echo e(route('admin.infrastructure.index')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 <?php echo e(request()->routeIs('admin.infrastructure.*') ? 'text-white' : 'text-slate-400 hover:text-slate-200'); ?>">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                                Infrastructure
                            </a>
                            <?php if(config('horizon.defaults')): ?>
                            <a href="<?php echo e(url(config('horizon.path', 'horizon'))); ?>" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition duration-150 text-slate-400 hover:text-slate-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.712 4.33a2.329 2.329 0 013.29 0l1.668 1.668a2.329 2.329 0 010 3.29l-1.315 1.315a16.88 16.88 0 01-6.09 4.066l-2.597.928.929-2.597a16.88 16.88 0 014.066-6.09L16.712 4.33z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 9.75l3 3m0 0l-3 3m3-3H3" />
                                </svg>
                                Horizon
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Sidebar Footer / Profile -->
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center justify-between mb-4 px-3">
                    <div class="flex flex-col min-w-0">
                        <span class="text-xs text-slate-500 uppercase tracking-wider font-semibold">User</span>
                        <span class="text-sm font-medium text-slate-200 truncate"><?php echo e(Auth::user()->name); ?></span>
                    </div>
                </div>
                
                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-slate-800/50 hover:text-slate-200 transition duration-150 mb-1">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Front Page
                </a>

                <a href="<?php echo e(route('logout')); ?>" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold text-rose-400 hover:bg-rose-950/20 hover:text-rose-300 transition duration-150">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" />
                    </svg>
                    Logout
                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50">
            <!-- Topbar Header -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 flex-shrink-0">
                <div class="flex items-center gap-3 overflow-hidden">
                    <!-- Mobile Hamburger Menu -->
                    <button @click="sidebarOpen = true" class="md:hidden p-1.5 -ml-1.5 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h1 class="font-outfit font-bold text-lg md:text-xl text-slate-800 truncate"><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
                </div>
                <div class="flex items-center gap-2 md:gap-4 flex-shrink-0">
                    <button id="clearCacheBtn" onclick="clearCache()" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] md:text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg ring-1 ring-inset ring-amber-700/20 transition" title="Hapus semua cache sistem">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Cache
                    </button>
                    <button id="toggle-sidebar-position" class="hidden md:block p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition duration-150" title="Switch Sidebar Side">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-12L21 9m0 0l-4.5 4.5M21 9H7.5" />
                        </svg>
                    </button>
                    <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-[10px] md:text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-700/10">Admin Panel</span>
                </div>
            </header>

            <!-- Scrollable Content View -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                <!-- Session Alert Messages -->
                <div class="max-w-7xl mx-auto mb-6">
                    <?php if(session('success')): ?>
                        <div class="mb-4 rounded-xl bg-emerald-50 p-4 border border-emerald-200 shadow-sm flex items-start gap-3">
                            <svg class="h-5 w-5 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-emerald-800"><?php echo e(session('success')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="mb-4 rounded-xl bg-rose-50 p-4 border border-rose-200 shadow-sm flex items-start gap-3">
                            <svg class="h-5 w-5 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-rose-800"><?php echo e(session('error')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="mb-4 rounded-xl bg-rose-50 p-4 border border-rose-200 shadow-sm">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold text-rose-800 mb-1">Please fix the following validation errors:</h4>
                                    <ul class="list-disc pl-5 text-xs text-rose-700 font-semibold space-y-0.5">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php echo $__env->yieldContent('admin_content'); ?>
            </main>
        </div>
    </div>

    <script>
        async function clearCache() {
            const btn = document.getElementById('clearCacheBtn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            try {
                const res = await fetch('<?php echo e(route("admin.settings.clear_cache")); ?>', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                });
                if (res.ok) {
                    btn.innerHTML = '<svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg> OK';
                    setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 2000);
                    location.reload();
                } else {
                    btn.innerHTML = orig; btn.disabled = false;
                    alert('Gagal membersihkan cache. Coba refresh dan ulangi.');
                }
            } catch (e) {
                btn.innerHTML = orig; btn.disabled = false;
                alert('Error: ' + e.message);
            }
        }

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
    <?php echo $__env->yieldContent('scripts'); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Program Marzuki\Projek Framework\SEOFAST\resources\views/layouts/admin.blade.php ENDPATH**/ ?>