<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Committee extends Model
{
    use HasSlug;

    protected $fillable = [
        'name', 'slug', 'type', 'description', 'parent_id',
        'cost_center_id', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function parent()
    {
        return $this->belongsTo(Committee::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Committee::class, 'parent_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'committee_members')
            ->withPivot('role', 'joined_at', 'left_at', 'term_starts_at', 'term_ends_at')
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
