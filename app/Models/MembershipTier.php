<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class MembershipTier extends Model
{
    use HasSlug;

    protected $fillable = [
        'name', 'slug', 'description', 'annual_fee', 'registration_fee',
        'requirements', 'benefits', 'cpd_points_required', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'annual_fee' => 'decimal:2',
            'registration_fee' => 'decimal:2',
            'requirements' => 'array',
            'benefits' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function activeMemberships()
    {
        return $this->memberships()->where('status', 'active');
    }
}
