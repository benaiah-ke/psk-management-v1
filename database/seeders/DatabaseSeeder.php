<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            MembershipTierSeeder::class,
            CpdCategorySeeder::class,
            TicketCategorySeeder::class,
            SettingsSeeder::class,
            NumberSequenceSeeder::class,
        ]);

        // Create Super Admin user
        $admin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@psk.or.ke',
            'phone' => '+254700000000',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole('Super Admin');
    }
}
