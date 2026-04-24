<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan APM</title>
    <style>
        @page {
            margin: 22px 24px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 10px;
            line-height: 1.4;
        }

        .header {
            margin-bottom: 14px;
            border-bottom: 2px solid #cd422b;
            padding-bottom: 10px;
        }

        .title {
            margin: 0;
            font-size: 20px;
            color: #111827;
        }

        .subtitle {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 10px;
        }

        .meta {
            margin-top: 6px;
            color: #6b7280;
            font-size: 10px;
        }

        .summary {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 14px;
        }

        .summary td {
            width: 20%;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 10px 8px;
            vertical-align: top;
            background: #fff;
        }

        .label {
            color: #6b7280;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 4px;
        }

        .value {
            font-weight: 700;
            font-size: 12px;
            color: #111827;
        }

        .table-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data thead {
            display: table-header-group;
        }

        table.data th,
        table.data td {
            border-bottom: 1px solid #e5e7eb;
            padding: 7px 8px;
            vertical-align: top;
        }

        table.data th {
            background: #f3f4f6;
            color: #374151;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .03em;
            text-align: left;
            white-space: nowrap;
        }

        table.data tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .num {
            text-align: right;
            white-space: nowrap;
        }

        .center {
            text-align: center;
            white-space: nowrap;
        }

        .badge-paid {
            color: #166534;
            font-weight: 700;
        }

        .badge-pending {
            color: #92400e;
            font-weight: 700;
        }

        .badge-failed {
            color: #b91c1c;
            font-weight: 700;
        }

        .badge-refunded {
            color: #7c3aed;
            font-weight: 700;
        }

        .small {
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Laporan Keuangan Admin APM</h1>
        <p class="subtitle">Ringkasan transaksi masuk, pendapatan, dan status pembayaran</p>
        <div class="meta">Generated: {{ now()->format('d M Y H:i') }}</div>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Gross Income</div>
                <div class="value">Rp{{ number_format($summary['gross_income'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Net Product</div>
                <div class="value">Rp{{ number_format($summary['net_product_income'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Shipping Collected</div>
                <div class="value">Rp{{ number_format($summary['shipping_collected'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Refunded</div>
                <div class="value">Rp{{ number_format($summary['refunded_amount'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Net After Refund</div>
                <div class="value">Rp{{ number_format($summary['net_after_refund'], 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th style="width: 11%;">Tanggal Bayar</th>
                    <th style="width: 16%;">Order ID</th>
                    <th style="width: 16%;">Customer</th>
                    <th style="width: 7%;">Tipe</th>
                    <th style="width: 10%;">Metode</th>
                    <th style="width: 12%;" class="num">Subtotal</th>
                    <th style="width: 12%;" class="num">Ongkir</th>
                    <th style="width: 12%;" class="num">Total</th>
                    <th style="width: 6%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $order)
                    @php
                        $paymentStatus = strtolower((string) $order->payment_status);
                        $statusClass = match ($paymentStatus) {
                            'paid' => 'badge-paid',
                            'pending' => 'badge-pending',
                            'failed', 'expired' => 'badge-failed',
                            'refunded' => 'badge-refunded',
                            default => ''
                        };
                    @endphp
                    <tr>
                        <td class="center">{{ optional($order->paid_at)->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $order->order_id }}</td>
                        <td>
                            <div>{{ $order->user->name ?? $order->recipient_name }}</div>
                            <div class="small">{{ $order->source === 'b2b_order' ? 'B2B Order' : 'B2C Order' }}</div>
                        </td>
                        <td class="center">{{ strtoupper($order->user->user_type ?? 'b2c') }}</td>
                        <td>{{ $order->midtrans_payment_type ? str_replace('_', ' ', $order->midtrans_payment_type) : '-' }}</td>
                        <td class="num">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        <td class="num">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        <td class="num">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="{{ $statusClass }}">{{ strtoupper($order->payment_status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="center" style="padding: 18px 8px;">Tidak ada transaksi pada filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
