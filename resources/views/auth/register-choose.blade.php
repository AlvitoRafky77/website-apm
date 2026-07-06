<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Daftar Akun</h2>
        <p class="text-sm text-gray-500">Pilih tipe akun yang sesuai kebutuhan Anda</p>
    </div>

    <div class="space-y-4">
        {{-- B2C Card --}}
        <a href="{{ route('register.b2c') }}" id="btn-register-b2c"
           class="group flex items-center gap-3.5 sm:gap-5 w-full p-4 sm:p-5 border-2 border-gray-200 rounded-2xl hover:border-[#CD422B] hover:bg-red-50/60 transition-all duration-300 text-left cursor-pointer">
            <div class="w-14 h-14 bg-orange-100 group-hover:bg-[#CD422B] rounded-2xl flex items-center justify-center shrink-0 transition-colors duration-300">
                <svg class="w-7 h-7 text-[#CD422B] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-1.5">
                    <span class="font-bold text-gray-900 text-base">Pelanggan Retail (B2C)</span>
                    <span class="inline-block text-[11px] bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full self-start">Langsung Aktif</span>
                </div>
                <p class="text-sm text-gray-500">Untuk pembelian individu. Akun langsung aktif setelah mendaftar.</p>
                <ul class="mt-2 text-xs text-gray-400 space-y-0.5">
                    <li>• Akses katalog produk</li>
                    <li>• Pembelian eceran</li>
                </ul>
            </div>
            <svg class="w-5 h-5 text-gray-300 group-hover:text-[#CD422B] transition-colors duration-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- B2B Card --}}
        <a href="{{ route('register.b2b') }}" id="btn-register-b2b"
           class="group flex items-center gap-3.5 sm:gap-5 w-full p-4 sm:p-5 border-2 border-gray-200 rounded-2xl hover:border-[#CD422B] hover:bg-red-50/60 transition-all duration-300 text-left cursor-pointer">
            <div class="w-14 h-14 bg-blue-100 group-hover:bg-[#CD422B] rounded-2xl flex items-center justify-center shrink-0 transition-colors duration-300">
                <svg class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-1.5">
                    <span class="font-bold text-gray-900 text-base">Mitra Bisnis (B2B)</span>
                    <span class="inline-block text-[11px] bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full self-start">Perlu Persetujuan</span>
                </div>
                <p class="text-sm text-gray-500">Untuk perusahaan, hotel, katering, dan mitra bisnis. Perlu verifikasi dokumen.</p>
                <ul class="mt-2 text-xs text-gray-400 space-y-0.5">
                    <li>• Harga kontrak khusus</li>
                    <li>• Pembelian partai besar</li>
                    <li>• Layanan distribusi prioritas</li>
                </ul>
            </div>
            <svg class="w-5 h-5 text-gray-300 group-hover:text-[#CD422B] transition-colors duration-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <p class="mt-6 text-center text-sm text-gray-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-bold text-[#CD422B] hover:text-red-700 transition-colors">Masuk di sini</a>
    </p>
</x-guest-layout>
