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
     * Hitung ongkir berdasarkan kota tujuan dan berat
     */
    public static function calculate(string $destinationCity, float $weightKg): ?array
    {
        $rate = self::where('destination_city', $destinationCity)
            ->where('is_active', true)
            ->first();

        if (!$rate) {
            return null;
        }

        return [
            'rate'            => $rate,
            'rate_per_kg'     => $rate->rate_per_kg,
            'weight_kg'       => $weightKg,
            'total'           => round($rate->rate_per_kg * $weightKg, 2),
            'estimated_days'  => $rate->estimated_days,
        ];
    }
}
