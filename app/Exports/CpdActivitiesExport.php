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
        return CpdActivity::query()->with(['user', 'category', 'verifier']);
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
            'Verified By',
            'Verified At',
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->user?->full_name,
            $activity->category?->name,
            $activity->title,
            number_format((float) $activity->points, 2),
            $activity->source?->label(),
            $activity->is_verified ? 'Verified' : 'Pending',
            $activity->activity_date?->format('Y-m-d'),
            $activity->verifier?->full_name,
            $activity->verified_at?->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
