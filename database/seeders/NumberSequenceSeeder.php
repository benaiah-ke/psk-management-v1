<?php

namespace Database\Seeders;

use App\Models\NumberSequence;
use Illuminate\Database\Seeder;

class NumberSequenceSeeder extends Seeder
{
    public function run(): void
    {
        $sequences = [
            ['type' => 'membership_number', 'prefix' => 'PSK', 'next_number' => 1, 'padding' => 5, 'format' => '{prefix}/{year}/{number}'],
            ['type' => 'invoice_number', 'prefix' => 'INV', 'next_number' => 1, 'padding' => 5, 'format' => '{prefix}/{year}/{number}'],
            ['type' => 'receipt_number', 'prefix' => 'RCT', 'next_number' => 1, 'padding' => 5, 'format' => '{prefix}/{year}/{number}'],
            ['type' => 'ticket_number', 'prefix' => 'TKT', 'next_number' => 1, 'padding' => 5, 'format' => '{prefix}/{number}'],
        ];

        foreach ($sequences as $sequence) {
            NumberSequence::create($sequence);
        }
    }
}
