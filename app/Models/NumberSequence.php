<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumberSequence extends Model
{
    protected $fillable = ['type', 'prefix', 'next_number', 'padding', 'format'];

    public function generateNext(): string
    {
        $number = str_pad($this->next_number, $this->padding, '0', STR_PAD_LEFT);
        $year = date('Y');

        $formatted = $this->format
            ? str_replace(['{prefix}', '{year}', '{number}'], [$this->prefix, $year, $number], $this->format)
            : "{$this->prefix}-{$year}-{$number}";

        $this->increment('next_number');

        return $formatted;
    }
}
