@extends('admin.layout')
@section('title', 'Transaksi B2B')
@section('subtitle', $order->order_number . ' — ' . ($order->b2bRegistration->company_name ?? '-'))

@section('content')

{{-- Breadcrumb --}}
<nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
    <a href="{{ route('admin.b2b.orders.index') }}" class="hover:text-[#CD422B] transition font-medium">Transaksi B2B</a>
    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="font-bold text-gray-700">{{ $order->order_number }}</span>
</nav>

@if(session('success'))
    <div class="mb-5 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl text-sm font-semibold">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-5 bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl text-sm font-semibold">❌ {{ session('error') }}</div>
@endif

{{-- Header --}}
@php
    $colorMap = [
        'gray'   => 'bg-gray-100 text-gray-700',   'amber'  => 'bg-amber-100 text-amber-800',
        'yellow' => 'bg-yellow-100 text-yellow-800','blue'   => 'bg-blue-100 text-blue-800',
        'orange' => 'bg-orange-100 text-orange-800','purple' => 'bg-purple-100 text-purple-800',
        'teal'   => 'bg-teal-100 text-teal-800',   'indigo' => 'bg-indigo-100 text-indigo-800',
        'green'  => 'bg-green-100 text-green-800',  'red'    => 'bg-red-100 text-red-800',
    ];
    $badge = $colorMap[$order->status_color] ?? 'bg-gray-100 text-gray-700';
