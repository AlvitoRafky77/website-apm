@extends('admin.layout')
@section('title', 'Keuangan')
@section('subtitle', 'Ringkasan transaksi masuk, grafik pendapatan, dan sinkronisasi Midtrans')

@section('content')
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
        <input type="date" name="date_from" value="{{ request('date_from') }}" min="{{ now()->subYears(5)->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">
        <input type="date" name="date_to" value="{{ request('date_to') }}" min="{{ now()->subYears(5)->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">

        @if(!request('customer_type'))
        <select name="customer_type" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">
            <option value="">Semua Customer</option>
            <option value="b2c" @selected(request('customer_type') === 'b2c')>B2C</option>
            <option value="b2b" @selected(request('customer_type') === 'b2b')>B2B</option>
        </select>
        @else
        <input type="hidden" name="customer_type" value="{{ request('customer_type') }}">
        @endif

        <select name="payment_type" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#CD422B]/20 focus:border-[#CD422B]">
            <option value="">Semua Metode Bayar</option>
            <option value="credit_card" @selected(request('payment_type') === 'credit_card')>Credit Card</option>
            <option value="bank_transfer" @selected(request('payment_type') === 'bank_transfer')>Bank Transfer</option>
            <option value="qris" @selected(request('payment_type') === 'qris')>QRIS</option>
            <option value="gopay" @selected(request('payment_type') === 'gopay')>GoPay</option>
            <option value="shopeepay" @selected(request('payment_type') === 'shopeepay')>ShopeePay</option>
            <option value="cstore" @selected(request('payment_type') === 'cstore')>C-Store</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="w-full bg-[#CD422B] text-white text-sm font-semibold rounded-xl px-4 py-2.5 hover:bg-red-700 transition">Terapkan</button>
            <a href="{{ route('admin.finances.index') }}" class="bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl px-4 py-2.5 hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>

    <div class="mt-3 flex flex-wrap gap-2">
        <a href="{{ route('admin.finances.transactions', request()->query()) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-100">Detail Transaksi</a>
        <a href="{{ route('admin.finances.export.csv', request()->query()) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-green-50 border border-green-200 text-xs font-semibold text-green-700 hover:bg-green-100">Export CSV</a>
        <a href="{{ route('admin.finances.export.pdf', request()->query()) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 border border-blue-200 text-xs font-semibold text-blue-700 hover:bg-blue-100">Export PDF</a>

        <form method="POST" action="{{ route('admin.finances.sync') }}" class="inline-flex">
            @csrf
            <input type="hidden" name="date_from" value="{{ request('date_from') }}">
            <input type="hidden" name="date_to" value="{{ request('date_to') }}">
            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-amber-50 border border-amber-200 text-xs font-semibold text-amber-700 hover:bg-amber-100">Sinkron Midtrans</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Gross Income</p>
        <p class="mt-2 text-2xl font-black text-gray-900">Rp{{ number_format($summary['gross_income'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Net Product</p>
        <p class="mt-2 text-2xl font-black text-gray-900">Rp{{ number_format($summary['net_product_income'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Shipping Collected</p>
        <p class="mt-2 text-2xl font-black text-gray-900">Rp{{ number_format($summary['shipping_collected'], 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h2 class="font-bold text-gray-800 text-lg mb-1">Tren Pendapatan (Gross vs Net)</h2>
        <p class="text-xs text-gray-500 mb-4">Data harian berdasarkan filter aktif</p>
        <div class="relative h-72"><canvas id="financeTrendChart"></canvas></div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h2 class="font-bold text-gray-800 text-lg mb-1">Metode Pembayaran</h2>
        <p class="text-xs text-gray-500 mb-4">Distribusi transaksi lunas</p>
        <div class="space-y-4">
            @forelse($methodBreakdown as $method)
                @php
                    $methodLabel = $method->method === 'unknown' ? 'Unknown' : str_replace('_', ' ', $method->method);
                    $percentage = $summary['gross_income'] > 0 ? ($method->gross_total / $summary['gross_income']) * 100 : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-xs font-semibold text-gray-600 mb-1">
                        <span class="capitalize">{{ $methodLabel }}</span>
                        <span>Rp{{ number_format($method->gross_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full bg-[#CD422B]" style="width: {{ min(100, round($percentage, 1)) }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada transaksi lunas pada filter ini.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-gray-800">Transaksi Terbaru</h3>
            <p class="text-xs text-gray-500">Ringkasan transaksi masuk terbaru</p>
        </div>
        <a href="{{ route('admin.finances.transactions', request()->query()) }}" class="text-xs font-semibold text-[#CD422B] hover:underline">Lihat semua</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-bold border-b border-gray-100">
                    <th class="px-6 py-4">Tanggal Bayar</th>
                    <th class="px-6 py-4">Order ID</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status Bayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($transactions as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">{{ optional($order->paid_at)->format('d M Y, H:i') ?? '-' }}</td>
                        <td class="px-6 py-4"><span class="font-mono font-bold text-gray-700">{{ $order->order_id }}</span></td>
                        <td class="px-6 py-4">{{ $order->user->name ?? $order->recipient_name }} <span class="text-[11px] text-gray-400">({{ strtoupper($order->user->user_type ?? 'b2c') }})</span></td>
                        <td class="px-6 py-4 capitalize">{{ $order->midtrans_payment_type ? str_replace('_', ' ', $order->midtrans_payment_type) : '-' }}</td>
                        <td class="px-6 py-4 font-bold">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $badge = match($order->payment_status) {
                                    'paid' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'failed', 'expired' => 'bg-red-100 text-red-700',
                                    'refunded' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold {{ $badge }}">{{ strtoupper($order->payment_status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">Tidak ada transaksi pada filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('financeTrendChart');
    if (!ctx) return;

    const labels = @json($timeSeries['labels']);
    const grossData = @json($timeSeries['gross']);
    const netData = @json($timeSeries['net']);

    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Gross (Total)',
                    data: grossData,
                    borderColor: '#CD422B',
                    backgroundColor: 'rgba(205, 66, 43, 0.12)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                },
                {
                    label: 'Net (Subtotal)',
                    data: netData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.06)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: false,
                    pointRadius: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y || 0);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + ' Jt';
                            if (value >= 1000) return (value / 1000).toFixed(0) + ' Rb';
                            return value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
