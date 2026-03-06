<?php

namespace App\Models;

use App\Enums\CpdActivitySource;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CpdActivity extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id', 'cpd_category_id', 'event_id', 'title', 'description',
        'points', 'activity_date', 'source', 'evidence_path', 'is_verified',
        'verified_by', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'source' => CpdActivitySource::class,
            'points' => 'decimal:2',
            'activity_date' => 'date',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnlyDirty();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CpdCategory::class, 'cpd_category_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeForYear($query, int $year)
    {
        return $query->whereYear('activity_date', $year);
    }
}
