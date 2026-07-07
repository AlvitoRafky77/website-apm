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
