<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'user_id', 'cost_center_id', 'type', 'status',
        'subtotal', 'tax_amount', 'discount_amount', 'total_amount',
        'amount_paid', 'balance_due', 'currency', 'due_date', 'paid_at',
        'notes', 'file_path',
        'etims_cu_serial', 'etims_cu_invoice_no', 'etims_receipt_no',
        'etims_qr_code', 'etims_submitted_at', 'etims_status',
    ];

    protected function casts(): array
    {
        return [
            'status' => InvoiceStatus::class,
            'type' => InvoiceType::class,
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'balance_due' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
            'etims_submitted_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items->sum(fn($item) => $item->quantity * $item->unit_price);
        $this->tax_amount = $this->items->sum('tax_amount');
        $this->discount_amount = $this->items->sum('discount_amount');
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->amount_paid = $this->payments()->where('status', 'completed')->sum('amount');
        $this->balance_due = $this->total_amount - $this->amount_paid;
        $this->save();
    }

    public function isPaid(): bool
    {
        return $this->status === InvoiceStatus::Paid;
    }

    public function scopeUnpaid($query)
    {
        return $query->whereNotIn('status', [InvoiceStatus::Paid, InvoiceStatus::Cancelled, InvoiceStatus::Void]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', [InvoiceStatus::Paid, InvoiceStatus::Cancelled, InvoiceStatus::Void]);
    }
}
