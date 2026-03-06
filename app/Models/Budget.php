<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'cost_center_id', 'fiscal_year', 'name', 'total_amount',
        'status', 'approved_by', 'approved_at', 'notes',
    ];

    protected function casts(): array
    {
        return ['total_amount' => 'decimal:2', 'approved_at' => 'datetime'];
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function lines()
    {
        return $this->hasMany(BudgetLine::class);
    }

    public function getTotalBudgetedAttribute(): float
    {
        return $this->lines->sum('budgeted_amount');
    }

    public function getTotalActualAttribute(): float
    {
        return $this->lines->sum('actual_amount');
    }
}
