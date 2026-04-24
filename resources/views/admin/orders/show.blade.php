@extends('admin.layout')
@section('title', 'Detail Transaksi')
@section('subtitle', 'Pesanan ID: ' . $order->order_id)

@section('content')
<div class="mb-5 flex gap-4 items-center">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-800 transition font-medium text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LEFT COLUMN: Order Details & Products -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Action / Timeline Panel -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Aksi Eksekusi Logistik</h3>

            @if($order->payment_status !== 'paid')
                <div class="flex items-center gap-3 bg-amber-50 text-amber-700 p-4 rounded-xl border border-amber-200 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Pesanan belum dilunasi. Aksi pengiriman terkunci.
                </div>
            @elseif($order->status === 'cancelled')
                <div class="flex items-center gap-3 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Pesanan ini telah dibatalkan.
                </div>
            @else

                @if(in_array($order->status, ['pending', 'confirmed']))
                    <p class="text-sm text-gray-600 mb-4">Pembayaran telah ada (<strong class="text-green-600">Terverifikasi</strong>). Silakan persiapkan barang dan tandai sebagai diproses.</p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}" class="flex-1 sm:flex-none">
                            @csrf @method('PATCH')
                            <input type="hidden" name="action" value="process">
                            <button type="submit" onclick="return confirm('Proses pesanan ini sekarang?')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-sm w-full">
                                Mulai Proses Pesanan (Kemas)
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}" class="flex-1 sm:flex-none">
                            @csrf @method('PATCH')
                            <input type="hidden" name="action" value="cancel">
                            <button type="submit" onclick="return confirm('Yakin ingin membatalkan pesanan ini secara paksa? Jika sudah dibayar, proses refund manual diperlukan.')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-sm w-full flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Batalkan Pesanan Secara Paksa
                            </button>
                        </form>
                    </div>

                @elseif($order->status === 'processing')
                    @php $isExpedisi = !empty($order->courier) || ($order->shipping_mode ?? '') === 'rajaongkir'; @endphp

                        @if($isExpedisi)
                        {{-- ── EKSPEDISI (RajaOngkir) ── --}}
                        <div class="bg-orange-50 border border-orange-200 p-5 rounded-xl space-y-3">
                            <p class="text-sm text-orange-800 font-semibold">📦 Barang sedang disiapkan — Pengiriman via Ekspedisi</p>
                            <p class="text-xs text-orange-600">Kurir: <strong>{{ strtoupper($order->courier ?? '-') }}</strong></p>
                            <p class="text-xs text-orange-600">Setelah paket diserahkan ke kurir, klik tombol auto-generate untuk membuat nomor resi otomatis dan menandai pesanan terkirim.</p>

                            {{-- Form dispatch ekspedisi (BUKAN nested) --}}
                            <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}" id="formExpedisi">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" value="dispatch_expedition">
                                <button type="submit"
                                        onclick="return confirm('Auto-generate nomor resi dan tandai pesanan sudah diserahkan ke kurir?')"
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                                    🚚 Auto Generate Resi & Tandai Dikirim via {{ strtoupper($order->courier ?? 'Ekspedisi') }}
                                </button>
                            </form>

                            {{-- Tombol batalkan (form terpisah, BUKAN nested) --}}
                            <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit"
                                        onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                        class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2 px-4 rounded-xl transition text-sm">
                                    ✕ Batalkan Pesanan
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- ── LALAMOVE ── --}}
                        <div class="bg-blue-50 border border-blue-200 p-5 rounded-xl">
                            <p class="text-sm text-blue-800 font-semibold mb-1">📦 Barang sedang disiapkan di gudang.</p>
                            <p class="text-xs text-blue-600 mb-4">Setelah barang selesai dikemas dan siap, tekan tombol di bawah untuk memanggil driver Lalamove.</p>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}" class="flex-1">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="dispatch_driver">
                                    <button type="submit"
                                            onclick="return confirm('Panggil driver Lalamove sekarang? Pastikan barang sudah siap di gudang.')"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-sm w-full flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        Panggil Driver Lalamove
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit"
                                            onclick="return confirm('Yakin ingin batalkan pesanan yang sedang diproses? Stok akan dikembalikan.')"
                                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-sm w-full flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Batalkan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                @elseif($order->status === 'shipped')
                    @php $isExpedisi = !empty($order->tracking_number) || (($order->shipping_mode ?? 'lalamove') === 'rajaongkir'); @endphp

                    @if($isExpedisi)
                        {{-- ── SHIPPED via EKSPEDISI ── --}}
                        <div class="bg-orange-50 border border-orange-200 p-5 rounded-xl space-y-4">
                            <div>
                                <p class="text-sm text-orange-800 font-bold flex items-center gap-2">
                                    🚚 Barang Sedang Dikirim via {{ strtoupper($order->courier ?? 'Ekspedisi') }}
                                </p>
                                <p class="text-xs text-orange-600 mt-1">
                                    No. Resi: <strong class="font-mono">{{ $order->tracking_number ?: '-' }}</strong>
                                </p>
                                @if($order->shipped_at)
                                <p class="text-xs text-orange-500 mt-0.5">
                                    Diserahkan ke kurir: {{ $order->shipped_at->format('d M Y, H:i') }}
                                </p>
                                @endif
                            </div>

                            @if($order->user_confirmed)
                                {{-- User sudah konfirmasi, admin bisa tandai selesai --}}
                                <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700 font-semibold">
                                    ✅ Pelanggan telah mengkonfirmasi pesanan diterima.
                                    {{ $order->user_confirmed_at?->format('d M Y, H:i') }}
                                </div>
                                <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="deliver">
                                    <button type="submit" onclick="return confirm('Tandai pesanan ini selesai?')"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                                        ✓ Tandai Pesanan Selesai
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center gap-2 text-xs text-orange-500 bg-white border border-orange-100 rounded-xl px-4 py-3">
                                    ⏳ Menunggu konfirmasi penerimaan dari pelanggan...
                                </div>
                                <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="deliver">
                                    <button type="submit" onclick="return confirm('Override: tandai selesai tanpa konfirmasi pelanggan?')"
                                            class="w-full bg-green-100 hover:bg-green-200 text-green-700 font-semibold py-2.5 px-6 rounded-xl transition text-sm">
                                        ✓ Tandai Selesai (Override)
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit" onclick="return confirm('Yakin batalkan? Paket sudah di jalan.')"
                                        class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2 px-4 rounded-xl transition text-sm">
                                    ✕ Batalkan Pesanan
                                </button>
                            </form>
                        </div>

                    @else
                        {{-- ── SHIPPED via LALAMOVE ── --}}
                        <div class="bg-indigo-50 border border-indigo-200 p-5 rounded-xl space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <p class="text-sm text-indigo-800 font-bold flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        Barang Sedang Dikirim via Lalamove
                                    </p>
                                    <p class="text-xs text-indigo-600 mt-1">
                                        Driver ID: <strong>{{ $order->lalamove_order_id ?: '-' }}</strong>
                                    </p>
                                </div>
                                @if($order->lalamove_share_link)
                                <a href="{{ $order->lalamove_share_link }}" target="_blank"
                                   class="text-xs font-bold text-indigo-700 border border-indigo-300 bg-white px-4 py-2 rounded-lg hover:bg-indigo-50 transition flex items-center gap-1.5 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    Lacak Driver
                                </a>
                                @endif
                            </div>

                            @if($order->lalamove_driver_name)
                            <div class="flex items-center gap-3 bg-white border border-indigo-100 rounded-xl px-4 py-3">
                                <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm">
                                    {{ strtoupper(substr($order->lalamove_driver_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $order->lalamove_driver_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->lalamove_driver_phone ?: '-' }}</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center gap-2 text-xs text-indigo-500 bg-white border border-indigo-100 rounded-xl px-4 py-3">
                                <svg class="w-4 h-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="10"/></svg>
                                Driver sedang menuju gudang APM...
                                @if(str_contains($order->lalamove_order_id ?? '', 'MOCK'))
                                <span class="ml-1 text-amber-500">(Mode Sandbox)</span>
                                @endif
                            </div>
                            @endif

                            @if($order->user_confirmed)
                                <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700 font-semibold">
                                    ✅ Pelanggan telah mengkonfirmasi pesanan diterima.
                                </div>
                            @endif

                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}" class="flex-1">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="deliver">
                                    <button type="submit" onclick="return confirm('Konfirmasi pesanan telah diterima?')"
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-lg transition shadow-sm w-full">
                                        ✓ Tandai Selesai Diterima
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.orders.status.update', $order->order_id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit" onclick="return confirm('Yakin batalkan? Paket sudah di jalan.')"
                                            class="bg-red-100 hover:bg-red-200 text-red-600 font-bold py-2.5 px-4 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                @elseif($order->status === 'delivered')
                    <div class="flex items-center gap-3 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 text-sm font-bold">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Sistem telah menandai Pesanan ini Selesai & Diterima Pembeli.
                    </div>
                @endif
            @endif
        </div>

        <!-- Product List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Detail Keranjang Pembeli</h3>

            @php $items = is_array($order->items) ? $order->items : json_decode($order->items, true); @endphp
            <div class="space-y-4">
                @foreach($items as $item)
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-xl bg-gray-50 border border-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden">
                        @if(isset($item['image']) && $item['image'])
                            <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 text-sm truncate">{{ $item['name'] }}</p>
                        @php
                            $weightPerUnit = $item['weight'] ?? 1;
                            $totalKg       = $item['quantity'] * $weightPerUnit;
                        @endphp
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $totalKg }} Kg × Rp {{ number_format($item['price'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold text-gray-900 text-sm">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Ongkos Kirim</span>
                    <span>{{ $order->shipping_cost > 0 ? 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') : 'Akan Dikonfirmasi' }}</span>
                </div>
                <div class="flex justify-between text-base font-black text-[#CD422B] pt-2 border-t border-gray-100 mt-2">
                    <span>Total Keseluruhan</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Customer & Payment Data -->
    <div class="space-y-6">

        <!-- Recipient Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Informasi Pengiriman</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-400 font-semibold mb-1">Penerima</p>
                    <p class="text-sm font-bold text-gray-900">{{ $order->recipient_name }}</p>
                    <p class="text-sm text-gray-600">{{ $order->recipient_phone }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold mb-1">Alamat Pengiriman</p>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $order->recipient_address }}</p>
                    <p class="text-sm text-gray-600 font-medium mt-1">{{ $order->recipient_city }}</p>
                </div>
                @if($order->notes)
                <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-lg text-sm text-yellow-800">
                    <strong>Catatan:</strong> {{ $order->notes }}
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Validasi Midtrans</h3>
            <div class="space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Status Bayar</span>
                    @if($order->payment_status === 'paid')
                        <span class="font-bold text-green-600 bg-green-50 px-2 rounded">Settlement (Lunas)</span>
                    @else
                        <span class="font-bold text-amber-600 bg-amber-50 px-2 rounded">{{ ucfirst($order->payment_status) }}</span>
                    @endif
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Channel</span>
                    <span class="font-bold text-gray-900 capitalize">{{ $order->midtrans_payment_type ? str_replace('_', ' ', $order->midtrans_payment_type) : '-' }}</span>
                </div>
                <div class="flex flex-col text-sm border-t border-gray-100 pt-3">
                    <span class="text-gray-500 text-xs mb-1">Midtrans Transaction ID</span>
                    <span class="font-mono text-[11px] text-gray-600 break-all">{{ $order->midtrans_transaction_id ?? 'Belum ada ID' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Waktu Bayar</span>
                    <span class="font-medium text-gray-800">{{ $order->paid_at ? $order->paid_at->format('d M Y, H:i') : '-' }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
