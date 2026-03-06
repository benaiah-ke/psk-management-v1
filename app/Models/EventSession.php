<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSession extends Model
{
    protected $fillable = [
        'event_id', 'title', 'description', 'speaker',
        'start_time', 'end_time', 'venue', 'cpd_points', 'max_attendees', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['start_time' => 'datetime', 'end_time' => 'datetime'];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
