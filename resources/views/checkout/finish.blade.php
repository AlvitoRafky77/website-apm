<x-app-layout>
    <title>Status Pembayaran — PT. Agro Pangan Maju</title>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center py-20 px-4">
        <div class="w-full max-w-lg">

            {{-- ====== BERHASIL ====== --}}
            @if($order && $order->payment_status === 'paid')
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-10 text-center">
                {{-- Animasi centang --}}
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce-slow">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Pembayaran Berhasil! 🎉</h1>
                <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                    Pesanan Anda telah dikonfirmasi dan sedang diproses oleh tim kami.<br>
                    Tim kami akan menghubungi Anda via WhatsApp.
                </p>

                {{-- Detail transaksi --}}
                <div class="bg-gray-50 rounded-2xl p-5 text-left space-y-3 mb-8 border border-gray-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">ID Pesanan</span>
                        <span class="font-bold text-gray-900 font-mono">{{ $order->order_id }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Dibayar</span>
                        <span class="font-bold text-[#CD422B] text-base">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    @if($order->midtrans_payment_type)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-semibold text-gray-700 capitalize">{{ str_replace('_', ' ', $order->midtrans_payment_type) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Waktu Bayar</span>
                        <span class="font-semibold text-gray-700">{{ $order->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-[#CD422B] to-[#b83220] text-white font-bold px-8 py-3.5 rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Kembali ke Beranda
                </a>
            </div>

            {{-- ====== PENDING ====== --}}
            @elseif($order && $order->payment_status === 'pending')

            {{-- Muat Snap.js untuk tombol Lanjutkan Pembayaran --}}
            <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                    data-client-key="{{ config('midtrans.client_key') }}"></script>

            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-10 text-center">
                <div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Menunggu Pembayaran ⏳</h1>
                <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                    Pesanan Anda sudah dibuat. Selesaikan pembayaran untuk melanjutkan.<br>
                    Jika sudah bayar, status akan otomatis terupdate.
                </p>

                {{-- Detail --}}
                <div class="bg-amber-50 rounded-2xl p-5 text-left space-y-3 mb-6 border border-amber-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">ID Pesanan</span>
                        <span class="font-bold text-gray-900 font-mono">{{ $order->order_id }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total</span>
                        <span class="font-bold text-amber-600 text-base">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Auto-refresh countdown --}}
                <div class="flex items-center justify-center gap-2 text-xs text-gray-400 mb-6">
                    <svg class="w-3.5 h-3.5 animate-spin text-gray-300" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    Mengecek status... refresh otomatis dalam <span id="countdown" class="font-bold text-gray-600">15</span> detik
                </div>

                <div class="flex flex-col gap-3">
                    {{-- Tombol Lanjutkan Pembayaran (pakai snap token yang tersimpan) --}}
                    @if($order->midtrans_snap_token)
                    <button id="continuePayBtn"
                            onclick="continuePayment('{{ $order->midtrans_snap_token }}', '{{ $order->order_id }}')"
                            class="w-full bg-gradient-to-r from-[#CD422B] to-[#b83220] text-white font-bold py-3.5 rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Lanjutkan Pembayaran
                    </button>
                    @endif

                    {{-- Cek status manual --}}
                    <button onclick="checkStatus()"
                            class="w-full bg-white border-2 border-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:border-[#CD422B] hover:text-[#CD422B] transition flex items-center justify-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Cek Status Sekarang
                    </button>

                    <a href="{{ route('home') }}"
                       class="text-sm text-gray-400 hover:text-gray-600 transition text-center py-2">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

            <script>
                // Auto-refresh countdown
                var seconds = 15;
                var countEl = document.getElementById('countdown');
                var timer = setInterval(function() {
                    seconds--;
                    if (countEl) countEl.textContent = seconds;
                    if (seconds <= 0) {
                        clearInterval(timer);
                        checkStatus();
                    }
                }, 1000);

                // Check status by reloading page (controller will re-check Midtrans API)
                function checkStatus() {
                    window.location.reload();
                }

                // Re-open Midtrans Snap popup with existing token
                function continuePayment(snapToken, orderId) {
                    snap.pay(snapToken, {
                        onSuccess: function(result) {
                            clearInterval(timer);
                            window.location.reload();
                        },
                        onPending: function(result) {
                            clearInterval(timer);
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal. Silakan coba lagi atau hubungi admin.');
                        },
                        onClose: function() {
                            // User closed — just restart countdown
                            seconds = 15;
                        },
                    });
                }
            </script>

            {{-- ====== FAILED / EXPIRED ====== --}}
            @elseif($order && in_array($order->payment_status, ['failed', 'expired']))
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-10 text-center">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-2">
                    {{ $order->payment_status === 'expired' ? 'Pembayaran Kedaluwarsa ⌛' : 'Pembayaran Gagal ❌' }}
                </h1>
                <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                    {{ $order->payment_status === 'expired'
                        ? 'Waktu pembayaran habis. Silakan buat pesanan baru.'
                        : 'Pembayaran tidak berhasil diproses. Silakan coba lagi.' }}
                </p>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#CD422B] to-[#b83220] text-white font-bold px-8 py-3.5 rounded-xl hover:shadow-lg transition-all duration-200 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Kembali ke Katalog
                    </a>
                    <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-gray-600 transition text-center py-1">
                        Ke Beranda
                    </a>
                </div>
            </div>

            {{-- ====== DEFAULT / TIDAK DIKETAHUI ====== --}}
            @else
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-10 text-center">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Pesanan Diterima</h1>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                    Terima kasih! Tim kami akan menghubungi Anda via WhatsApp untuk konfirmasi pesanan.
                </p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-[#CD422B] to-[#b83220] text-white font-bold px-8 py-3.5 rounded-xl hover:shadow-lg transition-all duration-200 shadow-md">
                    Kembali ke Beranda
                </a>
            </div>
            @endif

        </div>
    </div>

    <style>
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .animate-bounce-slow { animation: bounce-slow 2s infinite; }
    </style>

    @include('partials._footer')
</x-app-layout>
