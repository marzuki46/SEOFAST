<x-mail::message>
# Pembayaran Diverifikasi!

Halo **{{ $order->buyer->name }}**,

Pembayaran Anda untuk **{{ $order->product->name }}** telah berhasil diverifikasi.

Akses produk Anda sekarang sudah aktif. Silakan login ke dashboard buyer untuk mulai menggunakan produk.

<x-mail::button :url="route('buyer.products.index')">
Buka Produk Saya
</x-mail::button>

**Detail Pesanan:**
- **Order:** #{{ $order->order_number }}
- **Produk:** {{ $order->product->name }}
- **Total:** Rp {{ number_format($order->amount + $order->unique_amount, 0, ',', '.') }}

Terima kasih telah berbelanja di {{ config('app.name') }}.

<x-mail::subcopy>
Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda: {{ route('buyer.products.index') }}
</x-mail::subcopy>
</x-mail::message>
