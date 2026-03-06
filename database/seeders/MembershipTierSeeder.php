<?php

namespace Database\Seeders;

use App\Models\MembershipTier;
use Illuminate\Database\Seeder;

class MembershipTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'Student',
                'description' => 'For pharmacy students currently enrolled in an accredited institution.',
                'annual_fee' => 1000.00,
                'registration_fee' => 500.00,
                'cpd_points_required' => 0,
                'benefits' => json_encode([
                    'Access to student events and workshops',
                    'Mentorship programs',
                    'Student newsletter',
                    'Discounted event registration',
                ]),
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Intern',
                'description' => 'For pharmacy graduates undertaking their internship year.',
                'annual_fee' => 2000.00,
                'registration_fee' => 1000.00,
                'cpd_points_required' => 20,
                'benefits' => json_encode([
                    'Internship support resources',
                    'Exam preparation materials',
                    'Networking events access',
                    'Discounted event registration',
                ]),
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Pharmacist',
                'description' => 'For registered pharmacists with a valid PPB license.',
                'annual_fee' => 5000.00,
                'registration_fee' => 2000.00,
                'cpd_points_required' => 40,
                'benefits' => json_encode([
                    'Full member benefits',
                    'Voting rights at AGM',
                    'CPD tracking and certificates',
                    'Access to all events',
                    'Professional development resources',
                    'Member directory listing',
                ]),
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Fellow',
                'description' => 'For distinguished pharmacists recognized for outstanding contributions.',
                'annual_fee' => 5000.00,
                'registration_fee' => 5000.00,
                'cpd_points_required' => 40,
                'benefits' => json_encode([
                    'All Pharmacist tier benefits',
                    'Fellow designation (FPSK)',
                    'Priority event registration',
                    'Mentorship program leadership',
                    'Recognition at annual conference',
                ]),
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Corporate',
                'description' => 'For pharmaceutical companies, distributors, and related businesses.',
                'annual_fee' => 50000.00,
                'registration_fee' => 10000.00,
                'cpd_points_required' => 0,
                'benefits' => json_encode([
                    'Corporate member listing',
                    'Sponsorship opportunities',
                    'Event exhibition space priority',
                    'Advertising in PSK publications',
                    'Up to 5 delegate passes for events',
                ]),
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Honorary',
                'description' => 'Honorary membership conferred by the PSK Council.',
                'annual_fee' => 0.00,
                'registration_fee' => 0.00,
                'cpd_points_required' => 0,
                'benefits' => json_encode([
                    'All member benefits',
                    'Lifetime membership',
                    'No annual fee',
                    'Special recognition',
                ]),
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($tiers as $tier) {
            MembershipTier::create($tier);
        }
    }
}
