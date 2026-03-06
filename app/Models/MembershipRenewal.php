<?php

namespace App\Models;

use App\Enums\RenewalStatus;
use Illuminate\Database\Eloquent\Model;

class MembershipRenewal extends Model
{
    protected $fillable = [
        'membership_id', 'period_start', 'period_end', 'amount',
        'status', 'invoice_id', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => RenewalStatus::class,
            'period_start' => 'date',
            'period_end' => 'date',
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
