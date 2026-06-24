<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
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
        
        <div class="min-h-screen flex">
            <!-- Left Side / Branding -->
            <div class="hidden lg:flex lg:w-1/2 bg-brand flex-col justify-center items-center p-12 relative overflow-hidden">
                <!-- Decorative Circles -->
                <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-accent opacity-20 rounded-full translate-x-1/3 translate-y-1/3"></div>
                
                <div class="z-10 text-center flex flex-col items-center">
                    <img src="{{ asset('images/APM-Logo.webp') }}" alt="Logo PT Agro Pangan Maju" class="w-28 h-28 object-contain mb-6 drop-shadow-xl border-4 border-white/20 rounded-full bg-white/10 p-2" />
                    <h1 class="text-4xl font-extrabold text-white mb-4 tracking-tight">PT. Agro Pangan Maju</h1>
                    <p class="text-accent-light text-lg mb-8 max-w-md mx-auto">Distributor bahan baku makanan terbaik. Melayani pemesanan partner B2B dan pembelian retail B2C di seluruh Indonesia.</p>
                </div>
            </div>

            <!-- Right Side / Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center py-10 px-4 sm:px-0 bg-accent-light/10">
                <div class="lg:hidden mb-8 text-center flex flex-col items-center">
                    <img src="{{ asset('images/APM-Logo.webp') }}" alt="Logo PT Agro Pangan Maju" class="w-20 h-20 object-contain rounded-full shadow-md" />
                    <h2 class="mt-4 text-2xl font-extrabold text-brand-dark">Agro Pangan Maju</h2>
                </div>

                <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] overflow-hidden sm:rounded-3xl border border-gray-100">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
