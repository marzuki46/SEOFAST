@extends('layouts.frontend')

@section('title', 'Contact Us — Juki Digital Marketing')
@section('meta_description', 'Hubungi Juki Digital Marketing untuk konsultasi AI Automation, Web Development, SEO, dan Digital Marketing. Parangjoro, Grogol, Sukoharjo, Jawa Tengah.')

@section('content')
<!-- Hero -->
<section class="relative overflow-hidden" style="background-color: #0f172a; background-image: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #1e1b4b 100%);">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(99,102,241,0.15),transparent_50%)]"></div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 text-center">
        <span class="inline-block px-4 py-1.5 bg-white/10 text-white/80 text-xs font-semibold rounded-full mb-4 backdrop-blur-sm border border-white/10">Ada Project? Saya Siap Bantu!</span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-outfit leading-tight text-white drop-shadow-xl">
            Mari Diskusikan <br class="hidden sm:block"><span class="text-indigo-400">Project Anda</span>
        </h1>
        <p class="mt-6 text-lg md:text-xl text-white max-w-2xl mx-auto leading-relaxed">
            Isi form di bawah, dan saya akan menghubungi Anda dalam <strong class="text-indigo-300">1x24 jam</strong> untuk membahas solusi digital terbaik untuk bisnis Anda.
        </p>
    </div>
</section>

<!-- Contact Form + Info -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
            <!-- Form Column -->
            <div class="lg:col-span-3">
                <div class="bg-white border border-slate-200 rounded-2xl p-8 md:p-10 shadow-sm">
                    <h2 class="text-2xl font-bold font-outfit text-slate-900 mb-2">Kirim Inquiry</h2>
                    <p class="text-slate-500 text-sm mb-8">Jelaskan project Anda, dan saya akan merespon secepatnya.</p>

                    @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5" id="contactForm" onsubmit="disableButton(this)">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all" placeholder="Tri Marzuki">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all" placeholder="hello@domain.com">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all" placeholder="082213028718">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Subjek <span class="text-red-500">*</span></label>
                                <select name="subject" required
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all bg-white">
                                    <option value="">— Pilih Subjek —</option>
                                    <option value="AI Automation" @selected(old('subject')=='AI Automation' )>AI Automation</option>
                                    <option value="Web Development" @selected(old('subject')=='Web Development' )>Web Development</option>
                                    <option value="SEO Optimization" @selected(old('subject')=='SEO Optimization' )>SEO Optimization</option>
                                    <option value="Digital Marketing" @selected(old('subject')=='Digital Marketing' )>Digital Marketing</option>
                                    <option value="App Developer" @selected(old('subject')=='App Developer' )>App Developer</option>
                                    <option value="Marketing Tools" @selected(old('subject')=='Marketing Tools' )>Marketing Tools</option>
                                    <option value="Lainnya" @selected(old('subject')=='Lainnya' )>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pesan <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="6" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all resize-y" placeholder="Jelaskan project atau kebutuhan Anda secara detail...">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all hover:scale-[1.02]">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            Kirim Inquiry
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info Column -->
            <div class="lg:col-span-2">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-8 md:p-10 space-y-8">
                    <div>
                        <h3 class="text-lg font-bold font-outfit text-slate-900 mb-4">Informasi Kontak</h3>
                        <div class="space-y-4">
                            @php
                                $wa = \App\Models\SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
                                $waDisplay = $wa ? substr($wa, 0, 3) . '-' . substr($wa, 3, 3) . '-' . substr($wa, 6) : '0822-1302-8718';
                                $email = \App\Models\SystemSetting::get('contact_inquiry_email', 'hello@jukidigital.com');
                                $address = \App\Models\SystemSetting::get('contact_address', 'Parangjoro, Grogol, Sukoharjo');
                            @endphp
                            <a href="https://wa.me/{{ $wa }}" class="flex items-center gap-4 text-slate-600 hover:text-green-600 transition-colors group">
                                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">WhatsApp</p>
                                    <p class="font-semibold text-slate-800">{{ $waDisplay }}</p>
                                </div>
                            </a>
                            <div class="flex items-center gap-4 text-slate-600">
                                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Email</p>
                                    <p class="font-semibold text-slate-800">{{ $email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 text-slate-600">
                                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Lokasi</p>
                                    <p class="font-semibold text-slate-800">{{ $address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <h3 class="text-lg font-bold font-outfit text-slate-900 mb-3">Kenapa Menghubungi Saya?</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Respons cepat — saya biasanya membalas dalam <strong>1x24 jam</strong></span>
                            </li>
                            <li class="flex items-start gap-3 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Konsultasi <strong>gratis</strong> tanpa biaya awal</span>
                            </li>
                            <li class="flex items-start gap-3 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Solusi <strong>custom</strong> sesuai kebutuhan bisnis Anda</span>
                            </li>
                            <li class="flex items-start gap-3 text-sm text-slate-600">
                                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Hasil nyata yang sudah terbukti di berbagai project</span>
                            </li>
                        </ul>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <a href="https://wa.me/{{ $wa }}?text=Halo%20Juki%20Digital%20Marketing%2C%20saya%20ingin%20konsultasi..."
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all hover:scale-[1.02] shadow-lg shadow-green-600/20">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Chat via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ / Why Contact -->
<section class="py-20 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full mb-4">Mengapa Saya?</span>
        <h2 class="text-3xl md:text-4xl font-bold font-outfit text-slate-900 mb-4">Siap Mewujudkan Ide Digital Anda</h2>
        <p class="text-slate-500 text-lg mb-12 max-w-2xl mx-auto">
            Setiap project adalah prioritas. Saya memberikan perhatian penuh dari konsultasi hingga hasil akhir.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                </div>
                <h3 class="font-bold font-outfit text-slate-900 mb-2">Konsultasi Gratis</h3>
                <p class="text-sm text-slate-500">Diskusikan ide Anda tanpa biaya. Saya akan memberikan saran dan strategi terbaik.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                </div>
                <h3 class="font-bold font-outfit text-slate-900 mb-2">Eksekusi Cepat</h3>
                <p class="text-sm text-slate-500">Dengan AI automation dan workflow modern, project Anda selesai lebih cepat.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <h3 class="font-bold font-outfit text-slate-900 mb-2">Hasil Terjamin</h3>
                <p class="text-sm text-slate-500">Fokus pada hasil nyata: traffic meningkat, konversi naik, bisnis berkembang.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function disableButton(form) {
    var btn = form.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';
    }
}
</script>
@endpush
