<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Instalasi — SEOFAST</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-xl bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        <h1 class="text-2xl font-bold text-slate-900">Siap menjalankan instalasi</h1>
        <p class="text-slate-500 mt-2 text-sm">
            Koneksi database berhasil. Tahap berikutnya akan menulis konfigurasi, menjalankan migrasi,
            membuat akun admin, dan mengunci installer.
        </p>

        <form action="{{ route('install.run') }}" method="POST" class="mt-8 flex justify-between items-center">
            @csrf
            <a href="{{ route('install.database') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">
                &larr; Kembali
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm">
                Jalankan Instalasi
            </button>
        </form>
    </div>
</body>
</html>
