<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSurveyResponse extends Model
{
    protected $fillable = ['event_survey_id', 'user_id', 'responses', 'submitted_at'];

    protected function casts(): array
    {
        return [
            'responses' => 'array',
            'submitted_at' => 'datetime',
        ];
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
