<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Order B2B {{ $order->order_number }} — APM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen antialiased">

@include('partials._navbar_b2b')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
        <a href="{{ route('b2b.dashboard') }}" class="hover:text-[#CD422B] transition-colors">Dashboard B2B</a>
        <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('b2b.orders.index') }}" class="hover:text-[#CD422B] transition-colors">Daftar Pesanan</a>
        <svg class="w-3.5 h-3.5 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-semibold text-gray-600">{{ $order->order_number }}</span>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Header Order --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-8 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black tracking-widest text-gray-300 uppercase">Transaction ID</span>
                <span class="px-3 py-1 bg-gray-50 text-gray-500 rounded-full text-[10px] font-mono font-bold">{{ $order->order_number }}</span>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Order Details</h1>
            <p class="text-sm text-gray-400 font-medium">Terakhir diperbarui pada {{ $order->updated_at->format('d M Y, H:i') }} WIB</p>
        </div>

        <div class="flex flex-col items-end gap-3">
            <a href="{{ route('b2b.orders.invoice', $order->order_number) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#CD422B] text-white text-sm font-black shadow-sm hover:bg-red-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 2h9l5 5v15a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 13h8M8 17h5"></path>
                </svg>
                Cetak Invoice
            </a>
            @php
                $colorMap = [
                    'gray' => 'bg-gray-100 text-gray-700',
                    'amber' => 'bg-amber-100 text-amber-800 border-amber-200',
                    'yellow' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
                    'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
                    'teal' => 'bg-teal-100 text-teal-800 border-teal-200',
                    'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                    'green' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'red' => 'bg-red-100 text-red-800 border-red-200',
                ];
                $badge = $colorMap[$order->status_color] ?? 'bg-gray-100 text-gray-700';
            @endphp
            <div class="px-6 py-2.5 rounded-2xl border text-sm font-black {{ $badge }} shadow-sm">
                {{ $order->status_label }}
            </div>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Order Status</p>
        </div>
    </div>

    {{-- Progress Timeline --}}
    @php
        $steps = [
            ['key' => 'waiting_dp',          'label' => 'DP 50%',           'icon' => '💳'],
            ['key' => 'dp_uploaded',         'label' => 'Verifikasi',       'icon' => '🔍'],
            ['key' => 'in_transit',           'label' => 'Pengiriman',      'icon' => '🚛'],
            ['key' => 'settlement_uploaded',  'label' => 'Pelunasan',       'icon' => '💰'],
            ['key' => 'delivered',            'label' => 'Diterima',        'icon' => '📦'],
            ['key' => 'completed',            'label' => 'Selesai',         'icon' => '🎉'],
        ];
        $statusOrder = ['draft','waiting_dp','dp_uploaded','in_transit','waiting_settlement','settlement_uploaded','settlement_verified','delivered','completed'];
        $visualIdx = match($order->status) {
            'pending_review'      => 0,
            'waiting_dp'          => 0,
            'dp_uploaded'         => 1,
            'in_transit'          => 2,
            'settlement_uploaded' => 3,
            'settlement_verified' => 3,
            'delivered'           => 4,
            'completed'           => 5,
            default               => 0
        };
        $progressPct = ($visualIdx / (count($steps) - 1)) * 100;
    @endphp
    @if($order->status !== 'cancelled')
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-10 mb-10">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-lg font-black text-gray-900 tracking-tight">Timeline Pengadaan</h2>
            <span class="text-xs font-bold text-[#CD422B] bg-red-50 px-3 py-1 rounded-full">{{ round($progressPct) }}% Selesai</span>
        </div>
        <div class="relative">
            {{-- Line Background --}}
            <div class="absolute top-6 left-0 right-0 h-1.5 bg-gray-50 z-0 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#CD422B] to-orange-400 transition-all duration-1000" style="width: {{ $progressPct }}%"></div>
            </div>

            <div class="relative z-10 flex justify-between">
                @foreach($steps as $i => $step)
                @php $done = $i <= $visualIdx; @endphp
                <div class="flex flex-col items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-500 shadow-lg
                        {{ $done ? 'bg-white text-[#CD422B] scale-110 border-2 border-[#CD422B]' : 'bg-white text-gray-300 border-2 border-gray-50' }}">
                        <span class="text-xl">{{ $step['icon'] }}</span>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] font-black tracking-tighter uppercase {{ $done ? 'text-gray-900' : 'text-gray-300' }}">
                            {{ $step['label'] }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4 flex items-center gap-3 mb-6">
        <span class="text-xl">❌</span>
        <div>
            <p class="font-bold text-red-700 text-sm">Order Dibatalkan</p>
            @if($order->cancel_reason)<p class="text-xs text-red-500">{{ $order->cancel_reason }}</p>@endif
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">

        {{-- Kolom Kiri --}}
        <div class="lg:col-span-8 space-y-10">

            {{-- Produk Dipesan --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <h2 class="font-black text-gray-900 tracking-tight">Item Pesanan</h2>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ count($order->items) }} Produk</span>
                </div>
                <div class="p-10 space-y-8">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-6 group">
                        <div class="w-20 h-20 rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden shrink-0 group-hover:scale-105 transition-transform">
                            @if(!empty($item['image']))
                                <img src="{{ $item['image'] }}" class="w-full h-full object-cover" alt="{{ $item['name'] }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 text-2xl">📦</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-gray-900 text-lg leading-tight">{{ $item['name'] }}</h3>
                            <p class="text-sm text-gray-400 font-medium mt-1">
                                <span class="text-gray-900 font-bold">{{ $item['qty_ton'] }} Ton</span>
                                <span class="mx-2 opacity-20">|</span>
                                Rp {{ number_format($item['price_per_kg'], 0, ',', '.') }}/KG
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Subtotal</p>
                            <p class="font-black text-gray-900 text-lg">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Ringkasan Pembayaran & Rekening --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Detail Biaya --}}
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-10 flex flex-col justify-between">
                    <div>
                        <h2 class="font-black text-gray-900 mb-8 tracking-tight">Ringkasan Biaya</h2>
                        <div class="space-y-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400 font-medium">Subtotal Produk</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400 font-medium">Logistik Armada</span>
                                @if($order->shipping_cost > 0)
                                    <span class="font-bold text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-[10px] bg-amber-50 text-amber-600 px-3 py-1 rounded-full font-black uppercase">Menunggu Admin</span>
                                @endif
                            </div>
                            <div class="pt-4 border-t border-gray-50 flex justify-between items-end">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Bill</span>
                                <span class="text-2xl font-black text-[#CD422B]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-dashed border-gray-100 grid grid-cols-2 gap-4">
                        <div class="bg-blue-50/50 rounded-2xl p-4">
                            <p class="text-[9px] font-black text-blue-400 uppercase mb-1">DP (50%)</p>
                            <p class="font-black text-blue-700 text-sm">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-purple-50/50 rounded-2xl p-4">
                            <p class="text-[9px] font-black text-purple-400 uppercase mb-1">Pelunasan</p>
                            <p class="font-black text-purple-700 text-sm">Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Rekening --}}
                <div class="bg-[#1a1a2e] rounded-[2rem] shadow-xl p-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <h2 class="font-black text-white mb-6 tracking-tight flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Rekening Resmi
                        </h2>
                        <div class="space-y-6">
                            <div class="group">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-blue-400 transition-colors">Bank Central Asia (BCA)</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-mono font-black tracking-widest">1234 5678 90</span>
                                </div>
                            </div>
                            <div class="group">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 group-hover:text-blue-400 transition-colors">Bank DKI</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-mono font-black tracking-widest">0987 6543 21</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-500 font-bold italic mt-4">A/N PT. Agro Pangan Maju</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Pengiriman --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-10">
                <div class="flex items-center justify-between mb-10">
                    <h2 class="font-black text-gray-900 tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Logistik & Armada
                    </h2>
                </div>

                @if($order->delivery_date || $order->driver_name)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <div class="p-6 bg-gray-50 rounded-[1.5rem] border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Driver Ditugaskan</p>
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm">👨🏻‍✈️</div>
                                <div>
                                    <p class="font-black text-gray-900 text-lg leading-tight">{{ $order->driver_name ?? 'N/A' }}</p>
                                    @if($order->driver_phone)
                                        <a href="https://wa.me/{{ $order->driver_phone }}" class="text-sm text-green-600 font-bold hover:underline flex items-center gap-1 mt-1">
                                            <span>WhatsApp Driver</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Lokasi & Jadwal</p>
                            <div class="space-y-3">
                                <div class="flex gap-3">
                                    <span class="text-lg opacity-50">📍</span>
                                    <p class="text-sm font-bold text-gray-700 leading-relaxed">{{ $order->recipient_address }}</p>
                                </div>
                                <div class="flex gap-3">
                                    <span class="text-lg opacity-50">🗓️</span>
                                    <p class="text-sm font-black text-[#CD422B]">Estimasi Tiba: {{ \Carbon\Carbon::parse($order->delivery_date)->format('d F Y') }}</p>
                                </div>
                                @if($order->waybill_number)
                                <div class="inline-block px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-mono font-black text-gray-500 border border-gray-200">
                                    SJ NO: {{ $order->waybill_number }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="py-12 bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-100 text-center">
                    <p class="text-gray-400 font-bold italic text-sm">Armada sedang diproses. Mohon tunggu verifikasi DP.</p>
                </div>
                @endif
            </div>

            {{-- Ajukan Komplain / Dispute --}}
            @if($order->canDispute())
            <div class="bg-white rounded-[2rem] border border-red-50 shadow-sm p-10">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="font-black text-gray-900 tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Ajukan Komplain
                    </h2>
                </div>
                <form method="POST" action="{{ route('b2b.orders.dispute', $order->order_number) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <textarea name="dispute_reason" rows="3" required
                                  placeholder="Jelaskan kendala Anda secara detail (barang rusak, kurang qty, dll)..."
                                  class="w-full border border-gray-100 rounded-2xl px-6 py-5 text-sm focus:outline-none focus:ring-2 focus:ring-red-100 resize-none transition-all bg-gray-50/50"></textarea>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-6 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest shrink-0">Foto Bukti (Maks 5)</label>
                            <input type="file" name="dispute_images[]" multiple accept="image/*"
                                   class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-white file:text-red-600 hover:file:bg-red-50 transition-all cursor-pointer">
                        </div>

                        <button type="submit" onclick="return confirm('Yakin ingin mengajukan komplain? Admin akan meninjau laporan Anda.')"
                                class="w-full sm:w-auto px-12 bg-white border-2 border-red-100 text-red-600 hover:bg-red-600 hover:text-white font-black py-4 rounded-2xl transition-all shadow-sm text-xs uppercase tracking-widest">
                            Kirim Laporan Komplain
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- Jika sedang Dispute --}}
            @if($order->dispute_status !== 'none')
            <div class="rounded-[2rem] p-10 {{
                $order->dispute_status === 'resolved' ? 'bg-emerald-50 border border-emerald-100' :
                ($order->dispute_status === 'rejected' ? 'bg-red-50 border border-red-100' : 'bg-orange-50 border border-orange-100')
            }}">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <span class="text-3xl">
                            {{ $order->dispute_status === 'resolved' ? '✅' : ($order->dispute_status === 'rejected' ? '❌' : '⚠️') }}
                        </span>
                        <div>
                            <h3 class="font-black tracking-tight text-xl {{
                                $order->dispute_status === 'resolved' ? 'text-emerald-900' :
                                ($order->dispute_status === 'rejected' ? 'text-red-900' : 'text-orange-900')
                            }}">
                                {{ $order->dispute_status === 'resolved' ? 'Komplain Selesai' : ($order->dispute_status === 'rejected' ? 'Komplain Ditolak' : 'Komplain Sedang Ditinjau') }}
                            </h3>
                            <p class="text-xs font-bold opacity-60 uppercase tracking-widest mt-1">Status Laporan Komplain</p>
                        </div>
                    </div>
                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{
                        $order->dispute_status === 'resolved' ? 'bg-emerald-500 text-white' :
                        ($order->dispute_status === 'rejected' ? 'bg-red-500 text-white' : 'bg-orange-500 text-white shadow-lg shadow-orange-200 animate-pulse')
                    }}">
                        {{ $order->dispute_status }}
                    </span>
                </div>

                <div class="space-y-6">
                    <div class="bg-white/60 rounded-2xl p-8 text-sm text-gray-700 leading-relaxed shadow-inner">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 italic">Laporan Anda:</p>
                        "{{ $order->dispute_reason }}"
                    </div>

                    @if($order->dispute_admin_notes)
                    <div class="bg-blue-600 rounded-2xl p-8 text-sm text-white shadow-xl relative overflow-hidden">
                        <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full"></div>
                        <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-3">Tanggapan Admin:</p>
                        <div class="font-bold leading-relaxed text-lg">
                            {{ $order->dispute_admin_notes }}
                        </div>
                    </div>
                    @else
                    <p class="text-xs text-orange-600 font-bold italic">Admin akan segera memberikan tanggapan terkait laporan Anda.</p>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- Kolom Kanan — Aksi --}}
        <div class="lg:col-span-4 space-y-10">

            {{-- Tombol Konfirmasi Terima (Delivered Status) --}}
            @if($order->status === 'settlement_verified')
            <div class="bg-gradient-to-br from-gray-900 to-[#1a1a2e] rounded-[2rem] p-1 shadow-2xl overflow-hidden group">
                <div class="bg-gray-900 rounded-[1.9rem] p-10 text-white h-full relative">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-red-600/10 rounded-full blur-3xl group-hover:bg-red-600/20 transition-colors"></div>
                    <h3 class="font-black text-xl mb-3 tracking-tight">Barang Sudah Diterima?</h3>
                    <p class="text-xs text-gray-400 mb-8 leading-relaxed font-medium">Admin telah memverifikasi pelunasan Anda. Pastikan barang dalam kondisi baik sebelum konfirmasi penerimaan.</p>
                    <form method="POST" action="{{ route('b2b.orders.confirm-received', $order->order_number) }}">
                        @csrf
                        <button type="submit" class="w-full bg-[#CD422B] hover:bg-red-700 text-white font-black py-4 rounded-2xl transition-all shadow-xl hover:-translate-y-1 active:translate-y-0 text-sm tracking-widest uppercase">
                            Konfirmasi Terima
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Struk Lunas --}}
            @if(in_array($order->status, ['settlement_verified', 'delivered', 'completed']) && $order->settlement_verified_at)
            <div class="bg-emerald-600 rounded-[2rem] p-8 text-white shadow-lg relative overflow-hidden">
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                <div class="flex items-center gap-4 mb-6 relative z-10">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl shadow-inner">✅</div>
                    <div>
                        <p class="text-[9px] font-black text-emerald-100 uppercase tracking-widest mb-0.5">Verification Success</p>
                        <p class="font-black text-xl">LUNAS TERVERIFIKASI</p>
                    </div>
                </div>
                <div class="bg-emerald-700/50 rounded-2xl p-4 text-xs space-y-2 relative z-10">
                    <div class="flex justify-between border-b border-white/5 pb-2">
                        <span class="text-emerald-200">Waktu</span>
                        <span class="font-bold">{{ $order->settlement_verified_at->format('H:i') }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-emerald-200">Tanggal</span>
                        <span class="font-bold">{{ $order->settlement_verified_at->format('d M Y') }}</span>
                    </div>
                </div>
                <p class="text-[10px] text-center text-emerald-200 mt-6 font-bold italic">Bukti pembayaran sah sistem APM.</p>
            </div>
            @endif

            {{-- Upload Section (DP & Pelunasan) --}}
            @if($order->status === 'pending_review')
            <div class="bg-blue-50 border border-blue-100 rounded-[2rem] p-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-white rounded-[1.5rem] flex items-center justify-center text-3xl shadow-sm mx-auto mb-4">📑</div>
                <h3 class="font-black text-blue-800 mb-1">Pesanan Sedang Direview</h3>
                <p class="text-xs text-blue-700/70 font-medium leading-relaxed">Tim logistik kami sedang menghitung biaya pengiriman armada. Anda akan segera diberitahu untuk pembayaran DP.</p>
            </div>
            @endif

            @if($order->status === 'waiting_dp')
            <div class="bg-white rounded-[2rem] border border-amber-200 shadow-sm p-8">
                <h3 class="font-black text-gray-900 text-lg mb-2 tracking-tight">Upload Bukti DP</h3>
                <p class="text-xs text-gray-400 mb-6 font-medium leading-relaxed">Harap bayar <strong class="text-[#CD422B]">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</strong> agar pesanan bisa diproses.</p>
                <form method="POST" action="{{ route('b2b.orders.upload-dp', $order->order_number) }}" enctype="multipart/form-data">
                    @csrf
                    <label class="block w-full border-2 border-dashed border-amber-200 rounded-[1.5rem] p-8 text-center cursor-pointer hover:border-amber-400 hover:bg-amber-50/30 transition-all mb-6">
                        <input type="file" name="dp_proof" accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="this.parentElement.querySelector('span').textContent = this.files[0]?.name">
                        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-3">📁</div>
                        <span class="text-xs text-gray-500 font-bold">Pilih Bukti Transfer DP</span>
                    </label>
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-4 rounded-2xl transition-all shadow-md text-xs uppercase tracking-widest">
                        Submit Pembayaran
                    </button>
                </form>
            </div>
            @endif

            @if($order->status === 'in_transit')
            <div class="bg-emerald-50 border border-emerald-100 rounded-[2rem] p-6 mb-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl shadow-sm shrink-0">🚚</div>
                    <div>
                        <h3 class="font-black text-emerald-800 text-lg tracking-tight">DP Telah Diverifikasi</h3>
                        <p class="text-xs text-emerald-700/80 font-medium leading-relaxed mt-1">
                            Pembayaran DP Anda sudah diverifikasi. Barang sedang dikirim dan armada dalam perjalanan.
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-[2rem] border border-purple-200 shadow-sm p-8">
                <h3 class="font-black text-gray-900 text-lg mb-2 tracking-tight">Upload Pelunasan</h3>
                <p class="text-xs text-gray-400 mb-6 font-medium leading-relaxed">Sisa tagihan: <strong class="text-purple-600">Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</strong>. Bisa transfer/bayar ke driver.</p>
                <form method="POST" action="{{ route('b2b.orders.upload-settlement', $order->order_number) }}" enctype="multipart/form-data">
                    @csrf
                    <label class="block w-full border-2 border-dashed border-purple-200 rounded-[1.5rem] p-8 text-center cursor-pointer hover:border-purple-400 hover:bg-purple-50/30 transition-all mb-6">
                        <input type="file" name="settlement_proof" accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="this.parentElement.querySelector('span').textContent = this.files[0]?.name">
                        <div class="w-12 h-12 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-3">📄</div>
                        <span class="text-xs text-gray-500 font-bold">Pilih Bukti Pelunasan</span>
                    </label>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-black py-4 rounded-2xl transition-all shadow-md text-xs uppercase tracking-widest">
                        Submit Pelunasan
                    </button>
                </form>
            </div>
            @endif

            {{-- Status Waiting Messages --}}
            @if($order->status === 'dp_uploaded')
            <div class="bg-yellow-50 border border-yellow-100 rounded-[2rem] p-8 text-center">
                <div class="w-16 h-16 bg-white rounded-[1.5rem] flex items-center justify-center text-3xl shadow-sm mx-auto mb-4">⌛</div>
                <h3 class="font-black text-yellow-800 mb-1">DP Sedang Diverifikasi</h3>
                <p class="text-xs text-yellow-700/70 font-medium">Pembayaran DP sedang dicek. Barang dan armada sedang disiapkan oleh tim logistik.</p>
            </div>
            @endif

            @if($order->status === 'settlement_uploaded')
            <div class="bg-purple-50 border border-purple-100 rounded-[2rem] p-8 text-center">
                <div class="w-16 h-16 bg-white rounded-[1.5rem] flex items-center justify-center text-3xl shadow-sm mx-auto mb-4">🕒</div>
                <h3 class="font-black text-purple-800 mb-1">Cek Pembayaran</h3>
                <p class="text-xs text-purple-700/70 font-medium">Driver sedang mengonfirmasi dana masuk. Mohon tunggu sejenak.</p>
            </div>
            @endif

            @if($order->status === 'delivered')
            <div class="bg-indigo-50 border border-indigo-100 rounded-[2rem] p-8 text-center">
                <div class="w-16 h-16 bg-white rounded-[1.5rem] flex items-center justify-center text-3xl shadow-sm mx-auto mb-4">🏠</div>
                <h3 class="font-black text-indigo-800 mb-1">Barang Diterima</h3>
                <p class="text-xs text-indigo-700/70 font-medium leading-relaxed">Konfirmasi berhasil. Admin sedang memfinalisasi dokumen SPB Anda.</p>
            </div>
            @endif

            @if($order->status === 'completed' && $order->spb_path)
            <div class="bg-white rounded-[2rem] border border-green-100 shadow-sm p-10 text-center">
                <div class="w-20 h-20 bg-green-50 text-green-500 rounded-[1.5rem] flex items-center justify-center text-4xl shadow-sm mx-auto mb-6">📦</div>
                <h3 class="font-black text-gray-900 mb-6 tracking-tight text-xl">Dokumen TTD</h3>
                <a href="{{ Storage::url($order->spb_path) }}" target="_blank"
                   class="inline-flex items-center justify-center gap-3 w-full bg-green-600 hover:bg-green-700 text-white font-black py-5 rounded-2xl transition-all shadow-md text-xs uppercase tracking-widest">
                    <span>Download SPB</span>
                </a>
            </div>
            @endif

        </div> {{-- End of Kolom Kanan --}}
    </div> {{-- End of Grid --}}
</div> {{-- End of Main Container --}}

@include('partials._footer')
</body>
</html>
