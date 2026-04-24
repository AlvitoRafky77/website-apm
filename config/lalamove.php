<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Lalamove API Configuration
    |--------------------------------------------------------------------------
    | Daftar akun API Partner di: https://partnerportal.lalamove.com
    | Gunakan sandbox untuk development, switch ke production saat go-live.
    */

    'api_key'    => env('LALAMOVE_API_KEY', ''),
    'api_secret' => env('LALAMOVE_API_SECRET', ''),
    'market'     => env('LALAMOVE_MARKET', 'ID'),

    // Ganti ke https://rest.lalamove.com saat production
    'base_url'   => env('LALAMOVE_BASE_URL', 'https://rest.sandbox.lalamove.com'),

    'is_sandbox' => env('LALAMOVE_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | Titik Jemput Tetap (Gudang APM)
    |--------------------------------------------------------------------------
    | Pergudangan Panam Bizpark, Jl. Raya Pekanbaru - Bangkinang No B19,
    | Tuah Karya, Kec. Tampan, Kota Pekanbaru, Riau 28293
    */
    'warehouse' => [
        'lat'     => env('WAREHOUSE_LAT', '0.4955'),
        'lng'     => env('WAREHOUSE_LNG', '101.3561'),
        'address' => env('WAREHOUSE_ADDRESS', 'Pergudangan Panam Bizpark, Jl. Raya Pekanbaru - Bangkinang No B19, Tuah Karya, Tampan, Pekanbaru, Riau 28293'),
        'name'    => env('WAREHOUSE_NAME', 'PT Agro Pangan Maju'),
        'phone'   => env('WAREHOUSE_PHONE', '+6276111111'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Kendaraan & Tarif (Mock / Estimasi untuk Development)
    |--------------------------------------------------------------------------
    | Tarif per KM untuk simulasi ongkir saat API key belum tersedia.
    | Kapasitas dalam KG.
    */
    'vehicles' => [
        'MOTORCYCLE' => [
            'label'       => 'Motor',
            'max_weight'  => 20,
            'base_fare'   => 15000,
            'per_km'      => 2500,
            'icon'        => '🏍️',
        ],
        'MPV' => [
            'label'       => 'MPV',
            'max_weight'  => 200,
            'base_fare'   => 35000,
            'per_km'      => 5000,
            'icon'        => '🚗',
        ],
        'VAN' => [
            'label'       => 'Van',
            'max_weight'  => 500,
            'base_fare'   => 60000,
            'per_km'      => 8000,
            'icon'        => '🚐',
        ],
        'TRUCK550' => [
            'label'       => 'Pick-up',
            'max_weight'  => 1000,
            'base_fare'   => 100000,
            'per_km'      => 12000,
            'icon'        => '🛻',
        ],
        'TRUCK' => [
            'label'       => 'Engkel',
            'max_weight'  => 9999,
            'base_fare'   => 200000,
            'per_km'      => 18000,
            'icon'        => '🚛',
        ],
    ],
];
