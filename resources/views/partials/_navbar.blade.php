<header class="w-full bg-white/95 backdrop-blur-md px-6 lg:px-12 py-4 z-50 sticky top-0 shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto flex justify-between items-center">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('images/APM_Logo.webp') }}" alt="Logo APM" class="h-10 w-10 object-contain group-hover:scale-105 transition-transform">
            <span class="font-bold text-[#CD422B] tracking-tight hidden sm:block">Agro Pangan Maju</span>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center gap-1.5 lg:gap-2.5 text-sm font-semibold text-gray-600">
            <a href="{{ route('home') }}"
               class="px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'text-[#CD422B] bg-red-50' : 'hover:text-[#CD422B] hover:bg-red-50' }} transition-all duration-200">Home</a>
            <a href="{{ route('about') }}"
               class="px-3 py-2 rounded-lg {{ request()->routeIs('about') ? 'text-[#CD422B] bg-red-50' : 'hover:text-[#CD422B] hover:bg-red-50' }} transition-all duration-200">About Us</a>
            <a href="{{ route('products.index') }}"
               class="px-3 py-2 rounded-lg {{ request()->routeIs('products*') ? 'text-[#CD422B] bg-red-50' : 'hover:text-[#CD422B] hover:bg-red-50' }} transition-all duration-200">Product</a>
            <a href="{{ route('contact') }}"
               class="px-3 py-2 rounded-lg {{ request()->routeIs('contact') ? 'text-[#CD422B] bg-red-50' : 'hover:text-[#CD422B] hover:bg-red-50' }} transition-all duration-200">Contact Us</a>
            @auth
                @if(!Auth::user()->hasRole('admin') && !Auth::user()->isB2b())
                    <a href="{{ route('orders.index') }}"
                       class="px-3 py-2 rounded-lg {{ request()->routeIs('orders*') ? 'text-[#CD422B] bg-red-50' : 'hover:text-[#CD422B] hover:bg-red-50' }} transition-all duration-200">Pesanan Saya</a>
                @endif
            @endauth

            <!-- Separator -->
            <div class="h-5 w-px bg-gray-200 mx-2 lg:mx-3"></div>

            <!-- ===== CART ICON with hover dropdown ===== -->
            @auth
                @if(!Auth::user()->hasRole('admin') && !Auth::user()->isB2b())
                <div class="relative" id="cartDropdownWrapper">
                    <!-- Cart Button -->
                    <a href="{{ route('checkout.index') }}"
                       id="cartNavBtn"
                       class="relative flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-red-50 hover:text-[#CD422B] transition-all duration-200 group"
                       aria-label="Keranjang Belanja">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <!-- Badge -->
                        <span id="cartBadge"
                              data-cart-badge
                              style="display:none;"
                              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-[#CD422B] text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 shadow-sm animate-bounce-once">
                            0
                        </span>
                    </a>

                    {{-- ===== SINGLE DROPDOWN: Keranjang Belanja ===== --}}
                    <div id="cartDropdown"
                         class="absolute right-0 top-full mt-2 w-88 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50 opacity-0 invisible translate-y-2 transition-all duration-200 pointer-events-none">

                        {{-- Header Keranjang --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                            <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5 whitespace-nowrap">
                                <svg class="w-4 h-4 text-[#CD422B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Keranjang Belanja
                            </span>
                            <span id="cartDropCount" class="text-[11px] font-semibold text-gray-400 whitespace-nowrap shrink-0">0 item</span>
                        </div>

                        {{-- Cart Items --}}
                        <div id="tabCartPanel">
                            <div id="cartDropItems" class="max-h-64 overflow-y-auto divide-y divide-gray-50 px-1 py-1">
                                <div id="cartDropEmpty" class="flex flex-col items-center justify-center py-8 text-gray-400">
                                    <svg class="w-10 h-10 mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <p class="text-xs font-medium">Keranjang kosong</p>
                                    <p class="text-[11px] text-gray-300 mt-0.5">Tambahkan produk dari katalog</p>
                                </div>
                            </div>
                            <div id="cartDropFooter" class="hidden border-t border-gray-100 bg-gray-50/50 px-4 py-3">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs text-gray-500 font-medium">Total:</span>
                                    <span id="cartDropTotal" class="font-black text-[#CD422B] text-sm">Rp 0</span>
                                </div>
                                <a href="{{ route('checkout.index') }}"
                                   class="block w-full bg-gradient-to-r from-[#CD422B] to-[#b83220] text-white text-center font-bold text-xs py-2.5 rounded-xl hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                                    Lanjut ke Checkout →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endauth

            <!-- Auth Links -->
            <div class="flex items-center gap-2">
                @auth
                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2.5 bg-gray-50 border border-gray-100/80 rounded-full py-1.5 pr-3 pl-1.5 shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-300 group focus:outline-none">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=CD422B&color=fff&bold=true"
                                     alt="Profile" class="w-8 h-8 rounded-full border border-white shadow-sm group-hover:scale-105 transition-transform">
                                <span class="font-bold text-gray-700 text-sm tracking-tight group-hover:text-[#CD422B] transition-colors">{{ explode(' ', Auth::user()->name)[0] }}</span>
                                <svg class="fill-current h-4 w-4 text-gray-400 group-hover:text-[#CD422B] transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-gray-50">
                                <p class="text-xs font-bold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            @if(!Auth::user()->hasRole('admin') && !Auth::user()->isB2b())
                                <x-dropdown-link :href="route('orders.index')">Pesanan Saya</x-dropdown-link>
                            @endif
                            <x-dropdown-link :href="route('profile.edit')">Profil Saya</x-dropdown-link>
                            @if(Auth::user()->isB2b())
                                <x-dropdown-link :href="route('b2b.dashboard')">Dashboard B2B</x-dropdown-link>
                            @endif
                            @if(Auth::user()->hasRole('admin'))
                                <x-dropdown-link :href="route('admin.dashboard')">Admin CMS</x-dropdown-link>
                            @endif
                            <div class="border-t border-gray-100 my-1"></div>
                            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-logout-modal'))" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Keluar
                            </button>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 text-gray-600 font-bold hover:text-[#CD422B] transition text-sm">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-5 py-2 bg-[#CD422B] text-white font-bold text-sm rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg hover:-translate-y-0.5 transform duration-200">Register</a>
                    @endif
                @endauth
            </div>
        </nav>

        <!-- Mobile Right Side: Cart + Hamburger -->
        <div class="md:hidden flex items-center gap-2">
            @auth
                @if(!Auth::user()->hasRole('admin') && !Auth::user()->isB2b())
                <a href="{{ route('checkout.index') }}" class="relative p-2 text-gray-600 hover:text-[#CD422B] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span id="cartBadgeMobile" data-cart-badge style="display:none;"
                          class="absolute top-0.5 right-0.5 min-w-[16px] h-4 bg-[#CD422B] text-white text-[9px] font-bold rounded-full flex items-center justify-center px-0.5">0</span>
                </a>
                @endif
            @endauth
            <button id="apm-mobile-btn" class="text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition" aria-label="Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown -->
    <div id="apm-mobile-menu" class="hidden md:hidden mt-3 pb-3 border-t border-gray-100">
        <nav class="flex flex-col gap-1 pt-3">
            <a href="{{ route('home') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-[#CD422B] transition">Home</a>
            <a href="{{ route('about') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-[#CD422B] transition">About Us</a>
            <a href="{{ route('products.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-[#CD422B] transition">Product</a>
            <a href="{{ route('contact') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-[#CD422B] transition">Contact Us</a>
            @auth
                @if(!Auth::user()->hasRole('admin') && !Auth::user()->isB2b())
                    <a href="{{ route('orders.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-[#CD422B] transition">Pesanan Saya</a>
                @endif
                <div class="flex items-center gap-3 px-4 py-2.5 mt-1 border-t border-gray-100 pt-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=CD422B&color=fff&bold=true"
                         class="w-8 h-8 rounded-full" alt="Avatar">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                @if(Auth::user()->isB2b())
                    <a href="{{ route('b2b.dashboard') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-emerald-700 hover:bg-emerald-50 transition">Dashboard B2B</a>
                @endif
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-blue-700 hover:bg-blue-50 transition">Admin CMS</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Profil Saya</a>
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-logout-modal'))" class="w-full mt-1 py-2.5 bg-red-50 text-[#CD422B] rounded-xl text-sm font-semibold hover:bg-red-100 transition text-center">
                    Keluar
                </button>
            @else
                <div class="flex gap-3 pt-2 px-4">
                    <a href="{{ route('login') }}" class="flex-1 text-center py-2 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:border-[#CD422B] hover:text-[#CD422B] transition">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="flex-1 text-center py-2 bg-[#CD422B] text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition">Register</a>
                    @endif
                </div>
            @endauth
        </nav>
    </div>
</header>

<script>
(function() {
    // ===== Mobile menu toggle =====
    var btn  = document.getElementById('apm-mobile-btn');
    var menu = document.getElementById('apm-mobile-menu');
    if (btn && menu) {
        btn.addEventListener('click', function() { menu.classList.toggle('hidden'); });
    }

    // ===== Cart dropdown hover =====
    var wrapper  = document.getElementById('cartDropdownWrapper');
    var dropdown = document.getElementById('cartDropdown');
    if (!wrapper || !dropdown) return;

    var hoverTimer;

    function showDrop() {
        clearTimeout(hoverTimer);
        dropdown.classList.remove('opacity-0','invisible','translate-y-2','pointer-events-none');
        dropdown.classList.add('opacity-100','visible','translate-y-0');
        loadCartData();
    }
    function hideDrop() {
        hoverTimer = setTimeout(function() {
            dropdown.classList.add('opacity-0','invisible','translate-y-2','pointer-events-none');
            dropdown.classList.remove('opacity-100','visible','translate-y-0');
        }, 120);
    }

    wrapper.addEventListener('mouseenter', showDrop);
    wrapper.addEventListener('mouseleave', hideDrop);
    dropdown.addEventListener('mouseenter', function() { clearTimeout(hoverTimer); });
    dropdown.addEventListener('mouseleave', hideDrop);

    // ===== Load cart data from server =====
    function formatRp(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(n); }

    function loadCartData() {
        fetch('{{ route('cart.data') }}')
          .then(function(r) { return r.json(); })
          .then(function(data) {
              // Update badge(s)
              document.querySelectorAll('[data-cart-badge]').forEach(function(el) {
                  if (data.count > 0) {
                      el.textContent = data.count > 99 ? '99+' : data.count;
                      el.style.display = 'flex';
                  } else {
                      el.style.display = 'none';
                  }
              });

              // Update count label
              var countEl = document.getElementById('cartDropCount');
              if (countEl) countEl.textContent = data.count + ' item';

              // Populate items
              var itemsEl  = document.getElementById('cartDropItems');
              var emptyEl  = document.getElementById('cartDropEmpty');
              var footerEl = document.getElementById('cartDropFooter');
              var totalEl  = document.getElementById('cartDropTotal');

              if (!itemsEl) return;

              // Remove old item rows
              itemsEl.querySelectorAll('.cart-drop-item').forEach(function(e) { e.remove(); });

              if (data.items && data.items.length > 0) {
                  if (emptyEl) emptyEl.classList.add('hidden');
                  if (footerEl) footerEl.classList.remove('hidden');
                  if (totalEl) totalEl.textContent = formatRp(data.total);

                  data.items.forEach(function(item) {
                      var row = document.createElement('div');
                      row.className = 'cart-drop-item flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 rounded-xl transition';
                      row.innerHTML =
                          '<div class="w-11 h-11 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">' +
                              (item.image
                                  ? '<img src="' + item.image + '" alt="' + item.name + '" class="w-full h-full object-cover">'
                                  : '<div class="w-full h-full flex items-center justify-center text-gray-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>') +
                          '</div>' +
                          '<div class="flex-1 min-w-0">' +
                              '<p class="text-xs font-semibold text-gray-800 truncate">' + item.name + '</p>' +
                              '<p class="text-[11px] text-gray-400">' + item.quantity + ' × ' + formatRp(item.price) + '</p>' +
                          '</div>' +
                          '<p class="text-xs font-bold text-[#CD422B] flex-shrink-0">' + formatRp(item.price * item.quantity) + '</p>';
                      itemsEl.insertBefore(row, emptyEl);
                  });
              } else {
                  if (emptyEl) emptyEl.classList.remove('hidden');
                  if (footerEl) footerEl.classList.add('hidden');
              }
          })
          .catch(function() {});
    }

    // Initial load for badge
    loadCartData();

    // Expose globally so product pages can call it after adding to cart
    window.refreshCartBadge = loadCartData;
})();
</script>

{{-- ===== Chatbot Widget (muncul di semua halaman) ===== --}}
@include('partials._chatbot')

{{-- ===== Logout Confirmation Modal ===== --}}
@auth
    @include('partials._logout_modal')
@endauth
