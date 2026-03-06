<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Model;

class MembershipApplication extends Model
{
    protected $fillable = [
        'user_id', 'membership_tier_id', 'status', 'submitted_at',
        'reviewed_by', 'reviewed_at', 'review_notes', 'rejection_reason', 'form_data',
    ];

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'form_data' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tier()
    {
        return $this->belongsTo(MembershipTier::class, 'membership_tier_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents()
    {
        return $this->hasMany(MembershipApplicationDocument::class);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [ApplicationStatus::Submitted, ApplicationStatus::UnderReview]);
    }
}
