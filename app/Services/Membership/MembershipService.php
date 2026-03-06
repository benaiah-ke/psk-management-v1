<?php

namespace App\Services\Membership;

use App\Enums\ApplicationStatus;
use App\Enums\MembershipStatus;
use App\Enums\RenewalStatus;
use App\Models\Membership;
use App\Models\MembershipApplication;
use App\Models\MembershipRenewal;
use App\Models\MembershipTier;
use App\Models\User;
use App\Notifications\ApplicationApproved;
use App\Notifications\ApplicationRejected;
use App\Notifications\ApplicationSubmitted;
use App\Services\Finance\InvoiceService;
use App\Services\NumberSequence\NumberSequenceService;

class MembershipService
{
    public function __construct(
        private NumberSequenceService $numberSequence,
        private InvoiceService $invoiceService,
    ) {}

    public function submitApplication(User $user, int $tierId, array $formData, array $documents = []): MembershipApplication
    {
        $application = MembershipApplication::create([
            'user_id' => $user->id,
            'membership_tier_id' => $tierId,
            'status' => ApplicationStatus::Submitted,
            'form_data' => $formData,
            'submitted_at' => now(),
        ]);

        // Handle document uploads
        foreach ($documents as $file) {
            $path = $file->store('application-documents', 'local');
            $application->documents()->create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        }

        // Update user PPB registration if provided
        if (!empty($formData['ppb_registration_no'])) {
            $user->update(['ppb_registration_no' => $formData['ppb_registration_no']]);
        }

        // Notify admins
        $admins = User::role(['Super Admin', 'Admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new ApplicationSubmitted($application));
        }

        return $application;
    }

    public function approveApplication(MembershipApplication $application, ?string $reviewNotes = null): Membership
    {
        $application->load(['user', 'tier']);

        // Update application status
        $application->update([
            'status' => ApplicationStatus::Approved,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $reviewNotes,
        ]);

        // Create membership record
        $membership = Membership::create([
            'user_id' => $application->user_id,
            'membership_tier_id' => $application->membership_tier_id,
            'membership_number' => $this->numberSequence->generateMembershipNumber(),
            'status' => MembershipStatus::Pending, // Pending until payment
            'application_date' => $application->submitted_at,
            'approval_date' => now(),
            'approved_by' => auth()->id(),
            'expiry_date' => now()->addYear()->endOfYear(),
        ]);

        // Assign Member role if not already assigned
        if (!$application->user->hasRole('Member')) {
            $application->user->assignRole('Member');
        }

        // Create invoice for membership fees
        $tier = $application->tier;
        $invoice = $this->invoiceService->createMembershipInvoice(
            $application->user,
            "Membership Fee - {$tier->name} (" . now()->year . '/' . (now()->year + 1) . ')',
            (float) $tier->registration_fee,
            (float) $tier->annual_fee,
        );

        // Notify the applicant
        $application->user->notify(new ApplicationApproved($application));

        return $membership;
    }

    public function rejectApplication(MembershipApplication $application, string $reason, ?string $reviewNotes = null): void
    {
        $application->load('user');

        $application->update([
            'status' => ApplicationStatus::Rejected,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
            'review_notes' => $reviewNotes,
        ]);

        // Notify the applicant
        $application->user->notify(new ApplicationRejected($application));
    }

    public function initiateRenewal(Membership $membership): MembershipRenewal
    {
        $membership->load(['user', 'tier']);

        $periodStart = $membership->expiry_date ?? now();
        $periodEnd = $periodStart->copy()->addYear();

        // Create invoice for renewal
        $invoice = $this->invoiceService->createRenewalInvoice(
            $membership->user,
            $membership->tier->name,
            (float) $membership->tier->annual_fee,
        );

        // Create renewal record
        $renewal = MembershipRenewal::create([
            'membership_id' => $membership->id,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'amount' => $membership->tier->annual_fee,
            'status' => RenewalStatus::Invoiced,
            'invoice_id' => $invoice->id,
        ]);

        return $renewal;
    }
}
