<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    private string $apiKey;
    private string $baseUrl;

    // Pekanbaru destination ID di Komerce RajaOngkir
    // GET /destination/domestic-destination?search=Pekanbaru → ambil ID nya
    private string $originId = 'pekanbaru'; // akan di-resolve saat runtime

    public function __construct()
    {
        $this->apiKey  = config('rajaongkir.api_key', '');
        $this->baseUrl = rtrim(config('rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');
    }

    /**
     * Cari destination ID berdasarkan nama kota
     * Komerce endpoint: GET /destination/domestic-destination?search=<query>
     */
    public function findDestinationId(string $cityName): ?string
    {
        if (!$cityName) return null;

        $cacheKey = 'komerce_dest_' . md5(strtolower(trim($cityName)));

        return Cache::remember($cacheKey, 86400, function () use ($cityName) {
            try {
                $res = Http::withHeaders(['key' => $this->apiKey])
                    ->get("{$this->baseUrl}/destination/domestic-destination", [
                        'search' => $cityName,
                    ]);

                if (!$res->successful()) {
                    Log::warning('Komerce findDestination failed: ' . $res->status() . ' - ' . $res->body());
                    return null;
                }

                $data = $res->json();
                // Response: { data: [ { id, label, ... } ] }
                $results = $data['data'] ?? [];

                if (empty($results)) return null;

                // Ambil hasil pertama yang paling relevan
                return (string) ($results[0]['id'] ?? null);

            } catch (\Exception $e) {
                Log::error('Komerce findDestinationId error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Cari origin ID untuk Pekanbaru (di-cache selamanya)
     */
    public function getPekanbaruOriginId(): string
    {
        return Cache::rememberForever('komerce_origin_pekanbaru', function () {
            try {
                $res = Http::withHeaders(['key' => $this->apiKey])
                    ->get("{$this->baseUrl}/destination/domestic-destination", [
                        'search' => 'Pekanbaru',
                    ]);

                if (!$res->successful()) return 'pekanbaru';

                $results = $res->json('data', []);
                // Cari yang exact match kota Pekanbaru
                foreach ($results as $r) {
                    $label = strtolower($r['label'] ?? '');
                    if (str_contains($label, 'pekanbaru') && !str_contains($label, 'kab')) {
                        return (string) $r['id'];
                    }
                }

                return (string) ($results[0]['id'] ?? 'pekanbaru');
            } catch (\Exception $e) {
                Log::error('Komerce origin lookup error: ' . $e->getMessage());
                return 'pekanbaru';
            }
        });
    }

    /**
     * Hitung ongkir ke kota tujuan
     * Komerce endpoint: POST /calculate/domestic-cost
     */
    public function getShippingCost(string $destinationId, float $weightKg): array
    {
        $weightGram = max(1000, (int) ceil($weightKg * 1000));
        $originId   = $this->getPekanbaruOriginId();

        // Cache 1 jam per tujuan+berat — setelah pertama kali, langsung instan
        $cacheKey = "komerce_cost_{$destinationId}_{$weightGram}";
        return Cache::remember($cacheKey, 3600, function () use ($originId, $destinationId, $weightGram) {

            // Hanya 3 kurir paling populer untuk performa maksimal
            $couriers = ['jne', 'sicepat', 'jnt'];

            $responses = Http::pool(function ($pool) use ($couriers, $originId, $destinationId, $weightGram) {
                foreach ($couriers as $courier) {
                    $pool->as($courier)
                        ->timeout(8)
                        ->withHeaders(['key' => $this->apiKey])
                        ->asForm()
                        ->post("{$this->baseUrl}/calculate/domestic-cost", [
                            'origin'      => $originId,
                            'destination' => $destinationId,
                            'weight'      => $weightGram,
                            'courier'     => $courier,
                        ]);
                }
            });

            $results  = [];
            $excluded = ['T15', 'T25', 'T60', 'JTR<130', 'JTR>130', 'JTR>200', 'SDS', 'SPS', 'TRC'];

            foreach ($couriers as $courier) {
                try {
                    $res = $responses[$courier];
                    if (!($res instanceof \Illuminate\Http\Client\Response) || !$res->successful()) continue;

                    $services = $res->json('data', []);
                    foreach ($services as $svc) {
                        $price   = (int) ($svc['cost'] ?? $svc['price'] ?? 0);
                        $service = $svc['service'] ?? '';
                        if ($price <= 0 || in_array($service, $excluded)) continue;

                        $etd = $svc['etd'] ?? '?';
                        $courierName = $svc['name'] ?? strtoupper($courier);

                        $results[] = [
                            'courier'      => strtoupper($svc['code'] ?? $courier),
                            'courier_name' => $courierName,
                            'service'      => $service,
                            'description'  => $svc['description'] ?? '',
                            'price'        => $price,
                            'etd'          => $etd . (str_contains($etd, 'day') ? '' : ' hari'),
                            'label'        => $courierName . ' ' . $service,
                        ];
                    }
                } catch (\Exception $e) {
                    Log::warning("Komerce [{$courier}]: " . $e->getMessage());
                }
            }

            usort($results, fn($a, $b) => $a['price'] <=> $b['price']);
            return array_slice($results, 0, 6);
        });
    }
}
