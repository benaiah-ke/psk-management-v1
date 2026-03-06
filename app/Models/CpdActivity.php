<?php

namespace App\Models;

use App\Enums\CpdActivitySource;
use App\Enums\CpdActivityStatus;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CpdActivity extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id', 'cpd_category_id', 'event_id', 'title', 'description',
        'points', 'activity_date', 'source', 'status', 'evidence_file_path',
        'approved_by', 'approved_at', 'rejection_reason', 'period_year',
    ];

    protected function casts(): array
    {
        return [
            'source' => CpdActivitySource::class,
            'status' => CpdActivityStatus::class,
            'points' => 'integer',
            'activity_date' => 'date',
            'approved_at' => 'datetime',
            'period_year' => 'integer',
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

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', CpdActivityStatus::Approved);
    }

    public function scopeForYear($query, int $year)
    {
        return $query->whereYear('activity_date', $year);
    }
}
