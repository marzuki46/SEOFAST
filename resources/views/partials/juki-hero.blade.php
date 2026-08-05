{{-- Juki-style light hero (navy + gold) with brand logo — dipakai di halaman produk & blog --}}
@php
    $jukiLogo = \App\Models\SystemSetting::get('logo_url');
    $jukiLogoAlt = \App\Models\SystemSetting::get('logo_alt', 'Juki Website Developer Solo');
    $jukiWa = \App\Models\SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
    $badge = $badge ?? 'Juki Website Developer Solo';
    $heading = $heading ?? '';
    $subheadline = $subheadline ?? '';
    $ctaPrimaryUrl = $ctaPrimaryUrl ?? 'https://wa.me/' . $jukiWa;
    $ctaPrimaryText = $ctaPrimaryText ?? 'Chat via WhatsApp';
    $ctaSecondaryUrl = $ctaSecondaryUrl ?? url('/contact');
    $ctaSecondaryText = $ctaSecondaryText ?? 'Kirim Inquiry';
@endphp
<style>
    @keyframes juki-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-18px); }
    }
    .juki-hero .juki-float { animation: juki-float 7s ease-in-out infinite; }
    .juki-hero .reveal {
        transition: opacity .7s cubic-bezier(.22, .61, .36, 1), transform .7s cubic-bezier(.22, .61, .36, 1);
    }
    .juki-hero .reveal.is-animating { opacity: 0; transform: translateY(28px); }
    .juki-hero .reveal.is-visible { opacity: 1; transform: translateY(0); }
    @media (prefers-reduced-motion: reduce) {
        .juki-hero .reveal { transition: none !important; }
        .juki-hero .reveal.is-animating { opacity: 1 !important; transform: none !important; }
    }
</style>

<section class="juki-hero relative overflow-hidden bg-gradient-to-br from-white via-[#F4F7FB] to-[#E8EEF6]">
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-brand-navy/5 blur-3xl juki-float"></div>
    <div class="absolute bottom-10 left-0 w-72 h-72 rounded-full bg-brand-gold/10 blur-3xl juki-float" style="animation-delay: 1.5s;"></div>
    <div class="absolute inset-0 bg-[linear-gradient(rgba(21,37,63,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(21,37,63,0.04)_1px,transparent_1px)] bg-[size:56px_56px]"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-20 lg:pt-28 lg:pb-24 text-center">
        @if($jukiLogo)
        <div class="flex justify-center mb-8 reveal">
            <img src="{{ $jukiLogo }}" alt="{{ $jukiLogoAlt }}" class="h-14 sm:h-16 w-auto drop-shadow-xl" loading="eager">
        </div>
        @endif

        <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-[#E2E8F0] shadow-sm text-sm font-semibold text-brand-navy mb-8 reveal">
            <svg class="w-4 h-4 text-brand-gold-dark" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
            {{ $badge }}
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.1] tracking-tight text-[#0F172A] mb-6 reveal" data-delay="80">
            {!! nl2br(e($heading)) !!}
        </h1>

        @if($subheadline)
        <p class="text-lg lg:text-xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed reveal" data-delay="160">
            {{ $subheadline }}
        </p>
        @endif

        <div class="flex flex-col sm:flex-row justify-center gap-4 reveal" data-delay="240">
            <a href="{{ $ctaPrimaryUrl }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-brand-gold hover:bg-brand-gold/90 text-brand-navy font-bold text-lg shadow-xl shadow-brand-gold/25 transition-all hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                {{ $ctaPrimaryText }}
            </a>
            <a href="{{ $ctaSecondaryUrl }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-white border-2 border-brand-navy/15 text-brand-navy hover:border-brand-navy/40 font-bold text-lg transition-all hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                {{ $ctaSecondaryText }}
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var els = document.querySelectorAll('.juki-hero .reveal');
    if ('IntersectionObserver' in window) {
        els.forEach(function (el) { el.classList.add('is-animating'); });
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('is-animating');
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        els.forEach(function (el) { io.observe(el); });
    } else {
        els.forEach(function (el) { el.classList.add('is-visible'); });
    }
});
</script>
@endpush
