<?php

namespace App\Services\Finance;

use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;
use App\Notifications\PaymentReceived;
use App\Services\NumberSequence\NumberSequenceService;

class PaymentService
{
    public function __construct(
        private NumberSequenceService $numberSequence,
        private InvoiceService $invoiceService,
    ) {}

    public function recordPayment(Invoice $invoice, array $data): Payment
    {
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'payment_number' => $this->numberSequence->generate('receipt_number'),
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'payment_reference' => $data['payment_reference'] ?? null,
            'status' => PaymentStatus::Completed,
            'paid_at' => $data['paid_at'] ?? now(),
            'received_by' => $data['received_by'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        // Sync invoice payment status
        $this->invoiceService->syncPaymentStatus($invoice);

        // Generate receipt
        $this->generateReceipt($payment, $invoice);

        // Notify the user
        if ($invoice->user) {
            $invoice->user->notify(new PaymentReceived($payment));
        }

        return $payment;
    }

    private function generateReceipt(Payment $payment, Invoice $invoice): Receipt
    {
        return Receipt::create([
            'receipt_number' => $this->numberSequence->generate('receipt_number'),
            'payment_id' => $payment->id,
            'invoice_id' => $invoice->id,
            'user_id' => $invoice->user_id,
            'amount' => $payment->amount,
            'issued_date' => now(),
        ]);
    }
}
