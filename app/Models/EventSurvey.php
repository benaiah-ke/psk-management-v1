<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSurvey extends Model
{
    protected $fillable = ['event_id', 'title', 'description', 'questions', 'is_active'];

    protected function casts(): array
    {
        return ['questions' => 'array', 'is_active' => 'boolean'];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function responses()
    {
        return $this->hasMany(EventSurveyResponse::class);
    }
}
