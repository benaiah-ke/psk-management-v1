<?php

return [
    'organization' => [
        'name' => env('PSK_ORG_NAME', 'Pharmaceutical Society of Kenya'),
        'short_name' => env('PSK_SHORT_NAME', 'PSK'),
        'email' => env('PSK_EMAIL', 'info@psk.or.ke'),
        'phone' => env('PSK_PHONE', '+254 20 2721312'),
        'address' => env('PSK_ADDRESS', 'Hurlingham, Jabavu Road, Nairobi, Kenya'),
        'website' => env('PSK_WEBSITE', 'https://www.psk.or.ke'),
    ],

    'membership' => [
        'auto_approve' => env('PSK_AUTO_APPROVE', false),
        'renewal_reminder_days' => env('PSK_RENEWAL_REMINDER_DAYS', 30),
    ],

    'cpd' => [
        'annual_requirement' => env('PSK_CPD_ANNUAL_REQUIREMENT', 40),
        'year_start_month' => 1,
    ],

    'finance' => [
        'currency' => env('PSK_CURRENCY', 'KES'),
        'tax_rate' => env('PSK_TAX_RATE', 0),
        'payment_due_days' => env('PSK_PAYMENT_DUE_DAYS', 30),
    ],
];
