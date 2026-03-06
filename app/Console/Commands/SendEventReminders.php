<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send reminders for events happening in the next 48 hours';

    public function handle(): int
    {
        $events = Event::whereBetween('start_date', [now(), now()->addHours(48)])
            ->withCount(['registrations' => function ($query) {
                $query->whereNot('status', 'cancelled');
            }])
            ->get();

        foreach ($events as $event) {
            // TODO: Send actual email notifications to registered attendees
            $this->line("Event: {$event->title} starts {$event->start_date->format('Y-m-d H:i')} - {$event->registrations_count} attendees");
        }

        $this->info("Sent reminders for {$events->count()} upcoming events.");

        return Command::SUCCESS;
    }
}
