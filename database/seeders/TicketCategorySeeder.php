<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Membership', 'description' => 'Issues related to membership applications, renewals, or status.'],
            ['name' => 'Finance & Billing', 'description' => 'Invoice, payment, or receipt related inquiries.'],
            ['name' => 'Events', 'description' => 'Event registration, attendance, or CPD points issues.'],
            ['name' => 'CPD', 'description' => 'CPD activity verification, points tracking, or certificates.'],
            ['name' => 'Technical Support', 'description' => 'Platform login, account access, or system issues.'],
            ['name' => 'General Inquiry', 'description' => 'General questions or requests.'],
        ];

        foreach ($categories as $category) {
            TicketCategory::create(array_merge($category, ['is_active' => true]));
        }
    }
}
