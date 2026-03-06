<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSurveyResponse extends Model
{
    protected $fillable = ['event_survey_id', 'user_id', 'answers'];

    protected function casts(): array
    {
        return ['answers' => 'array'];
    }

    public function survey()
    {
        return $this->belongsTo(EventSurvey::class, 'event_survey_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
