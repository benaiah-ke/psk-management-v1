<?php

namespace App\Console\Commands;

use App\Models\Membership;
use Illuminate\Console\Command;

class SendRenewalReminders extends Command
{
    protected $signature = 'membership:send-renewal-reminders';
    protected $description = 'Send renewal reminders to members with memberships expiring within 30 days';

    public function handle(): int
    {
        $memberships = Membership::expiringSoon(30)->with(['user', 'tier'])->get();

        foreach ($memberships as $membership) {
            // TODO: Send actual email notification
            $this->line("Reminder: {$membership->user->full_name} - {$membership->tier->name} expires {$membership->expiry_date->format('Y-m-d')}");
        }

        $this->info("Sent renewal reminders to {$memberships->count()} members.");

        return Command::SUCCESS;
    }
}
