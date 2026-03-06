<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function query()
    {
        return User::query()
            ->whereHas('membership')
            ->with(['membership.tier']);
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'PPB Reg No', 'Membership No', 'Tier', 'Status', 'Expiry Date', 'Joined'];
    }

    public function map($user): array
    {
        return [
            $user->full_name,
            $user->email,
            $user->phone,
            $user->ppb_registration_no,
            $user->membership?->membership_number,
            $user->membership?->tier?->name,
            $user->membership?->status?->label(),
            $user->membership?->expiry_date?->format('Y-m-d'),
            $user->created_at->format('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
