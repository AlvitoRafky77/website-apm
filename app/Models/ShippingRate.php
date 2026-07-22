<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
        'origin_city',
        'destination_city',
        'rate_per_kg',
        'min_weight_kg',
        'estimated_days',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'rate_per_kg'   => 'float',
        'min_weight_kg' => 'float',
        'is_active'     => 'boolean',
    ];

    /**
     * Hitung ongkir berdasarkan kota tujuan dan berat (Smart Fuzzy City Matching)
     */
    public static function calculate(string $destinationCity, float $weightKg): ?array
    {
        $inputCity = trim($destinationCity);
        if (empty($inputCity)) {
            return null;
        }

        // 1. Exact match
        $rate = self::where('is_active', true)
            ->where('destination_city', $inputCity)
            ->first();

        // 2. Case-insensitive / partial LIKE match
        if (!$rate) {
            $rate = self::where('is_active', true)
                ->where('destination_city', 'LIKE', '%' . $inputCity . '%')
                ->first();
        }

        // 3. Clean keywords (Remove Kota, Kabupaten, Kab, DKI, etc.)
        if (!$rate) {
            $cleanCity = preg_replace('/^(kota|kabupaten|kab\.|dki)\s+/i', '', $inputCity);
            $cleanCity = trim(preg_replace('/\s+(pusat|selatan|utara|barat|timur)$/i', '', $cleanCity));

            if (!empty($cleanCity)) {
                $rate = self::where('is_active', true)
                    ->where(function($q) use ($cleanCity, $inputCity) {
                        $q->where('destination_city', 'LIKE', '%' . $cleanCity . '%')
                          ->orWhereRaw('? LIKE CONCAT("%", destination_city, "%")', [$inputCity]);
                    })
                    ->first();
            }
        }

        // 4. Resilient Fallback Rate if city is not in table
        if (!$rate) {
            $rate = self::where('is_active', true)->first();
        }

        if (!$rate) {
            return null;
        }

        $effectiveWeight = max($weightKg, $rate->min_weight_kg ?? 1);
        $totalCost = round($rate->rate_per_kg * $effectiveWeight, 0);

        return [
            'rate'            => $rate,
            'rate_per_kg'     => $rate->rate_per_kg,
            'weight_kg'       => $weightKg,
            'total'           => $totalCost,
            'estimated_days'  => $rate->estimated_days ?? '2-3',
            'matched_city'    => $rate->destination_city,
        ];
    }
}
