<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Branch extends Model
{
    use HasSlug;

    protected $fillable = [
        'name', 'slug', 'county', 'region', 'description',
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

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'branch_members')
            ->withPivot('role', 'joined_at', 'left_at')
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
