<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class AdminShippingRateController extends Controller
{
    public function index()
    {
        $rates = ShippingRate::orderBy('destination_city')->get();
        return view('admin.shipping-rates.index', compact('rates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'destination_city' => 'required|string|max:100',
            'rate_per_kg'      => 'required|numeric|min:0',
            'min_weight_kg'    => 'required|numeric|min:0',
            'estimated_days'   => 'nullable|integer|min:1|max:30',
            'notes'            => 'nullable|string|max:500',
        ]);

        ShippingRate::updateOrCreate(
            [
                'origin_city'      => 'Pekanbaru',
                'destination_city' => $request->destination_city,
            ],
            [
                'rate_per_kg'    => $request->rate_per_kg,
                'min_weight_kg'  => $request->min_weight_kg,
                'estimated_days' => $request->estimated_days,
                'notes'          => $request->notes,
                'is_active'      => true,
            ]
        );

        return back()->with('success', "Tarif ongkir ke {$request->destination_city} berhasil disimpan.");
    }

    public function update(Request $request, ShippingRate $rate)
    {
        $request->validate([
            'destination_city' => 'required|string|max:100',
            'rate_per_kg'      => 'required|numeric|min:0',
            'min_weight_kg'    => 'required|numeric|min:0',
            'estimated_days'   => 'nullable|integer|min:1|max:30',
            'is_active'        => 'nullable|boolean',
            'notes'            => 'nullable|string|max:500',
        ]);

        $rate->update([
            'destination_city' => $request->destination_city,
            'rate_per_kg'      => $request->rate_per_kg,
            'min_weight_kg'    => $request->min_weight_kg,
            'estimated_days'   => $request->estimated_days,
            'is_active'        => $request->boolean('is_active', true),
            'notes'            => $request->notes,
        ]);

        return back()->with('success', "Tarif ongkir ke {$rate->destination_city} berhasil diperbarui.");
    }

    public function destroy(ShippingRate $rate)
    {
        $city = $rate->destination_city;
        $rate->delete();
        return back()->with('success', "Tarif ongkir ke {$city} berhasil dihapus.");
    }

    /** API endpoint: hitung ongkir untuk kota tujuan + berat */
    public function calculate(Request $request)
    {
        $request->validate([
            'destination_city' => 'required|string',
            'weight_kg'        => 'required|numeric|min:1',
        ]);

        $result = ShippingRate::calculate($request->destination_city, $request->weight_kg);

        if (!$result) {
            return response()->json([
                'found' => false,
                'message' => 'Tarif untuk kota tujuan ini belum tersedia.',
            ]);
        }

        return response()->json([
            'found'          => true,
            'rate_per_kg'    => $result['rate_per_kg'],
            'weight_kg'      => $result['weight_kg'],
            'total'          => $result['total'],
            'estimated_days' => $result['estimated_days'],
        ]);
    }
}
