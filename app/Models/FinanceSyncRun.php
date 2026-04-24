<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceSyncRun extends Model
{
    protected $fillable = [
        'started_at',
        'finished_at',
        'status',
        'total_orders',
        'synced_orders',
        'failed_orders',
        'notes',
        'context',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'context' => 'array',
    ];
}
