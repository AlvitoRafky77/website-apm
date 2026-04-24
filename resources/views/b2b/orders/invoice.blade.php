<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice B2B {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 24px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 10px;
            line-height: 1.5;
            margin: 0;
        }

        .header {
            border-bottom: 3px solid #CD422B;
            padding-bottom: 18px;
            margin-bottom: 24px;
        }

        .brand-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-name {
            font-size: 22px;
            font-weight: 800;
            color: #CD422B;
            margin: 0;
            line-height: 1;
        }

        .brand-sub {
            font-size: 10px;
            color: #6b7280;
            margin-top: 4px;
        }

        .brand-contact {
            font-size: 9px;
            color: #6b7280;
            margin-top: 10px;
            line-height: 1.6;
        }

        .invoice-box {
            text-align: right;
            vertical-align: top;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 900;
            color: #e5e7eb;
            margin: 0;
            line-height: 1;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .invoice-number {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin-top: 6px;
        }

        .status-badge {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-failed {
            background: #fee2e2;
            color: #b91c1c;
        }

        .meta-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 16px 0;
            margin-bottom: 24px;
        }

        .meta-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 16px;
            background: #fff;
            vertical-align: top;
        }

        .section-label {
            color: #6b7280;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 4px;
        }

        .section-value {
            font-size: 11px;
            font-weight: 700;
            color: #111827;
        }

        .section-text {
            font-size: 9px;
            color: #4b5563;
            line-height: 1.6;
        }

        .items-title {
            font-size: 11px;
            font-weight: 800;
            color: #111827;
            margin: 0 0 12px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .items-table thead th {
            background: #111827;
            color: #fff;
            padding: 12px 10px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: left;
        }

        .items-table tbody td {
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 10px;
            vertical-align: top;
        }

        .items-table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
            white-space: nowrap;
        }

        .product-name {
            font-size: 10px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
        }

        .product-meta {
            font-size: 8px;
            color: #6b7280;
        }

        .summary-wrap {
            width: 100%;
            margin-top: 16px;
        }

        .summary-left {
            width: 64%;
            vertical-align: top;
            padding-right: 14px;
        }

        .summary-right {
            width: 36%;
            vertical-align: top;
            padding-left: 6px;
        }

        .note-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fafafa;
            padding: 18px 20px;
        }

        .note-title {
            font-size: 9px;
            font-weight: 800;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 6px;
        }

        .note-text {
            font-size: 9px;
            color: #374151;
            line-height: 1.6;
        }

        .totals {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        .totals td {
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 10px;
        }

        .totals .label {
            color: #6b7280;
        }

        .totals .value {
            text-align: right;
            font-weight: 700;
            color: #111827;
            white-space: nowrap;
        }

        .grand-total {
            margin-top: 16px;
            background: #CD422B;
            color: #fff;
            border-radius: 10px;
            padding: 16px 18px;
        }

        .grand-total .gt-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .grand-total .gt-value {
            float: right;
            font-size: 18px;
            font-weight: 900;
            line-height: 1;
        }

        .clear {
            clear: both;
        }

        .bank-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px 20px;
            background: #fff;
            margin-top: 16px;
        }

        .bank-title {
            font-size: 10px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 4px;
        }

        .bank-text {
            font-size: 9px;
            color: #4b5563;
            line-height: 1.6;
        }

        .footer {
            margin-top: 28px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="brand-table">
            <tr>
                <td>
                    <h1 class="brand-name">PT. Agro Pangan Maju</h1>
                    <div class="brand-sub">Invoice Pengadaan B2B</div>
                    <div class="brand-contact">
                        Pergudangan Panam Bizpark, Jl. Raya Pekanbaru - Bangkinang No B19<br>
                        Tuah Karya, Kec. Tampan, Kota Pekanbaru, Riau 28293<br>
                        T: +62 852-7433-1328 · E: info@agropanganmaju.com
                    </div>
                </td>
                <td class="invoice-box">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">#{{ $order->order_number }}</div>
                    @php
                        $paymentStatus = strtolower((string) $order->status);
                        $badgeClass = match ($paymentStatus) {
                            'completed', 'delivered', 'settlement_verified' => 'status-paid',
                            'cancelled' => 'status-failed',
                            default => 'status-pending',
                        };
                        $statusLabel = match ($paymentStatus) {
                            'completed' => 'Selesai',
                            'delivered' => 'Diterima',
                            'settlement_verified' => 'Lunas Terverifikasi',
                            'cancelled' => 'Dibatalkan',
                            'in_transit' => 'Dalam Pengiriman',
                            'settlement_uploaded' => 'Pelunasan Menunggu Verifikasi',
                            'dp_uploaded' => 'DP Menunggu Verifikasi',
                            'waiting_dp' => 'Menunggu DP',
                            'waiting_settlement' => 'Menunggu Pelunasan',
                            'draft' => 'Draft',
                            default => ucfirst($paymentStatus),
                        };
                    @endphp
                    <span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="meta-grid">
        <tr>
            <td class="meta-card" style="width: 50%;">
                <div class="section-label">Ditagihkan Kepada</div>
                <div class="section-value">{{ $order->recipient_name }}</div>
                <div class="section-text">
                    {{ $order->recipient_phone }}<br>
                    {{ $order->recipient_address }}<br>
                    {{ $order->recipient_city }}
                </div>
            </td>
            <td class="meta-card" style="width: 50%;">
                <div class="section-label">Detail Order</div>
                <div class="section-value">{{ optional($order->created_at)->format('d M Y, H:i') ?? '-' }}</div>
                <div class="section-text" style="margin-top: 4px;">
                    <strong>Customer Type:</strong> B2B<br>
                    <strong>Company:</strong> {{ $order->b2bRegistration->company_name ?? '-' }}<br>
                    <strong>PIC:</strong> {{ $order->b2bRegistration->pic_name ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="items-title">Rincian Item</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 7%;">No.</th>
                <th>Produk</th>
                <th style="width: 16%;">Qty</th>
                <th style="width: 20%;" class="text-right">Harga / KG</th>
                <th style="width: 20%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $items = is_array($order->items) ? $order->items : json_decode($order->items, true);
            @endphp
            @forelse($items as $index => $item)
                @php
                    $qtyDisplay = isset($item['qty_ton']) ? $item['qty_ton'] . ' Ton' : (($item['qty_kg'] ?? 0) . ' KG');
                    $unitPrice = $item['price_per_kg'] ?? $item['price'] ?? 0;
                    $lineSubtotal = $item['subtotal'] ?? ((float) $unitPrice * (float) ($item['qty_kg'] ?? 0));
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item['name'] ?? '-' }}</div>
                        <div class="product-meta">Satuan: {{ $item['unit'] ?? 'KG' }}</div>
                    </td>
                    <td>{{ $qtyDisplay }}</td>
                    <td class="text-right">Rp{{ number_format((float) $unitPrice, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp{{ number_format((float) $lineSubtotal, 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 18px 8px;">Tidak ada item pada invoice ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-wrap">
        <tr>
            <td class="summary-left">
                <div class="note-box">
                    <div class="note-title">Catatan Order</div>
                    <div class="note-text">
                        @if($order->notes)
                            {{ $order->notes }}
                        @else
                            Tidak ada catatan tambahan dari mitra.
                        @endif
                    </div>
                </div>

                <div class="bank-box">
                    <div class="bank-title">Skema Pembayaran B2B</div>
                    <div class="bank-text">
                        DP 50% dibayarkan terlebih dahulu untuk memproses pesanan.<br>
                        Pelunasan 50% dilakukan setelah barang dikirim / sebelum serah terima.
                    </div>
                </div>

                <div class="bank-box">
                    <div class="bank-title">Rekening Pembayaran</div>
                    <div class="bank-text">
                        Bank Central Asia (BCA) — 1234 5678 90<br>
                        Bank DKI — 0987 6543 21<br>
                        A/N PT. Agro Pangan Maju
                    </div>
                </div>
            </td>
            <td class="summary-right">
                <table class="totals">
                    <tr>
                        <td class="label">Subtotal Produk</td>
                        <td class="value">Rp{{ number_format((float) $order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Biaya Pengiriman</td>
                        <td class="value">Rp{{ number_format((float) $order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">DP 50%</td>
                        <td class="value">Rp{{ number_format((float) $order->dp_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Pelunasan 50%</td>
                        <td class="value">Rp{{ number_format((float) $order->settlement_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="grand-total">
                    <span class="gt-label">Total Tagihan</span>
                    <span class="gt-value">Rp{{ number_format((float) $order->total, 0, ',', '.') }}</span>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Invoice ini dibuat otomatis oleh sistem PT. Agro Pangan Maju.<br>
        Mohon simpan dokumen ini sebagai bukti transaksi.
    </div>
</body>
</html>
