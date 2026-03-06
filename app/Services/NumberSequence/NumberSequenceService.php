<?php

namespace App\Services\NumberSequence;

use App\Models\NumberSequence;

class NumberSequenceService
{
    public function generate(string $type): string
    {
        $sequence = NumberSequence::where('type', $type)->lockForUpdate()->first();

        if (!$sequence) {
            throw new \RuntimeException("Number sequence not found for type: {$type}");
        }

        return $sequence->generateNext();
    }

    public function generateMembershipNumber(): string
    {
        return $this->generate('membership_number');
    }

    public function generateInvoiceNumber(): string
    {
        return $this->generate('invoice_number');
    }

    public function generateReceiptNumber(): string
    {
        return $this->generate('receipt_number');
    }

    public function generateTicketNumber(): string
    {
        return $this->generate('ticket_number');
    }
}
