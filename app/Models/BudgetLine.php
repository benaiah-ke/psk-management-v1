<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetLine extends Model
{
    protected $fillable = ['budget_id', 'category', 'description', 'budgeted_amount', 'actual_amount', 'variance'];

    protected function casts(): array
    {
        return ['budgeted_amount' => 'decimal:2', 'actual_amount' => 'decimal:2', 'variance' => 'decimal:2'];
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
