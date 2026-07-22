<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'items',
        'subtotal',
        'shipping_cost',
        'shipping_vehicle',
        'shipping_mode',
        'total',
        'recipient_name',
        'recipient_phone',
        'recipient_city',
        'recipient_address',
        'recipient_lat',
        'recipient_lng',
        'notes',
        'payment_method',
        'payment_status',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_snap_token',
        'paid_at',
        'settlement_at',
        'settlement_status',
        'refund_amount',
        'refunded_at',
        'refund_reason',
        'midtrans_status_payload',
        'midtrans_sync_at',
        'status',
        'stock_reserved',
        'courier',
        'tracking_number',
        'shipped_at',
        'user_confirmed',
        'user_confirmed_at',
        'lalamove_quotation_id',
        'lalamove_order_id',
        'lalamove_status',
        'lalamove_driver_name',
        'lalamove_driver_phone',
        'lalamove_share_link',
    ];

    protected $casts = [
        'items'          => 'array',
        'paid_at'        => 'datetime',
        'settlement_at'  => 'datetime',
        'refunded_at'    => 'datetime',
        'midtrans_status_payload' => 'array',
        'midtrans_sync_at' => 'datetime',
        'shipped_at'     => 'datetime',
        'user_confirmed' => 'boolean',
        'user_confirmed_at' => 'datetime',
        'subtotal'       => 'float',
        'total'          => 'float',
        'shipping_cost'  => 'float',
        'refund_amount'  => 'float',
        'recipient_lat'  => 'float',
        'recipient_lng'  => 'float',
        'stock_reserved' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hitung tenggat waktu SLA (2x24 jam kerja dari paid_at / created_at)
     */
    public function getSlaDeadlineAttribute(): ?\Illuminate\Support\Carbon
    {
        $startTime = $this->paid_at ?? $this->created_at;
        if (!$startTime) {
            return null;
        }

        return $startTime->copy()->addHours(48);
    }

    /**
     * Status SLA B2C Order: 'safe', 'warning', 'expired', 'completed', 'cancelled'
     */
    public function getSlaStatusInfoAttribute(): array
    {
        if ($this->status === 'cancelled') {
            return ['status' => 'cancelled', 'label' => 'Dibatalkan', 'badge' => 'bg-gray-100 text-gray-600'];
        }

        if (in_array($this->status, ['shipped', 'delivered'], true)) {
            return ['status' => 'completed', 'label' => 'SLA Terpenuhi', 'badge' => 'bg-emerald-100 text-emerald-800'];
        }

        if ($this->payment_status !== 'paid') {
            return ['status' => 'pending_pay', 'label' => 'Menunggu Bayar', 'badge' => 'bg-amber-100 text-amber-800'];
        }

        $deadline = $this->sla_deadline;
        if (!$deadline) {
            return ['status' => 'unknown', 'label' => 'SLA -', 'badge' => 'bg-gray-100 text-gray-600'];
        }

        $now = now();
        if ($now->greaterThan($deadline)) {
            return ['status' => 'expired', 'label' => 'SLA Terlewat (Auto-Cancel)', 'badge' => 'bg-red-100 text-red-700 border border-red-200 animate-pulse'];
        }

        $diffHours = (int) ceil($now->diffInMinutes($deadline) / 60);
        if ($diffHours <= 6) {
            return ['status' => 'warning', 'label' => "SLA Segera Habis (Sisa {$diffHours} Jam)", 'badge' => 'bg-amber-100 text-amber-800 border border-amber-200'];
        }

        return ['status' => 'safe', 'label' => "SLA Aman (Sisa {$diffHours} Jam)", 'badge' => 'bg-blue-100 text-blue-800 border border-blue-200'];
    }

    /** Generate a unique order ID like APM-20260415-00001 */
    public static function generateOrderId(): string
    {
        $date  = now()->format('Ymd');
        $last  = static::whereDate('created_at', today())->count() + 1;
        $orderId = 'APM-' . $date . '-' . str_pad($last, 5, '0', STR_PAD_LEFT);
        if (app()->environment('testing')) {
            $orderId .= '-' . rand(10000, 99999);
        }
        return $orderId;
    }
}
