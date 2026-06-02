{{-- Logout Confirmation Modal --}}
<div x-data="{ show: false }"
     x-on:open-logout-modal.window="show = true"
     x-cloak
     class="relative z-[99999]">

    {{-- Backdrop --}}
    <div x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm"
         @click="show = false">
    </div>

    {{-- Modal --}}
    <div x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="fixed inset-0 flex items-center justify-center p-4"
         @click.self="show = false">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center border border-gray-100">
            {{-- Icon --}}
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-50 flex items-center justify-center">
                <svg class="w-8 h-8 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>

            {{-- Text --}}
            <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Logout</h3>
            <p class="text-sm text-gray-500 mb-6 leading-relaxed">Apakah Anda yakin ingin keluar dari akun ini?</p>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl bg-gray-100 border border-gray-200 text-sm font-semibold text-gray-500 hover:bg-gray-200 transition">
                        Ya, Logout
                    </button>
                </form>
                <button @click="show = false"
                        class="flex-1 py-2.5 px-4 rounded-xl bg-[#CD422B] text-white text-sm font-bold hover:bg-red-700 transition shadow-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
