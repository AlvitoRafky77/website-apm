<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Kedatangan Driver</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

<div class="bg-white rounded-3xl shadow-xl w-full max-w-sm overflow-hidden">
    <!-- Header -->
    <div class="bg-[#1a1a2e] p-6 text-center text-white">
        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <h2 class="font-black text-xl tracking-tight">Konfirmasi Tiba</h2>
        <p class="text-xs text-white/70 mt-1 font-mono">{{ $order->order_number }}</p>
    </div>

    <!-- Body -->
    <div class="p-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-2xl text-center mb-5">
                <p class="font-bold text-sm">✅ Kedatangan Tercatat!</p>
                <p class="text-xs mt-1">Terima kasih. Anda sudah bisa menyerahkan barang ke mitra.</p>
            </div>
        @else
            <div class="space-y-4 mb-6">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tujuan Pengiriman</p>
                    <p class="font-bold text-gray-900 text-sm">{{ $order->recipient_name }} ({{ $order->recipient_phone }})</p>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ $order->recipient_address }}, {{ $order->recipient_city }}</p>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-6">
                <p class="text-xs text-blue-800 text-center leading-relaxed">
                    Klik tombol di bawah <strong>HANYA</strong> jika armada Anda sudah berada di lokasi tujuan.
                </p>
            </div>

            <form method="POST" action="{{ request()->fullUrl() }}">
                @csrf
                <button type="submit" onclick="return confirm('Apakah Anda yakin sudah sampai di lokasi mitra?')" 
                        class="w-full bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-black text-lg py-5 rounded-2xl shadow-xl shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                    SAYA SUDAH SAMPAI
                </button>
            </form>
        @endif
    </div>
</div>

</body>
</html>
