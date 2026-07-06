<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pendaftaran Mitra Bisnis B2B — PT. Agro Pangan Maju</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 min-h-screen">

    {{-- Top Navbar Minimal --}}
    <header class="w-full bg-white border-b border-gray-100 px-4 sm:px-6 py-3 sticky top-0 z-40 shadow-sm">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group">
                <img src="{{ asset('images/APM_Logo.webp') }}" alt="APM" class="h-8 w-8 sm:h-9 sm:w-9 object-contain">
                <span class="font-bold text-[#CD422B] tracking-tight text-xs sm:text-sm">Agro Pangan Maju</span>
            </a>
            <a href="{{ route('login') }}" class="text-xs sm:text-sm text-gray-500 hover:text-[#CD422B] font-semibold transition-colors">
                <span class="hidden sm:inline text-gray-400 font-normal">Sudah punya akun? </span>
                <span class="text-[#CD422B] hover:underline">Masuk →</span>
            </a>
        </div>
    </header>

    {{-- Progress Banner --}}
    <div class="w-full bg-gradient-to-r from-[#CD422B] to-[#a83220] py-6 px-6">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-block bg-white/20 text-white text-xs font-bold px-2.5 py-1 rounded-full">Mitra Bisnis (B2B)</span>
                    <span class="text-white/60 text-xs">Langkah 1 dari 1</span>
                </div>
                <h1 class="text-white text-xl font-extrabold">Pendaftaran Perusahaan</h1>
                <p class="text-white/70 text-sm mt-0.5">Lengkapi semua data untuk mengajukan kemitraan bisnis</p>
            </div>
            {{-- Steps --}}
            <div class="flex items-center gap-2 shrink-0">
                <div class="flex items-center gap-1.5">
                    <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <span class="text-white text-xs font-semibold hidden sm:block">Isi Formulir</span>
                </div>
                <div class="w-8 h-px bg-white/30"></div>
                <div class="flex items-center gap-1.5 opacity-50">
                    <div class="w-7 h-7 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-white text-xs font-semibold hidden sm:block">Verifikasi Admin</span>
                </div>
                <div class="w-8 h-px bg-white/30"></div>
                <div class="flex items-center gap-1.5 opacity-50">
                    <div class="w-7 h-7 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-white text-xs font-semibold hidden sm:block">Akun Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Form Area --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        {{ $slot }}
    </main>

    {{-- Footer minimal --}}
    <footer class="text-center py-6 text-xs text-gray-400 border-t border-gray-100 mt-8">
        © {{ date('Y') }} PT. Agro Pangan Maju. Semua hak dilindungi.
        <span class="mx-2">·</span>
        <a href="{{ route('home') }}" class="hover:text-[#CD422B] transition">Kembali ke Beranda</a>
    </footer>
</body>
</html>
