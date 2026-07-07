<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instalasi Gagal — SEOFAST</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-lg">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center">
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h1 class="text-2xl font-bold font-outfit text-slate-900 mb-2">Instalasi Gagal</h1>
            <p class="text-slate-500 text-sm mb-8">Terjadi error selama proses instalasi. Detail di bawah:</p>

            @if(isset($output))
            <div class="bg-red-50 rounded-xl border border-red-200 p-4 mb-8 text-left">
                <h3 class="text-xs font-bold text-red-400 uppercase tracking-wider mb-2">Error Log</h3>
                <div class="space-y-1">
                    @foreach($output as $line)
                    <div class="text-xs text-red-700 font-mono {{ str_starts_with($line, 'ERROR') ? 'font-bold' : '' }}">{{ $line }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex gap-3 justify-center">
                <a href="{{ route('install.welcome') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all text-sm">Coba Lagi</a>
            </div>
        </div>
    </div>
</body>
</html>
