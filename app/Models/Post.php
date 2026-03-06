<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'slug', 'body', 'type', 'status',
        'branch_id', 'committee_id', 'is_pinned', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
            'is_pinned' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::Published);
    }
}
