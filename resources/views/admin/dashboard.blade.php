@extends('admin.layout')
@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan data website APM')

@section('content')
<!-- Statistik Cards (Sekarang di Atas) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-[#CD422B]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ $totalProducts }}</p>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Produk</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ $totalCategories }}</p>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Kategori</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ $featuredCount }}</p>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Top Produk</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 {{ $pendingOrdersCount > 0 ? 'ring-2 ring-red-500' : '' }}">
        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ $pendingOrdersCount }}</p>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Order B2C</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 {{ $pendingB2bCount > 0 ? 'ring-2 ring-amber-500' : '' }}">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <p class="text-2xl font-black text-gray-900">{{ $pendingB2bCount }}</p>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Reg B2B</p>
        </div>
    </div>
</div>

<!-- Aksi Cepat Menu (Sekarang di Bawah Statistik) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 p-4 bg-[#CD422B] text-white rounded-2xl hover:bg-red-700 transition shadow-sm group">
        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <span class="font-bold text-sm">Validasi Pesanan</span>
    </a>

    <a href="{{ route('admin.products.create') }}" class="flex items-center gap-3 p-4 bg-white border border-gray-100 text-gray-700 rounded-2xl hover:bg-gray-50 transition shadow-sm group">
        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-[#CD422B] transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </div>
        <span class="font-bold text-sm">Tambah Produk</span>
    </a>

    <a href="{{ route('admin.b2b.index') }}" class="flex items-center gap-3 p-4 bg-white border border-gray-100 text-gray-700 rounded-2xl hover:bg-gray-50 transition shadow-sm group">
        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:text-blue-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
        </div>
        <span class="font-bold text-sm">Cek Registrasi B2B</span>
    </a>

    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 text-gray-500 rounded-2xl hover:bg-gray-100 transition shadow-sm group">
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
        <span class="font-bold text-xs">Lihat Website</span>
    </a>
</div>

<!-- Grafik Kiri Kanan -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Grafik B2C -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-bold text-gray-800 text-base">Omzet B2C (Retail)</h2>
                <p class="text-[10px] text-gray-400">7 Hari Terakhir</p>
            </div>
            <span class="w-3 h-3 rounded-full bg-[#CD422B]"></span>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="b2cChart"></canvas>
        </div>
    </div>

    <!-- Grafik B2B -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-bold text-gray-800 text-base">Omzet B2B (Mitra)</h2>
                <p class="text-[10px] text-gray-400">7 Hari Terakhir (Verified DP)</p>
            </div>
            <span class="w-3 h-3 rounded-full bg-blue-600"></span>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="b2bChart"></canvas>
        </div>
    </div>
</div>

<!-- Produk Terlaris (Bawah) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 text-sm mb-5 flex items-center gap-2">
            <span class="w-1.5 h-6 bg-[#CD422B] rounded-full"></span>
            Top 5 Produk B2C
        </h3>
        <div class="space-y-3">
            @forelse($b2cTopProducts as $p)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-red-50 transition border border-transparent hover:border-red-100">
                <span class="text-xs font-semibold text-gray-700">{{ $p['name'] }}</span>
                <span class="text-xs font-black text-gray-900">{{ number_format($p['total'], 0, ',', '.') }} <span class="text-[10px] text-gray-400 font-normal">KG</span></span>
            </div>
            @empty
            <p class="text-center text-xs text-gray-400 py-4">Belum ada data.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 text-sm mb-5 flex items-center gap-2">
            <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span>
            Top 5 Produk B2B
        </h3>
        <div class="space-y-3">
            @forelse($b2bTopProducts as $p)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition border border-transparent hover:border-blue-100">
                <span class="text-xs font-semibold text-gray-700">{{ $p['name'] }}</span>
                <div class="text-right">
                    <div class="text-xs font-black text-gray-900">{{ number_format($p['total'], 0, ',', '.') }} <span class="text-[10px] text-gray-400 font-normal">KG</span></div>
                    @if($p['total'] >= 1000)
                    <div class="text-[9px] text-emerald-600 font-bold">≈ {{ number_format($p['total']/1000, 1) }} Ton</div>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-center text-xs text-gray-400 py-4">Belum ada data.</p>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const dataLabels = @json($labels);
    const dataB2c    = @json($salesData);
    const dataB2b    = @json($b2bSalesData);

    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                    }
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 9 } } },
            y: { 
                beginAtZero: true, 
                grid: { color: '#f1f5f9' },
                ticks: {
                    font: { size: 9 },
                    callback: function(value) {
                        if (value >= 1000000) return (value/1000000).toFixed(1) + 'Jt';
                        return value;
                    }
                }
            }
        }
    };

    // B2C Chart
    new Chart(document.getElementById('b2cChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dataLabels,
            datasets: [{
                data: dataB2c,
                borderColor: '#CD422B',
                backgroundColor: 'rgba(205, 66, 43, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 2
            }]
        },
        options: commonOptions
    });

    // B2B Chart
    new Chart(document.getElementById('b2bChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dataLabels,
            datasets: [{
                data: dataB2b,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 2
            }]
        },
        options: commonOptions
    });
});
</script>
@endsection
