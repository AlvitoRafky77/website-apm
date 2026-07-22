<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class B2bOrder extends Model
{
    protected $table = 'b2b_orders';

    protected $fillable = [
        'order_number', 'user_id', 'b2b_registration_id',
        'items', 'subtotal', 'shipping_cost', 'total',

        // DP
        'dp_amount', 'dp_proof_path', 'dp_bank_name', 'dp_transfer_reference', 'dp_uploaded_at',
        'dp_verified_at', 'dp_verified_by', 'dp_admin_notes',

        // Settlement
        'settlement_amount', 'settlement_proof_path', 'settlement_bank_name', 'settlement_transfer_reference', 'settlement_uploaded_at',
        'settlement_verified_at', 'settlement_verified_by', 'settlement_admin_notes',

        // Pengiriman
        'recipient_name', 'recipient_phone', 'recipient_address', 'recipient_city',
        'delivery_date', 'driver_name', 'driver_phone', 'waybill_number', 'delivery_notes',

        // Inspeksi Barang
        'inspection_status', 'inspection_notes', 'inspection_images',
        'inspected_at', 'arrived_at',

        // SPB
        'spb_path', 'spb_uploaded_at', 'received_at',

        // Dispute
        'dispute_status', 'dispute_reason', 'dispute_images',
        'dispute_at', 'dispute_admin_notes', 'dispute_resolved_at', 'dispute_resolved_by',

        // Potongan Kerusakan
        'damage_deduction', 'damage_notes', 'damage_set_by', 'damage_set_at',

        // Lainnya
        'status', 'cancel_reason', 'notes',
    ];

    protected $casts = [
        'items'                 => 'array',
        'inspection_images'     => 'array',
        'dispute_images'        => 'array',
        'subtotal'              => 'float',
        'shipping_cost'         => 'float',
        'total'                 => 'float',
        'dp_amount'             => 'float',
        'settlement_amount'     => 'float',
        'damage_deduction'      => 'float',
        'damage_set_at'         => 'datetime',
        'dp_uploaded_at'        => 'datetime',
        'dp_verified_at'        => 'datetime',
        'settlement_uploaded_at'=> 'datetime',
        'settlement_verified_at' => 'datetime',
        'delivery_date'         => 'date',
        'arrived_at'            => 'datetime',
        'inspected_at'          => 'datetime',
        'spb_uploaded_at'       => 'datetime',
        'received_at'           => 'datetime',
        'dispute_at'            => 'datetime',
        'dispute_resolved_at'   => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function b2bRegistration(): BelongsTo
    {
        return $this->belongsTo(B2bRegistration::class);
    }

    public function dpVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dp_verified_by');
    }

    public function settlementVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'settlement_verified_by');
    }

    // ─── Status Helpers ───────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_review'       => 'Menunggu Review Admin',
            'waiting_dp'           => 'Menunggu DP (50%)',
            'dp_uploaded'          => 'DP Menunggu Verifikasi',
            'dp_verified'          => 'DP Terverifikasi — Siapkan Armada',
            'in_transit'           => 'Dalam Pengiriman',
            'arrived'              => 'Barang Tiba — Inspeksi',
            'goods_accepted'       => $this->inspection_status === 'damaged'
                                        ? 'Barang Diterima — Ada Kerusakan'
                                        : 'Barang Diterima Baik',
            'settlement_uploaded'  => 'Pelunasan Menunggu Verifikasi',
            'settlement_verified'  => 'Pembayaran Lunas',
            'completed'            => 'Pesanan Selesai',
            'cancelled'            => 'Dibatalkan',
            default                => ucfirst((string) $this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending_review'       => 'orange',
            'waiting_dp'           => 'amber',
            'dp_uploaded'          => 'yellow',
            'dp_verified'          => 'teal',
            'in_transit'           => 'blue',
            'arrived'              => 'indigo',
            'goods_accepted'       => $this->inspection_status === 'damaged' ? 'orange' : 'teal',
            'settlement_uploaded'  => 'purple',
            'settlement_verified'  => 'teal',
            'completed'            => 'green',
            'cancelled'            => 'red',
            default                => 'gray',
        };
    }

    public function getDisputeStatusLabelAttribute(): string
    {
        return match ($this->dispute_status) {
            'none'       => 'Tidak Ada',
            'open'       => 'Komplain Dibuka',
            'reviewing'  => 'Sedang Ditinjau',
            'resolved'   => 'Diselesaikan',
            'rejected'   => 'Ditolak',
            default      => '-',
        };
    }

    /**
     * Hitung indikator status SLA B2B (2-3 hari kerja dari DP terverifikasi)
     */
    public function getSlaStatusInfoAttribute(): array
    {
        if (!$this->dp_verified_at || !in_array($this->status, ['dp_verified', 'in_transit'], true)) {
            return ['badge' => 'bg-gray-100 text-gray-600', 'label' => '—'];
        }

        $deadline = $this->dp_verified_at->copy()->addHours(72); // 3 hari kerja
        $remainingHours = (int) now()->diffInHours($deadline, false);

        if ($remainingHours < 0) {
            return ['badge' => 'bg-red-100 text-red-700 border border-red-200 font-bold animate-pulse', 'label' => '⏱️ SLA 3 Hari Terlewat!'];
        }

        if ($remainingHours <= 24) {
            return ['badge' => 'bg-amber-100 text-amber-800 border border-amber-200 font-bold', 'label' => '⏱️ SLA Sisa ' . ceil($remainingHours) . ' Jam'];
        }

        return ['badge' => 'bg-blue-50 text-blue-700 border border-blue-200', 'label' => '⏱️ SLA Armada Aman'];
    }

    public function canUploadDp(): bool
    {
        return $this->status === 'waiting_dp';
    }

    /** Mitra bisa upload pelunasan setelah barang diterima baik **/
    public function canUploadSettlement(): bool
    {
        return $this->status === 'goods_accepted';
    }

    /** Mitra bisa inspeksi barang saat status arrived **/
    public function canInspect(): bool
    {
        return $this->status === 'arrived';
    }

    /** Admin upload SPB setelah pelunasan terverifikasi **/
    public function canUploadSpb(): bool
    {
        return in_array($this->status, ['settlement_verified'], true);
    }

    /** Pelunasan setelah dikurangi potongan kerusakan **/
    public function getAdjustedSettlementAttribute(): float
    {
        return max(0, $this->settlement_amount - ($this->damage_deduction ?? 0));
    }

    /** Admin bisa set potongan kerusakan saat inspeksi menunjukkan kerusakan **/
    public function canSetDamageDeduction(): bool
    {
        return $this->status === 'goods_accepted'
            && $this->inspection_status === 'damaged'
            && $this->damage_deduction == 0;
    }

    public function canDispute(): bool
    {
        // Komplain bisa diajukan setelah pesanan selesai
        return $this->status === 'completed'
            && $this->dispute_status === 'none';
    }

    public function hasActiveDispute(): bool
    {
        return in_array($this->dispute_status, ['open', 'reviewing']);
    }

    // ─── Generate Order Number ─────────────────────────────────────
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'B2B-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }
}
