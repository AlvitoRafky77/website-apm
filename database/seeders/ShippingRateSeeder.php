<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            ['destination_city' => 'Padang',      'rate_per_kg' => 500,  'estimated_days' => 2, 'notes' => 'Via jalur darat Pekanbaru-Padang'],
            ['destination_city' => 'Medan',        'rate_per_kg' => 800,  'estimated_days' => 3, 'notes' => 'Via jalur darat Sumatra'],
            ['destination_city' => 'Dumai',        'rate_per_kg' => 300,  'estimated_days' => 1, 'notes' => 'Kota terdekat'],
            ['destination_city' => 'Bengkalis',    'rate_per_kg' => 350,  'estimated_days' => 1, 'notes' => 'Via jalur darat'],
            ['destination_city' => 'Kampar',       'rate_per_kg' => 200,  'estimated_days' => 1, 'notes' => 'Jarak dekat'],
            ['destination_city' => 'Siak',         'rate_per_kg' => 250,  'estimated_days' => 1, 'notes' => 'Via jalur darat'],
            ['destination_city' => 'Rokan Hilir',  'rate_per_kg' => 400,  'estimated_days' => 2, 'notes' => 'Via jalur darat'],
            ['destination_city' => 'Indragiri Hulu', 'rate_per_kg' => 450, 'estimated_days' => 2, 'notes' => 'Via jalur darat'],
            ['destination_city' => 'Batam',        'rate_per_kg' => 1000, 'estimated_days' => 3, 'notes' => 'Via darat + laut'],
            ['destination_city' => 'Jakarta',      'rate_per_kg' => 1200, 'estimated_days' => 4, 'notes' => 'Via jalur darat antar pulau'],
        ];

        foreach ($rates as $rate) {
            ShippingRate::updateOrCreate(
                [
                    'origin_city'      => 'Pekanbaru',
                    'destination_city' => $rate['destination_city'],
                ],
                [
                    'rate_per_kg'    => $rate['rate_per_kg'],
                    'min_weight_kg'  => 1000,
                    'estimated_days' => $rate['estimated_days'],
                    'notes'          => $rate['notes'],
                    'is_active'      => true,
                ]
            );
        }
    }
}
