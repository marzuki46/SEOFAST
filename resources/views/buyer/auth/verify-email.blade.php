@extends('layouts.app')

@section('title', 'Verifikasi Email - ' . config('app.name'))

@section('content')
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Verifikasi Email Anda</h1>
            <p class="mt-2 text-sm text-gray-500">Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda.</p>
        </div>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white px-6 py-8 shadow sm:rounded-lg sm:px-10">
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-md">
                    Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
                </div>
            @endif

            <p class="text-sm text-gray-600 mb-6">Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan yang baru.</p>

            <form method="POST" action="{{ route('buyer.verification.send') }}">
                @csrf
                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Kirim Ulang Email Verifikasi
                    </button>
                </div>
            </form>

            <div class="mt-6 flex items-center justify-center gap-4">
                <form method="POST" action="{{ route('buyer.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-gray-900 hover:text-indigo-600 underline underline-offset-4">
                        Keluar (Log Out)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
