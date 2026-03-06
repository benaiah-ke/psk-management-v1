<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\EventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'title', 'slug', 'description', 'short_description', 'type', 'status',
        'venue_name', 'venue_address', 'is_virtual', 'virtual_link',
        'start_date', 'end_date', 'registration_opens', 'registration_closes',
        'max_attendees', 'cost_center_id', 'cpd_points', 'featured_image_path',
        'created_by', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => EventStatus::class, 'type' => EventType::class,
            'is_virtual' => 'boolean', 'start_date' => 'datetime', 'end_date' => 'datetime',
            'registration_opens' => 'datetime', 'registration_closes' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(EventTicketType::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function sessions()
    {
        return $this->hasMany(EventSession::class)->orderBy('sort_order');
    }

    public function sponsors()
    {
        return $this->hasMany(EventSponsor::class);
    }

    public function surveys()
    {
        return $this->hasMany(EventSurvey::class);
    }

    public function isRegistrationOpen(): bool
    {
        if (!in_array($this->status, [EventStatus::Published, EventStatus::RegistrationOpen])) return false;
        $now = now();
        if ($this->registration_opens && $now->lt($this->registration_opens)) return false;
        if ($this->registration_closes && $now->gt($this->registration_closes)) return false;
        return true;
    }

    public function isFull(): bool
    {
        if (!$this->max_attendees) return false;
        return $this->registrations()->whereNotIn('status', ['cancelled'])->count() >= $this->max_attendees;
    }

    public function getRegisteredCountAttribute(): int
    {
        return $this->registrations()->whereNotIn('status', ['cancelled'])->count();
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())->orderBy('start_date');
    }
}
