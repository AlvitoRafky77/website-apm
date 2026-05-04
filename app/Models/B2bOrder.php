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

        // SPB
        'spb_path', 'spb_uploaded_at', 'received_at',

        // Dispute
        'dispute_status', 'dispute_reason', 'dispute_images',
        'dispute_at', 'dispute_admin_notes', 'dispute_resolved_at', 'dispute_resolved_by',

        // Lainnya
        'status', 'cancel_reason', 'notes',
    ];

    protected $casts = [
        'items'                 => 'array',
        'dispute_images'        => 'array',
        'subtotal'              => 'float',
        'shipping_cost'         => 'float',
        'total'                 => 'float',
        'dp_amount'             => 'float',
        'settlement_amount'     => 'float',
        'dp_uploaded_at'        => 'datetime',
        'dp_verified_at'        => 'datetime',
        'settlement_uploaded_at'=> 'datetime',
        'settlement_verified_at' => 'datetime',
        'delivery_date'         => 'date',
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
            'settlement_uploaded'   => 'Pelunasan Menunggu Verifikasi',
            'settlement_verified'   => 'Pembayaran Lunas',
            'delivered'            => 'Barang Diterima',
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
            'settlement_uploaded'  => 'purple',
            'settlement_verified'  => 'teal',
            'delivered'            => 'indigo',
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

    public function canUploadDp(): bool
    {
        return $this->status === 'waiting_dp';
    }

    /** Mitra bisa upload pelunasan ketika barang sudah dalam perjalanan **/
    public function canUploadSettlement(): bool
    {
        return $this->status === 'in_transit';
    }

    /** Admin upload SPB setelah barang diterima **/
    public function canUploadSpb(): bool
    {
        return in_array($this->status, ['settlement_verified', 'delivered'], true);
    }

    public function canConfirmReceived(): bool
    {
        return $this->status === 'settlement_verified';
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
