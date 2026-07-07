<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installasi — SEOFAST</title>
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
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans" x-data="wizard()" x-init="init()">
    <div class="w-full max-w-3xl">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-600/20 mb-4">
                <span class="text-white text-2xl font-bold font-outfit">S</span>
            </div>
            <h1 class="text-3xl font-bold font-outfit text-slate-900">SEOFAST Installation</h1>
            <p class="text-slate-500 mt-1">Setup wizard akan memandu Anda melalui proses instalasi.</p>
        </div>

        <!-- Steps Progress -->
        <div class="flex items-center justify-center gap-2 mb-10 text-sm">
            <template x-for="(step, i) in steps" :key="i">
                <div class="flex items-center gap-2">
                    <div :class="stepClass(i)" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all">
                        <span x-show="i < currentStep" class="text-white">&#10003;</span>
                        <span x-show="i >= currentStep" x-text="i + 1"></span>
                    </div>
                    <span x-text="step" class="hidden sm:inline" :class="i === currentStep ? 'font-semibold text-slate-900' : 'text-slate-400'"></span>
                    <template x-if="i < steps.length - 1">
                        <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </template>
                </div>
            </template>
        </div>

        <!-- Step 1: Welcome & Requirements -->
        <div x-show="currentStep === 0" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <h2 class="text-xl font-bold font-outfit text-slate-900 mb-1">Selamat Datang</h2>
            <p class="text-sm text-slate-500 mb-6">Pastikan server Anda memenuhi persyaratan berikut sebelum melanjutkan.</p>

            <div class="space-y-2 mb-8">
                @foreach($requirements as $key => $req)
                <div class="flex items-center justify-between p-3 rounded-xl {{ $req['ok'] ? 'bg-emerald-50' : 'bg-red-50' }}">
                    <div class="flex items-center gap-3">
                        @if($req['ok'])
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        @endif
                        <span class="text-sm font-medium {{ $req['ok'] ? 'text-emerald-800' : 'text-red-800' }}">{{ $req['label'] }}</span>
                    </div>
                    <span class="text-xs {{ $req['ok'] ? 'text-emerald-600' : 'text-red-600' }}">{{ $req['note'] }}</span>
                </div>
                @endforeach
            </div>

            @if($hasErrors)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800 mb-6">
                <strong class="font-bold">Perhatian:</strong> Beberapa persyaratan tidak terpenuhi. Instalasi mungkin gagal. Perbaiki terlebih dahulu atau lanjutkan dengan risiko sendiri.
            </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('install.database') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all text-sm shadow-lg shadow-indigo-600/20">
                    Lanjut ke Database
                    <svg class="w-4 h-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </div>

    <script>
        function wizard() {
            return {
                steps: ['Requirements', 'Database', 'Admin', 'Install'],
                currentStep: 0,
                stepClass(i) {
                    if (i < this.currentStep) return 'bg-emerald-500 text-white';
                    if (i === this.currentStep) return 'bg-indigo-600 text-white';
                    return 'bg-slate-200 text-slate-500';
                },
                init() {
                    this.currentStep = 0;
                }
            }
        }
    </script>
</body>
</html>
