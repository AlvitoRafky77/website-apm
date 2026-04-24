<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — PT. Agro Pangan Maju</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=poppins:400,600,700,800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="antialiased bg-white text-gray-800">
    @include('partials._navbar')


    {{-- Placeholder Hero --}}
    <section class="relative bg-gray-900 py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-[#CD422B]/30 to-gray-900"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-12 text-center">
            <div class="flex items-center justify-center gap-2 text-sm text-gray-400 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-[#F6A02B] font-semibold">{{ $title }}</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-black text-white mb-4">{{ $title }}</h1>
            <p class="text-gray-400 text-lg">PT. Agro Pangan Maju</p>
        </div>
    </section>

    {{-- Coming Soon --}}
    <section class="flex items-center justify-center min-h-[50vh] py-20">
        <div class="text-center max-w-md px-6">
            <div class="w-20 h-20 bg-red-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-[#CD422B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/></svg>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-3">Segera Hadir</h2>
            <p class="text-gray-500 leading-relaxed mb-8">
                Halaman <strong>{{ $title }}</strong> sedang dalam tahap pengembangan. Kami akan segera menghadirkan konten terbaik untuk Anda.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('home') }}" class="px-6 py-3 bg-[#CD422B] text-white font-bold text-sm rounded-xl hover:bg-red-700 transition">Kembali ke Beranda</a>
                <a href="{{ route('contact') }}" class="px-6 py-3 border border-gray-200 text-gray-600 font-semibold text-sm rounded-xl hover:border-[#CD422B] hover:text-[#CD422B] transition">Hubungi Kami</a>
            </div>
        </div>
    </section>

    @include('partials._footer')
</body>
</html>
