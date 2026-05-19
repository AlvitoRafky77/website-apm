<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\B2bOrder;
use App\Models\B2bRegistration;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $featuredCount = Product::where('is_featured', true)->count();
        $pendingB2bCount = B2bRegistration::where('status', 'pending')->count();
        $pendingOrdersCount = Order::where('status', 'pending')->where('payment_status', 'paid')->count();
        $pendingB2bOrdersCount = B2bOrder::whereIn('status', ['pending_review', 'dp_uploaded'])->count();

        // All products stock monitoring
        $allProducts = Product::where('is_active', true)
            ->with('category')
            ->orderBy('stock_b2c')
            ->get();

        $salesData = [];
        $b2bSalesData = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');

            $dailyTotal = Order::where('payment_status', 'paid')
                ->whereDate('paid_at', $date)
                ->sum('total');
            $salesData[] = (int) $dailyTotal;

            $dailyB2bTotal = B2bOrder::whereNotIn('status', ['pending_review', 'waiting_dp', 'dp_uploaded', 'cancelled'])
                ->whereDate('dp_verified_at', $date)
                ->sum('total');
            $b2bSalesData[] = (int) $dailyB2bTotal;
        }

        $b2cOrders = Order::whereNotIn('status', ['cancelled'])->get();
        $b2cTopProducts = [];

        foreach ($b2cOrders as $order) {
            $items = is_array($order->items) ? $order->items : json_decode($order->items, true);

            if ($items) {
                foreach ($items as $item) {
                    $pId = $item['product_id'] ?? 0;

                    if (!isset($b2cTopProducts[$pId])) {
                        $b2cTopProducts[$pId] = ['name' => $item['name'] ?? 'Unknown', 'total' => 0];
                    }

                    $b2cTopProducts[$pId]['total'] += $item['quantity'] ?? 0;
                }
            }
        }

        usort($b2cTopProducts, fn ($a, $b) => $b['total'] <=> $a['total']);
        $b2cTopProducts = array_slice($b2cTopProducts, 0, 5);

        $b2bOrders = B2bOrder::whereNotIn('status', ['cancelled'])->get();
        $b2bTopProducts = [];

        foreach ($b2bOrders as $order) {
            $items = is_array($order->items) ? $order->items : json_decode($order->items, true);

            if ($items) {
                foreach ($items as $item) {
                    $pId = $item['product_id'] ?? 0;

                    if (!isset($b2bTopProducts[$pId])) {
                        $b2bTopProducts[$pId] = ['name' => $item['name'] ?? 'Unknown', 'total' => 0];
                    }

                    $b2bTopProducts[$pId]['total'] += $item['qty_kg'] ?? 0;
                }
            }
        }

        usort($b2bTopProducts, fn ($a, $b) => $b['total'] <=> $a['total']);
        $b2bTopProducts = array_slice($b2bTopProducts, 0, 5);

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'featuredCount',
            'pendingB2bCount',
            'pendingOrdersCount',
            'pendingB2bOrdersCount',
            'allProducts',
            'labels',
            'salesData',
            'b2bSalesData',
            'b2cTopProducts',
            'b2bTopProducts'
        ));
    }
}
