<?php

namespace App\Http\Controllers;

use App\Models\B2bOrder;
use Illuminate\Http\Request;

class DriverOrderController extends Controller
{
    public function show($orderNumber)
    {
        $order = B2bOrder::where('order_number', $orderNumber)->firstOrFail();

        // Jika sudah bukan in_transit, mungkin sudah tiba atau selesai
        if ($order->status !== 'in_transit') {
            return view('driver.order_already_processed', compact('order'));
        }

        return view('driver.order', compact('order'));
    }

    public function markArrived(Request $request, $orderNumber)
    {
        $order = B2bOrder::where('order_number', $orderNumber)->firstOrFail();

        if ($order->status === 'in_transit') {
            $order->update([
                'status' => 'arrived',
                'arrived_at' => now(),
            ]);
        }

        return redirect(\Illuminate\Support\Facades\URL::signedRoute('driver.order.show', ['orderNumber' => $orderNumber]))
            ->with('success', 'Berhasil! Anda telah menandai bahwa barang sudah sampai di lokasi.');
    }
}
