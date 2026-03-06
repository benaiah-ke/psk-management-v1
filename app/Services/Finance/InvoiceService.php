<?php

namespace App\Services\Finance;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use App\Services\NumberSequence\NumberSequenceService;

class InvoiceService
{
    public function __construct(private NumberSequenceService $numberSequence) {}

    public function createMembershipInvoice(User $user, string $description, float $registrationFee, float $annualFee, ?int $costCenterId = null): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => $this->numberSequence->generateInvoiceNumber(),
            'user_id' => $user->id,
            'cost_center_id' => $costCenterId,
            'type' => InvoiceType::Membership,
            'status' => InvoiceStatus::Sent,
            'currency' => 'KES',
            'due_date' => now()->addDays(30),
            'subtotal' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 0,
            'amount_paid' => 0,
            'balance_due' => 0,
        ]);

        if ($registrationFee > 0) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => 'Registration Fee',
                'quantity' => 1,
                'unit_price' => $registrationFee,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total' => $registrationFee,
            ]);
        }

        if ($annualFee > 0) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $description,
                'quantity' => 1,
                'unit_price' => $annualFee,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total' => $annualFee,
            ]);
        }

        $invoice->recalculateTotals();

        return $invoice->fresh();
    }

    public function createEventInvoice(User $user, string $eventTitle, float $ticketPrice, ?int $costCenterId = null): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => $this->numberSequence->generateInvoiceNumber(),
            'user_id' => $user->id,
            'cost_center_id' => $costCenterId,
            'type' => InvoiceType::Event,
            'status' => InvoiceStatus::Sent,
            'currency' => 'KES',
            'due_date' => now()->addDays(7),
            'subtotal' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 0,
            'amount_paid' => 0,
            'balance_due' => 0,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => "Event Registration: {$eventTitle}",
            'quantity' => 1,
            'unit_price' => $ticketPrice,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total' => $ticketPrice,
        ]);

        $invoice->recalculateTotals();

        return $invoice->fresh();
    }

    public function createRenewalInvoice(User $user, string $tierName, float $annualFee, ?int $costCenterId = null): Invoice
    {
        $invoice = Invoice::create([
            'invoice_number' => $this->numberSequence->generateInvoiceNumber(),
            'user_id' => $user->id,
            'cost_center_id' => $costCenterId,
            'type' => InvoiceType::Membership,
            'status' => InvoiceStatus::Sent,
            'currency' => 'KES',
            'due_date' => now()->addDays(30),
            'subtotal' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => 0,
            'amount_paid' => 0,
            'balance_due' => 0,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => "Membership Renewal - {$tierName} (" . now()->year . '/' . (now()->year + 1) . ')',
            'quantity' => 1,
            'unit_price' => $annualFee,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total' => $annualFee,
        ]);

        $invoice->recalculateTotals();

        return $invoice->fresh();
    }

    public function syncPaymentStatus(Invoice $invoice): void
    {
        $invoice->recalculateTotals();

        if ($invoice->balance_due <= 0) {
            $invoice->update([
                'status' => InvoiceStatus::Paid,
                'paid_at' => now(),
            ]);
        } elseif ($invoice->amount_paid > 0) {
            $invoice->update(['status' => InvoiceStatus::PartiallyPaid]);
        }
    }
}
