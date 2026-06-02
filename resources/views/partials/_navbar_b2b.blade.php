<header class="w-full bg-white/95 backdrop-blur-md px-4 sm:px-6 lg:px-12 py-4 sm:py-5 z-50 sticky top-0 shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3 sm:gap-4">
            <a href="{{ route('b2b.dashboard') }}" class="flex items-center gap-2 sm:gap-3 group">
                <img src="{{ asset('images/APM_Logo.webp') }}" alt="Logo APM" class="h-9 w-9 sm:h-12 sm:w-12 object-contain">
                <div class="flex flex-col">
                    <span class="text-sm sm:text-lg font-bold text-[#CD422B] tracking-tight leading-none">Agro Pangan Maju</span>
                    <span class="text-[10px] sm:text-xs uppercase font-black text-gray-500 tracking-widest mt-0.5 sm:mt-1">B2B Portal</span>
                </div>
            </a>
        </div>
        
        {{-- Desktop Nav --}}
        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ route('b2b.dashboard') }}" class="text-base font-bold {{ request()->routeIs('b2b.dashboard') ? 'text-[#CD422B] border-b-2 border-[#CD422B] pb-1' : 'text-gray-500 hover:text-gray-900 transition' }}">Dashboard</a>
            <a href="{{ route('b2b.orders.index') }}" class="text-base font-semibold {{ request()->routeIs('b2b.orders.index') || request()->routeIs('b2b.orders.show') ? 'text-[#CD422B] border-b-2 border-[#CD422B] pb-1' : 'text-gray-500 hover:text-gray-900 transition' }}">Pesanan B2B</a>
            <a href="{{ route('b2b.orders.create') }}" class="text-base font-semibold {{ request()->routeIs('b2b.orders.create') ? 'text-[#CD422B] border-b-2 border-[#CD422B] pb-1' : 'text-gray-500 hover:text-gray-900 transition' }}">Order Baru</a>
            
            <div class="ml-6 pl-6 border-l border-gray-200">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 bg-gray-50 border border-gray-100/80 rounded-full py-2 pr-3 pl-2 shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-300 group focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1e293b&color=fff&bold=true" alt="Profile" class="w-9 h-9 rounded-full border border-white shadow-sm">
                            <div class="flex flex-col text-left mr-2">
                                <span class="font-bold text-gray-800 text-sm tracking-tight group-hover:text-[#CD422B] transition-colors leading-none">{{ Auth::user()->name }}</span>
                                <span class="text-xs font-medium text-gray-500 mt-0.5">Mitra B2B</span>
                            </div>
                            <svg class="fill-current h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-sm py-2">Profil Akses</x-dropdown-link>
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-logout-modal'))" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            Keluar
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>
        </nav>

        {{-- Mobile Hamburger --}}
        <button id="b2b-mobile-btn" class="md:hidden text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition" aria-label="Menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div id="b2b-mobile-menu" class="hidden md:hidden mt-3 pb-3 border-t border-gray-100">
        <nav class="flex flex-col gap-1 pt-3">
            <a href="{{ route('b2b.dashboard') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('b2b.dashboard') ? 'text-[#CD422B] bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-[#CD422B]' }} transition">Dashboard</a>
            <a href="{{ route('b2b.orders.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('b2b.orders.*') ? 'text-[#CD422B] bg-red-50' : 'text-gray-700 hover:bg-red-50 hover:text-[#CD422B]' }} transition">Pesanan B2B</a>
            <a href="{{ route('b2b.orders.create') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-[#CD422B] transition">+ Order Baru</a>
            <a href="{{ route('b2b.contact.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-[#CD422B] transition">Hubungi AM</a>

            <div class="flex items-center gap-3 px-4 py-2.5 mt-1 border-t border-gray-100 pt-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1e293b&color=fff&bold=true" class="w-8 h-8 rounded-full" alt="Avatar">
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">Mitra B2B</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Profil Akses</a>
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-logout-modal'))" class="w-full mt-1 py-2.5 bg-red-50 text-[#CD422B] rounded-xl text-sm font-semibold hover:bg-red-100 transition text-center">
                Keluar
            </button>
        </nav>
    </div>
</header>

<script>
(function() {
    var btn = document.getElementById('b2b-mobile-btn');
    var menu = document.getElementById('b2b-mobile-menu');
    if (btn && menu) {
        btn.addEventListener('click', function() { menu.classList.toggle('hidden'); });
    }
})();
</script>

{{-- ===== Logout Confirmation Modal ===== --}}
@auth
    @include('partials._logout_modal')
@endauth
