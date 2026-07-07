<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_id }}</title>
    <style>
        @page { margin: 0; }
        * {
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        body { 
            font-size: 11px; 
            color: #1e293b; 
            line-height: 1.5;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        /* Top Accent Bar */
        .top-bar { height: 12px; background: #CD422B; width: 100%; }

        .container { padding: 40px 50px; }

        /* Header Area */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .brand-name { font-size: 32px; font-weight: 900; color: #CD422B; letter-spacing: -1.5px; }
        .brand-sub { font-size: 12px; color: #475569; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        
        .invoice-label-box { text-align: right; vertical-align: top; }
        .invoice-text { font-size: 40px; font-weight: 900; color: #e2e8f0; text-transform: uppercase; margin: 0; line-height: 0.8; } /* Dipergelap sedikit dari f3f4f6 */
        .invoice-id-text { font-size: 16px; font-weight: bold; color: #0f172a; margin-top: 5px; letter-spacing: 0.5px; }

        /* Company Contact */
        .company-contact { font-size: 10px; color: #64748b; line-height: 1.5; margin-top: 15px; }

        /* Billing Section */
        .billing-grid { width: 100%; border-collapse: collapse; margin-top: 35px; margin-bottom: 45px; }
        .card-inner { padding: 18px 22px; border-radius: 12px; min-height: 80px; }
        .billing-card { background: #fff1f0; border: 1px solid #fecaca; }
        .info-card { background: #f8fafc; border: 1px solid #e2e8f0; }
        
        .label { font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block; }
        .value-name { font-size: 13px; font-weight: bold; color: #0f172a; margin-bottom: 4px; }
        .value-text { font-size: 10px; color: #475569; }

        /* Status Badge */
        .badge { 
            display: inline-block; 
            padding: 6px 14px; 
            border-radius: 8px; 
            font-size: 10px; 
            font-weight: bold; 
            margin-top: 12px;
            text-transform: uppercase;
        }
        .badge-paid { background: #059669; color: #fff; }
        .badge-pending { background: #d97706; color: #fff; }

        /* Table Design */
        .table-container { margin-bottom: 35px; }
        .items-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .items-table thead th { 
            background: #0f172a; 
            color: #fff; 
            text-align: left; 
            padding: 14px 18px; 
            font-size: 10px; 
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .items-table thead th:first-child { border-radius: 10px 0 0 0; }
        .items-table thead th:last-child { border-radius: 0 10px 0 0; text-align: right; }
        
        .items-table tbody td { padding: 16px 18px; border-bottom: 1px solid #f1f5f9; }
        .items-table .text-right { text-align: right; }
        .items-table .text-center { text-align: center; }
        .prod-name { font-weight: bold; color: #0f172a; font-size: 11.5px; margin-bottom: 2px; }
        .prod-meta { font-size: 9px; color: #64748b; }

        /* Summary Area */
        .summary-wrapper { width: 100%; }
        .summary-box { float: right; width: 320px; }
        .summary-row { padding: 10px 0; border-bottom: 1px solid #f1f5f9; clear: both; overflow: hidden; }
        .summary-label { float: left; color: #64748b; font-weight: 600; font-size: 11px; }
        .summary-value { float: right; text-align: right; font-weight: bold; color: #0f172a; font-size: 11px; }
        
        .total-box { 
            margin-top: 20px; 
            background: #CD422B; 
            padding: 18px 22px; 
            border-radius: 12px; 
            color: #fff; 
            clear: both;
            box-shadow: 0 4px 6px -1px rgba(205, 66, 43, 0.2);
        }
        .total-label { font-size: 15px; font-weight: bold; }
        .total-value { font-size: 22px; font-weight: 900; float: right; line-height: 1; }

        /* Extra Info */
        .notes-area { width: 55%; padding-top: 10px; }
        .thank-you { font-style: italic; color: #CD422B; font-size: 14px; margin-top: 35px; }

        /* Footer */
        .footer { margin-top: 80px; text-align: center; color: #94a3b8; font-size: 10px; border-top: 1px solid #f1f5f9; padding-top: 25px; }
        .footer strong { color: #64748b; }
    </style>
</head>
<body>
    <div class="top-bar"></div>
    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    <div class="brand-name">APM</div>
                    <div class="brand-sub">PT. Agro Pangan Maju</div>
                    <div class="company-contact">
                        Pergudangan Panam Bizpark, Jl. Raya Pekanbaru - Bangkinang No B19<br>
                        Tuah Karya, Kec. Tampan, Kota Pekanbaru, Riau 28293<br>
                        <strong>T:</strong> +62 852-7433-1328 | <strong>E:</strong> info@agropanganmaju.com
                    </div>
                </td>
                <td class="invoice-label-box">
                    <h1 class="invoice-text" style="color: #cbd5e1;">INVOICE</h1> <!-- Dipertegas kontrasnya ke Slate-300 -->
                    <div class="invoice-id-text">#{{ $order->order_id }}</div>
                    @if($order->payment_status === 'paid')
                        <span class="badge badge-paid">SUDAH LUNAS</span>
                    @else
                        <span class="badge badge-pending">MENUNGGU BAYAR</span>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Billing Info -->
        <table class="billing-grid">
            <tr>
                <td style="width: 48%; padding-right: 20px;">
                    <div class="card-inner billing-card">
                        <span class="label">Penerima Tagihan:</span>
                        <div class="value-name">{{ $order->recipient_name }}</div>
                        <div class="value-text">
                            {{ $order->recipient_phone }}<br>
                            {{ $order->recipient_address }}, {{ $order->recipient_city }}
                        </div>
                    </div>
                </td>
                <td style="width: 48%;">
                    <div class="card-inner info-card">
                        <span class="label">Rincian Transaksi:</span>
                        <table style="width: 100%; font-size: 10px;">
                            <tr>
                                <td style="color: #64748b; padding-bottom: 4px;">Tanggal Pesanan</td>
                                <td style="text-align: right; font-weight: bold; color: #0f172a;">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b; padding-bottom: 4px;">Metode Pembayaran</td>
                                <td style="text-align: right; font-weight: bold; color: #0f172a;">{{ $order->midtrans_payment_type ? str_replace('_', ' ', ucwords($order->midtrans_payment_type)) : 'E-Wallet/Transfer' }}</td>
                            </tr>
                            @if($order->paid_at)
                            <tr>
                                <td style="color: #64748b;">Waktu Pelunasan</td>
                                <td style="text-align: right; font-weight: bold; color: #0f172a;">{{ $order->paid_at->format('H:i') }} WIB</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Table -->
        <div class="table-container">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;">No.</th>
                        <th>Deskripsi Produk</th>
                        <th class="text-center" style="width: 80px;">Jumlah</th>
                        <th class="text-right" style="width: 130px;">Harga Satuan</th>
                        <th class="text-right" style="width: 140px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $items = is_array($order->items) ? $order->items : json_decode($order->items, true); @endphp
                    @foreach($items as $index => $item)
                    <tr>
                        <td class="text-center" style="color: #94a3b8;">{{ $index + 1 }}</td>
                        <td>
                            <div class="prod-name">{{ $item['name'] }}</div>
                            <div class="prod-meta">Satuan: {{ $item['unit'] ?? 'KG' }}</div>
                        </td>
                        <td class="text-center" style="font-weight: bold;">{{ $item['quantity'] }}</td>
                        <td class="text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: bold; color: #0f172a;">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Area -->
        <table style="width: 100%; margin-top: 20px;">
            <tr>
                <td style="width: 55%; vertical-align: top;">
                    @if($order->notes)
                        <span class="label">Catatan Pesanan:</span>
                        <p style="font-size: 10px; color: #475569; margin-top: 5px; line-height: 1.4; padding-right: 40px;">{{ $order->notes }}</p>
                    @endif
                    <div class="thank-you" style="margin-top: 30px;">
                        Terima kasih telah mempercayai kami.
                    </div>
                </td>
                <td style="width: 45%; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td class="summary-label" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">Subtotal Pesanan</td>
                            <td class="summary-value" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; text-align: right;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="summary-label" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">Biaya Pengiriman</td>
                            <td class="summary-value" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; text-align: right;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 15px;">
                                <div class="total-box">
                                    <span class="total-label">TOTAL TAGIHAN</span>
                                    <span class="total-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                    <div style="clear: both;"></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            Dibuat secara otomatis oleh sistem <strong>PT. Agro Pangan Maju</strong>.<br>
            Jl. Raya Pekanbaru - Bangkinang No B19, Pekanbaru.
        </div>
    </div>
</body>
</html>
