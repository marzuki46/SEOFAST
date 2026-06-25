<div class="my-8 max-w-sm mx-auto bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden relative group">
    <!-- Decorative Gradient -->
    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-brand-indigo to-brand-purple"></div>

    <div class="p-8">
        <!-- Header -->
        <div class="text-center mb-6">
            <h4 class="text-xl font-bold text-slate-900 font-outfit mb-2">{{ $product->name }}</h4>
            <div class="flex items-center justify-center gap-1.5 mb-2">
                <span class="text-sm font-semibold text-slate-500">Rp</span>
                <span class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
            @if($product->description)
                <p class="text-sm text-slate-500 font-medium">{{ $product->description }}</p>
            @endif
        </div>

        <!-- Features List -->
        @if($product->features && count($product->features) > 0)
        <div class="space-y-3 mb-8">
            @foreach($product->features as $feature)
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center mt-0.5">
                    <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <span class="text-sm text-slate-700 font-medium">{{ $feature }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Checkout Action -->
        <div class="mt-auto">
            <button class="w-full bg-slate-900 text-white rounded-xl py-3.5 px-4 font-bold text-sm shadow-md hover:bg-slate-800 hover:shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                Checkout Now
            </button>
            <p class="text-center text-xs text-slate-400 font-medium mt-3 flex items-center justify-center gap-1.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                Secure payment via Midtrans
            </p>
        </div>
    </div>
</div>
