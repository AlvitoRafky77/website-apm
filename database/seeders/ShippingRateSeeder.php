<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    /**
     * Seed shipping rates for major Indonesian B2B cities.
     */
    public function run(): void
    {
        $rates = [
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Jakarta Pusat',   'rate_per_kg' => 2500, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Armada Pendingin B2B Utama'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Jakarta Selatan', 'rate_per_kg' => 2500, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Armada Pendingin B2B Utama'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Jakarta Barat',   'rate_per_kg' => 2500, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Armada Pendingin B2B Utama'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Jakarta Timur',   'rate_per_kg' => 2500, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Armada Pendingin B2B Utama'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Jakarta Utara',   'rate_per_kg' => 2500, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Armada Pendingin B2B Utama'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Jakarta',         'rate_per_kg' => 2500, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Armada Pendingin B2B Utama'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'DKI Jakarta',     'rate_per_kg' => 2500, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Armada Pendingin B2B Utama'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Pekanbaru',       'rate_per_kg' => 1000, 'min_weight_kg' => 50,  'estimated_days' => 1, 'notes' => 'Lokal Dalam Kota'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Surabaya',        'rate_per_kg' => 3500, 'min_weight_kg' => 100, 'estimated_days' => 3, 'notes' => 'Jalur Ekspedisi Beku Jawa'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Bandung',         'rate_per_kg' => 3000, 'min_weight_kg' => 100, 'estimated_days' => 3, 'notes' => 'Jalur Ekspedisi Beku Jawa'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Medan',           'rate_per_kg' => 2000, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Jalur Sumatera Barat/Utara'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Padang',          'rate_per_kg' => 1800, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Jalur Lintas Sumatera'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Batam',           'rate_per_kg' => 2800, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Jalur Laut & Udara Kepri'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Palembang',       'rate_per_kg' => 2200, 'min_weight_kg' => 100, 'estimated_days' => 2, 'notes' => 'Jalur Lintas Sumatera'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Semarang',        'rate_per_kg' => 3200, 'min_weight_kg' => 100, 'estimated_days' => 3, 'notes' => 'Jalur Ekspedisi Beku Jawa'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Makassar',        'rate_per_kg' => 4500, 'min_weight_kg' => 100, 'estimated_days' => 4, 'notes' => 'Jalur Ekspedisi Sulawesi'],
            ['origin_city' => 'Pekanbaru', 'destination_city' => 'Denpasar',       'rate_per_kg' => 4000, 'min_weight_kg' => 100, 'estimated_days' => 4, 'notes' => 'Jalur Ekspedisi Bali'],
        ];

        foreach ($rates as $rateData) {
            ShippingRate::updateOrCreate(
                [
                    'origin_city'      => $rateData['origin_city'],
                    'destination_city' => $rateData['destination_city'],
                ],
                array_merge($rateData, ['is_active' => true])
            );
        }
    }
}
