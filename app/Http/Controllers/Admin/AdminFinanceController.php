<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceSyncRun;
use App\Models\Order;
use App\Services\AdminFinanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Midtrans\Config as MidtransConfig;
use Midtrans\Transaction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminFinanceController extends Controller
{
    public function __construct(private readonly AdminFinanceService $finance)
    {
    }

    public function index(Request $request)
    {
        $request->validate([
            'date_from' => ['nullable', 'date', 'after_or_equal:' . now()->subYears(5)->format('Y-m-d'), 'before_or_equal:today'],
            'date_to'   => ['nullable', 'date', 'after_or_equal:date_from', 'after_or_equal:' . now()->subYears(5)->format('Y-m-d'), 'before_or_equal:today'],
        ]);

        $summary = $this->finance->getSummary($request);
        $timeSeries = $this->finance->getTimeSeries($request);
        $methodBreakdown = $this->finance->getPaymentMethodBreakdown($request);
        $transactions = $this->finance->getTransactionsPaginated($request, 10);

        return view('admin.finances.index', compact('summary', 'timeSeries', 'methodBreakdown', 'transactions'));
    }

    public function transactions(Request $request)
    {
        $request->validate([
            'date_from' => ['nullable', 'date', 'after_or_equal:' . now()->subYears(5)->format('Y-m-d'), 'before_or_equal:today'],
            'date_to'   => ['nullable', 'date', 'after_or_equal:date_from', 'after_or_equal:' . now()->subYears(5)->format('Y-m-d'), 'before_or_equal:today'],
        ]);

        $transactions = $this->finance->getTransactionsPaginated($request, 25);

        return view('admin.finances.transactions', compact('transactions'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $transactions = $this->finance->getTransactionsForExport($request);
        $fileName = 'laporan-keuangan-apm-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Tanggal Bayar',
                'Order ID',
                'Nama Customer',
                'Tipe Customer',
                'Metode Bayar',
                'Subtotal',
                'Ongkir',
                'Total',
                'Status Bayar',
                'Status Fulfillment',
            ], ';');

            foreach ($transactions as $order) {
                fputcsv($handle, [
                    optional($order->paid_at)->format('Y-m-d H:i:s') ?? '-',
                    $order->order_id,
                    $order->user->name ?? $order->recipient_name,
                    strtoupper($order->user->user_type ?? 'b2c'),
                    $order->midtrans_payment_type ? str_replace('_', ' ', $order->midtrans_payment_type) : '-',
                    number_format((float) $order->subtotal, 2, '.', ''),
                    number_format((float) $order->shipping_cost, 2, '.', ''),
                    number_format((float) $order->total, 2, '.', ''),
                    $order->payment_status,
                    $order->status,
                ], ';');
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function exportPdf(Request $request): Response
    {
        $transactions = $this->finance->getTransactionsForExport($request);
        $summary = $this->finance->getSummary($request);

        $pdf = Pdf::loadView('admin.finances.pdf.report', compact('transactions', 'summary'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-keuangan-apm-' . now()->format('Ymd-His') . '.pdf');
    }

    public function syncMidtrans(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date_from' => ['nullable', 'date', 'after_or_equal:' . now()->subYears(5)->format('Y-m-d'), 'before_or_equal:today'],
            'date_to'   => ['nullable', 'date', 'after_or_equal:date_from', 'after_or_equal:' . now()->subYears(5)->format('Y-m-d'), 'before_or_equal:today'],
        ]);

        $query = Order::query()->where('payment_method', 'midtrans');

        if (!empty($validated['date_from'])) {
            $query->whereDate('created_at', '>=', $validated['date_from']);
        }

        if (!empty($validated['date_to'])) {
            $query->whereDate('created_at', '<=', $validated['date_to']);
        }

        $orders = $query->get();

        if ($orders->isEmpty()) {
            return back()->with('info', 'Tidak ada transaksi Midtrans pada rentang tanggal tersebut.');
        }

        $syncRun = FinanceSyncRun::create([
            'started_at' => now(),
            'status' => 'running',
            'total_orders' => $orders->count(),
            'context' => [
                'date_from' => $validated['date_from'] ?? null,
                'date_to' => $validated['date_to'] ?? null,
            ],
        ]);

        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');

        $synced = 0;
        $failed = 0;
        $errorMessages = [];

        foreach ($orders as $order) {
            try {
                $status = Transaction::status($order->order_id);

                $transactionStatus = $status->transaction_status ?? null;
                $fraudStatus = $status->fraud_status ?? null;
                $mappedPaymentStatus = $this->mapMidtransPaymentStatus($transactionStatus, $fraudStatus);

                $settlementAt = $this->parseDate($status->settlement_time ?? null);
                $transactionTime = $this->parseDate($status->transaction_time ?? null);

                $payload = json_decode(json_encode($status), true);
                $grossAmount = (float) ($status->gross_amount ?? 0);

                $updatePayload = [
                    'payment_status' => $mappedPaymentStatus,
                    'midtrans_transaction_id' => $status->transaction_id ?? $order->midtrans_transaction_id,
                    'midtrans_payment_type' => $status->payment_type ?? $order->midtrans_payment_type,
                    'settlement_status' => $transactionStatus,
                    'settlement_at' => $settlementAt,
                    'midtrans_status_payload' => $payload,
                    'midtrans_sync_at' => now(),
                ];

                if ($mappedPaymentStatus === 'paid') {
                    $updatePayload['paid_at'] = $order->paid_at ?? $settlementAt ?? $transactionTime ?? now();
                    if ($order->status === 'pending') {
                        $updatePayload['status'] = 'confirmed';
                    }
                }

                if ($mappedPaymentStatus === 'refunded') {
                    $updatePayload['refund_amount'] = $grossAmount > 0 ? $grossAmount : (float) $order->total;
                    $updatePayload['refunded_at'] = $order->refunded_at ?? now();
                    $updatePayload['refund_reason'] = $status->status_message ?? $order->refund_reason;
                }

                $order->update($updatePayload);
                $synced++;
            } catch (\Throwable $exception) {
                $failed++;
                if (count($errorMessages) < 5) {
                    $errorMessages[] = $order->order_id . ': ' . $exception->getMessage();
                }
            }
        }

        $syncRun->update([
            'finished_at' => now(),
            'status' => $failed > 0 ? 'partial' : 'success',
            'synced_orders' => $synced,
            'failed_orders' => $failed,
            'notes' => $failed > 0 ? implode("\n", $errorMessages) : 'Sinkronisasi berhasil.',
        ]);

        return back()->with(
            $failed > 0 ? 'error' : 'success',
            $failed > 0
                ? "Sync Midtrans selesai parsial. Berhasil: {$synced}, Gagal: {$failed}."
                : "Sync Midtrans berhasil. Total transaksi tersinkron: {$synced}."
        );
    }

    private function mapMidtransPaymentStatus(?string $transactionStatus, ?string $fraudStatus): string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'accept' ? 'paid' : 'pending';
        }

        return match ($transactionStatus) {
            'settlement' => 'paid',
            'refund', 'partial_refund' => 'refunded',
            'expire' => 'expired',
            'cancel', 'deny' => 'failed',
            default => 'pending',
        };
    }

    private function parseDate(?string $value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
