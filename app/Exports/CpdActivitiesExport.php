<?php

namespace App\Exports;

use App\Models\CpdActivity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CpdActivitiesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function query()
    {
        return CpdActivity::query()->with(['user', 'category', 'approver']);
    }

    public function headings(): array
    {
        return [
            'Member Name',
            'Category',
            'Title',
            'Points',
            'Source',
            'Status',
            'Activity Date',
            'Approved By',
            'Approved At',
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->user?->full_name,
            $activity->category?->name,
            $activity->title,
            $activity->points,
            $activity->source?->label(),
            $activity->status?->label() ?? 'Pending',
            $activity->activity_date?->format('Y-m-d'),
            $activity->approver?->full_name,
            $activity->approved_at?->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
