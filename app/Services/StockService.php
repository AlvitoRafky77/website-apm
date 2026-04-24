<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    /**
     * Reserve stock when customer places an order.
     * Deducts stock_b2c immediately to prevent overselling.
     */
    public function reserveStock(array $cart, Order $order): bool
    {
        return DB::transaction(function () use ($cart, $order) {
            foreach ($cart as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product) {
                    Log::warning("StockService: Product {$item['product_id']} not found.");
                    continue;
                }

                if ($product->stock_b2c < $item['quantity']) {
                    Log::warning("StockService: Insufficient stock for product {$product->id}. Requested: {$item['quantity']}, Available: {$product->stock_b2c}");
                    // Rollback and throw — checkout should have prevented this
                    throw new \Exception("Stok {$product->name} tidak mencukupi. Tersisa {$product->stock_b2c} KG.");
                }

                $product->decrement('stock_b2c', $item['quantity']);
            }

            // Mark order as having reserved stock
            $order->update(['stock_reserved' => true]);

            return true;
        });
    }

    /**
     * Commit stock — called after successful payment.
     * Stock is already deducted, just mark reservation as completed.
     */
    public function commitStock(Order $order): void
    {
        if ($order->stock_reserved) {
            $order->update(['stock_reserved' => false]);
            Log::info("StockService: Stock committed for order {$order->order_id}");
        }
    }

    /**
     * Release reserved stock back — called when:
     * - Payment fails / expires / is cancelled
     * - Admin cancels the order
     */
    public function releaseStock(Order $order): void
    {
        if (!$order->stock_reserved) {
            // Stock already released or was never reserved
            return;
        }

        DB::transaction(function () use ($order) {
            $items = is_array($order->items) ? $order->items : json_decode($order->items, true);

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->increment('stock_b2c', $item['quantity']);
                    Log::info("StockService: Released {$item['quantity']} units of product {$item['product_id']} for order {$order->order_id}");
                }
            }

            $order->update(['stock_reserved' => false]);
        });
    }

    /**
     * Force restore stock for PAID orders that are cancelled by admin.
     * Used when commitStock() already set stock_reserved=false but admin cancelled.
     */
    public function forceRestoreStock(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $items = is_array($order->items) ? $order->items : json_decode($order->items, true);

            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->increment('stock_b2c', $item['quantity']);
                    Log::info("StockService: Force-restored {$item['quantity']} units of product {$item['product_id']} for cancelled paid order {$order->order_id}");
                }
            }
        });
    }

    /**
     * Check if all items in cart are sufficiently stocked.
     * Returns array of errors, empty if all fine.
     */
    public function checkStock(array $cart): array
    {
        $errors = [];

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);

            if (!$product) {
                $errors[] = "Produk tidak ditemukan.";
                continue;
            }

            if ($product->stock_b2c < $item['quantity']) {
                $errors[] = "Stok {$product->name} tidak mencukupi. Tersisa {$product->stock_b2c} KG, kamu memesan {$item['quantity']} KG.";
            }
        }

        return $errors;
    }
}
