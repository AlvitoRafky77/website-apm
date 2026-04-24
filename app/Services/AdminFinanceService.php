<?php

namespace App\Services;

use App\Models\B2bOrder;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminFinanceService
{
    public function buildFilteredQuery(Request $request): Builder
    {
        return Order::query()->with('user');
    }

    public function getSummary(Request $request): array
    {
        $transactions = $this->getUnifiedTransactions($request);

        $grossIncome = (float) $transactions->where('payment_status', 'paid')->sum('total');
        $netProductIncome = (float) $transactions->where('payment_status', 'paid')->sum('subtotal');
        $shippingCollected = (float) $transactions->where('payment_status', 'paid')->sum('shipping_cost');

        $refundedAmount = (float) $transactions->where('payment_status', 'refunded')->sum('refund_amount');
        $netAfterRefund = $grossIncome - $refundedAmount;

        return [
            'gross_income' => $grossIncome,
            'net_product_income' => $netProductIncome,
            'shipping_collected' => $shippingCollected,
            'refunded_amount' => $refundedAmount,
            'net_after_refund' => (float) $netAfterRefund,
            'total_transactions' => (int) $transactions->count(),
            'paid_transactions' => (int) $transactions->where('payment_status', 'paid')->count(),
            'pending_transactions' => (int) $transactions->where('payment_status', 'pending')->count(),
            'failed_transactions' => (int) $transactions->whereIn('payment_status', ['failed', 'expired'])->count(),
        ];
    }

    public function getTimeSeries(Request $request): array
    {
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : now()->subDays(29)->startOfDay();

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : now()->endOfDay();

        $transactions = $this->getUnifiedTransactions($request)
            ->filter(function ($transaction) use ($dateFrom, $dateTo) {
                $paidAt = $transaction->paid_at ?? $transaction->created_at;

                return $paidAt >= $dateFrom && $paidAt <= $dateTo;
            });

        $labels = [];
        $gross = [];
        $net = [];

        $period = CarbonPeriod::create($dateFrom->copy()->startOfDay(), '1 day', $dateTo->copy()->startOfDay());

        foreach ($period as $day) {
            $labels[] = $day->format('d M');

            $dayTransactions = $transactions->filter(function ($transaction) use ($day) {
                $paidAt = $transaction->paid_at ?? $transaction->created_at;

                return $paidAt?->toDateString() === $day->toDateString()
                    && $transaction->payment_status === 'paid';
            });

            $gross[] = (float) $dayTransactions->sum('total');
            $net[] = (float) $dayTransactions->sum('subtotal');
        }

        return [
            'labels' => $labels,
            'gross' => $gross,
            'net' => $net,
        ];
    }

    public function getPaymentMethodBreakdown(Request $request): Collection
    {
        return $this->getUnifiedTransactions($request)
            ->where('payment_status', 'paid')
            ->groupBy(function ($transaction) {
                return $transaction->midtrans_payment_type ?: 'b2b';
            })
            ->map(function (Collection $items, string $method) {
                return (object) [
                    'method' => $method,
                    'total_transactions' => $items->count(),
                    'gross_total' => (float) $items->sum('total'),
                ];
            })
            ->sortByDesc('gross_total')
            ->values();
    }

    public function getTransactionsPaginated(Request $request, int $perPage = 20): LengthAwarePaginator
    {
        $transactions = $this->getUnifiedTransactions($request)
            ->sortByDesc(fn ($transaction) => $transaction->created_at?->getTimestamp() ?? 0)
            ->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $slice = $transactions->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $slice,
            $transactions->count(),
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]
        );
    }

    public function getTransactionsForExport(Request $request): Collection
    {
        return $this->getUnifiedTransactions($request)
            ->sortByDesc(fn ($transaction) => $transaction->created_at?->getTimestamp() ?? 0)
            ->values();
    }

    private function getUnifiedTransactions(Request $request): Collection
    {
        $customerType = $request->input('customer_type');
        $paymentStatus = $request->input('payment_status');
        $fulfillmentStatus = $request->input('fulfillment_status');
        $paymentType = $request->input('payment_type');
        $search = $request->input('search');

        $orders = Order::query()->with('user')->get()->map(function (Order $order) {
            return $this->normalizeOrder($order);
        });

        $b2bOrders = B2bOrder::query()->with('user')->get()->map(function (B2bOrder $order) {
            return $this->normalizeB2bOrder($order);
        });

        $transactions = $orders->concat($b2bOrders);

        if ($request->filled('date_from')) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $transactions = $transactions->filter(fn ($transaction) => ($transaction->created_at ?? now()) >= $dateFrom);
        }

        if ($request->filled('date_to')) {
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            $transactions = $transactions->filter(fn ($transaction) => ($transaction->created_at ?? now()) <= $dateTo);
        }

        if ($customerType === 'b2b') {
            $transactions = $transactions->where('customer_type', 'b2b');
        } elseif ($customerType === 'b2c') {
            $transactions = $transactions->where('customer_type', 'b2c');
        }

        if ($paymentStatus) {
            $transactions = $transactions->where('payment_status', $paymentStatus);
        }

        if ($fulfillmentStatus) {
            $transactions = $transactions->where('status', $fulfillmentStatus);
        }

        if ($paymentType) {
            $transactions = $transactions->filter(fn ($transaction) => $transaction->midtrans_payment_type === $paymentType);
        }

        if ($search) {
            $transactions = $transactions->filter(function ($transaction) use ($search) {
                return str_contains(mb_strtolower((string) $transaction->order_id), mb_strtolower($search))
                    || str_contains(mb_strtolower((string) $transaction->recipient_name), mb_strtolower($search))
                    || str_contains(mb_strtolower((string) ($transaction->customer_name ?? '')), mb_strtolower($search));
            });
        }

        return $transactions->values();
    }

    private function normalizeOrder(Order $order): object
    {
        return (object) [
            'source' => 'order',
            'id' => $order->id,
            'order_id' => $order->order_id,
            'customer_type' => $order->user?->user_type === 'b2b' ? 'b2b' : 'b2c',
            'customer_name' => $order->user?->name ?? $order->recipient_name,
            'recipient_name' => $order->recipient_name,
            'user' => $order->user,
            'subtotal' => (float) $order->subtotal,
            'shipping_cost' => (float) $order->shipping_cost,
            'total' => (float) $order->total,
            'payment_status' => $order->payment_status,
            'status' => $order->status,
            'midtrans_payment_type' => $order->midtrans_payment_type,
            'paid_at' => $order->paid_at,
            'created_at' => $order->created_at,
            'refund_amount' => (float) ($order->refund_amount ?? 0),
        ];
    }

    private function normalizeB2bOrder(B2bOrder $order): object
    {
        $isPaid = in_array($order->status, ['settlement_verified', 'completed'], true);

        return (object) [
            'source' => 'b2b_order',
            'id' => $order->id,
            'order_id' => $order->order_number,
            'customer_type' => 'b2b',
            'customer_name' => $order->user?->name ?? $order->recipient_name,
            'recipient_name' => $order->recipient_name,
            'user' => $order->user,
            'subtotal' => (float) $order->subtotal,
            'shipping_cost' => (float) $order->shipping_cost,
            'total' => (float) $order->total,
            'payment_status' => $isPaid ? 'paid' : ($order->status === 'cancelled' ? 'failed' : 'pending'),
            'status' => $order->status,
            'midtrans_payment_type' => null,
            'paid_at' => $order->settlement_verified_at ?? $order->settlement_uploaded_at ?? $order->dp_verified_at ?? $order->created_at,
            'created_at' => $order->created_at,
            'refund_amount' => 0,
        ];
    }
}
