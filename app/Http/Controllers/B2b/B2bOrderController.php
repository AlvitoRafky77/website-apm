<?php

namespace App\Http\Controllers\B2b;

use App\Http\Controllers\Controller;
use App\Models\B2bOrder;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class B2bOrderController extends Controller
{
    private function ensureB2bAccess(): void
    {
        abort_unless(
            Auth::check()
                && Auth::user()->isB2b()
                && Auth::user()->b2bRegistration,
            403,
            'Akses ditolak.'
        );
    }

    // ─── Daftar Order ────────────────────────────────────────────────
    public function index()
    {
        $this->ensureB2bAccess();

        $orders = B2bOrder::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('b2b.orders.index', compact('orders'));
    }

    // ─── Form Buat Order Baru ─────────────────────────────────────────
    public function create()
    {
        $this->ensureB2bAccess();

        $products = Product::where('is_active', true)
            ->where('stock_b2b', '>', 0)
            ->with('category')
            ->get();

        return view('b2b.orders.create', compact('products'));
    }

    // ─── Simpan Order Baru ────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->ensureB2bAccess();

        $request->validate([
            'products'              => 'required|array|min:1',
            'products.*.id'         => 'required|integer|distinct|exists:products,id',
            'products.*.qty_ton'    => 'required|numeric|min:1|max:10',
            'recipient_name'        => 'required|string|max:255',
            'recipient_phone'       => 'required|string|max:20',
            'recipient_address'     => 'required|string',
            'recipient_city'        => 'required|string|max:100',
            'notes'                 => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $reg  = $user->b2bRegistration;

        $totalTon = collect($request->products)->sum(fn ($line) => (float) $line['qty_ton']);
        if ($totalTon < 1 || $totalTon > 10) {
            return back()->withErrors(['products' => 'Total pesanan harus antara 1 sampai 10 ton.'])->withInput();
        }

        $order = DB::transaction(function () use ($request, $user, $reg) {
            $items    = [];
            $subtotal = 0;

            foreach ($request->products as $line) {
                $product = Product::where('is_active', true)
                    ->lockForUpdate()
                    ->find($line['id']);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'products' => "Produk dengan ID {$line['id']} tidak ditemukan atau tidak aktif.",
                    ]);
                }

                $qtyTon = (float) $line['qty_ton'];
                $qtyKg  = (int) round($qtyTon * 1000);

                if ($qtyKg < 1000) {
                    throw ValidationException::withMessages([
                        'products' => 'Setiap item minimal harus 1 ton.',
                    ]);
                }

                if ($product->stock_b2b < $qtyKg) {
                    throw ValidationException::withMessages([
                        'products' => "Stok B2B {$product->name} tidak mencukupi. Tersisa " . number_format($product->stock_b2b, 0, ',', '.') . " KG.",
                    ]);
                }

                $lineTotal = $product->price * $qtyKg;
                $subtotal += $lineTotal;

                $items[] = [
                    'product_id'   => $product->id,
                    'name'         => $product->name,
                    'image'        => $product->image,
                    'unit'         => $product->unit,
                    'qty_ton'      => $qtyTon,
                    'qty_kg'       => $qtyKg,
                    'price_per_kg' => $product->price,
                    'subtotal'     => $lineTotal,
                ];

                $product->decrement('stock_b2b', $qtyKg);
            }

            $total      = $subtotal;
            $dpAmount   = round($total * 0.5, 2);
            $settlement = round($total * 0.5, 2);

            return B2bOrder::create([
                'order_number'        => B2bOrder::generateOrderNumber(),
                'user_id'             => $user->id,
                'b2b_registration_id' => $reg->id,
                'items'               => $items,
                'subtotal'            => $subtotal,
                'shipping_cost'       => 0,
                'total'               => $total,
                'dp_amount'           => $dpAmount,
                'settlement_amount'   => $settlement,
                'recipient_name'      => $request->recipient_name,
                'recipient_phone'     => $request->recipient_phone,
                'recipient_address'   => $request->recipient_address,
                'recipient_city'      => $request->recipient_city,
                'notes'               => $request->notes,
                'status'              => 'pending_review',
            ]);
        });

        return redirect()->route('b2b.orders.show', $order->order_number)
            ->with('success', 'Order berhasil dibuat! Silakan lakukan pembayaran DP 50%.');
    }

    // ─── Detail Order ─────────────────────────────────────────────────
    public function show(string $orderNumber)
    {
        $this->ensureB2bAccess();

        $order = B2bOrder::with(['user', 'b2bRegistration'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('b2b.orders.show', compact('order'));
    }

    public function downloadInvoice(string $orderNumber)
    {
        $this->ensureB2bAccess();

        $order = B2bOrder::with(['user', 'b2bRegistration'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pdf = Pdf::loadView('b2b.orders.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice-B2B-' . $order->order_number . '.pdf');
    }

    // ─── Upload Bukti DP ──────────────────────────────────────────────
    public function uploadDp(Request $request, string $orderNumber)
    {
        $this->ensureB2bAccess();

        $request->validate([
            'dp_proof'                => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'dp_bank_name'            => 'nullable|string|max:100',
            'dp_transfer_reference'   => 'nullable|string|max:100',
        ]);

        $order = B2bOrder::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$order->canUploadDp()) {
            return back()->with('error', 'Status order tidak memungkinkan upload bukti DP saat ini.');
        }

        if ($order->dp_proof_path) {
            Storage::disk('public')->delete($order->dp_proof_path);
        }

        $path = $request->file('dp_proof')->store('b2b/dp-proofs', 'public');

        $order->update([
            'dp_proof_path'          => $path,
            'dp_uploaded_at'         => now(),
            'dp_bank_name'           => $request->filled('dp_bank_name') ? trim($request->input('dp_bank_name')) : null,
            'dp_transfer_reference'  => $request->filled('dp_transfer_reference') ? trim($request->input('dp_transfer_reference')) : null,
            'status'                 => 'dp_uploaded',
        ]);

        return back()->with('success', 'Bukti DP berhasil diupload. Menunggu verifikasi admin.');
    }

    // ─── Upload Bukti Pelunasan ───────────────────────────────────────
    // Alur baru: mitra upload pelunasan SETELAH barang diterima baik
    public function uploadSettlement(Request $request, string $orderNumber)
    {
        $this->ensureB2bAccess();

        $request->validate([
            'settlement_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $order = B2bOrder::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$order->canUploadSettlement()) {
            return back()->with('error', 'Upload pelunasan hanya bisa dilakukan setelah barang diterima baik.');
        }

        if ($order->settlement_proof_path) {
            Storage::disk('public')->delete($order->settlement_proof_path);
        }

        $path = $request->file('settlement_proof')->store('b2b/settlement-proofs', 'public');

        $order->update([
            'settlement_proof_path'  => $path,
            'settlement_uploaded_at' => now(),
            'status'                 => 'settlement_uploaded',
        ]);

        return back()->with('success', 'Bukti pelunasan berhasil diupload. Admin akan memverifikasi pembayaran Anda.');
    }

    // ─── Inspeksi Barang (Mitra + Driver cek bersama) ──────────────────
    public function inspectGoods(Request $request, string $orderNumber)
    {
        $this->ensureB2bAccess();

        $request->validate([
            'inspection_status' => 'required|in:good,damaged',
            'inspection_notes'  => 'required_if:inspection_status,damaged|nullable|string|max:1000',
            'inspection_images' => 'nullable|array|max:5',
            'inspection_images.*' => 'image|max:3072',
        ]);

        $order = B2bOrder::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$order->canInspect()) {
            return back()->with('error', 'Inspeksi barang belum bisa dilakukan pada status ini.');
        }

        $imagePaths = [];
        if ($request->hasFile('inspection_images')) {
            foreach ($request->file('inspection_images') as $img) {
                $imagePaths[] = $img->store('b2b/inspection', 'public');
            }
        }

        $order->update([
            'inspection_status' => $request->inspection_status,
            'inspection_notes'  => $request->inspection_notes,
            'inspection_images' => $imagePaths ?: null,
            'inspected_at'      => now(),
            'received_at'       => now(),
            'status'            => 'goods_accepted',
        ]);

        if ($request->inspection_status === 'damaged') {
            return back()->with('success', 'Inspeksi dicatat dengan laporan kerusakan. Barang tetap diterima dan klaim asuransi akan diproses oleh APM. Silakan lanjutkan upload pelunasan.');
        }

        return back()->with('success', 'Barang diterima dalam kondisi baik. Silakan lanjutkan upload bukti pelunasan 50%.');
    }

    // ─── Buka Dispute / Komplain ─────────────────────────────────────
    public function openDispute(Request $request, string $orderNumber)
    {
        $this->ensureB2bAccess();

        $request->validate([
            'dispute_reason'     => 'required|string|min:20|max:2000',
            'dispute_images'     => 'nullable|array|max:5',
            'dispute_images.*'   => 'image|max:3072',
        ]);

        $order = B2bOrder::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$order->canDispute()) {
            return back()->with('error', 'Komplain tidak dapat dibuka pada status order ini.');
        }

        $imagePaths = [];
        if ($request->hasFile('dispute_images')) {
            foreach ($request->file('dispute_images') as $img) {
                $imagePaths[] = $img->store('b2b/disputes', 'public');
            }
        }

        $order->update([
            'dispute_status' => 'open',
            'dispute_reason' => $request->dispute_reason,
            'dispute_images' => $imagePaths ?: null,
            'dispute_at'     => now(),
        ]);

        return back()->with('success', 'Komplain berhasil diajukan. Tim kami akan segera meninjau laporan Anda.');
    }
}
