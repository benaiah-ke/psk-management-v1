<?php

namespace App\Services\Communication;

use App\Enums\CommunicationType;
use App\Models\Communication;
use App\Models\CommunicationRecipient;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CommunicationService
{
    public function send(array $data, array $recipientFilters): Communication
    {
        // Build recipient query
        $recipientQuery = User::query()->where('is_active', true);

        if (!empty($recipientFilters['tier_ids'])) {
            $recipientQuery->whereHas('membership', function ($q) use ($recipientFilters) {
                $q->whereIn('membership_tier_id', $recipientFilters['tier_ids'])
                  ->where('status', 'active');
            });
        }

        if (!empty($recipientFilters['branch_ids'])) {
            $recipientQuery->whereHas('branches', function ($q) use ($recipientFilters) {
                $q->whereIn('branches.id', $recipientFilters['branch_ids']);
            });
        }

        if (!empty($recipientFilters['role'])) {
            $recipientQuery->role($recipientFilters['role']);
        }

        $recipients = $recipientQuery->get();

        // Create communication record
        $communication = Communication::create([
            'type' => $data['type'] ?? CommunicationType::Email,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'sent_by' => auth()->id(),
            'sent_at' => now(),
            'recipient_count' => $recipients->count(),
            'sent_count' => 0,
            'failed_count' => 0,
        ]);

        // Create recipient records and queue emails
        foreach ($recipients as $user) {
            $recipient = CommunicationRecipient::create([
                'communication_id' => $communication->id,
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => 'queued',
            ]);

            try {
                Mail::raw($data['body'], function ($message) use ($user, $data) {
                    $message->to($user->email, $user->full_name)
                            ->subject($data['subject']);
                });

                $recipient->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                $communication->increment('sent_count');
            } catch (\Exception $e) {
                $recipient->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                    'failure_reason' => $e->getMessage(),
                ]);
                $communication->increment('failed_count');
            }
        }

        return $communication;
    }
}
