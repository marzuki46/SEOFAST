<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin & Site — SEOFAST Install</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
            <h1 class="text-2xl font-bold font-outfit text-slate-900">Site & Admin</h1>
            <p class="text-slate-500 mt-1 text-sm">Konfigurasi website dan akun admin.</p>
        </div>

        <form action="{{ route('install.save-admin') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-5">
            @csrf

            <div>
                <label for="site_name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Website</label>
                <input type="text" name="site_name" id="site_name" value="{{ old('site_name', 'SEOFAST') }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label for="site_description" class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Website</label>
                <textarea name="site_description" id="site_description" rows="2"
                          class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">{{ old('site_description') }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Akan digunakan sebagai meta description default.</p>
            </div>

            <div>
                <label for="app_url" class="block text-sm font-semibold text-slate-700 mb-1">URL Website</label>
                <input type="url" name="app_url" id="app_url" value="{{ old('app_url', config('app.url')) }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                <p class="text-xs text-slate-400 mt-1">Contoh: https://seofast.com</p>
            </div>

            <hr class="border-slate-100">

            <div>
                <label for="admin_email" class="block text-sm font-semibold text-slate-700 mb-1">Email Admin</label>
                <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label for="admin_password" class="block text-sm font-semibold text-slate-700 mb-1">Password Admin</label>
                <input type="password" name="admin_password" id="admin_password" required minlength="8"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter.</p>
            </div>

            <div>
                <label for="admin_password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" required minlength="8"
                       class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
                <strong>&#9888; Perhatian:</strong> Instalasi akan menjalankan migrasi database, seed data, dan membuat akun admin. Pastikan database sudah siap dan kosong (atau bisa ditimpa).
            </div>

            <div class="flex justify-between pt-2">
                <a href="{{ route('install.welcome') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">&larr; Kembali</a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all text-sm shadow-lg shadow-indigo-600/20">
                    Simpan & Lanjutkan
                    <svg class="w-4 h-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</body>
</html>
