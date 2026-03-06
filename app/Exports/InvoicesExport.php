<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function query()
    {
        return Invoice::query()->with(['user']);
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Member Name',
            'Type',
            'Status',
            'Subtotal',
            'Tax',
            'Total',
            'Amount Paid',
            'Balance Due',
            'Due Date',
            'Created Date',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->user?->full_name,
            $invoice->type?->label(),
            $invoice->status?->label(),
            number_format((float) $invoice->subtotal, 2),
            number_format((float) $invoice->tax_amount, 2),
            number_format((float) $invoice->total_amount, 2),
            number_format((float) $invoice->amount_paid, 2),
            number_format((float) $invoice->balance_due, 2),
            $invoice->due_date?->format('Y-m-d'),
            $invoice->created_at->format('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
