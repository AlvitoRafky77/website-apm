<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PT Agro Pangan Maju') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white">
        {{-- Global Page Loader --}}
        <div id="apm-preloader" class="fixed inset-0 z-[99999] bg-white flex flex-col items-center justify-center transition-opacity duration-500">
            <div class="relative w-16 h-16">
                <!-- Outer ring -->
                <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
                <!-- Spinning ring -->
                <div class="absolute inset-0 rounded-full border-4 border-[#CD422B] border-t-transparent animate-spin"></div>
                <!-- Center Logo -->
                <img src="{{ asset('images/APM_Logo.webp') }}" class="absolute inset-0 m-auto w-8 h-8 object-contain animate-pulse" alt="APM">
            </div>
            <p class="mt-4 text-[10px] font-black text-[#CD422B] tracking-[0.2em] uppercase animate-pulse">Memuat...</p>
        </div>
        <script>
            function hidePreloader() {
                const loader = document.getElementById('apm-preloader');
                if (loader && loader.style.display !== 'none') {
                    loader.style.pointerEvents = 'none';
                    loader.style.opacity = '0';
                    setTimeout(() => { loader.style.display = 'none'; }, 500);
                }
            }
            window.addEventListener('load', hidePreloader);
            // Fallback: hide preloader after 3s max even if 'load' hasn't fired
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(hidePreloader, 3000);
            });
        </script>

        {{-- Conditional Navbar: B2B vs Default --}}
        @if(Auth::check() && Auth::user()->isB2b())
            @include('partials._navbar_b2b')
        @else
            @include('partials._navbar')
        @endif
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        @stack('scripts')
    </body>
</html>
