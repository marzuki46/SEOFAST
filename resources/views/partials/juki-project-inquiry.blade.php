@php
    $inquiryWa = \App\Models\SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
    $inquiryWaUrl = 'https://wa.me/' . $inquiryWa . '?text=' . urlencode('Halo Mas Tri Marzuki, saya ingin konsultasi mengenai proyek digital (Website/SEO/Otomasi/AI).');
    $inquiryContactUrl = url('/contact');
@endphp

<section class="py-16 sm:py-20 bg-gradient-to-br from-slate-900 via-brand-navy to-slate-950 text-white relative overflow-hidden border-t border-slate-800">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(217,119,6,0.12),transparent_50%)] pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/15 text-xs font-semibold uppercase tracking-wider text-amber-300 mb-6">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
            Siap Memulai Kolaborasi
        </div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-6 font-outfit">
            Punya proyek dalam pikiran?
        </h2>
        <p class="text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto mb-10 leading-relaxed font-sans">
            Mari bahas bagaimana saya dapat membantu Anda dalam pengembangan web, SEO, otomasi, atau AI.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ $inquiryContactUrl }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl text-base font-bold text-slate-900 bg-white hover:bg-slate-100 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                <svg class="w-5 h-5 text-slate-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Hubungi Saya
            </a>
            <a href="{{ $inquiryWaUrl }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl text-base font-bold text-white bg-emerald-600 hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-900/30 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.11 7.43C8.94 7.43 8.67 7.5 8.44 7.74C8.21 8 7.56 8.6 7.56 9.83C7.56 11.07 8.46 12.26 8.58 12.43C8.71 12.6 10.35 15.13 12.86 16.21C13.46 16.47 13.92 16.62 14.29 16.74C14.89 16.93 15.43 16.9 15.86 16.84C16.35 16.77 17.36 16.22 17.57 15.63C17.78 15.04 17.78 14.53 17.71 14.43C17.65 14.33 17.48 14.27 17.22 14.15C16.97 14.02 15.71 13.4 15.48 13.31C15.25 13.23 15.08 13.19 14.91 13.44C14.74 13.7 14.25 14.27 14.1 14.44C13.96 14.62 13.81 14.64 13.56 14.51C13.3 14.39 12.49 14.12 11.52 13.26C10.77 12.59 10.26 11.76 10.11 11.51C9.97 11.26 10.1 11.12 10.22 11C10.34 10.88 10.48 10.7 10.62 10.54C10.76 10.37 10.8 10.25 10.9 10.05C11 9.85 10.94 9.68 10.87 9.53C10.8 9.38 10.22 7.95 9.98 7.37C9.74 6.81 9.5 6.89 9.33 6.88C9.17 6.88 8.97 6.88 9.11 7.43Z"/></svg>
                WhatsApp Saya
            </a>
        </div>
    </div>
</section>
