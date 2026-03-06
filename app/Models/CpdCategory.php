<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CpdCategory extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug', 'description', 'max_points_per_year', 'is_active'];

    protected function casts(): array
    {
        return ['max_points_per_year' => 'integer', 'is_active' => 'boolean'];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function activities()
    {
        return $this->hasMany(CpdActivity::class);
    }
}
