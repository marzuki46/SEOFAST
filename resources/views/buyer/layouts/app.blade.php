<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SEOFAST') }} - Buyer Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-inter text-slate-800 antialiased selection:bg-brand-indigo/30">

    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <a href="{{ route('buyer.dashboard') }}" class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-tr from-brand-indigo to-brand-purple rounded-lg flex items-center justify-center text-white font-outfit font-bold text-sm shadow-md">
                                    SF
                                </div>
                                <span class="font-outfit font-bold text-xl tracking-tight text-slate-900">Buyer<span class="text-brand-indigo">Portal</span></span>
                            </a>
                        </div>
                        <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                            <a href="{{ route('buyer.dashboard') }}" class="inline-flex items-center border-b-2 {{ request()->routeIs('buyer.dashboard') ? 'border-brand-indigo text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} px-1 pt-1 text-sm font-medium">Dashboard</a>
                            <a href="{{ route('buyer.products.index') }}" class="inline-flex items-center border-b-2 {{ request()->routeIs('buyer.products.*') ? 'border-brand-indigo text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} px-1 pt-1 text-sm font-medium">Produk Saya</a>
                            <a href="{{ route('buyer.orders.index') }}" class="inline-flex items-center border-b-2 {{ request()->routeIs('buyer.orders.*') ? 'border-brand-indigo text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} px-1 pt-1 text-sm font-medium">Riwayat Transaksi</a>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Profile dropdown -->
                        <div class="relative ml-3" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" type="button" class="relative flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-indigo focus:ring-offset-2" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="absolute -inset-1.5"></span>
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="{{ Auth::guard('buyer')->user()->avatar_url }}" alt="" loading="lazy">
                                </button>
                            </div>
                            <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-sm text-slate-900 font-medium truncate">{{ Auth::guard('buyer')->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ Auth::guard('buyer')->user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('buyer.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-slate-50" role="menuitem" tabindex="-1">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <header class="bg-white shadow-sm border-b border-slate-200">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold font-outfit tracking-tight text-slate-900">@yield('header')</h1>
            </div>
        </header>
        
        <main>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 py-8">
                @if(session('success'))
                    <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
