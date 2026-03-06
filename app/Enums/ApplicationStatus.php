<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case AdditionalInfoRequired = 'additional_info_required';

    public function label(): string
    {
        return match($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted',
            self::UnderReview => 'Under Review',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::AdditionalInfoRequired => 'Additional Info Required',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft => 'gray',
            self::Submitted => 'blue',
            self::UnderReview => 'yellow',
            self::Approved => 'green',
            self::Rejected => 'red',
            self::AdditionalInfoRequired => 'orange',
        };
    }
}
