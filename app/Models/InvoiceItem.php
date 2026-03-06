<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'description', 'quantity', 'unit_price',
        'tax_rate', 'tax_amount', 'discount_amount', 'total',
        'invoiceable_type', 'invoiceable_id',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2', 'unit_price' => 'decimal:2',
            'tax_rate' => 'decimal:2', 'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2', 'total' => 'decimal:2',
        ];
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceable()
    {
        return $this->morphTo();
    }
}
