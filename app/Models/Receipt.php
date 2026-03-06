<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'receipt_number', 'payment_id', 'invoice_id', 'user_id',
        'amount', 'issued_date', 'file_path',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'issued_date' => 'date'];
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
