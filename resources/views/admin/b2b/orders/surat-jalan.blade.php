<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Jalan — {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: #fff; color: #111; font-size: 13px; padding: 30px 40px; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #CD422B; padding-bottom: 16px; margin-bottom: 20px; }
        .company-name { font-size: 20px; font-weight: 900; color: #CD422B; letter-spacing: -0.5px; }
        .company-sub { font-size: 11px; color: #666; margin-top: 2px; }
        .doc-title { text-align: right; }
        .doc-title h1 { font-size: 18px; font-weight: 900; color: #1a1a2e; }
        .doc-title p { font-size: 11px; color: #666; margin-top: 2px; }

        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        .meta-box { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; }
        .meta-box h3 { font-size: 10px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 8px; }
        .meta-row { display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 12px; }
        .meta-label { color: #666; }
        .meta-value { font-weight: 700; color: #111; text-align: right; max-width: 55%; }

        .section-title { font-size: 11px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        thead tr { background: #1a1a2e; color: #fff; }
        thead th { padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 700; }
        tbody tr { border-bottom: 1px solid #f3f4f6; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody td { padding: 8px 10px; }
        tfoot tr { background: #f9fafb; font-weight: 700; }
        tfoot td { padding: 8px 10px; border-top: 2px solid #e5e7eb; }

        .payment-box { background: #fef3f2; border: 1px solid #fecaca; border-radius: 8px; padding: 14px; margin-bottom: 20px; }
        .payment-box h3 { font-size: 12px; font-weight: 700; color: #991b1b; margin-bottom: 10px; }
        .payment-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 12px; }
        .payment-total { font-size: 15px; font-weight: 900; color: #CD422B; border-top: 1px solid #fca5a5; padding-top: 6px; margin-top: 6px; }

        .sign-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 10px; }
        .sign-box { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; text-align: center; }
        .sign-box h3 { font-size: 11px; font-weight: 700; color: #666; margin-bottom: 40px; }
        .sign-line { border-top: 1px solid #111; padding-top: 6px; font-size: 11px; font-weight: 700; }
        .sign-sub { font-size: 10px; color: #999; }

        .footer-note { margin-top: 20px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #999; text-align: center; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 10px; font-weight: 700; background: #dcfce7; color: #166534; }

        @media print {
            body { padding: 20px 30px; }
            .no-print { display: none !important; }
            @page { size: A4; margin: 15mm; }
        }
    </style>
</head>
<body>

{{-- Print Button --}}
<div class="no-print" style="margin-bottom: 20px; display: flex; gap: 10px;">
    <button onclick="window.print()" style="background:#CD422B;color:#fff;border:none;padding:10px 20px;border-radius:8px;font-weight:700;cursor:pointer;font-size:13px;">
        🖨️ Cetak Surat Jalan
    </button>
    <a href="{{ route('admin.b2b.orders.show', $order->order_number) }}"
       style="background:#f3f4f6;color:#374151;border:none;padding:10px 20px;border-radius:8px;font-weight:700;text-decoration:none;font-size:13px;">
        ← Kembali
    </a>
</div>

{{-- Header --}}
<div class="header">
    <div>
        <div class="company-name">PT Agro Pangan Maju</div>
        <div class="company-sub">Pergudangan Panam Nirpark No.8-19, Jl.Raya Pekan Baru - Bangkinang</div>
        <div class="company-sub">Kel.Tuah Madani, Kec.Tampan, Kota Pekanbaru, Riau</div>
    </div>
    <div class="doc-title">
        <h1>SURAT JALAN</h1>
        <p>No: {{ $order->waybill_number ?? $order->order_number }}</p>
        <p>Tanggal: {{ now()->format('d F Y') }}</p>
        <span class="badge">RESMI</span>
    </div>
</div>

{{-- Meta Info --}}
@php
    $driverName    = $order->driver_name    ?: request('driver_name', '-');
    $driverPhone   = $order->driver_phone   ?: request('driver_phone', '-');
    $deliveryDate  = $order->delivery_date  ?: request('delivery_date');
    $waybillNumber = $order->waybill_number ?: request('waybill_number', '-');
@endphp
<div class="meta-grid">
    <div class="meta-box">
        <h3>Informasi Mitra Penerima</h3>
        <div class="meta-row"><span class="meta-label">Perusahaan</span><span class="meta-value">{{ $order->b2bRegistration->company_name ?? '-' }}</span></div>
        <div class="meta-row"><span class="meta-label">NPWP</span><span class="meta-value">{{ $order->b2bRegistration->npwp ?? '-' }}</span></div>
        <div class="meta-row"><span class="meta-label">Penerima</span><span class="meta-value">{{ $order->recipient_name }}</span></div>
        <div class="meta-row"><span class="meta-label">No. HP</span><span class="meta-value">{{ $order->recipient_phone }}</span></div>
        <div class="meta-row"><span class="meta-label">Alamat</span><span class="meta-value">{{ $order->recipient_address }}</span></div>
        <div class="meta-row"><span class="meta-label">Kota</span><span class="meta-value">{{ $order->recipient_city }}</span></div>
    </div>
    <div class="meta-box">
        <h3>Informasi Pengiriman</h3>
        <div class="meta-row"><span class="meta-label">No. Order</span><span class="meta-value">{{ $order->order_number }}</span></div>
        <div class="meta-row"><span class="meta-label">Tgl. Kirim</span><span class="meta-value">{{ $deliveryDate ? \Carbon\Carbon::parse($deliveryDate)->format('d F Y') : '-' }}</span></div>
        <div class="meta-row"><span class="meta-label">Driver</span><span class="meta-value">{{ $driverName }}</span></div>
        <div class="meta-row"><span class="meta-label">No. HP Driver</span><span class="meta-value">{{ $driverPhone }}</span></div>
        <div class="meta-row"><span class="meta-label">No. Surat Jalan</span><span class="meta-value">{{ $waybillNumber }}</span></div>
        <div class="meta-row"><span class="meta-label">Dicetak</span><span class="meta-value">{{ now()->format('d/m/Y H:i') }}</span></div>
    </div>
</div>

{{-- Tabel Produk --}}
<div class="section-title">Daftar Barang yang Dikirim</div>
<table>
    <thead>
        <tr>
            <th style="width:5%">No.</th>
            <th>Nama Produk</th>
            <th style="text-align:center">Jumlah (Ton)</th>
            <th style="text-align:center">Jumlah (KG)</th>
            <th style="text-align:right">Harga/KG</th>
            <th style="text-align:right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $item['name'] }}</strong></td>
            <td style="text-align:center">{{ number_format($item['qty_ton'], 1, ',', '.') }} Ton</td>
            <td style="text-align:center">{{ number_format($item['qty_kg'], 0, ',', '.') }} KG</td>
            <td style="text-align:right">Rp {{ number_format($item['price_per_kg'], 0, ',', '.') }}</td>
            <td style="text-align:right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align:right; font-size:11px; color:#666;">Total Berat:</td>
            <td style="text-align:center">{{ number_format(collect($order->items)->sum('qty_kg'), 0, ',', '.') }} KG</td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
</table>

{{-- Info Pembayaran --}}
<div class="payment-box">
    <h3>⚠️ Informasi Pembayaran — Tunjukkan ke Driver Sebelum Barang Diturunkan</h3>
    <div class="payment-row"><span>Subtotal Produk</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
    <div class="payment-row"><span>Ongkos Kirim Armada</span><span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
    <div class="payment-row"><span>Total Nilai Order</span><span>Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
    <div class="payment-row"><span>DP 50% (Sudah Dibayar)</span><span style="color:#166534;">✅ Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span></div>
    <div class="payment-row payment-total">
        <span>SISA PELUNASAN 50% yang Harus Dibayar</span>
        <span>Rp {{ number_format($order->settlement_amount, 0, ',', '.') }}</span>
    </div>
    <div style="margin-top:8px; font-size:11px; color:#7f1d1d;">
        Transfer ke: <strong>BCA 1234 5678 90</strong> atau <strong>Bank DKI 0987 6543 21</strong> — A/N PT. Agro Pangan Maju
    </div>
</div>

{{-- Tanda Tangan --}}
<div class="sign-grid">
    <div class="sign-box">
        <h3>Dikirim Oleh<br>(Armada APM)</h3>
        <div class="sign-line">{{ $driverName !== '-' ? $driverName : '___________________' }}</div>
        <div class="sign-sub">Driver / Pengirim</div>
    </div>
    <div class="sign-box">
        <h3>Pelunasan Diterima Oleh<br>(Driver di Lokasi)</h3>
        <div class="sign-line">{{ $driverName !== '-' ? $driverName : '___________________' }}</div>
        <div class="sign-sub">Tanda tangan + stempel</div>
    </div>
    <div class="sign-box">
        <h3>Barang Diterima Oleh<br>(Mitra Penerima)</h3>
        <div class="sign-line">{{ $order->recipient_name }}</div>
        <div class="sign-sub">{{ $order->b2bRegistration->company_name ?? '' }}</div>
    </div>
</div>

<div class="footer-note">
    Dokumen ini dicetak secara otomatis dari sistem APM Portal B2B · {{ now()->format('d/m/Y H:i:s') }} ·
    Order: {{ $order->order_number }}
</div>

</body>
</html>
