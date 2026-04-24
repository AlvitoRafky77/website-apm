<?php

return [
    'api_key'  => env('RAJAONGKIR_API_KEY', ''),
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter'),

    // Radius dalam KM: jika jarak customer ke gudang <= nilai ini, pakai Lalamove
    // Jika lebih, pakai RajaOngkir (JNE/J&T/dll)
    'lalamove_radius_km' => env('LALAMOVE_RADIUS_KM', 70),
];
