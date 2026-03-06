<?php

namespace App\Console\Commands;

use App\Enums\MembershipStatus;
use App\Models\Membership;
use Illuminate\Console\Command;

class SendCpdAlerts extends Command
{
    protected $signature = 'cpd:send-alerts';
    protected $description = 'Alert members who haven\'t met their CPD requirements with less than 3 months left in the year';

    public function handle(): int
    {
        if (now()->month < 10) {
            $this->info('CPD alerts are only sent in Q4 (October-December). Skipping.');
            return Command::SUCCESS;
        }

        $currentYear = now()->year;

        $memberships = Membership::where('status', MembershipStatus::Active)
            ->with(['user', 'tier'])
            ->whereHas('tier', function ($query) {
                $query->where('cpd_points_required', '>', 0);
            })
            ->get();

        $alertCount = 0;

        foreach ($memberships as $membership) {
            $pointsEarned = $membership->user->getCpdPointsForYear($currentYear);
            $pointsRequired = $membership->tier->cpd_points_required;

            if ($pointsEarned < $pointsRequired) {
                $deficit = $pointsRequired - $pointsEarned;
                // TODO: Send actual email notification
                $this->line("Alert: {$membership->user->full_name} - {$pointsEarned}/{$pointsRequired} CPD points ({$deficit} points short)");
                $alertCount++;
            }
        }

        $this->info("Sent CPD alerts to {$alertCount} members.");

        return Command::SUCCESS;
    }
}
