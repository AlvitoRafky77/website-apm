<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request, string $orderId): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:120',
            'comment' => 'nullable|string|max:1200',
            'image' => 'nullable|image|max:2048',
        ]);

        $order = Order::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment_status !== 'paid') {
            return back()->with('error', 'Ulasan hanya dapat diberikan untuk pesanan yang sudah dibayar.');
        }

        $isAcceptedStatus = $order->user_confirmed || $order->status === 'delivered';
        if (!$isAcceptedStatus) {
            return back()->with('error', 'Ulasan dapat diberikan setelah pesanan diterima.');
        }

        $items = is_array($order->items) ? $order->items : json_decode((string) $order->items, true);
        $productIdsInOrder = collect($items)->pluck('product_id')->map(fn ($id) => (int) $id)->all();

        if (!in_array((int) $validated['product_id'], $productIdsInOrder, true)) {
            return back()->with('error', 'Produk tidak ditemukan pada pesanan ini.');
        }

        $alreadyReviewed = ProductReview::where('order_id', $order->id)
            ->where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('info', 'Anda sudah mengulas produk ini pada pesanan tersebut.');
        }

        $product = Product::findOrFail($validated['product_id']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product-reviews', 'public');
        }

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'] ?? null,
            'image_path' => $imagePath,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}
