@php
    $waFloatingEnabled = \App\Models\SystemSetting::get('whatsapp_floating_enabled', true);
    $waFloatingNumber  = (string) \App\Models\SystemSetting::get('whatsapp_floating_number', '');
    if ($waFloatingNumber === '') {
        $waFloatingNumber = (string) \App\Models\SystemSetting::get('contact_inquiry_whatsapp', '');
    }
    if ($waFloatingNumber === '') {
        $waFloatingNumber = '6282213028718';
    }
    $waFloatingTitle    = (string) \App\Models\SystemSetting::get('whatsapp_floating_title', 'CS Juki Website Developer');
    $waFloatingStatus   = (string) \App\Models\SystemSetting::get('whatsapp_floating_status', 'Online — biasanya balas dalam 5 menit');
    $waFloatingGreeting = (string) \App\Models\SystemSetting::get('whatsapp_floating_greeting', 'Halo! 👋 Selamat datang di Juki Website Developer Solo. Mau konsultasi gratis tentang website, sistem informasi, atau SEO? Silakan isi data di bawah ya.');
@endphp

@if($waFloatingEnabled)
<style>
    /* Honeypot anti-spam (hidden from humans) */
    .hp-field {
        position: absolute !important;
        left: -9999px !important;
        width: 1px !important;
        height: 1px !important;
        overflow: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
    /* Live Chat Widget scroll area */
    .juki-chat-body {
        overflow-y: auto;
        scrollbar-width: thin;
    }
</style>

<div x-data="{ open: false, sent: false }" x-cloak>
    {{-- Chat Panel --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-24 right-6 z-50 w-[calc(100vw-3rem)] max-w-sm rounded-2xl overflow-hidden shadow-2xl shadow-black/30 bg-white">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#075E54] to-[#128C7E] text-white px-4 py-3 flex items-center gap-3">
            <div class="relative shrink-0">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm">CS</div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-[#075E54]"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm leading-tight">{{ $waFloatingTitle }}</p>
                <p class="text-xs text-emerald-100">{{ $waFloatingStatus }}</p>
            </div>
            <button type="button" @click="open = false; sent = false" aria-label="Tutup chat" class="p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="bg-[#ECE5DD] p-4 h-64 overflow-y-auto juki-chat-body">
            <div class="bg-white rounded-lg rounded-tl-none px-3 py-2 shadow-sm inline-block max-w-[85%]">
                <p class="text-sm text-slate-700">{{ $waFloatingGreeting }}</p>
            </div>

            @if($errors->any())
            <div class="mt-3 bg-red-50 border border-red-200 text-red-800 rounded-xl p-3 text-xs">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('lead.store') }}" method="POST" class="mt-3 space-y-2" onsubmit="disableButton(this)" x-show="!sent">
                @csrf
                <div>
                    <label for="chat_name" class="sr-only">Nama Anda</label>
                    <input type="text" id="chat_name" name="name" required maxlength="255" placeholder="Nama Anda"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all">
                </div>
                <div>
                    <label for="chat_phone" class="sr-only">Nomor WhatsApp</label>
                    <input type="tel" id="chat_phone" name="phone" required maxlength="50" placeholder="Nomor WhatsApp (0822...)"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all">
                </div>
                <div>
                    <label for="chat_msg" class="sr-only">Pertanyaan</label>
                    <textarea id="chat_msg" name="message" rows="2" required maxlength="5000" placeholder="Pertanyaan / kebutuhan Anda..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/20 outline-none transition-all resize-y"></textarea>
                </div>
                <input type="hidden" name="subject" value="Live Chat">
                <input type="hidden" name="source" value="chat">
                <input type="text" name="website" class="hp-field" tabindex="-1" autocomplete="off" aria-hidden="true">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#25D366] hover:bg-[#1FBD5A] text-white font-bold text-sm transition-all">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Mulai Chat WhatsApp
                </button>
            </form>
        </div>
    </div>

    {{-- Floating Button --}}
    <button type="button" @click="open = !open" aria-label="Chat WhatsApp {{ $waFloatingTitle }}"
        class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-[#25D366] hover:bg-[#1FBD5A] text-white flex items-center justify-center shadow-xl shadow-black/25 transition-transform hover:scale-110">
        <svg x-show="!open" class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

<script>
    function disableButton(form) {
        var btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';
        }
    }
</script>
@endif
