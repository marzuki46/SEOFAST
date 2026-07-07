<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instalasi Selesai — SEOFAST</title>
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
<body class="bg-slate-50 min-h-screen flex items-start justify-center p-4 font-sans pt-10">
    <div class="w-full max-w-xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center mb-6">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-2xl font-bold font-outfit text-slate-900 mb-2">Instalasi Berhasil!</h1>
            <p class="text-slate-500 text-sm mb-2">SEOFAST telah berhasil diinstal.</p>
            <p class="text-slate-400 text-xs">Simpan kredensial di bawah ini. Jangan bagikan ke siapapun.</p>
        </div>

        @if(session('credentials'))
        @php $c = session('credentials'); @endphp
        <div class="bg-white rounded-2xl border-2 border-amber-200 shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold font-outfit text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Data Installasi
                </h2>
                <div class="flex gap-2">
                    <button onclick="copyCreds()" class="px-3 py-1.5 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-lg text-xs font-bold transition">Copy</button>
                    <button onclick="downloadTxt()" class="px-3 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-bold transition">Download .txt</button>
                </div>
            </div>

            <div id="creds-table" class="text-sm space-y-1.5">
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg"><span class="text-slate-500">Website</span><span class="font-semibold text-slate-900">{{ $c['site_name'] }}</span></div>
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg"><span class="text-slate-500">URL</span><span class="font-semibold text-slate-900">{{ $c['app_url'] }}</span></div>
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg"><span class="text-slate-500">Admin Email</span><span class="font-semibold text-slate-900">{{ $c['admin_email'] }}</span></div>
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg"><span class="text-slate-500">Admin Password</span><span class="font-semibold text-slate-900 font-mono text-amber-700">{{ $c['admin_password'] }}</span></div>
                <hr class="border-slate-200 my-2">
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg"><span class="text-slate-500">DB Host</span><span class="font-semibold text-slate-900">{{ $c['db_host'] }}:{{ $c['db_port'] }}</span></div>
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg"><span class="text-slate-500">DB Name</span><span class="font-semibold text-slate-900">{{ $c['db_database'] }}</span></div>
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg"><span class="text-slate-500">DB User</span><span class="font-semibold text-slate-900">{{ $c['db_username'] }}</span></div>
                <div class="flex justify-between p-2 bg-slate-50 rounded-lg"><span class="text-slate-500">DB Password</span><span class="font-semibold text-slate-900 font-mono">{{ $c['db_password'] ?: '(kosong)' }}</span></div>
            </div>

            <p class="text-xs text-amber-600 mt-4 flex items-center gap-1">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                Simpan data ini. Password tidak bisa dipulihkan jika hilang.
            </p>
        </div>
        @endif

        @if(session('output'))
        <details class="bg-slate-50 rounded-xl border border-slate-200 p-4 mb-6">
            <summary class="text-xs font-bold text-slate-400 uppercase tracking-wider cursor-pointer">Lihat Log Instalasi</summary>
            <div class="space-y-1 mt-2">
                @foreach(session('output') as $line)
                <div class="text-xs text-slate-600 font-mono">{{ $line }}</div>
                @endforeach
            </div>
        </details>
        @endif

        <div class="text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all hover:scale-105">
                Login ke Admin
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </div>

    <script>
        function getCredsText() {
            @if(session('credentials'))
            @php $c = session('credentials'); @endphp
            return [
                '================================',
                ' SEOFAST — Data Installasi',
                '================================',
                '',
                'Website    : {{ $c['site_name'] }}',
                'URL        : {{ $c['app_url'] }}',
                '',
                '--- Admin ---',
                'Email      : {{ $c['admin_email'] }}',
                'Password   : {{ $c['admin_password'] }}',
                '',
                '--- Database ---',
                'Host       : {{ $c['db_host'] }}:{{ $c['db_port'] }}',
                'Database   : {{ $c['db_database'] }}',
                'Username   : {{ $c['db_username'] }}',
                'Password   : {{ $c['db_password'] ?: '(kosong)' }}',
                '',
                'Disimpan: {{ now()->format('Y-m-d H:i:s') }}',
                '================================',
            ].join('\n');
            @else
            return '';
            @endif
        }

        function copyCreds() {
            const text = getCredsText();
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                const btn = event.target;
                const orig = btn.textContent;
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = orig, 2000);
            });
        }

        function downloadTxt() {
            const text = getCredsText();
            if (!text) return;
            const blob = new Blob([text], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'seofast-credentials.txt';
            a.click();
            URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>
