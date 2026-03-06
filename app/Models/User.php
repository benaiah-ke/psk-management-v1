<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, LogsActivity;

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'email', 'phone',
        'ppb_registration_no', 'national_id', 'date_of_birth', 'gender',
        'avatar_path', 'password', 'is_active', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'date_of_birth' => 'date',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['first_name', 'last_name', 'email', 'phone', 'is_active']);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function membership()
    {
        return $this->hasOne(Membership::class)->latest();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function membershipApplications()
    {
        return $this->hasMany(MembershipApplication::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function cpdActivities()
    {
        return $this->hasMany(CpdActivity::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_members')
            ->withPivot('role', 'joined_at', 'is_active')
            ->withTimestamps();
    }

    public function committees()
    {
        return $this->belongsToMany(Committee::class, 'committee_members')
            ->withPivot('role', 'appointed_at', 'term_ends_at', 'is_active')
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function hasActiveMembership(): bool
    {
        return $this->membership && $this->membership->status->value === 'active';
    }

    public function getCpdPointsForYear(int $year): int
    {
        return (int) $this->cpdActivities()
            ->where('period_year', $year)
            ->where('status', 'approved')
            ->sum('points');
    }
}
