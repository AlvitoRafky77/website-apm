<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductReview;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Status label & config
    private array $statusConfig = [
        'pending'    => ['label' => 'Menunggu Pembayaran', 'color' => 'amber',  'icon' => '⏳'],
        'confirmed'  => ['label' => 'Dikonfirmasi',         'color' => 'blue',   'icon' => '✅'],
        'processing' => ['label' => 'Diproses',             'color' => 'purple', 'icon' => '📦'],
        'shipped'    => ['label' => 'Dikirim',              'color' => 'indigo', 'icon' => '🚚'],
        'delivered'  => ['label' => 'Selesai',              'color' => 'green',  'icon' => '🎉'],
        'cancelled'  => ['label' => 'Dibatalkan',           'color' => 'red',    'icon' => '❌'],
    ];

    /**
     * List all orders for current user.
     */
    public function index(Request $request)
    {
        $tab    = $request->input('status', 'all');
        $query  = Order::where('user_id', Auth::id())->latest();

        if ($tab !== 'all') {
            if ($tab === 'need_payment') {
                $query->where('payment_status', 'pending')->where('status', 'pending');
            } else {
                $query->where('status', $tab);
            }
        }

        $orders = $query->paginate(10)->withQueryString();

        // Counts for tabs
        $counts = [
            'all'          => Order::where('user_id', Auth::id())->count(),
            'need_payment' => Order::where('user_id', Auth::id())->where('payment_status', 'pending')->count(),
            'confirmed'    => Order::where('user_id', Auth::id())->where('status', 'confirmed')->count(),
            'processing'   => Order::where('user_id', Auth::id())->where('status', 'processing')->count(),
            'shipped'      => Order::where('user_id', Auth::id())->where('status', 'shipped')->count(),
            'delivered'    => Order::where('user_id', Auth::id())->where('status', 'delivered')->count(),
        ];

        return view('orders.index', compact('orders', 'tab', 'counts'));
    }

    /**
     * Show a single order detail.
     */
    public function show(string $orderId)
    {
        $order = Order::where('order_id', $orderId)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        $items = collect(is_array($order->items) ? $order->items : json_decode((string) $order->items, true));

        $reviewedProductIds = ProductReview::query()
            ->where('order_id', $order->id)
            ->where('user_id', Auth::id())
            ->pluck('product_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $canReview = $order->payment_status === 'paid' && ($order->user_confirmed || $order->status === 'delivered');

        $reviewableItems = $items->map(function ($item) use ($reviewedProductIds) {
            $item['product_id'] = (int) ($item['product_id'] ?? 0);
            $item['is_reviewed'] = in_array($item['product_id'], $reviewedProductIds, true);

            return $item;
        })->all();

        return view('orders.show', compact('order', 'reviewableItems', 'canReview'));
    }

    /**
     * Download invoice as PDF.
     */
    public function downloadInvoice(string $orderId)
    {
        $order = Order::where('order_id', $orderId)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        $pdf = Pdf::loadView('orders.invoice', compact('order'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice-' . $order->order_id . '.pdf');
    }

    /**
     * User confirms they received the order.
     */
    public function confirmReceived(string $orderId)
    {
        $order = Order::where('order_id', $orderId)
                      ->where('user_id', Auth::id())
                      ->where('status', 'shipped')
                      ->firstOrFail();

        if ($order->user_confirmed) {
            return back()->with('info', 'Pesanan ini sudah dikonfirmasi sebelumnya.');
        }

        $order->update([
            'user_confirmed'    => true,
            'user_confirmed_at' => now(),
        ]);

        return back()->with('success', 'Terima kasih! Konfirmasi penerimaan berhasil. Tim kami akan segera memproses.');
    }
}
