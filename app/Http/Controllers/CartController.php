<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\LalamoveService;
use App\Services\RajaOngkirService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class CartController extends Controller
{
    private LalamoveService   $lalamove;
    private StockService      $stock;
    private RajaOngkirService $rajaOngkir;

    public function __construct(LalamoveService $lalamove, StockService $stock, RajaOngkirService $rajaOngkir)
    {
        $this->lalamove   = $lalamove;
        $this->stock      = $stock;
        $this->rajaOngkir = $rajaOngkir;
    }

    // ────────────────────────────────────────────────────────
    // Cart Helpers
    // ────────────────────────────────────────────────────────

    private function getCart(): array
    {
        return session('cart', []);
    }

    private function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    // ────────────────────────────────────────────────────────
    // Add Item to Cart
    // ────────────────────────────────────────────────────────

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (Auth::check() && Auth::user()->isB2b()) {
            return response()->json(['error' => 'Mitra B2B menggunakan portal khusus.'], 403);
        }

        $minOrder = 10; // minimum 10 KG per transaksi
        $maxOrder = min(200, (int) $product->stock_b2c);

        if ($product->stock_b2c <= 0) {
            return response()->json(['error' => 'Stok produk habis.'], 422);
        }

        if ($maxOrder < $minOrder) {
            return response()->json(['error' => 'Stok belum memenuhi minimal pembelian 10 KG.'], 422);
        }

        $qty = min((int) $request->quantity, $maxOrder);
        $qty = max($minOrder, $qty);

        $cart = $this->getCart();
        $key  = (string) $product->id;

        if (isset($cart[$key])) {
            $newQty          = min($cart[$key]['quantity'] + $qty, $maxOrder);
            $cart[$key]['quantity'] = $newQty;
            $cart[$key]['max_order'] = $maxOrder;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => (float) $product->price,
                'unit'       => $product->unit,
                'weight'     => (float) ($product->weight ?? 1),
                'image'      => $product->image,
                'slug'       => $product->slug,
                'max_order'  => $maxOrder,
                'quantity'   => $qty,
            ];
        }

        $this->saveCart($cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));
        $cartTotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));

        return response()->json([
            'success'    => true,
            'message'    => "{$product->name} berhasil ditambahkan ke keranjang.",
            'cart_count' => $cartCount,
            'cart_total' => $cartTotal,
            'cart_items' => array_values($cart),
        ]);
    }

    // ────────────────────────────────────────────────────────
    // Update Quantity
    // ────────────────────────────────────────────────────────

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = $this->getCart();
        $key  = (string) $request->product_id;

        if (!isset($cart[$key])) {
            return response()->json(['error' => 'Item tidak ditemukan di keranjang.'], 404);
        }

        $product = Product::find($request->product_id);
        if (!$product || $product->stock_b2c <= 0) {
            return response()->json(['error' => 'Stok produk habis.'], 422);
        }

        $minOrder = 10;
        $maxOrder = min(200, (int) $product->stock_b2c);
        if ($maxOrder < $minOrder) {
            return response()->json(['error' => 'Stok belum memenuhi minimal pembelian 10 KG.'], 422);
        }

        $qty = min((int) $request->quantity, $maxOrder);
        $qty = max($minOrder, $qty);

        $cart[$key]['max_order'] = $maxOrder;
        $cart[$key]['quantity'] = $qty;

        $this->saveCart($cart);
        return $this->cartSummaryResponse($cart);
    }

    // ────────────────────────────────────────────────────────
    // Remove Item
    // ────────────────────────────────────────────────────────

    public function remove(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required']);

        $cart = $this->getCart();
        unset($cart[(string) $request->product_id]);
        $this->saveCart($cart);

        return $this->cartSummaryResponse($cart);
    }

    // ────────────────────────────────────────────────────────
    // Clear Cart
    // ────────────────────────────────────────────────────────

    public function clear(): JsonResponse
    {
        $this->saveCart([]);
        return response()->json(['success' => true, 'cart_count' => 0]);
    }

    // ────────────────────────────────────────────────────────
    // Checkout Page
    // ────────────────────────────────────────────────────────

    public function checkout(): View|RedirectResponse
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('products.index')
                ->with('info', 'Keranjang belanja Anda kosong. Silakan pilih produk terlebih dahulu.');
        }

        $subtotal    = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $totalWeight = array_sum(array_map(fn($i) => ($i['weight'] ?? 1) * $i['quantity'], $cart));
        $shipping    = 0;
        $total       = $subtotal + $shipping;

        $warehouse = config('lalamove.warehouse');

        return view('checkout.index', compact('cart', 'subtotal', 'shipping', 'total', 'totalWeight', 'warehouse'));
    }

    // ────────────────────────────────────────────────────────
    // AJAX: Get Shipping Quote
    // ────────────────────────────────────────────────────────

    public function getShippingQuote(Request $request): JsonResponse
    {
        $request->validate([
            'lat'      => 'required|numeric',
            'lng'      => 'required|numeric',
            'city'     => 'nullable|string',
            'mode'     => 'nullable|string', // 'lalamove' | 'rajaongkir'
            'courier'  => 'nullable|string', // untuk rajaongkir
        ]);

        $cart        = $this->getCart();
        $totalWeight = array_sum(array_map(fn($i) => ($i['weight'] ?? 1) * $i['quantity'], $cart));
        if ($totalWeight <= 0) $totalWeight = 1;

        $destLat = (float) $request->lat;
        $destLng = (float) $request->lng;

        // Hitung jarak dari gudang ke customer (km)
        $warehouse   = config('lalamove.warehouse');
        $distanceKm  = $this->haversine(
            (float) $warehouse['lat'], (float) $warehouse['lng'],
            $destLat, $destLng
        );
        $radiusLimit = (float) config('rajaongkir.lalamove_radius_km', 70);

        // ── Dalam kota: pakai Lalamove ────────────────────────
        if ($distanceKm <= $radiusLimit) {
            $quote = $this->lalamove->getQuotation($destLat, $destLng, $totalWeight, $request->address ?? '');
            return response()->json(array_merge($quote, [
                'shipping_mode' => 'lalamove',
                'distance_km'   => round($distanceKm, 1),
            ]));
        }

        // ── Luar kota: pakai RajaOngkir ───────────────────────
        // Jika mode=rajaongkir dan ada courier dipilih, return 1 service saja
        if ($request->mode === 'rajaongkir' && $request->courier) {
            // Cari city id
            $cityId  = $this->rajaOngkir->findDestinationId($request->city ?? $request->address ?? '');
            if (!$cityId) {
                return response()->json(['success' => false, 'message' => 'Kota tujuan tidak ditemukan. Coba ketik nama kota utama (contoh: Jakarta, Medan).']);
            }
            $options = $this->rajaOngkir->getShippingCost($cityId, $totalWeight);
            // Filter hanya yang dipilih
            $selected = collect($options)->firstWhere('service', $request->courier) ?? ($options[0] ?? null);
            if (!$selected) {
                return response()->json(['success' => false, 'message' => 'Layanan kurir tidak tersedia ke kota ini.']);
            }
            return response()->json([
                'success'       => true,
                'shipping_mode' => 'rajaongkir',
                'price'         => $selected['price'],
                'courier'       => $selected['courier'],
                'service'       => $selected['service'],
                'label'         => $selected['label'],
                'etd'           => $selected['etd'],
                'distance_km'   => round($distanceKm, 1),
                'is_mock'       => false,
            ]);
        }

        // Return daftar semua pilihan kurir (untuk ditampilkan ke user)
        $cityId = $this->rajaOngkir->findDestinationId($request->city ?? $request->address ?? '');
        if (!$cityId) {
            return response()->json([
                'success'       => false,
                'shipping_mode' => 'rajaongkir',
                'distance_km'   => round($distanceKm, 1),
                'message'       => 'Masukkan nama kota tujuan untuk menghitung ongkir ekspedisi.',
                'need_city'     => true,
            ]);
        }

        $options = $this->rajaOngkir->getShippingCost($cityId, $totalWeight);
        if (empty($options)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada layanan pengiriman tersedia ke kota ini.']);
        }

        return response()->json([
            'success'       => true,
            'shipping_mode' => 'rajaongkir',
            'distance_km'   => round($distanceKm, 1),
            'options'       => $options, // daftar JNE REG, JNE YES, TIKI dll
        ]);
    }

    /** Haversine formula — distance in KM */
    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a    = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    // ────────────────────────────────────────────────────────
    // Place Order
    // ────────────────────────────────────────────────────────

    public function placeOrder(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'recipient_name'        => 'required|string|max:255',
            'recipient_phone'       => 'required|string|max:20',
            'recipient_address'     => 'required|string',
            'recipient_city'        => 'nullable|string|max:100',
            'recipient_lat'         => 'required|numeric',
            'recipient_lng'         => 'required|numeric',
            'shipping_cost'         => 'required|numeric|min:0',
            'shipping_mode'         => 'nullable|string|in:lalamove,rajaongkir',
            'shipping_vehicle'      => 'nullable|string',
            'shipping_courier'      => 'nullable|string',
            'lalamove_quotation_id' => 'nullable|string',
            'notes'                 => 'nullable|string|max:500',
        ]);

        $cart = $this->getCart();
        if (empty($cart)) {
            return redirect()->route('products.index')->with('info', 'Keranjang Anda kosong.');
        }

        // Check stock availability before processing
        $stockErrors = $this->stock->checkStock($cart);
        if (!empty($stockErrors)) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $stockErrors),
            ], 422);
        }

        $shippingMode = $request->shipping_mode ?? 'lalamove';
        $subtotal     = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $shippingCost = (float) $request->shipping_cost;
        $total        = $subtotal + $shippingCost;
        $orderId      = Order::generateOrderId();
        $recipientCity = trim((string) ($request->recipient_city ?? ''));
        if ($recipientCity === '') {
            $recipientCity = trim((string) (Auth::user()->city ?? ''));
        }
        if ($recipientCity === '') {
            $recipientCity = trim((string) Str::limit($request->recipient_address, 100, ''));
        }

        // Tentukan label kurir
        $courierLabel = match($shippingMode) {
            'rajaongkir' => strtoupper($request->shipping_courier ?? 'Ekspedisi') . ' ' . strtoupper($request->shipping_vehicle ?? ''),
            default      => 'Lalamove',
        };

        // Save order to DB
        $order = Order::create([
            'order_id'              => $orderId,
            'user_id'               => Auth::id(),
            'items'                 => array_values($cart),
            'subtotal'              => $subtotal,
            'shipping_cost'         => $shippingCost,
            'shipping_vehicle'      => $request->shipping_vehicle,
            'total'                 => $total,
            'recipient_name'        => $request->recipient_name,
            'recipient_phone'       => $request->recipient_phone,
            'recipient_city'        => $recipientCity,
            'recipient_address'     => $request->recipient_address,
            'recipient_lat'         => (float) $request->recipient_lat,
            'recipient_lng'         => (float) $request->recipient_lng,
            'notes'                 => $request->notes,
            'payment_method'        => 'midtrans',
            'payment_status'        => 'pending',
            'status'                => 'pending',
            'courier'               => $shippingMode === 'rajaongkir' ? $courierLabel : null,
            'lalamove_quotation_id' => $shippingMode === 'lalamove' ? $request->lalamove_quotation_id : null,
            'stock_reserved'        => false,
        ]);

        // Reserve stock immediately
        try {
            $this->stock->reserveStock($cart, $order);
        } catch (\Exception $e) {
            $order->delete();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        // Configure Midtrans
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized  = config('midtrans.is_sanitized');
        MidtransConfig::$is3ds        = config('midtrans.is_3ds');

        // Build Snap params
        $itemDetails = [];
        foreach ($cart as $item) {
            $itemDetails[] = [
                'id'       => (string) $item['product_id'],
                'price'    => (int) $item['price'],
                'quantity' => $item['quantity'],
                'name'     => substr($item['name'], 0, 50),
            ];
        }

        // Add shipping as a line item
        if ($shippingCost > 0) {
            $shippingLabel = $shippingMode === 'rajaongkir'
                ? "Ongkir {$courierLabel}"
                : ('Ongkir Lalamove (' . (config('lalamove.vehicles')[$request->shipping_vehicle]['label'] ?? $request->shipping_vehicle) . ')');
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $shippingCost,
                'quantity' => 1,
                'name'     => substr($shippingLabel, 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $total,
            ],
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name' => $request->recipient_name,
                'phone'      => $request->recipient_phone,
                'email'      => Auth::user()->email,
                'shipping_address' => [
                    'first_name'   => $request->recipient_name,
                    'phone'        => $request->recipient_phone,
                    'address'      => $request->recipient_address,
                    'city'         => $recipientCity,
                    'country_code' => 'IDN',
                ],
            ],
            'callbacks' => ['finish' => route('checkout.finish')],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['midtrans_snap_token' => $snapToken]);
            $this->saveCart([]);

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap error: ' . $e->getMessage());
            // Release reserved stock on failure
            $this->stock->releaseStock($order);
            $order->delete();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran. Silakan coba lagi.',
            ], 500);
        }
    }

    // ────────────────────────────────────────────────────────
    // Midtrans Payment Notification Webhook
    // ────────────────────────────────────────────────────────

    public function notification(Request $request): JsonResponse
    {
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');

        try {
            $notif = new Notification();

            $orderId           = $notif->order_id;
            $transactionStatus = $notif->transaction_status;
            $fraudStatus       = $notif->fraud_status;
            $paymentType       = $notif->payment_type;
            $transactionId     = $notif->transaction_id;

            $order = Order::where('order_id', $orderId)->first();
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            if ($transactionStatus === 'capture') {
                $paymentStatus = ($fraudStatus === 'accept') ? 'paid' : 'failed';
            } elseif ($transactionStatus === 'settlement') {
                $paymentStatus = 'paid';
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $paymentStatus = $transactionStatus === 'expire' ? 'expired' : 'failed';
            } else {
                $paymentStatus = 'pending';
            }

            $order->update([
                'payment_status'          => $paymentStatus,
                'midtrans_transaction_id' => $transactionId,
                'midtrans_payment_type'   => $paymentType,
                'status'                  => $paymentStatus === 'paid' ? 'confirmed' : $order->status,
                'paid_at'                 => $paymentStatus === 'paid' ? now() : null,
            ]);

            // Manage stock based on payment result
            if ($paymentStatus === 'paid') {
                $this->stock->commitStock($order);
            } elseif (in_array($paymentStatus, ['failed', 'expired'])) {
                $this->stock->releaseStock($order);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    // ────────────────────────────────────────────────────────
    // Lalamove Webhook
    // ────────────────────────────────────────────────────────

    public function lalamoveWebhook(Request $request): JsonResponse
    {
        Log::info('Lalamove webhook received: ' . json_encode($request->all()));

        $lalamoveOrderId = $request->input('data.orderId');
        $status          = $request->input('data.status');
        $driverInfo      = $request->input('data.driverInfo');

        if (!$lalamoveOrderId) {
            return response()->json(['message' => 'OK']);
        }

        $order = Order::where('lalamove_order_id', $lalamoveOrderId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $updateData = ['lalamove_status' => $status];

        if ($driverInfo) {
            $updateData['lalamove_driver_name']  = $driverInfo['name'] ?? null;
            $updateData['lalamove_driver_phone'] = $driverInfo['phone'] ?? null;
        }

        // If Lalamove marks as completed, update order status
        if ($status === 'COMPLETED') {
            $updateData['status'] = 'delivered';
        }

        $order->update($updateData);

        return response()->json(['message' => 'OK']);
    }

    // ────────────────────────────────────────────────────────
    // Finish Payment Page
    // ────────────────────────────────────────────────────────

    public function finishPayment(Request $request): View|RedirectResponse
    {
        $orderId = $request->query('order_id');
        $order   = $orderId ? Order::where('order_id', $orderId)->first() : null;

        if ($order && $order->payment_status === 'pending') {
            try {
                MidtransConfig::$serverKey    = config('midtrans.server_key');
                MidtransConfig::$isProduction = config('midtrans.is_production');

                $status            = \Midtrans\Transaction::status($orderId);
                $transactionStatus = $status->transaction_status ?? null;
                $fraudStatus       = $status->fraud_status ?? null;

                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    $paymentStatus = ($transactionStatus === 'capture' && $fraudStatus !== 'accept') ? 'pending' : 'paid';
                } elseif ($transactionStatus === 'expire') {
                    $paymentStatus = 'expired';
                } elseif (in_array($transactionStatus, ['cancel', 'deny'])) {
                    $paymentStatus = 'failed';
                } else {
                    $paymentStatus = 'pending';
                }

                if ($paymentStatus !== 'pending') {
                    $order->update([
                        'payment_status'          => $paymentStatus,
                        'midtrans_transaction_id' => $status->transaction_id ?? null,
                        'midtrans_payment_type'   => $status->payment_type ?? null,
                        'status'                  => $paymentStatus === 'paid' ? 'confirmed' : ($paymentStatus === 'failed' ? 'cancelled' : $order->status),
                        'paid_at'                 => $paymentStatus === 'paid' ? now() : null,
                    ]);

                    if ($paymentStatus === 'paid') {
                        $this->stock->commitStock($order);
                    } elseif (in_array($paymentStatus, ['failed', 'expired'])) {
                        $this->stock->releaseStock($order);
                    }

                    $order->refresh();
                }
            } catch (\Exception $e) {
                Log::info('Midtrans status check: ' . $e->getMessage());
            }
        }

        return view('checkout.finish', compact('order'));
    }

    // ────────────────────────────────────────────────────────
    // Cart Data for Navbar
    // ────────────────────────────────────────────────────────

    public function cartData(): JsonResponse
    {
        $cart  = $this->getCart();
        $count = array_sum(array_column($cart, 'quantity'));
        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));

        return response()->json([
            'count' => $count,
            'total' => $total,
            'items' => array_values($cart),
        ]);
    }

    // ────────────────────────────────────────────────────────
    // Helper
    // ────────────────────────────────────────────────────────

    private function cartSummaryResponse(array $cart): JsonResponse
    {
        $count = array_sum(array_column($cart, 'quantity'));
        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));

        return response()->json([
            'success'    => true,
            'cart_count' => $count,
            'cart_total' => $total,
            'cart_items' => array_values($cart),
        ]);
    }
}
