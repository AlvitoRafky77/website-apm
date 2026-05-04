@extends('admin.layout')
@section('title', 'Transaksi B2B')
@section('subtitle', 'Manajemen pengadaan mitra B2B — verifikasi pembayaran & pengiriman')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div></div>
    @if($counts['dispute'] > 0)
    <a href="{{ route('admin.b2b.orders.index', ['dispute' => 1]) }}"
       class="flex items-center gap-2 bg-orange-100 border border-orange-200 text-orange-800 font-bold px-4 py-2 rounded-xl text-sm hover:bg-orange-200 transition">
        ⚠️ {{ $counts['dispute'] }} Komplain Aktif
    </a>
    @endif
</div>

{{-- Filter Tabs --}}
<div class="flex gap-2 flex-wrap mb-6">
    @php
        $tabs = [
            'all'                  => ['label' => 'Semua',             'count' => $counts['all']],
            'pending_review'       => ['label' => 'Butuh Review',      'count' => $counts['pending_review']],
            'dp_uploaded'          => ['label' => 'DP Masuk',          'count' => $counts['dp_uploaded']],
            'dp_verified'          => ['label' => 'Siap Kirim',        'count' => $counts['dp_verified']],
            'in_transit'           => ['label' => 'Dalam Perjalanan',  'count' => $counts['in_transit']],
            'settlement_uploaded'  => ['label' => 'Pelunasan Masuk',   'count' => $counts['settlement_uploaded']],
            'completed'            => ['label' => 'Selesai',           'count' => $counts['completed']],
            'cancelled'            => ['label' => 'Dibatalkan',        'count' => $counts['cancelled']],
        ];
    @endphp
    @foreach($tabs as $key => $tab)
    <a href="{{ route('admin.b2b.orders.index', ['status' => $key]) }}"
       class="px-4 py-2 rounded-full text-sm font-semibold border transition
           {{ $status === $key
               ? 'bg-[#CD422B] text-white border-[#CD422B]'
               : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300' }}">
        {{ $tab['label'] }}
        @if($tab['count'] > 0)
            <span class="ml-1 {{ $status === $key ? 'bg-white/20' : 'bg-gray-100' }} px-2 py-0.5 rounded-full text-xs font-bold">{{ $tab['count'] }}</span>
        @endif
    </a>
    @endforeach
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-semibold border-b border-gray-100">
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Mitra</th>
                    <th class="px-6 py-4">Total / Nilai</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Dispute</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                @php
                    $colorMap = [
                        'gray'   => 'bg-gray-100 text-gray-700',
                        'amber'  => 'bg-amber-100 text-amber-800',
                        'yellow' => 'bg-yellow-100 text-yellow-800',
                        'blue'   => 'bg-blue-100 text-blue-800',
                        'orange' => 'bg-orange-100 text-orange-800',
                        'purple' => 'bg-purple-100 text-purple-800',
                        'teal'   => 'bg-teal-100 text-teal-800',
                        'indigo' => 'bg-indigo-100 text-indigo-800',
                        'green'  => 'bg-green-100 text-green-800',
                        'red'    => 'bg-red-100 text-red-800',
                    ];
                    $badge = $colorMap[$order->status_color] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <tr class="hover:bg-gray-50/60 transition group">
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-900 text-sm font-mono">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800 text-sm">{{ $order->b2bRegistration->company_name ?? '-' }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->user->name ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-[#CD422B] text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">DP: Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($order->dispute_status === 'none')
                            <span class="text-xs text-gray-300">—</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700">
                                {{ $order->dispute_status_label }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.b2b.orders.show', $order->order_number) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 shadow-sm rounded-lg text-xs font-bold text-gray-700 hover:bg-[#CD422B] hover:text-white hover:border-[#CD422B] transition-all">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <p class="font-semibold">Tidak ada order B2B</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection
