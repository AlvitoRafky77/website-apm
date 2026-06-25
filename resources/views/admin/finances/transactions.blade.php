@extends('admin.layout')
@section('title', 'Keuangan - Detail Transaksi')
@section('subtitle', 'Daftar detail uang masuk berdasarkan filter')

@section('content')
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-3">
        <input type="date" name="date_from" value="{{ request('date_from') }}" min="{{ now()->subYears(5)->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">
        <input type="date" name="date_to" value="{{ request('date_to') }}" min="{{ now()->subYears(5)->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">

        <select name="customer_type" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">
            <option value="">Semua Customer</option>
            <option value="b2c" @selected(request('customer_type') === 'b2c')>B2C</option>
            <option value="b2b" @selected(request('customer_type') === 'b2b')>B2B</option>
        </select>

        <select name="payment_type" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">
            <option value="">Semua Metode Bayar</option>
            <option value="credit_card" @selected(request('payment_type') === 'credit_card')>Credit Card</option>
            <option value="bank_transfer" @selected(request('payment_type') === 'bank_transfer')>Bank Transfer</option>
            <option value="qris" @selected(request('payment_type') === 'qris')>QRIS</option>
            <option value="gopay" @selected(request('payment_type') === 'gopay')>GoPay</option>
            <option value="shopeepay" @selected(request('payment_type') === 'shopeepay')>ShopeePay</option>
            <option value="cstore" @selected(request('payment_type') === 'cstore')>C-Store</option>
        </select>

        <select name="payment_status" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">
            <option value="">Semua Status Bayar</option>
            <option value="paid" @selected(request('payment_status') === 'paid')>Paid</option>
            <option value="pending" @selected(request('payment_status') === 'pending')>Pending</option>
            <option value="failed" @selected(request('payment_status') === 'failed')>Failed</option>
            <option value="expired" @selected(request('payment_status') === 'expired')>Expired</option>
            <option value="refunded" @selected(request('payment_status') === 'refunded')>Refunded</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="w-full bg-[#CD422B] text-white text-sm font-semibold rounded-xl px-4 py-2.5 hover:bg-red-700 transition">Terapkan</button>
            <a href="{{ route('admin.finances.transactions') }}" class="bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl px-4 py-2.5 hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>

    <div class="mt-3 flex flex-wrap gap-2">
        <a href="{{ route('admin.finances.export.csv', request()->query()) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-green-50 border border-green-200 text-xs font-semibold text-green-700 hover:bg-green-100">Export CSV</a>
        <a href="{{ route('admin.finances.export.pdf', request()->query()) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 text-xs font-semibold text-blue-700 hover:bg-blue-100">Export PDF</a>
        <a href="{{ route('admin.finances.index', request()->query()) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-100">Kembali ke Summary</a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-bold border-b border-gray-100">
                    <th class="px-6 py-4">Tanggal Bayar</th>
                    <th class="px-6 py-4">Order ID</th>
                    <th class="px-6 py-4">Nama Customer</th>
                    <th class="px-6 py-4">Tipe Customer</th>
                    <th class="px-6 py-4">Metode Bayar</th>
                    <th class="px-6 py-4">Subtotal</th>
                    <th class="px-6 py-4">Ongkir</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status Bayar</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($transactions as $order)
                    @php
                        $badge = match($order->payment_status) {
                            'paid' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'failed', 'expired' => 'bg-red-100 text-red-700',
                            'refunded' => 'bg-purple-100 text-purple-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">{{ optional($order->paid_at)->format('d M Y, H:i') ?? '-' }}</td>
                        <td class="px-6 py-4"><span class="font-mono font-bold text-gray-700">{{ $order->order_id }}</span></td>
                        <td class="px-6 py-4">{{ $order->user->name ?? $order->recipient_name }}</td>
                        <td class="px-6 py-4"><span class="text-[11px] uppercase font-bold bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $order->user->user_type ?? 'b2c' }}</span></td>
                        <td class="px-6 py-4 capitalize">{{ $order->midtrans_payment_type ? str_replace('_', ' ', $order->midtrans_payment_type) : '-' }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4"><span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold {{ $badge }}">{{ strtoupper($order->payment_status) }}</span></td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order->order_id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[#CD422B] bg-red-50 hover:bg-[#CD422B] hover:text-white rounded-lg transition-colors">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-10 text-center text-gray-500">Tidak ada data transaksi pada filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection
