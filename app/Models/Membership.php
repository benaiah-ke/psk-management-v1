<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Membership extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id', 'membership_tier_id', 'membership_number', 'status',
        'application_date', 'approval_date', 'approved_by', 'expiry_date',
        'renewal_date', 'suspension_reason', 'suspension_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => MembershipStatus::class,
            'application_date' => 'date',
            'approval_date' => 'date',
            'expiry_date' => 'date',
            'renewal_date' => 'date',
            'suspension_date' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['status', 'expiry_date']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tier()
    {
        return $this->belongsTo(MembershipTier::class, 'membership_tier_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function renewals()
    {
        return $this->hasMany(MembershipRenewal::class);
    }

    public function certificates()
    {
        return $this->hasMany(MembershipCertificate::class);
    }

    public function isActive(): bool
    {
        return $this->status === MembershipStatus::Active;
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expiry_date && $this->expiry_date->isBetween(now(), now()->addDays($days));
    }

    public function scopeActive($query)
    {
        return $query->where('status', MembershipStatus::Active);
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('status', MembershipStatus::Active)
            ->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }
}
