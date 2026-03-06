<?php

namespace App\Exports;

use App\Models\EventRegistration;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EventRegistrationsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        protected int $eventId
    ) {}

    public function query()
    {
        return EventRegistration::query()
            ->where('event_id', $this->eventId)
            ->with(['user', 'ticketType']);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Ticket Type',
            'Status',
            'Amount Paid',
            'Registered At',
            'Checked In At',
        ];
    }

    public function map($registration): array
    {
        return [
            $registration->user?->full_name,
            $registration->user?->email,
            $registration->user?->phone,
            $registration->ticketType?->name,
            $registration->status?->label(),
            number_format((float) $registration->amount_paid, 2),
            $registration->created_at->format('Y-m-d H:i:s'),
            $registration->checked_in_at?->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
