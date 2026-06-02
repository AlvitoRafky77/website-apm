<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin CMS - @yield('title', 'Dashboard') | APM</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .sidebar-link.active { background-color: rgba(205,66,43,0.15); color: #CD422B; font-weight: 600; }
        .sidebar-link.active svg { color: #CD422B; }
    </style>
</head>
<body class="bg-gray-100 antialiased text-gray-800">
    {{-- Global Page Loader --}}
    <div id="apm-preloader" class="fixed inset-0 z-[99999] bg-white flex flex-col items-center justify-center transition-opacity duration-300 pointer-events-none select-none">
        <div class="relative w-12 h-12">
            <div class="absolute inset-0 rounded-full border-3 border-gray-100"></div>
            <div class="absolute inset-0 rounded-full border-3 border-[#CD422B] border-t-transparent animate-spin"></div>
            <img src="{{ asset('images/APM_Logo.webp') }}" class="absolute inset-0 m-auto w-6 h-6 object-contain" alt="APM">
        </div>
        <p class="mt-3 text-[9px] font-black text-[#CD422B] tracking-[0.18em] uppercase">Memuat...</p>
    </div>
    <script>
        (function () {
            function hideAdminPreloader() {
                const loader = document.getElementById('apm-preloader');
                if (!loader || loader.dataset.hidden === '1') return;

                loader.dataset.hidden = '1';
                loader.style.opacity = '0';
                setTimeout(() => { loader.style.display = 'none'; }, 120);
            }

            if (document.readyState === 'interactive' || document.readyState === 'complete') {
                hideAdminPreloader();
                return;
            }

            document.addEventListener('DOMContentLoaded', hideAdminPreloader, { once: true });
            window.addEventListener('load', hideAdminPreloader, { once: true });
            window.addEventListener('pageshow', hideAdminPreloader, { once: true });
            setTimeout(hideAdminPreloader, 450);
        })();
    </script>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-xl flex flex-col fixed top-0 left-0 h-full z-40">
        <!-- Logo -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
            <img src="{{ asset('images/APM_Logo.webp') }}" alt="APM" class="w-9 h-9 object-contain">
            <div>
                <p class="font-black text-[#CD422B] text-sm tracking-tight leading-none">Agro Pangan Maju</p>
                <p class="text-[10px] text-gray-400 tracking-wide uppercase mt-0.5">Admin CMS</p>
            </div>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition-all group {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CD422B] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <!-- Divider -->
            <div class="pt-3 pb-1"><p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-4">Konten</p></div>

            <!-- Produk -->
            <a href="{{ route('admin.products.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition-all group {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CD422B] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Produk
            </a>

            <!-- Kategori -->
            <a href="{{ route('admin.categories.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition-all group {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CD422B] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kategori
            </a>

            <!-- Divider -->
            <div class="pt-3 pb-1"><p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-4">Pelanggan</p></div>

            <!-- Manajemen Pesanan -->
            @php
                $pendingOrders = \App\Models\Order::whereIn('status', ['pending', 'confirmed', 'processing'])
                    ->whereNotIn('payment_status', ['expired', 'failed'])
                    ->count();
            @endphp
            <a href="{{ route('admin.orders.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition-all group {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CD422B] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span class="flex-1">Transaksi B2C</span>
                @if($pendingOrders > 0)
                    <span class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full leading-none shadow-sm min-w-[20px] text-center" title="Pesanan baru menunggu diproses">
                        {{ $pendingOrders > 99 ? '99+' : $pendingOrders }}
                    </span>
                @endif
            </a>

            <!-- Keuangan (with submenu) -->
            <div x-data="{ open: {{ request()->routeIs('admin.finances*') ? 'true' : 'false' }} }">
                <button @click="open = !open" type="button"
                   class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition-all group {{ request()->routeIs('admin.finances*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CD422B] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V6m0 12v2m8-8a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
                    <span class="flex-1 text-left">Keuangan</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition.duration.200ms class="ml-8 mt-1 space-y-0.5">
                    <a href="{{ route('admin.finances.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-gray-500 hover:bg-red-50 hover:text-[#CD422B] transition {{ request()->routeIs('admin.finances.index') && !request('customer_type') ? 'text-[#CD422B] bg-red-50/60' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                        Semua
                    </a>
                    <a href="{{ route('admin.finances.index', ['customer_type' => 'b2c']) }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-gray-500 hover:bg-red-50 hover:text-[#CD422B] transition {{ request('customer_type') === 'b2c' ? 'text-[#CD422B] bg-red-50/60' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                        Keuangan B2C
                    </a>
                    <a href="{{ route('admin.finances.index', ['customer_type' => 'b2b']) }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold text-gray-500 hover:bg-red-50 hover:text-[#CD422B] transition {{ request('customer_type') === 'b2b' ? 'text-[#CD422B] bg-red-50/60' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Keuangan B2B
                    </a>
                </div>
            </div>

            <!-- Pesan Kontak -->
            @php
                $unreadMessages = \App\Models\ContactMessage::where('status', 'new')->count();
            @endphp
            <a href="{{ route('admin.contact-messages.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition-all group {{ request()->routeIs('admin.contact-messages*') ? 'active' : '' }}">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CD422B] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m-6 8h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="flex-1">Pesan Kontak</span>
                @if($unreadMessages > 0)
                    <span class="bg-[#CD422B] text-white text-[10px] font-black px-2 py-0.5 rounded-full leading-none shadow-sm" title="Ada Pesan Baru">
                        {{ $unreadMessages > 99 ? '99+' : $unreadMessages }}
                    </span>
                @endif
            </a>

            <!-- Registrasi B2B -->
            <a href="{{ route('admin.b2b.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition-all group {{ request()->routeIs('admin.b2b.index') || request()->routeIs('admin.b2b.show') || request()->routeIs('admin.b2b.approve') || request()->routeIs('admin.b2b.reject') || request()->routeIs('admin.b2b.mou') ? 'active' : '' }}">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CD422B] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011-1v5m-4 0h4"></path></svg>
                <span class="flex-1">Registrasi B2B</span>
                @php $pendingRegCount = \App\Models\B2bRegistration::where('status','pending')->count(); @endphp
                @if($pendingRegCount > 0)
                    <span class="bg-orange-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full leading-none shadow-sm">{{ $pendingRegCount }}</span>
                @endif
            </a>

            {{-- Order B2B --}}
            <a href="{{ route('admin.b2b.orders.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition-all group {{ request()->routeIs('admin.b2b.orders*') ? 'active' : '' }}">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CD422B] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span class="flex-1">Transaksi B2B</span>
                @php
                    $b2bPendingReview = \App\Models\B2bOrder::where('status', 'pending_review')->count();
                    $b2bDisputeCount = \App\Models\B2bOrder::whereIn('dispute_status', ['open','reviewing'])->count();
                    $b2bPendingPayments = \App\Models\B2bOrder::whereIn('status', ['dp_uploaded','settlement_uploaded'])->count();
                @endphp

                <div class="flex items-center gap-1.5 ml-auto">
                    @if($b2bPendingReview > 0)
                        <span class="bg-orange-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full leading-none shadow-sm" title="Perlu Review Ongkir">
                            {{ $b2bPendingReview }}
                        </span>
                    @endif
                    @if($b2bDisputeCount > 0)
                        <span class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full leading-none shadow-sm" title="Ada Komplain">
                            {{ $b2bDisputeCount }}
                        </span>
                    @endif
                    @if($b2bPendingPayments > 0)
                        <span class="bg-emerald-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full leading-none shadow-sm" title="Perlu Verifikasi Bayar">
                            {{ $b2bPendingPayments }}
                        </span>
                    @endif
                </div>
            </a>

            {{-- Tarif Ongkir --}}
            <a href="{{ route('admin.shipping-rates.index') }}"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition-all group {{ request()->routeIs('admin.shipping-rates*') ? 'active' : '' }}">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CD422B] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="flex-1">Tarif Ongkir</span>
            </a>
        </nav>

        <!-- User & Logout -->
        <div class="px-4 py-5 border-t border-gray-100">
            <div class="flex items-center gap-3 mb-3 px-2">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=CD422B&color=fff&bold=true" class="w-9 h-9 rounded-full" alt="Admin">
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">Administrator</p>
                </div>
            </div>
            <div class="space-y-2">
                {{-- Lihat Website --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-center gap-2 text-xs text-gray-500 hover:text-[#CD422B] py-2 px-3 rounded-lg hover:bg-red-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Website
                        <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition @click.away="open = false"
                         class="mt-1 bg-gray-50 rounded-xl border border-gray-100 overflow-hidden">
                        <a href="{{ route('home') }}" target="_blank"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Halaman Utama
                            <svg class="w-3 h-3 ml-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <a href="{{ route('b2b.dashboard') }}" target="_blank"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-gray-600 hover:bg-red-50 hover:text-[#CD422B] transition border-t border-gray-100">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Dashboard Mitra
                            <svg class="w-3 h-3 ml-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Logout --}}
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-logout-modal'))" class="w-full flex items-center justify-center gap-2 text-xs text-gray-500 hover:text-red-600 py-2 px-3 rounded-lg hover:bg-red-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        <!-- Top Header -->
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h1 class="font-bold text-gray-900 text-lg">@yield('title', 'Dashboard')</h1>
                <p class="text-xs text-gray-400">@yield('subtitle', 'Admin CMS - PT Agro Pangan Maju')</p>
            </div>
            <div class="flex items-center gap-3">
                @if(session('success'))
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-4 py-2 rounded-full">✓ {{ session('success') }}</span>
                @endif
                @if(session('error'))
                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-4 py-2 rounded-full">✗ {{ session('error') }}</span>
                @endif
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</div>

@include('partials._logout_modal')

</body>
@stack('scripts')
</html>
