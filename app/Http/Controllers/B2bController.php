<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class B2bController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Admin boleh melihat dashboard B2B untuk monitoring
        if (!$user->isB2b() && !$user->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan Mitra B2B.');
        }

        $isAdminPreview = $user->hasRole('admin') && !$user->isB2b();

        $b2bProducts = \App\Models\Product::query()
            ->where('is_active', true)
            ->where('stock_b2b', '>', 0)
            ->with('category')
            ->orderBy('name')
            ->get();

        // Admin preview: tampilkan semua order B2B untuk monitoring
        if ($isAdminPreview) {
            $ordersQuery = \App\Models\B2bOrder::query();
        } else {
            $ordersQuery = \App\Models\B2bOrder::query()
                ->where('user_id', $user->id);
        }

        $stats = [
            'total'       => (clone $ordersQuery)->count(),
            'active'      => (clone $ordersQuery)->whereIn('status', ['waiting_dp', 'dp_uploaded', 'in_transit', 'settlement_uploaded', 'settlement_verified'])->count(),
            'completed'   => (clone $ordersQuery)->where('status', 'completed')->count(),
            'need_action' => (clone $ordersQuery)->whereIn('status', ['waiting_dp', 'in_transit'])->count(),
        ];

        $recentOrders = (clone $ordersQuery)
            ->latest()
            ->take(5)
            ->get();

        return view('b2b.dashboard', compact('user', 'b2bProducts', 'stats', 'recentOrders', 'isAdminPreview'));
    }

    public function productShow(string $slug)
    {
        $user = Auth::user();
        // Admin boleh melihat detail produk B2B
        if (!$user->isB2b() && !$user->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $product = \App\Models\Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        // Related products same category, B2B stock > 0
        $related = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock_b2b', '>', 0)
            ->limit(4)
            ->get();

        return view('b2b.product-show', compact('user', 'product', 'related'));
    }
}
