<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicketType extends Model
{
    protected $fillable = [
        'event_id', 'name', 'description', 'price', 'quantity',
        'sold_count', 'sale_starts', 'sale_ends', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2', 'is_active' => 'boolean',
            'sale_starts' => 'datetime', 'sale_ends' => 'datetime',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class, 'ticket_type_id');
    }

    public function isAvailable(): bool
    {
        if (!$this->is_active) return false;
        if ($this->quantity && $this->sold_count >= $this->quantity) return false;
        return true;
    }

    public function getRemainingAttribute(): ?int
    {
        return $this->quantity ? $this->quantity - $this->sold_count : null;
    }
}
