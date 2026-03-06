<?php

namespace App\Console\Commands;

use App\Enums\MembershipStatus;
use App\Models\Membership;
use Illuminate\Console\Command;

class ExpireOverdueMemberships extends Command
{
    protected $signature = 'membership:expire-overdue';
    protected $description = 'Mark expired memberships that are past their expiry date';

    public function handle(): int
    {
        $expired = Membership::where('status', MembershipStatus::Active)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->get();

        foreach ($expired as $membership) {
            $membership->update(['status' => MembershipStatus::Expired]);
            $this->line("Expired: {$membership->user->full_name} (#{$membership->membership_number}) - expired {$membership->expiry_date->format('Y-m-d')}");
        }

        $this->info("Marked {$expired->count()} memberships as expired.");

        return Command::SUCCESS;
    }
}
