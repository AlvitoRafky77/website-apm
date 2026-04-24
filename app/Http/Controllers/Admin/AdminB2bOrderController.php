<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\B2bOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminB2bOrderController extends Controller
{
    // ─── Daftar Semua Order B2B ───────────────────────────────────────
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $query  = B2bOrder::with(['user', 'b2bRegistration'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->input('dispute') === '1') {
            $query->whereIn('dispute_status', ['open', 'reviewing']);
        }

        $orders = $query->paginate(15)->withQueryString();

        $counts = [
            'all'                 => B2bOrder::count(),
            'pending_review'      => B2bOrder::where('status', 'pending_review')->count(),
            'waiting_dp'          => B2bOrder::where('status', 'waiting_dp')->count(),
            'dp_uploaded'         => B2bOrder::where('status', 'dp_uploaded')->count(),
            'in_transit'          => B2bOrder::where('status', 'in_transit')->count(),
            'settlement_uploaded' => B2bOrder::where('status', 'settlement_uploaded')->count(),
            'settlement_verified' => B2bOrder::where('status', 'settlement_verified')->count(),
            'completed'           => B2bOrder::where('status', 'completed')->count(),
            'cancelled'           => B2bOrder::where('status', 'cancelled')->count(),
            'dispute'             => B2bOrder::whereIn('dispute_status', ['open', 'reviewing'])->count(),
        ];

        return view('admin.b2b.orders.index', compact('orders', 'status', 'counts'));
    }

    // ─── Review Order Baru (Input Ongkir) ────────────────────────────
    public function approveReview(Request $request, string $orderNumber)
    {
        $request->validate([
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $order = B2bOrder::where('order_number', $orderNumber)->firstOrFail();

        if ($order->status !== 'pending_review') {
            return back()->with('error', 'Status order tidak memungkinkan review saat ini.');
        }

        $shippingCost  = (float) $request->shipping_cost;
        $newTotal      = $order->subtotal + $shippingCost;
        $newDp         = round($newTotal * 0.5, 2);
        $newSettlement = round($newTotal * 0.5, 2);

        $order->update([
            'shipping_cost'     => $shippingCost,
            'total'             => $newTotal,
            'dp_amount'         => $newDp,
            'settlement_amount' => $newSettlement,
            'status'            => 'waiting_dp',
        ]);

        return back()->with('success', 'Review selesai. Ongkir telah diinput dan mitra dapat melanjutkan pembayaran DP.');
    }

    // ─── Detail Order B2B ─────────────────────────────────────────────
    public function show(string $orderNumber)
    {
        $order = B2bOrder::where('order_number', $orderNumber)
            ->with(['user', 'b2bRegistration', 'dpVerifiedBy', 'settlementVerifiedBy'])
            ->firstOrFail();

        return view('admin.b2b.orders.show', compact('order'));
    }

    // ─── Verifikasi DP → Dispatch Armada ─────────────────────────────
    // Alur baru: DP verified → in_transit (barang langsung dikirim)
    public function verifyDp(Request $request, string $orderNumber)
    {
        $request->validate([
            'action'         => 'required|in:approve,reject',
            'shipping_cost'  => 'required_if:action,approve|nullable|numeric|min:0',
            'delivery_date'  => 'required_if:action,approve|nullable|date|after:today',
            'driver_name'    => 'required_if:action,approve|nullable|string|max:100',
            'driver_phone'   => 'required_if:action,approve|nullable|string|max:20',
            'waybill_number' => 'nullable|string|max:50',
            'dp_admin_notes' => 'nullable|string|max:500',
        ]);

        $order = B2bOrder::where('order_number', $orderNumber)->firstOrFail();

        if ($order->status !== 'dp_uploaded') {
            return back()->with('error', 'Status order tidak memungkinkan verifikasi DP.');
        }

        if ($request->action === 'approve') {
            $shippingCost  = (float) $request->shipping_cost;
            $newTotal      = $order->subtotal + $shippingCost;
            $newDp         = round($newTotal * 0.5, 2);
            $newSettlement = round($newTotal * 0.5, 2);

            $order->update([
                'shipping_cost'     => $shippingCost,
                'total'             => $newTotal,
                'dp_amount'         => $newDp,
                'settlement_amount' => $newSettlement,
                'dp_verified_at'    => now(),
                'dp_verified_by'    => Auth::id(),
                'dp_admin_notes'    => $request->dp_admin_notes,
                'delivery_date'     => $request->delivery_date,
                'driver_name'       => $request->driver_name,
                'driver_phone'      => $request->driver_phone,
                'waybill_number'    => $request->waybill_number,
                // Langsung in_transit — barang disiapkan & dikirim
                'status'            => 'in_transit',
            ]);

            return back()->with('success', 'DP diverifikasi. Surat jalan dapat dicetak. Armada siap dikirim.');
        } else {
            $order->update([
                'dp_admin_notes' => $request->dp_admin_notes,
                'dp_verified_by' => Auth::id(),
                'status'         => 'waiting_dp',
            ]);

            return back()->with('success', 'DP ditolak. Mitra perlu mengupload ulang bukti pembayaran.');
        }
    }

    // ─── Cetak Surat Jalan ────────────────────────────────────────────
    public function printSuratJalan(string $orderNumber)
    {
        $order = B2bOrder::where('order_number', $orderNumber)
            ->with(['user', 'b2bRegistration'])
            ->firstOrFail();

        if (!in_array($order->status, ['in_transit', 'settlement_uploaded', 'settlement_verified', 'completed'])) {
            return back()->with('error', 'Surat jalan hanya tersedia setelah DP diverifikasi.');
        }

        return view('admin.b2b.orders.surat-jalan', compact('order'));
    }

    // ─── Konfirmasi Pelunasan Diterima (oleh Admin/Driver) ───────────
    // Alur baru: mitra upload pelunasan saat in_transit → admin konfirmasi → settlement_verified
    public function verifySettlement(Request $request, string $orderNumber)
    {
        $request->validate([
            'action'                 => 'required|in:approve,reject',
            'settlement_admin_notes' => 'nullable|string|max:500',
        ]);

        $order = B2bOrder::where('order_number', $orderNumber)->firstOrFail();

        if ($order->status !== 'settlement_uploaded') {
            return back()->with('error', 'Status order tidak memungkinkan konfirmasi pelunasan.');
        }

        if ($request->action === 'approve') {
            $order->update([
                'settlement_verified_at'  => now(),
                'settlement_verified_by'  => Auth::id(),
                'settlement_admin_notes'  => $request->settlement_admin_notes,
                // Driver konfirmasi pelunasan diterima → mitra tinggal TTD surat terima
                'status'                  => 'settlement_verified',
            ]);

            return back()->with('success', 'Pelunasan dikonfirmasi. Menunggu konfirmasi penerimaan barang dari mitra.');
        } else {
            $order->update([
                'settlement_admin_notes' => $request->settlement_admin_notes,
                'settlement_verified_by' => Auth::id(),
                'status'                 => 'in_transit',
            ]);

            return back()->with('success', 'Pelunasan ditolak. Mitra perlu mengupload ulang bukti pelunasan.');
        }
    }

    // ─── Upload SPB ───────────────────────────────────────────────────
    public function uploadSpb(Request $request, $orderNumber)
    {
        $order = B2bOrder::where('order_number', $orderNumber)->firstOrFail();

        if (!$order->canUploadSpb()) {
            return back()->with('error', 'Status pesanan tidak mengizinkan upload SPB saat ini.');
        }

        $request->validate([
            'spb_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('spb_file')) {
            $path = $request->file('spb_file')->store('b2b/spb', 'public');
            $order->update([
                'spb_path'        => $path,
                'spb_uploaded_at' => now(),
                'status'          => 'completed' // SPB diupload = Pesanan Berhasil/Selesai
            ]);

            return back()->with('success', 'Surat Penerimaan Barang (SPB) berhasil diupload. Pesanan ditandai sebagai Selesai.');
        }

        return back()->with('error', 'Gagal mengupload file.');
    }

    // ─── Tanggapi Dispute ─────────────────────────────────────────────
    public function handleDispute(Request $request, string $orderNumber)
    {
        $request->validate([
            'action'              => 'required|in:reviewing,resolved,rejected',
            'dispute_admin_notes' => 'required|string|min:10|max:2000',
        ]);

        $order = B2bOrder::where('order_number', $orderNumber)->firstOrFail();

        $updateData = [
            'dispute_status'      => $request->action,
            'dispute_admin_notes' => $request->dispute_admin_notes,
        ];

        if (in_array($request->action, ['resolved', 'rejected'])) {
            $updateData['dispute_resolved_at'] = now();
            $updateData['dispute_resolved_by'] = Auth::id();
        }

        $order->update($updateData);

        $label = match($request->action) {
            'reviewing' => 'Sedang ditinjau',
            'resolved'  => 'Diselesaikan',
            'rejected'  => 'Ditolak',
        };

        return back()->with('success', "Dispute berhasil diupdate: $label.");
    }

    // ─── Batalkan Order ───────────────────────────────────────────────
    public function cancel(Request $request, string $orderNumber)
    {
        $request->validate([
            'cancel_reason' => 'required|string|min:10|max:500',
        ]);

        $order = B2bOrder::where('order_number', $orderNumber)->firstOrFail();

        // Hanya bisa dibatalkan sebelum barang berangkat
        $cancelableStatuses = ['waiting_dp', 'dp_uploaded'];
        if (!in_array($order->status, $cancelableStatuses)) {
            return back()->with('error', 'Order tidak dapat dibatalkan setelah barang dikirim.');
        }

        \DB::transaction(function () use ($order, $request) {
            // Balikkan stok ke produk
            if (is_array($order->items)) {
                foreach ($order->items as $item) {
                    $product = \App\Models\Product::find($item['product_id']);
                    if ($product) {
                        $product->increment('stock_b2b', $item['qty_kg']);
                    }
                }
            }

            $order->update([
                'status'        => 'cancelled',
                'cancel_reason' => $request->cancel_reason,
            ]);
        });

        return back()->with('success', 'Order B2B berhasil dibatalkan dan stok telah dikembalikan.');
    }
}
