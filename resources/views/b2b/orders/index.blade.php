<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan B2B Saya — APM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen antialiased">

@include('partials._navbar_b2b')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Pesanan B2B Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau seluruh transaksi pengadaan Anda</p>
        </div>
        <a href="{{ route('b2b.orders.create') }}"
           class="bg-[#CD422B] hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded-xl transition shadow-md flex items-center gap-2 text-sm self-start sm:self-auto shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Order Baru
        </a>
    </div>

    @if($orders->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p class="font-bold text-gray-700 text-lg mb-2">Belum ada pesanan</p>
        <p class="text-gray-500 text-sm mb-6">Buat order B2B pertama Anda sekarang</p>
        <a href="{{ route('b2b.orders.create') }}" class="bg-[#CD422B] text-white font-bold px-6 py-3 rounded-xl hover:bg-red-700 transition text-sm">
            Buat Order Sekarang
        </a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($orders as $order)
        @php
            $colorMap = [
                'gray' => 'bg-gray-100 text-gray-700',
                'amber' => 'bg-amber-100 text-amber-800',
                'yellow' => 'bg-yellow-100 text-yellow-800',
                'blue' => 'bg-blue-100 text-blue-800',
                'orange' => 'bg-orange-100 text-orange-800',
                'purple' => 'bg-purple-100 text-purple-800',
                'teal' => 'bg-teal-100 text-teal-800',
                'indigo' => 'bg-indigo-100 text-indigo-800',
                'green' => 'bg-green-100 text-green-800',
                'red' => 'bg-red-100 text-red-800',
            ];
            $badge = $colorMap[$order->status_color] ?? 'bg-gray-100 text-gray-700';
            $totalTon = collect($order->items)->sum('qty_ton');
        @endphp
        <a href="{{ route('b2b.orders.show', $order->order_number) }}"
           class="block bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-gray-200 transition-all p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <p class="font-black text-gray-900 text-sm">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y') }} · {{ count($order->items) }} produk · {{ $totalTon }} Ton</p>
                        @if($order->hasActiveDispute())
                        <span class="inline-block mt-1 bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-0.5 rounded-full">⚠️ Ada Komplain Aktif</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3 sm:gap-4 sm:shrink-0">
                    <div class="text-right">
                        <p class="text-xs text-gray-400">Total</p>
                        <p class="font-black text-[#CD422B] text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }} whitespace-nowrap">
                        {{ $order->status_label }}
                    </span>
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif

</div>


</body>
</html>
