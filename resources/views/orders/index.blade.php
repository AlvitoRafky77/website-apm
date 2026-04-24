<x-app-layout>
<title>Pesanan Saya — PT. Agro Pangan Maju</title>

<div class="min-h-screen bg-gray-50 pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900">Pesanan Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola semua transaksi Anda di sini</p>
        </div>

        {{-- Status Tabs (Tokopedia style) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-x-auto">
            <div class="flex min-w-max">
                @php
                    $tabs = [
                        'all'          => ['label' => 'Semua',              'icon' => '📋'],
                        'need_payment' => ['label' => 'Belum Bayar',        'icon' => '⏳'],
                        'confirmed'    => ['label' => 'Dikonfirmasi',       'icon' => '✅'],
                        'processing'   => ['label' => 'Diproses',           'icon' => '📦'],
                        'shipped'      => ['label' => 'Dikirim',            'icon' => '🚚'],
                        'delivered'    => ['label' => 'Selesai',            'icon' => '🎉'],
                        'cancelled'    => ['label' => 'Dibatalkan',         'icon' => '❌'],
                    ];
                @endphp
                @foreach($tabs as $key => $info)
                <a href="{{ route('orders.index', ['status' => $key]) }}"
                   class="relative flex flex-col items-center gap-1 px-5 py-3.5 text-xs font-semibold transition-all whitespace-nowrap
                          {{ $tab === $key
                                ? 'text-[#CD422B] border-b-2 border-[#CD422B]'
                                : 'text-gray-500 hover:text-gray-800 border-b-2 border-transparent' }}">
                    <span class="text-base">{{ $info['icon'] }}</span>
                    {{ $info['label'] }}
                    @if(($counts[$key] ?? 0) > 0 && !in_array($key, ['all', 'delivered', 'cancelled']))
                    <span class="absolute top-2 right-2 bg-[#CD422B] text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center shadow-sm">
                        {{ $counts[$key] > 9 ? '9+' : $counts[$key] }}
                    </span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>

        {{-- Order List --}}
        @forelse($orders as $order)
        @php
            $statusMap = [
                'pending'    => ['label' => 'Menunggu Pembayaran', 'bg' => 'bg-amber-100',  'text' => 'text-amber-700'],
                'confirmed'  => ['label' => 'Dikonfirmasi',         'bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
                'processing' => ['label' => 'Sedang Diproses',      'bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                'shipped'    => ['label' => 'Sedang Dikirim',       'bg' => 'bg-indigo-100', 'text' => 'text-indigo-700'],
                'delivered'  => ['label' => 'Selesai',              'bg' => 'bg-green-100',  'text' => 'text-green-700'],
                'cancelled'  => ['label' => 'Dibatalkan',           'bg' => 'bg-red-100',    'text' => 'text-red-700'],
            ];
            $s = $statusMap[$order->status] ?? $statusMap['pending'];
            $items = is_array($order->items) ? $order->items : json_decode($order->items, true);
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden transition hover:shadow-md">
            {{-- Order Header --}}
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-50 bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-mono font-bold text-gray-700">{{ $order->order_id }}</span>
                    <span class="text-gray-300">·</span>
                    <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <span class="text-xs font-bold px-3 py-1 rounded-full {{ $s['bg'] }} {{ $s['text'] }}">
                    {{ $s['label'] }}
                </span>
            </div>

            {{-- Items Preview (maks 2) --}}
            <div class="px-5 py-4 space-y-3">
                @foreach(array_slice($items, 0, 2) as $item)
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-100 overflow-hidden flex-shrink-0">
                        @if(!empty($item['image']))
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $item['name'] }}</p>
                        @php
                            $wpu = $item['weight'] ?? 1;
                            $totalKg = $item['quantity'] * $wpu;
                        @endphp
                        <p class="text-xs text-gray-400">{{ $totalKg }} Kg × Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach

                @if(count($items) > 2)
                <p class="text-xs text-gray-400 pl-15">+{{ count($items) - 2 }} produk lainnya</p>
                @endif
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between px-5 py-3 border-t border-gray-50 bg-gray-50/30">
                <div>
                    <p class="text-xs text-gray-400">Total Pembayaran</p>
                    <p class="text-base font-extrabold text-[#CD422B]">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Bayar Sekarang (jika pending) --}}
                    @if($order->payment_status === 'pending' && $order->midtrans_snap_token)
                    <a href="{{ route('checkout.finish') }}?order_id={{ $order->order_id }}"
                       class="text-xs font-bold bg-[#CD422B] text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        Bayar Sekarang
                    </a>
                    @endif
                    {{-- Detail --}}
                    <a href="{{ route('orders.show', $order->order_id) }}"
                       class="text-xs font-bold border border-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:border-[#CD422B] hover:text-[#CD422B] transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-gray-700 font-bold mb-2">Belum ada pesanan</h3>
            <p class="text-gray-400 text-sm mb-6">Yuk mulai belanja produk segar dari APM!</p>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 bg-[#CD422B] text-white font-bold px-6 py-3 rounded-xl hover:shadow-md transition text-sm">
                Belanja Sekarang
            </a>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="mt-4">{{ $orders->links() }}</div>
        @endif

    </div>
</div>

@include('partials._footer')
</x-app-layout>
