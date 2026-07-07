<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database — SEOFAST Install</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], outfit: ['Outfit', 'sans-serif'] },
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-xl">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-600/20 mb-4">
                <span class="text-white text-2xl font-bold font-outfit">S</span>
            </div>
            <h1 class="text-2xl font-bold font-outfit text-slate-900">Konfigurasi Database</h1>
            <p class="text-slate-500 mt-1 text-sm">Masukkan kredensial database MySQL Anda.</p>
        </div>

        <form action="{{ route('install.save-db') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-5">
            @csrf

            @if($errors->has('db_connection'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                <strong class="font-bold">Koneksi gagal:</strong> {{ $errors->first('db_connection') }}
            </div>
            @endif

            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <label for="db_host" class="block text-sm font-semibold text-slate-700 mb-1">Host</label>
                    <input type="text" name="db_host" id="db_host" value="{{ old('db_host', '127.0.0.1') }}" required
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label for="db_port" class="block text-sm font-semibold text-slate-700 mb-1">Port</label>
                    <input type="text" name="db_port" id="db_port" value="{{ old('db_port', '3306') }}" required
                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label for="db_database" class="block text-sm font-semibold text-slate-700 mb-1">Nama Database</label>
                <input type="text" name="db_database" id="db_database" value="{{ old('db_database') }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                <p class="text-xs text-slate-400 mt-1">Database harus sudah dibuat sebelumnya.</p>
            </div>

            <div>
                <label for="db_username" class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
                <input type="text" name="db_username" id="db_username" value="{{ old('db_username', 'root') }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label for="db_password" class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="db_password" id="db_password" value="{{ old('db_password') }}"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div class="flex justify-between pt-2">
                <a href="{{ route('install.admin') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">&larr; Kembali</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all text-sm shadow-lg shadow-indigo-600/20">
                    Test & Lanjutkan
                    <svg class="w-4 h-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</body>
</html>
