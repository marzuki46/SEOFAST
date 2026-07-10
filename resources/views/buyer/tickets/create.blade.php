@extends('buyer.layouts.app')

@section('header', 'Buat Ticket Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('buyer.tickets.index') }}" class="text-sm text-slate-500 hover:text-slate-900 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Ticket
        </a>
    </div>

    <form action="{{ route('buyer.tickets.store') }}" method="POST" onsubmit="disableButton(this)">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                <h3 class="text-lg font-bold font-outfit text-slate-900">Form Pengaduan / Pertanyaan</h3>
                <p class="text-sm text-slate-500 mt-1">Isi form di bawah dengan detail kendala atau pertanyaan Anda.</p>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Subjek <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required
                        class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20 transition-colors"
                        placeholder="Contoh: Akses produk tidak muncul">
                    @error('subject') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                        <select name="category"
                            class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20 transition-colors">
                            <option value="">Pilih kategori (opsional)</option>
                            <option value="produk" {{ old('category') == 'produk' ? 'selected' : '' }}>Masalah Produk</option>
                            <option value="pembayaran" {{ old('category') == 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                            <option value="akun" {{ old('category') == 'akun' ? 'selected' : '' }}>Masalah Akun</option>
                            <option value="teknis" {{ old('category') == 'teknis' ? 'selected' : '' }}>Kendala Teknis</option>
                            <option value="saran" {{ old('category') == 'saran' ? 'selected' : '' }}>Saran / Masukan</option>
                            <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Prioritas</label>
                        <select name="priority" required
                            class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20 transition-colors">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Pesan <span class="text-red-500">*</span></label>
                    <textarea name="message" rows="6" required
                        class="block w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-brand-indigo focus:ring-2 focus:ring-brand-indigo/20 transition-colors"
                        placeholder="Jelaskan kendala atau pertanyaan Anda dengan detail...">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-200 flex items-center justify-between">
                <p class="text-xs text-slate-400">Tim kami akan merespon maksimal 1x24 jam kerja.</p>
                <button type="submit" class="px-6 py-2.5 bg-brand-indigo text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Kirim Ticket
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function disableButton(form) {
    var btn = form.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim...';
    }
}
</script>
@endpush