@endphp
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <p class="text-xs text-gray-400 mb-1 font-mono">{{ $order->order_number }}</p>
        <h2 class="text-xl font-black text-gray-900">{{ $order->b2bRegistration->company_name ?? '-' }}</h2>
        <p class="text-sm text-gray-500 mt-0.5">PIC: {{ $order->user->name }} · {{ $order->created_at->format('d F Y, H:i') }}</p>
    </div>
    <div class="flex items-center gap-3 self-start sm:self-center flex-wrap">
        <span class="px-4 py-1.5 rounded-full text-sm font-bold {{ $badge }}">{{ $order->status_label }}</span>
        @if($order->hasActiveDispute())
        <span class="px-3 py-1.5 rounded-full text-sm font-bold bg-orange-100 text-orange-800">⚠️ Dispute Aktif</span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Kolom Kiri --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Produk --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Produk Dipesan</h3>
            <div class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 py-3 first:pt-0 last:pb-0">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                        @if(!empty($item['image']))
                            <img src="{{ $item['image'] }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-lg">📦</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-900 text-sm">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $item['qty_ton'] }} Ton ({{ number_format($item['qty_kg'], 0, ',', '.') }} KG) × Rp {{ number_format($item['price_per_kg'], 0, ',', '.') }}/KG</p>
                    </div>
                    <p class="font-bold text-gray-900 text-sm shrink-0">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
            {{-- Ringkasan Harga --}}
            <div class="mt-5 pt-5 border-t border-gray-100 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Ongkos Kirim Armada</span>
                    @if($order->shipping_cost > 0)
                        <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    @else
                        <span class="text-xs text-amber-600 font-semibold bg-amber-50 px-2 py-0.5 rounded-full">Belum diisi</span>
                    @endif
                </div>
                <div class="flex justify-between pt-2 border-t border-gray-100">
                    <span class="font-bold text-gray-900">Total</span>
                    <span class="font-black text-[#CD422B]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between"><span class="text-gray-500">DP 50%</span><span class="font-bold text-blue-700">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span></div>
                @if($order->damage_deduction > 0)
                <div class="flex justify-between"><span class="text-gray-500">Pelunasan 50%</span><span class="font-bold text-gray-400 line-through">Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-orange-600 text-xs">🛡️ Potongan Kerusakan</span><span class="font-bold text-orange-600">- Rp {{ number_format($order->damage_deduction, 0, ',', '.') }}</span></div>
                <div class="flex justify-between bg-green-50 rounded-lg px-3 py-1.5 -mx-1"><span class="font-bold text-green-700">Pelunasan Akhir</span><span class="font-black text-green-700">Rp {{ number_format($order->adjusted_settlement, 0, ',', '.') }}</span></div>
                @else
                <div class="flex justify-between"><span class="text-gray-500">Pelunasan 50%</span><span class="font-bold text-purple-700">Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</span></div>
                @endif
            </div>
        </div>

        {{-- Info Mitra & Pengiriman --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Informasi Mitra & Pengiriman</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Perusahaan</p>
                    <p class="font-bold text-gray-900">{{ $order->b2bRegistration->company_name ?? '-' }}</p>
                    <p class="text-gray-600">NPWP: {{ $order->b2bRegistration->npwp ?? '-' }}</p>
                    <p class="text-gray-500 text-xs mt-1">PIC: {{ $order->b2bRegistration->pic_name ?? '-' }} ({{ $order->b2bRegistration->pic_position ?? '-' }})</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Alamat Kirim</p>
                    <p class="font-bold text-gray-900">{{ $order->recipient_name }}</p>
                    <p class="text-gray-600">{{ $order->recipient_phone }}</p>
                    <p class="text-gray-600 text-xs mt-0.5">{{ $order->recipient_address }}, {{ $order->recipient_city }}</p>
                </div>
                @if($order->delivery_date)
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Info Pengiriman</p>
                    <p class="text-gray-700">Jadwal: <strong>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d F Y') }}</strong></p>
                    @if($order->driver_name)<p class="text-gray-700">Driver: <strong>{{ $order->driver_name }}</strong> ({{ $order->driver_phone }})</p>@endif
                    @if($order->waybill_number)<p class="text-gray-700">No. Surat Jalan: <strong class="font-mono">{{ $order->waybill_number }}</strong></p>@endif
                </div>
                @endif
            </div>
            @if($order->notes)
            <div class="mt-4 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-600">📝 <em>{{ $order->notes }}</em></div>
            @endif
        </div>

        {{-- Bukti Pembayaran --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">Bukti Pembayaran</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="border border-gray-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-gray-500 mb-3">Bukti DP 50%</p>
                    @if($order->dp_proof_path)
                        @php $ext = pathinfo($order->dp_proof_path, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                            <a href="{{ Storage::url($order->dp_proof_path) }}" target="_blank">
                                <img src="{{ Storage::url($order->dp_proof_path) }}" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition mb-2">
                            </a>
                        @else
                            <a href="{{ Storage::url($order->dp_proof_path) }}" target="_blank" class="flex items-center gap-2 text-blue-600 text-sm font-semibold hover:underline mb-2">📄 Lihat File PDF</a>
                        @endif
                        @if($order->dp_bank_name || $order->dp_transfer_reference)
                        <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 mb-2 text-xs text-amber-900 space-y-1">
                            @if($order->dp_bank_name)
                                <p><span class="font-bold">Bank:</span> {{ $order->dp_bank_name }}</p>
                            @endif
                            @if($order->dp_transfer_reference)
                                <p><span class="font-bold">Referensi:</span> {{ $order->dp_transfer_reference }}</p>
                            @endif
                        </div>
                        @endif
                        <p class="text-xs text-gray-400">Diupload: {{ $order->dp_uploaded_at?->format('d M Y, H:i') }}</p>
                        @if($order->dp_verified_at)
                            <p class="text-xs text-green-600 font-semibold mt-1">✅ Terverifikasi {{ $order->dp_verified_at->format('d M Y') }}</p>
                        @endif
                    @else
                        <p class="text-xs text-gray-400">Belum ada bukti DP</p>
                    @endif
                </div>
                <div class="border border-gray-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-gray-500 mb-3">Bukti Pelunasan 50%</p>
                    @if($order->settlement_proof_path)
                        @php $ext2 = pathinfo($order->settlement_proof_path, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($ext2), ['jpg','jpeg','png']))
                            <a href="{{ Storage::url($order->settlement_proof_path) }}" target="_blank">
                                <img src="{{ Storage::url($order->settlement_proof_path) }}" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition mb-2">
                            </a>
                        @else
                            <a href="{{ Storage::url($order->settlement_proof_path) }}" target="_blank" class="flex items-center gap-2 text-blue-600 text-sm font-semibold hover:underline mb-2">📄 Lihat File PDF</a>
                        @endif
                        @if($order->settlement_bank_name || $order->settlement_transfer_reference)
                        <div class="bg-purple-50 border border-purple-100 rounded-lg p-3 mb-2 text-xs text-purple-900 space-y-1">
                            @if($order->settlement_bank_name)
                                <p><span class="font-bold">Bank:</span> {{ $order->settlement_bank_name }}</p>
                            @endif
                            @if($order->settlement_transfer_reference)
                                <p><span class="font-bold">Referensi:</span> {{ $order->settlement_transfer_reference }}</p>
                            @endif
                        </div>
                        @endif
                        <p class="text-xs text-gray-400">Diupload: {{ $order->settlement_uploaded_at?->format('d M Y, H:i') }}</p>
                        @if($order->settlement_verified_at)
                            <p class="text-xs text-green-600 font-semibold mt-1">✅ Terverifikasi {{ $order->settlement_verified_at->format('d M Y') }}</p>
                        @endif
                    @else
                        <p class="text-xs text-gray-400">Belum ada bukti pelunasan</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Dispute --}}
        @if($order->dispute_status !== 'none')
        <div class="bg-white rounded-2xl border border-orange-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-sm">⚠️ Komplain Mitra</h3>
                @php
                    $dBadge = match($order->dispute_status) {
                        'open'      => 'bg-orange-100 text-orange-800',
                        'reviewing' => 'bg-blue-100 text-blue-800',
                        'resolved'  => 'bg-green-100 text-green-800',
                        'rejected'  => 'bg-red-100 text-red-800',
                        default     => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $dBadge }}">{{ $order->dispute_status_label }}</span>
            </div>
            <p class="text-sm text-gray-700 leading-relaxed mb-3 bg-orange-50 rounded-xl p-4">{{ $order->dispute_reason }}</p>
            @if($order->dispute_images)
            <div class="flex gap-2 flex-wrap mb-4">
                @foreach($order->dispute_images as $img)
                <a href="{{ Storage::url($img) }}" target="_blank">
                    <img src="{{ Storage::url($img) }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition">
                </a>
                @endforeach
            </div>
            @endif
            @if($order->dispute_admin_notes)
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                <p class="text-xs font-bold text-gray-500 mb-1">Tanggapan Admin Sebelumnya:</p>
                <p class="text-sm text-gray-700">{{ $order->dispute_admin_notes }}</p>
            </div>
            @endif
            @if(in_array($order->dispute_status, ['open', 'reviewing']))
            <form method="POST" action="{{ route('admin.b2b.orders.dispute', $order->order_number) }}" class="mt-4 border-t border-orange-100 pt-4">
                @csrf
                <label class="text-xs font-semibold text-gray-600 block mb-1.5">Tanggapan Admin</label>
                <textarea name="dispute_admin_notes" rows="3" required placeholder="Tulis tanggapan atau keputusan Anda..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20 resize-none mb-3"></textarea>
                <div class="flex gap-2 flex-wrap">
                    <button type="submit" name="action" value="reviewing" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition">Sedang Ditinjau</button>
                    <button type="submit" name="action" value="resolved" onclick="return confirm('Tandai dispute sebagai selesai?')" class="px-4 py-2 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition">Selesaikan</button>
                    <button type="submit" name="action" value="rejected" onclick="return confirm('Tolak dispute ini?')" class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition">Tolak</button>
                </div>
            </form>
            @endif
        </div>
        @endif

    </div>

    {{-- Kolom Kanan — Aksi Admin --}}
    <div class="space-y-5">

        {{-- Review Order & Input Ongkir --}}
        @if($order->status === 'pending_review')
        @php
            $totalWeightKg = collect($order->items)->sum('qty_kg');
            $shippingCalc = \App\Models\ShippingRate::calculate($order->recipient_city, $totalWeightKg);
        @endphp
        <div class="bg-white rounded-2xl border border-blue-200 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-3">🚚 Review & Input Ongkir</h3>
            <p class="text-xs text-gray-500 mb-4">Tentukan biaya pengiriman untuk pesanan ini. Tujuan: <strong>{{ $order->recipient_city }}</strong> · Berat: <strong>{{ number_format($totalWeightKg, 0, ',', '.') }} KG</strong></p>
            
            @if($shippingCalc)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-4">
                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wide mb-1">💡 Sugesti Tarif Ongkir</p>
                <p class="text-xs text-gray-700">Rute: <strong>Pekanbaru → {{ $order->recipient_city }}</strong></p>
                <p class="text-xs text-gray-700">Tarif: Rp {{ number_format($shippingCalc['rate_per_kg'], 0, ',', '.') }}/KG × {{ number_format($totalWeightKg, 0, ',', '.') }} KG</p>
                <p class="text-sm font-black text-blue-700 mt-1">= Rp {{ number_format($shippingCalc['total'], 0, ',', '.') }}</p>
                @if($shippingCalc['estimated_days'])
                <p class="text-[10px] text-blue-500 mt-1">Estimasi: {{ $shippingCalc['estimated_days'] }} hari</p>
                @endif
                <button type="button" onclick="document.getElementById('shipping_cost_input').value = {{ (int) $shippingCalc['total'] }}" class="mt-2 text-[10px] font-bold text-blue-600 bg-white border border-blue-200 px-3 py-1 rounded-lg hover:bg-blue-100 transition">
                    Gunakan Tarif Ini
                </button>
            </div>
            @else
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-4">
                <p class="text-[10px] font-bold text-amber-600">⚠️ Tarif ongkir untuk kota <strong>{{ $order->recipient_city }}</strong> belum tersedia di tabel tarif.</p>
                <a href="{{ route('admin.shipping-rates.index') }}" class="text-[10px] text-blue-600 font-bold hover:underline">Tambah tarif →</a>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.b2b.orders.approve-review', $order->order_number) }}">
                @csrf
                <div class="mb-4">
                    <label class="text-xs font-semibold text-gray-600 block mb-1.5">Biaya Ongkir (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="shipping_cost" id="shipping_cost_input" required min="0"
                               value="{{ $shippingCalc ? (int) $shippingCalc['total'] : '' }}"
                               placeholder="0"
                               class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#CD422B]/20">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 italic">*Total & DP akan otomatis dihitung ulang setelah ongkir disimpan.</p>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition text-sm shadow-md flex items-center justify-center gap-2">
                    ✅ Simpan & Buka Pembayaran
                </button>
            </form>
        </div>
        @endif

        {{-- Verifikasi DP (Hanya Approve/Reject) --}}
        @if($order->status === 'dp_uploaded')
        <div class="bg-white rounded-2xl border border-amber-200 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-2">🔍 Verifikasi Bukti DP</h3>
            <p class="text-xs text-gray-500 mb-4">Cek bukti DP mitra senilai <strong class="text-[#CD422B]">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</strong>. Pastikan jumlah & bukti transfer sesuai.</p>
            
            @if($order->dp_proof_path)
            <div class="bg-gray-50 rounded-xl p-3 mb-4">
                @php $ext = pathinfo($order->dp_proof_path, PATHINFO_EXTENSION); @endphp
                @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                    <a href="{{ Storage::url($order->dp_proof_path) }}" target="_blank">
                        <img src="{{ Storage::url($order->dp_proof_path) }}" class="w-full h-40 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition">
                    </a>
                @else
                    <a href="{{ Storage::url($order->dp_proof_path) }}" target="_blank" class="flex items-center gap-2 text-blue-600 text-sm font-semibold hover:underline">📄 Lihat File PDF</a>
                @endif
                @if($order->dp_bank_name)
                <p class="text-xs text-gray-500 mt-2">Bank: <strong>{{ $order->dp_bank_name }}</strong></p>
                @endif
                @if($order->dp_transfer_reference)
                <p class="text-xs text-gray-500">Ref: <strong>{{ $order->dp_transfer_reference }}</strong></p>
                @endif
                <p class="text-[10px] text-gray-400 mt-1">Diupload: {{ $order->dp_uploaded_at?->format('d M Y, H:i') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.b2b.orders.verify-dp', $order->order_number) }}">
                @csrf
                <div class="mb-4">
                    <label class="text-xs font-semibold text-gray-600 block mb-1">Catatan Admin (Opsional)</label>
                    <textarea name="dp_admin_notes" rows="2" placeholder="Contoh: Bukti DP valid, nominal sesuai." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none resize-none focus:ring-2 focus:ring-amber-200"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" name="action" value="approve" onclick="return confirm('Setujui bukti DP ini? Status akan menjadi DP Terverifikasi.')" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl transition text-sm shadow-sm flex items-center justify-center gap-1">
                        ✅ Setujui DP
                    </button>
                    <button type="submit" name="action" value="reject" onclick="return confirm('Tolak bukti DP ini? Mitra akan diminta upload ulang.')" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 rounded-xl transition text-sm flex items-center justify-center gap-1">
                        ❌ Tolak DP
                    </button>
                </div>
            </form>
        </div>
        @endif

        {{-- Dispatch Armada (Setelah DP Verified) --}}
        @if($order->status === 'dp_verified')
        <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-0">
            <div class="flex items-center gap-2">
                <span class="text-xl">✅</span>
                <div>
                    <p class="font-bold text-green-800 text-sm">DP Telah Terverifikasi</p>
                    <p class="text-[10px] text-green-600">Diverifikasi {{ $order->dp_verified_at?->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-blue-200 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-2">🚛 Siapkan & Dispatch Armada</h3>
            <p class="text-xs text-gray-500 mb-4">Isi data driver dan jadwal pengiriman. Setelah dispatch, status akan berubah menjadi <strong>Dalam Pengiriman</strong>.</p>
            <form method="POST" action="{{ route('admin.b2b.orders.dispatch-armada', $order->order_number) }}">
                @csrf
                <div class="space-y-3 mb-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-1">Tgl Kirim</label>
                            <input type="date" name="delivery_date" required min="{{ now()->format('Y-m-d') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-1">No. Surat Jalan</label>
                            <input type="text" name="waybill_number" value="{{ $order->order_number }}-SJ"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-1">Nama Driver</label>
                            <input type="text" name="driver_name" required placeholder="Nama Driver"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-1">HP Driver</label>
                            <input type="text" name="driver_phone" required placeholder="08xxxx"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 block mb-1">Catatan (Opsional)</label>
                        <textarea name="dp_admin_notes" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                    </div>
                </div>
                <button type="submit" onclick="return confirm('Dispatch armada sekarang? Barang akan langsung dikirim.')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition text-sm shadow-md flex items-center justify-center gap-2">
                    🚛 Dispatch Armada Sekarang
                </button>
            </form>
        </div>
        @endif

        {{-- Tandai Barang Tiba (Admin/Driver) --}}
        @if($order->status === 'in_transit')
        <div class="bg-white rounded-2xl border border-blue-200 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-2">📍 Barang Tiba di Lokasi?</h3>
            <p class="text-xs text-gray-500 mb-4">Tandai bahwa armada sudah sampai di lokasi mitra. Mitra akan diminta melakukan inspeksi barang bersama driver.</p>
            <form method="POST" action="{{ route('admin.b2b.orders.mark-arrived', $order->order_number) }}">
                @csrf
                <button type="submit" onclick="return confirm('Tandai barang sudah tiba? Mitra akan diminta inspeksi.')" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition text-sm shadow-md flex items-center justify-center gap-2">
                    📍 Tandai Barang Tiba
                </button>
            </form>
        </div>
        @endif

        {{-- Menunggu Inspeksi --}}
        @if($order->status === 'arrived')
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🔍</span>
                <div>
                    <p class="font-bold text-indigo-800 text-sm">Menunggu Inspeksi Mitra</p>
                    <p class="text-[10px] text-indigo-600">Barang tiba {{ $order->arrived_at?->format('d M Y, H:i') }}. Mitra sedang melakukan pengecekan bersama driver.</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Hasil Inspeksi --}}
        @if($order->inspection_status)
        <div class="bg-white rounded-2xl border {{ $order->inspection_status === 'good' ? 'border-green-200' : 'border-orange-200' }} shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-3">🔍 Hasil Inspeksi Barang</h3>
            <div class="flex items-center gap-2 mb-3">
                @if($order->inspection_status === 'good')
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">✅ Barang Baik</span>
                @else
                    <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-bold rounded-full">⚠️ Ada Kerusakan — Klaim Asuransi</span>
                @endif
            </div>
            @if($order->inspection_notes)
            <div class="bg-gray-50 rounded-xl p-3 mb-3 text-xs text-gray-700">
                <p class="font-bold text-gray-500 mb-1">Catatan Inspeksi:</p>
                {{ $order->inspection_notes }}
            </div>
            @endif
            @if($order->inspection_images)
            <div class="flex gap-2 flex-wrap">
                @foreach($order->inspection_images as $img)
                <a href="{{ Storage::url($img) }}" target="_blank">
                    <img src="{{ Storage::url($img) }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition">
                </a>
                @endforeach
            </div>
            @endif
            <p class="text-[10px] text-gray-400 mt-2">Diinspeksi: {{ $order->inspected_at?->format('d M Y, H:i') }}</p>
        </div>
        @endif

        {{-- Form Potongan Kerusakan (hanya jika inspeksi rusak) --}}
        @if($order->canSetDamageDeduction())
        <div class="bg-white rounded-2xl border border-orange-200 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-2">🛡️ Tetapkan Potongan Kerusakan</h3>
            <p class="text-xs text-gray-500 mb-4">Inspeksi menunjukkan ada kerusakan. Tentukan potongan dari pelunasan mitra. Kerugian ini ditanggung asuransi pengiriman APM.</p>
            
            <div class="bg-gray-50 rounded-xl p-3 mb-4 text-xs">
                <div class="flex justify-between mb-1"><span class="text-gray-500">Pelunasan Awal (50%)</span><span class="font-bold">Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-orange-600"><span>Potongan Kerusakan</span><span class="font-bold" id="preview-deduction">- Rp 0</span></div>
                <div class="flex justify-between border-t border-gray-200 mt-2 pt-2"><span class="font-bold text-gray-700">Yang Harus Dilunasi</span><span class="font-black text-green-700" id="preview-final">Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</span></div>
            </div>

            <form method="POST" action="{{ route('admin.b2b.orders.set-damage-deduction', $order->order_number) }}">
                @csrf
                <div class="mb-3">
                    <label class="text-xs font-semibold text-gray-600 block mb-1">Nominal Potongan (Rp)</label>
                    <input type="number" name="damage_deduction" id="input-deduction" required min="0" max="{{ (int) $order->settlement_amount }}" placeholder="Contoh: 1400000"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-200 focus:outline-none"
                           oninput="updateDeductionPreview()">
                </div>
                <div class="mb-4">
                    <label class="text-xs font-semibold text-gray-600 block mb-1">Catatan Kerusakan</label>
                    <textarea name="damage_notes" rows="2" required placeholder="Contoh: 10 KG Kepiting Soka rusak saat pengiriman, potongan senilai 10 KG × Rp 140.000" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                </div>
                <button type="submit" onclick="return confirm('Tetapkan potongan kerusakan? Pelunasan mitra akan otomatis disesuaikan.')" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-xl transition text-sm shadow-md">
                    Tetapkan Potongan
                </button>
            </form>
        </div>
        <script>
            function updateDeductionPreview() {
                const val = parseFloat(document.getElementById('input-deduction').value) || 0;
                const settlement = {{ (int) $order->settlement_amount }};
                const final_amount = Math.max(0, settlement - val);
                document.getElementById('preview-deduction').textContent = '- Rp ' + new Intl.NumberFormat('id-ID').format(val);
                document.getElementById('preview-final').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(final_amount);
            }
        </script>
        @endif

        {{-- Info Potongan Kerusakan (sudah ditetapkan) --}}
        @if($order->damage_deduction > 0)
        <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-3">🛡️ Potongan Kerusakan</h3>
            <div class="text-xs space-y-1.5">
                <div class="flex justify-between"><span class="text-gray-500">Pelunasan Awal</span><span class="font-bold line-through text-gray-400">Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-orange-700"><span>Potongan Kerusakan</span><span class="font-bold">- Rp {{ number_format($order->damage_deduction, 0, ',', '.') }}</span></div>
                <div class="flex justify-between border-t border-orange-200 pt-2"><span class="font-bold text-gray-700">Pelunasan Akhir</span><span class="font-black text-green-700">Rp {{ number_format($order->adjusted_settlement, 0, ',', '.') }}</span></div>
            </div>
            @if($order->damage_notes)
            <div class="mt-3 bg-white rounded-lg p-2.5 border border-orange-100">
                <p class="text-[10px] font-bold text-orange-500 uppercase mb-0.5">Catatan:</p>
                <p class="text-xs text-gray-700">{{ $order->damage_notes }}</p>
            </div>
            @endif
            <p class="text-[10px] text-gray-400 mt-2">Ditetapkan: {{ $order->damage_set_at?->format('d M Y, H:i') }}</p>
        </div>
        @endif

        {{-- Cetak Surat Jalan (Hanya muncul saat persiapan kirim) --}}
        @if($order->status === 'dp_verified')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-3">📄 Cetak Surat Jalan</h3>
            <p class="text-xs text-gray-500 mb-3">Isi data driver di atas terlebih dahulu, lalu cetak surat jalan. Informasi driver akan otomatis terisi di dokumen.</p>
            <button type="button" id="btn-cetak-sj"
               class="flex items-center justify-center gap-2 w-full bg-[#1a1a2e] hover:bg-black text-white font-bold py-3 rounded-xl transition text-sm shadow-md cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Surat Jalan (Print PDF)
            </button>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mt-3">
                <p class="text-[10px] text-amber-700 font-bold">⚠️ Alur yang benar: <strong>Isi data driver → Cetak surat jalan → Berikan ke driver → Dispatch armada</strong></p>
            </div>
        </div>
        <script>
            document.getElementById('btn-cetak-sj').addEventListener('click', function() {
                const driverName = document.querySelector('input[name="driver_name"]')?.value || '';
                const driverPhone = document.querySelector('input[name="driver_phone"]')?.value || '';
                const deliveryDate = document.querySelector('input[name="delivery_date"]')?.value || '';
                const waybillNumber = document.querySelector('input[name="waybill_number"]')?.value || '';

                let url = "{{ route('admin.b2b.orders.print-surat-jalan', $order->order_number) }}";
                const params = new URLSearchParams();
                if (driverName) params.set('driver_name', driverName);
                if (driverPhone) params.set('driver_phone', driverPhone);
                if (deliveryDate) params.set('delivery_date', deliveryDate);
                if (waybillNumber) params.set('waybill_number', waybillNumber);

                if (params.toString()) url += '?' + params.toString();
                window.open(url, '_blank');
            });
        </script>
        @endif

        {{-- Verifikasi Pelunasan --}}
        @if($order->status === 'settlement_uploaded')
        <div class="bg-white rounded-2xl border border-purple-200 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-4">💰 Konfirmasi Pelunasan</h3>
            @if($order->damage_deduction > 0)
            <div class="bg-orange-50 rounded-xl p-3 mb-4 text-xs space-y-1">
                <div class="flex justify-between"><span class="text-gray-500">Pelunasan Awal</span><span class="font-bold line-through text-gray-400">Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-orange-700"><span>Potongan Kerusakan</span><span class="font-bold">- Rp {{ number_format($order->damage_deduction, 0, ',', '.') }}</span></div>
                <div class="flex justify-between border-t border-orange-200 pt-1.5"><span class="font-bold">Yang Harus Dilunasi</span><span class="font-black text-green-700">Rp {{ number_format($order->adjusted_settlement, 0, ',', '.') }}</span></div>
            </div>
            @else
            <p class="text-xs text-gray-500 mb-4">Sisa yang harus dilunasi: <strong>Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</strong></p>
            @endif
            <form method="POST" action="{{ route('admin.b2b.orders.verify-settlement', $order->order_number) }}">
                @csrf
                <div class="mb-4">
                    <label class="text-xs font-semibold text-gray-600 block mb-1">Catatan Konfirmasi</label>
                    <textarea name="settlement_admin_notes" rows="2" placeholder="Contoh: Pembayaran sudah diterima via transfer." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" name="action" value="approve" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl transition text-sm">✅ Konfirmasi Lunas</button>
                    <button type="submit" name="action" value="reject" onclick="return confirm('Tolak bukti pelunasan ini?')" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 rounded-xl transition text-sm">❌ Tolak</button>
                </div>
            </form>
        </div>
        @endif

        {{-- Upload SPB (Setelah pelunasan terverifikasi) --}}
        @if($order->status === 'settlement_verified')
        <div class="bg-white rounded-2xl border border-teal-200 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 text-sm mb-1">📄 Upload SPB & Selesaikan</h3>
            <p class="text-xs text-gray-500 mb-4">Upload Surat Penerimaan Barang (SPB) fisik yang sudah ditandatangani mitra sebagai bukti final.</p>
            <form method="POST" action="{{ route('admin.b2b.orders.upload-spb', $order->order_number) }}" enctype="multipart/form-data">
                @csrf
                <label class="block w-full border-2 border-dashed border-teal-300 rounded-xl p-4 text-center cursor-pointer hover:border-teal-400 transition mb-3">
                    <input type="file" name="spb_file" required accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="this.parentElement.querySelector('span').textContent = this.files[0]?.name || 'Pilih file SPB'">
                    <svg class="w-8 h-8 text-teal-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span class="text-xs text-gray-500">Pilih file scan/foto SPB (JPG, PNG, PDF · max 5MB)</span>
                </label>
                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-xl transition text-sm">
                    Upload SPB & Selesaikan Pesanan
                </button>
            </form>
        </div>
        @endif

        {{-- SPB sudah ada --}}
        @if($order->spb_path)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <p class="font-bold text-green-800 text-sm mb-1">✅ Order Selesai</p>
            <p class="text-xs text-green-700 mb-3">SPB berhasil diupload. Order telah diselesaikan.</p>
            <a href="{{ Storage::url($order->spb_path) }}" target="_blank"
               class="block text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl transition text-sm">
                Lihat SPB
            </a>
        </div>
        @endif

        {{-- Batalkan Order --}}
        @if(in_array($order->status, ['draft','pending_review','waiting_dp','dp_uploaded','dp_verified']))
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 text-sm mb-3">Batalkan Order</h3>
            <form method="POST" action="{{ route('admin.b2b.orders.cancel', $order->order_number) }}">
                @csrf
                <textarea name="cancel_reason" rows="2" required placeholder="Alasan pembatalan..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none resize-none mb-3"></textarea>
                <button type="submit" onclick="return confirm('Yakin membatalkan order ini?')"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl transition text-sm">
                    Batalkan Order
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

@endsection
