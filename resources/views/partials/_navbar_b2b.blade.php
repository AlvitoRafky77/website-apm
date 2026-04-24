<header class="w-full bg-white/95 backdrop-blur-md px-6 lg:px-12 py-5 z-50 sticky top-0 shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-4">
            <a href="{{ route('b2b.dashboard') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/APM_Logo.webp') }}" alt="Logo APM" class="h-12 w-12 object-contain">
                <div class="flex flex-col">
                    <span class="text-lg font-bold text-[#CD422B] tracking-tight leading-none">Agro Pangan Maju</span>
                    <span class="text-xs uppercase font-black text-gray-500 tracking-widest mt-1">B2B Portal</span>
                </div>
            </a>
        </div>
        
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
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm py-2">Keluar</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </nav>
    </div>
</header>
