<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LalamoveService
{
    private string $apiKey;
    private string $apiSecret;
    private string $baseUrl;
    private string $market;
    private bool   $isSandbox;

    public function __construct()
    {
        $this->apiKey    = config('lalamove.api_key', '');
        $this->apiSecret = config('lalamove.api_secret', '');
        $this->baseUrl   = config('lalamove.base_url', 'https://rest.sandbox.lalamove.com');
        $this->market    = config('lalamove.market', 'ID');
        $this->isSandbox = config('lalamove.is_sandbox', true);
    }

    /* ─────────────────────────────────────────────────────────
     | PUBLIC: Determine vehicle based on total weight
     ───────────────────────────────────────────────────────── */
    public function determineVehicle(float $totalWeightKg): string
    {
        $vehicles = config('lalamove.vehicles');
        foreach ($vehicles as $type => $info) {
            if ($totalWeightKg <= $info['max_weight']) {
                return $type;
            }
        }
        return 'TRUCK';
    }

    /* ─────────────────────────────────────────────────────────
     | PUBLIC: Get shipping quotation
     ───────────────────────────────────────────────────────── */
    public function getQuotation(
        float  $destLat,
        float  $destLng,
        float  $totalWeightKg,
        string $destAddress = ''
    ): array {
        $vehicleType = $this->determineVehicle($totalWeightKg);

        // If no API key, use mock calculation
        if (empty($this->apiKey) || $this->isSandbox) {
            return $this->mockQuotation($destLat, $destLng, $vehicleType);
        }

        // Real Lalamove API call
        return $this->realQuotation($destLat, $destLng, $vehicleType, $destAddress);
    }

    /* ─────────────────────────────────────────────────────────
     | PUBLIC: Place a Lalamove order (dispatch driver)
     ───────────────────────────────────────────────────────── */
    public function placeOrder(
        string $quotationId,
        string $recipientName,
        string $recipientPhone,
        string $recipientAddress,
        float  $destLat,
        float  $destLng
    ): array {
        if (empty($this->apiKey) || $this->isSandbox) {
            return $this->mockPlaceOrder($recipientName, $recipientPhone);
        }

        return $this->realPlaceOrder(
            $quotationId, $recipientName, $recipientPhone, $recipientAddress, $destLat, $destLng
        );
    }

    /* ─────────────────────────────────────────────────────────
     | PUBLIC: Get order status from Lalamove
     ───────────────────────────────────────────────────────── */
    public function getOrderStatus(string $lalamoveOrderId): array
    {
        if (empty($this->apiKey) || $this->isSandbox) {
            return ['status' => 'PICKING_UP', 'driver' => null];
        }

        try {
            $path     = "/v3/orders/{$lalamoveOrderId}";
            $response = $this->makeRequest('GET', $path);
            $data     = $response['data'] ?? [];

            return [
                'status'       => $data['status'] ?? 'UNKNOWN',
                'driver'       => $data['driverInfo'] ?? null,
                'share_link'   => $data['shareLink'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Lalamove getOrderStatus error: ' . $e->getMessage());
            return ['status' => 'UNKNOWN', 'driver' => null];
        }
    }

    /* ─────────────────────────────────────────────────────────
     | PRIVATE: Mock quotation (no API key needed)
     ───────────────────────────────────────────────────────── */
    private function mockQuotation(float $destLat, float $destLng, string $vehicleType): array
    {
        $warehouse  = config('lalamove.warehouse');
        $vehicles   = config('lalamove.vehicles');
        $vehicle    = $vehicles[$vehicleType];

        // Haversine formula to estimate distance in KM
        $distanceKm = $this->calculateDistance(
            (float) $warehouse['lat'],
            (float) $warehouse['lng'],
            $destLat,
            $destLng
        );

        $distanceKm = max(1, $distanceKm); // minimum 1 KM
        $price      = $vehicle['base_fare'] + ($distanceKm * $vehicle['per_km']);
        $price      = (int) ceil($price / 1000) * 1000; // round to nearest 1000

        $estimateMinutes = (int) ceil($distanceKm * 2.5 + 10); // rough estimate

        return [
            'success'        => true,
            'quotation_id'   => 'MOCK-' . strtoupper(uniqid()),
            'vehicle_type'   => $vehicleType,
            'vehicle_label'  => $vehicle['label'],
            'vehicle_icon'   => $vehicle['icon'],
            'price'          => $price,
            'currency'       => 'IDR',
            'distance_km'    => round($distanceKm, 1),
            'estimate_mins'  => $estimateMinutes,
            'is_mock'        => true,
        ];
    }

    /* ─────────────────────────────────────────────────────────
     | PRIVATE: Mock place order (sandbox simulation)
     ───────────────────────────────────────────────────────── */
    private function mockPlaceOrder(string $recipientName, string $recipientPhone): array
    {
        return [
            'success'      => true,
            'order_id'     => 'MOCK-ORD-' . strtoupper(uniqid()),
            'status'       => 'ASSIGNING_DRIVER',
            'driver_name'  => null,
            'driver_phone' => null,
            'share_link'   => null,
            'is_mock'      => true,
        ];
    }

    /* ─────────────────────────────────────────────────────────
     | PRIVATE: Real Lalamove quotation API call
     ───────────────────────────────────────────────────────── */
    private function realQuotation(
        float  $destLat,
        float  $destLng,
        string $vehicleType,
        string $destAddress
    ): array {
        $warehouse = config('lalamove.warehouse');

        $body = json_encode([
            'data' => [
                'serviceType' => $vehicleType,
                'language'    => 'id_ID',
                'stops'       => [
                    [
                        'coordinates' => [
                            'lat' => (string) $warehouse['lat'],
                            'lng' => (string) $warehouse['lng'],
                        ],
                        'address' => $warehouse['address'],
                    ],
                    [
                        'coordinates' => [
                            'lat' => (string) $destLat,
                            'lng' => (string) $destLng,
                        ],
                        'address' => $destAddress,
                    ],
                ],
                'item' => [
                    'quantity'    => '1',
                    'weight'      => 'LESS_THAN_3KG',
                    'handlingInstructions' => [],
                ],
            ],
        ]);

        try {
            $path     = '/v3/quotations';
            $response = $this->makeRequest('POST', $path, $body);
            $data     = $response['data'] ?? [];

            $price   = (int) ($data['priceBreakdown']['total'] ?? 0);
            $vehicles = config('lalamove.vehicles');

            return [
                'success'        => true,
                'quotation_id'   => $data['quotationId'] ?? '',
                'vehicle_type'   => $vehicleType,
                'vehicle_label'  => $vehicles[$vehicleType]['label'] ?? $vehicleType,
                'vehicle_icon'   => $vehicles[$vehicleType]['icon'] ?? '🚗',
                'price'          => $price,
                'currency'       => $data['currency'] ?? 'IDR',
                'distance_km'    => round(($data['distance']['value'] ?? 0) / 1000, 1),
                'estimate_mins'  => null,
                'is_mock'        => false,
            ];
        } catch (\Exception $e) {
            Log::error('Lalamove quotation error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal menghitung ongkir. Coba lagi.'];
        }
    }

    /* ─────────────────────────────────────────────────────────
     | PRIVATE: Real Lalamove place order API call
     ───────────────────────────────────────────────────────── */
    private function realPlaceOrder(
        string $quotationId,
        string $recipientName,
        string $recipientPhone,
        string $recipientAddress,
        float  $destLat,
        float  $destLng
    ): array {
        $warehouse = config('lalamove.warehouse');

        $body = json_encode([
            'data' => [
                'quotationId' => $quotationId,
                'sender' => [
                    'stopId' => '1',
                    'name'   => $warehouse['name'],
                    'phone'  => $warehouse['phone'],
                ],
                'recipients' => [
                    [
                        'stopId' => '2',
                        'name'   => $recipientName,
                        'phone'  => '+62' . ltrim($recipientPhone, '0'),
                    ],
                ],
                'isPurchaseOrderNumber' => false,
            ],
        ]);

        try {
            $path     = '/v3/orders';
            $response = $this->makeRequest('POST', $path, $body);
            $data     = $response['data'] ?? [];

            return [
                'success'      => true,
                'order_id'     => $data['orderId'] ?? '',
                'status'       => $data['status'] ?? 'ASSIGNING_DRIVER',
                'driver_name'  => null,
                'driver_phone' => null,
                'share_link'   => $data['shareLink'] ?? null,
                'is_mock'      => false,
            ];
        } catch (\Exception $e) {
            Log::error('Lalamove placeOrder error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal memanggil driver Lalamove.'];
        }
    }

    /* ─────────────────────────────────────────────────────────
     | PRIVATE: Build authorized request to Lalamove API
     ───────────────────────────────────────────────────────── */
    private function makeRequest(string $method, string $path, ?string $body = null): array
    {
        $timestamp = (int) (microtime(true) * 1000);
        $bodyStr   = $body ?? '';
        $signature = $this->generateSignature($timestamp, $method, $path, $bodyStr);

        $headers = [
            'Authorization' => "hmac {$this->apiKey}:{$timestamp}:{$signature}",
            'Content-Type'  => 'application/json',
            'Market'        => $this->market,
            'Request-ID'    => uniqid('apm-', true),
        ];

        $response = Http::withHeaders($headers)
            ->{strtolower($method)}($this->baseUrl . $path, $bodyStr ? json_decode($bodyStr, true) : []);

        if (!$response->successful()) {
            throw new \Exception("Lalamove API error {$response->status()}: " . $response->body());
        }

        return $response->json();
    }

    /* ─────────────────────────────────────────────────────────
     | PRIVATE: Generate HMAC-SHA256 signature
     ───────────────────────────────────────────────────────── */
    private function generateSignature(int $timestamp, string $method, string $path, string $body): string
    {
        $rawSignature = "{$timestamp}\r\n{$method}\r\n{$path}\r\n\r\n{$body}";
        return hash_hmac('sha256', $rawSignature, $this->apiSecret);
    }

    /* ─────────────────────────────────────────────────────────
     | PRIVATE: Haversine formula — distance in KM
     ───────────────────────────────────────────────────────── */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
