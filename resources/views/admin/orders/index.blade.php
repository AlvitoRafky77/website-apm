@extends('admin.layout')
@section('title', 'Daftar Transaksi')
@section('subtitle', 'Manajemen pesanan dan pembayaran customer')

@section('content')
<div class="mb-5">
    <h2 class="text-base font-bold text-gray-500">Transaksi B2C (E-Commerce)</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    
    <!-- Top Controls -->
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row gap-4 justify-between items-center">
            <input type="hidden" name="type" value="{{ request('type', 'b2c') }}">
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-80">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Order ID / Nama..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-[#CD422B]/20 transition-all">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto">
                <select name="status" class="bg-white border border-gray-200 rounded-xl pl-4 pr-8 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 w-full md:w-48 transition-all">
                    <option value="">Semua Status</option>
                    <option value="paid_pending" {{ request('status') === 'paid_pending' ? 'selected' : '' }}>Lunas (Perlu Diproses)</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Sedang Dikirim</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap">
                    Terapkan
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.orders.index') }}" class="text-xs text-red-500 font-medium hover:underline whitespace-nowrap">Reset</a>
                @endif
            </div>

        </form>
    </div>

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
