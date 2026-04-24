<x-guest-layout>
    <div class="text-center py-4">
        {{-- Animated Clock / Pending Icon --}}
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Pengajuan Terkirim!</h2>
        <p class="text-sm text-gray-500 mb-6 leading-relaxed">
            Pendaftaran mitra bisnis Anda sedang kami tinjau.<br>
            Tim kami akan memverifikasi data dan dokumen perusahaan Anda.
        </p>

        {{-- Steps indicator --}}
        <div class="space-y-3 text-left bg-gray-50 rounded-2xl p-5 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-green-500 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Data terkirim</p>
                    <p class="text-xs text-gray-400">Formulir pendaftaran Anda telah diterima</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-amber-400 rounded-full flex items-center justify-center shrink-0 animate-pulse">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Verifikasi oleh Admin</p>
                    <p class="text-xs text-amber-600 font-medium">Sedang dalam proses (1-3 hari kerja)</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Notifikasi Email</p>
                    <p class="text-xs text-gray-400">Anda akan dihubungi via email saat akun aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-left">
            <p class="text-xs text-blue-700 font-semibold mb-1">ℹ️ Informasi Penting</p>
            <p class="text-xs text-blue-600 leading-relaxed">
                Pastikan Anda memantau kotak masuk (dan folder <em>spam</em>) email yang telah didaftarkan.
                Jika dalam 3 hari kerja belum ada konfirmasi, silakan hubungi kami di
                <strong>admin@agrpanganmaju.com</strong> atau WhatsApp <strong>0811-xxxx-xxxx</strong>.
            </p>
        </div>

        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 bg-[#CD422B] text-white font-semibold text-sm px-6 py-3 rounded-xl hover:bg-red-700 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Kembali ke Beranda
        </a>
    </div>
</x-guest-layout>
