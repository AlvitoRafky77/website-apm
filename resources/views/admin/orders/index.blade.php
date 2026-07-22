@extends('admin.layout')
@section('title', 'Transaksi B2C')
@section('subtitle', 'Manajemen pesanan dan pembayaran customer')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-base font-bold text-gray-500">Transaksi B2C (E-Commerce)</h2>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
        <div class="relative w-full sm:w-72">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Order ID / Nama..." 
                   class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#CD422B]/20 transition-all">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <button type="submit" class="bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 px-4 py-2 rounded-xl font-semibold text-sm transition-all whitespace-nowrap">
            Cari
        </button>
        @if(request()->filled('search'))
            <a href="{{ route('admin.orders.index', ['status' => request('status', 'all')]) }}" class="text-xs text-red-500 font-medium hover:underline whitespace-nowrap">Reset</a>
        @endif
    </form>
</div>

{{-- Filter Tabs (Disamakan dengan B2B) --}}
<div class="flex gap-2 flex-wrap mb-6">
    @php
        $currentStatus = $status ?? request('status', 'all');
        $tabs = [
            'all'             => ['label' => 'Semua',             'count' => $counts['all'] ?? 0],
            'pending_payment' => ['label' => 'Menunggu Bayar',    'count' => $counts['pending_payment'] ?? 0],
            'paid_pending'    => ['label' => 'Perlu Diproses',     'count' => $counts['paid_pending'] ?? 0],
            'processing'      => ['label' => 'Sedang Diproses',   'count' => $counts['processing'] ?? 0],
            'shipped'         => ['label' => 'Dalam Pengiriman',  'count' => $counts['shipped'] ?? 0],
            'delivered'       => ['label' => 'Selesai',           'count' => $counts['delivered'] ?? 0],
            'cancelled'       => ['label' => 'Dibatalkan',        'count' => $counts['cancelled'] ?? 0],
        ];
    @endphp
    @foreach($tabs as $key => $tab)
    <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => $key])) }}"
       class="px-4 py-2 rounded-full text-sm font-semibold border transition
           {{ $currentStatus === $key
               ? 'bg-[#CD422B] text-white border-[#CD422B]'
               : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300' }}">
        {{ $tab['label'] }}
        @if($tab['count'] > 0)
            <span class="ml-1 {{ $currentStatus === $key ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }} px-2 py-0.5 rounded-full text-xs font-bold">{{ $tab['count'] }}</span>
        @endif
    </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-bold border-b border-gray-100">
                    <th class="px-6 py-4">Tanggal & Waktu</th>
                    <th class="px-6 py-4">Order ID</th>
                    <th class="px-6 py-4">Pembayaran (Channel)</th>
                    <th class="px-6 py-4">Status Transaksi</th>
                    <th class="px-6 py-4">Nilai</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="text-gray-900 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-gray-600 font-bold bg-gray-100 px-2 py-0.5 rounded">{{ $order->order_id }}</span>
                        <p class="text-[11px] text-gray-400 mt-1">{{ $order->user->email ?? $order->recipient_name }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="capitalize text-gray-800 font-semibold">{{ $order->midtrans_payment_type ? str_replace('_', ' ', $order->midtrans_payment_type) : '-' }}</span>
                        <p class="text-[11px] text-gray-400 font-mono mt-0.5" title="Midtrans ID">{{ $order->midtrans_transaction_id ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1.5 items-start">
                            <!-- Payment Badge (Midtrans style) -->
                            @php
                                $payStyle = match($order->payment_status) {
                                    'paid' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'failed','expired','cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                                $payLabel = match($order->payment_status) {
                                    'paid' => 'Settlement (Lunas)',
                                    'pending' => 'Pending',
                                    'failed' => 'Ditolak',
                                    'expired' => 'Expired',
                                    default => 'Batal'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $payStyle }}">
                                <span class="w-1.5 h-1.5 rounded-full currentColor bg-current"></span>
                                {{ $payLabel }}
                            </span>

                            <!-- Shipping Workflow Badge -->
                            @if($order->payment_status === 'paid')
                                @php
                                    $shipStyle = match($order->status) {
                                        'pending','confirmed' => 'text-amber-600 border-amber-200 bg-amber-50',
                                        'processing' => 'text-blue-600 border-blue-200 bg-blue-50',
                                        'shipped' => 'text-indigo-600 border-indigo-200 bg-indigo-50',
                                        'delivered' => 'text-green-600 border-green-200 bg-green-50',
                                        default => 'text-gray-600 border-gray-200 bg-gray-50'
                                    };
                                    $shipLabel = match($order->status) {
                                        'pending','confirmed' => 'Perlu Diproses!',
                                        'processing' => 'Sedang Diproses',
                                        'shipped' => 'Dikirim (' . $order->courier . ')',
                                        'delivered' => 'Selesai',
                                        default => 'Dibatalkan'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded border text-[10px] font-semibold {{ $shipStyle }}">
                                    🚚 {{ $shipLabel }}
                                </span>

                                {{-- SLA Indicator Badge --}}
                                @if(in_array($order->status, ['pending', 'confirmed', 'processing'], true))
                                    @php $sla = $order->sla_status_info; @endphp
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold {{ $sla['badge'] }}">
                                        ⏱️ {{ $sla['label'] }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-900 font-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.orders.show', $order->order_id) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[#CD422B] bg-red-50 hover:bg-[#CD422B] hover:text-white rounded-lg transition-colors">
                            Buka Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="font-medium text-base">Tidak ada transaksi ditemukan</p>
                        <p class="text-xs mt-1">Coba sesuaikan filter atau pencarian Anda.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
