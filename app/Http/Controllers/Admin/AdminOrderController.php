<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\LalamoveService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminOrderController extends Controller
{
    private LalamoveService $lalamove;
    private StockService    $stock;

    public function __construct(LalamoveService $lalamove, StockService $stock)
    {
        $this->lalamove = $lalamove;
        $this->stock    = $stock;
    }

    /**
     * Menampilkan daftar semua pesanan.
     */
    public function index(Request $request)
    {
        $type      = $request->input('type', 'b2c');
        $baseQuery = Order::with('user')->latest();

        if ($type === 'b2b') {
            $baseQuery->whereHas('user', fn($q) => $q->where('user_type', 'b2b'));
        } else {
            $baseQuery->whereHas('user', fn($q) => $q->where('user_type', '!=', 'b2b')->orWhereNull('user_type'));
        }

        // Calculate counts for B2C status tabs
        $counts = [
            'all'             => (clone $baseQuery)->count(),
            'pending_payment' => (clone $baseQuery)->where('payment_status', 'pending')->where('status', '!=', 'cancelled')->count(),
            'paid_pending'    => (clone $baseQuery)->where('payment_status', 'paid')->whereIn('status', ['pending', 'confirmed'])->count(),
            'processing'      => (clone $baseQuery)->where('status', 'processing')->count(),
            'shipped'         => (clone $baseQuery)->where('status', 'shipped')->count(),
            'delivered'       => (clone $baseQuery)->where('status', 'delivered')->count(),
            'cancelled'       => (clone $baseQuery)->where(function($q) {
                $q->where('status', 'cancelled')
                  ->orWhereIn('payment_status', ['failed', 'expired', 'cancelled']);
            })->count(),
        ];

        $status = $request->input('status', 'all');
        $query  = clone $baseQuery;

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', '%' . $search . '%')
                  ->orWhere('recipient_name', 'like', '%' . $search . '%');
            });
        }

        if ($status && $status !== 'all') {
            if ($status === 'pending_payment') {
                $query->where('payment_status', 'pending')->where('status', '!=', 'cancelled');
            } elseif ($status === 'paid_pending') {
                $query->where('payment_status', 'paid')->whereIn('status', ['pending', 'confirmed']);
            } elseif ($status === 'cancelled') {
                $query->where(function($q) {
                    $q->where('status', 'cancelled')
                      ->orWhereIn('payment_status', ['failed', 'expired', 'cancelled']);
                });
            } else {
                $query->where('status', $status);
            }
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders', 'counts', 'status'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show($id)
    {
        $order = Order::with('user')->where('order_id', $id)->firstOrFail();
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Memperbarui status pesanan
     */
    public function updateStatus(Request $request, $id)
    {
        $order  = Order::where('order_id', $id)->firstOrFail();
        $action = $request->input('action');

        switch ($action) {

            case 'process':
                $order->update(['status' => 'processing']);
                return back()->with('success', 'Pesanan sedang diproses dan disiapkan.');

            case 'dispatch_driver':
                // Panggil driver Lalamove
                if (!$order->recipient_lat || !$order->recipient_lng) {
                    return back()->with('error', 'Koordinat alamat penerima tidak tersedia. Pesanan ini dibuat sebelum integrasi Lalamove.');
                }

                $result = $this->lalamove->placeOrder(
                    quotationId:      $order->lalamove_quotation_id ?? '',
                    recipientName:    $order->recipient_name,
                    recipientPhone:   $order->recipient_phone,
                    recipientAddress: $order->recipient_address,
                    destLat:          (float) $order->recipient_lat,
                    destLng:          (float) $order->recipient_lng,
                );

                if (!($result['success'] ?? false)) {
                    return back()->with('error', $result['message'] ?? 'Gagal memanggil driver Lalamove. Coba lagi.');
                }

                $order->update([
                    'status'               => 'shipped',
                    'lalamove_order_id'    => $result['order_id'],
                    'lalamove_status'      => $result['status'],
                    'lalamove_share_link'  => $result['share_link'] ?? null,
                    'lalamove_driver_name' => $result['driver_name'] ?? null,
                    'lalamove_driver_phone'=> $result['driver_phone'] ?? null,
                    'shipped_at'           => now(),
                    'courier'              => 'Lalamove',
                    'tracking_number'      => $result['order_id'],
                ]);

                $mockNote = ($result['is_mock'] ?? false) ? ' (Mode Sandbox — driver simulasi)' : '';
                return back()->with('success', "Driver Lalamove berhasil dipanggil!{$mockNote}");

            case 'dispatch_expedition':
                // Tandai dikirim via ekspedisi (JNE/SiCepat/dll) — tanpa Lalamove
                $waybill = trim($request->input('waybill_number', ''));
                if (!$waybill) {
                    $waybill = $this->generateExpeditionWaybill($order);
                }

                $order->update([
                    'status'          => 'shipped',
                    'tracking_number' => $waybill,
                    'shipped_at'      => now(),
                ]);

                return back()->with('success', "Pesanan ditandai dikirim via " . strtoupper($order->courier ?? 'Ekspedisi') . ". No. Resi: {$waybill}");

            case 'deliver':
                $order->update(['status' => 'delivered']);
                return back()->with('success', 'Pesanan ditandai telah selesai.');

            case 'cancel':
                $order->update(['status' => 'cancelled']);

                // Kembalikan stok: releaseStock hanya jalan jika stock_reserved=true
                // Untuk order paid (commitStock sudah set stock_reserved=false), kita force restore
                $this->stock->releaseStock($order);

                if ($order->payment_status === 'paid' && !$order->stock_reserved) {
                    $this->stock->forceRestoreStock($order);
                }

                return back()->with('success', 'Pesanan dibatalkan dan stok dikembalikan.');

            default:
                return back()->with('error', 'Aksi tidak valid.');
        }
    }

    private function generateExpeditionWaybill(Order $order): string
    {
        $courierRaw = strtoupper((string) ($order->courier ?? 'EXP'));
        $courierPrefix = preg_replace('/[^A-Z]/', '', $courierRaw);
        $courierPrefix = Str::substr($courierPrefix ?: 'EXP', 0, 4);

        do {
            $candidate = sprintf(
                '%s%s%s',
                $courierPrefix,
                now()->format('ymdHis'),
                strtoupper(Str::random(4))
            );
        } while (Order::where('tracking_number', $candidate)->exists());

        return $candidate;
    }
}
