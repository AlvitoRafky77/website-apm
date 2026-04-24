<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class B2bRegistration extends Model
{
    use HasFactory;

    protected $table = 'b2b_registrations';

    protected $fillable = [
        'company_name',
        'company_type',
        'npwp',
        'company_address',
        'company_city',
        'company_province',
        'company_postal_code',
        'pic_name',
        'pic_position',
        'email',
        'phone',
        'password',
        'company_profile_path',
        'siup_path',
        'akta_path',
        'status',
        'admin_notes',
        'approved_at',
        'approved_by',
        'user_id',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get completeness percentage of the registration data.
     */
    public function getCompletenessAttribute(): int
    {
        $fields = [
            'company_name', 'company_type', 'npwp', 'company_address',
            'company_city', 'pic_name', 'pic_position', 'email', 'phone',
            'company_profile_path', 'siup_path',
        ];

        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        return (int)(($filled / count($fields)) * 100);
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => 'Tidak Diketahui',
        };
    }

    /**
     * Get status badge color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'amber',
            'approved' => 'green',
            'rejected' => 'red',
            default    => 'gray',
        };
    }

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }
}
