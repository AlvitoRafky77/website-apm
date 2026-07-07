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

        $selectedMonth = request('filter_month', '7days');
        
        $monthOptions = [];
        for ($m = 0; $m < 12; $m++) {
            $carbonMonth = now()->subMonths($m);
            $monthVal = $carbonMonth->format('Y-m');
            $monthLabel = $carbonMonth->translatedFormat('F Y'); // e.g. "Juli 2026"
            $monthOptions[$monthVal] = $monthLabel;
        }

        $labels = [];
        $salesData = [];
        $b2bSalesData = [];

        if ($selectedMonth && isset($monthOptions[$selectedMonth])) {
            $carbonSelected = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $startDate = $carbonSelected->copy()->startOfMonth()->toDateTimeString();
            $endDate = $carbonSelected->copy()->endOfMonth()->toDateTimeString();

            $b2cSales = Order::where('payment_status', 'paid')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->groupBy(\DB::raw('DATE(paid_at)'))
                ->selectRaw('DATE(paid_at) as date, SUM(total) as total')
                ->pluck('total', 'date')
                ->toArray();

            $b2bSales = B2bOrder::whereNotIn('status', ['pending_review', 'waiting_dp', 'dp_uploaded', 'cancelled'])
                ->whereBetween('dp_verified_at', [$startDate, $endDate])
                ->groupBy(\DB::raw('DATE(dp_verified_at)'))
                ->selectRaw('DATE(dp_verified_at) as date, SUM(total) as total')
                ->pluck('total', 'date')
                ->toArray();

            $daysInMonth = $carbonSelected->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dateStr = $carbonSelected->copy()->day($day)->format('Y-m-d');
                $labels[] = $day . ' ' . $carbonSelected->translatedFormat('M');
                $salesData[] = (int) ($b2cSales[$dateStr] ?? 0);
                $b2bSalesData[] = (int) ($b2bSales[$dateStr] ?? 0);
            }
            $chartPeriodText = "Bulan " . $monthOptions[$selectedMonth];
        } else {
            $selectedMonth = '7days';
            $startDate = now()->subDays(6)->startOfDay()->toDateTimeString();
            $endDate = now()->endOfDay()->toDateTimeString();

            $b2cSales = Order::where('payment_status', 'paid')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->groupBy(\DB::raw('DATE(paid_at)'))
                ->selectRaw('DATE(paid_at) as date, SUM(total) as total')
                ->pluck('total', 'date')
                ->toArray();

            $b2bSales = B2bOrder::whereNotIn('status', ['pending_review', 'waiting_dp', 'dp_uploaded', 'cancelled'])
                ->whereBetween('dp_verified_at', [$startDate, $endDate])
                ->groupBy(\DB::raw('DATE(dp_verified_at)'))
                ->selectRaw('DATE(dp_verified_at) as date, SUM(total) as total')
                ->pluck('total', 'date')
                ->toArray();

            for ($i = 6; $i >= 0; $i--) {
                $carbonDay = now()->subDays($i);
                $dateStr = $carbonDay->format('Y-m-d');
                $labels[] = $carbonDay->format('d M');
                $salesData[] = (int) ($b2cSales[$dateStr] ?? 0);
                $b2bSalesData[] = (int) ($b2bSales[$dateStr] ?? 0);
            }
            $chartPeriodText = "7 Hari Terakhir";
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
            'b2bTopProducts',
            'monthOptions',
            'selectedMonth',
            'chartPeriodText'
        ));
    }
}
