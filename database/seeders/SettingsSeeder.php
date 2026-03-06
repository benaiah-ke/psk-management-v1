<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'organization', 'key' => 'organization_name', 'value' => 'Pharmaceutical Society of Kenya', 'type' => 'string'],
            ['group' => 'organization', 'key' => 'organization_short_name', 'value' => 'PSK', 'type' => 'string'],
            ['group' => 'organization', 'key' => 'organization_email', 'value' => 'info@psk.or.ke', 'type' => 'string'],
            ['group' => 'organization', 'key' => 'organization_phone', 'value' => '+254 20 2717077', 'type' => 'string'],
            ['group' => 'organization', 'key' => 'organization_address', 'value' => 'Pamstech House, Woodlands Road, Nairobi', 'type' => 'string'],
            ['group' => 'finance', 'key' => 'currency', 'value' => 'KES', 'type' => 'string'],
            ['group' => 'finance', 'key' => 'tax_rate', 'value' => '16', 'type' => 'integer'],
            ['group' => 'finance', 'key' => 'invoice_due_days', 'value' => '30', 'type' => 'integer'],
            ['group' => 'cpd', 'key' => 'cpd_year_requirement', 'value' => '40', 'type' => 'integer'],
            ['group' => 'membership', 'key' => 'renewal_reminder_days', 'value' => '30', 'type' => 'integer'],
            ['group' => 'membership', 'key' => 'membership_expiry_grace_days', 'value' => '90', 'type' => 'integer'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
