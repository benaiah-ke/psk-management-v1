<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MembershipCertificate;
use App\Models\MembershipTier;
use App\Services\Membership\MembershipService;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function __construct(private MembershipService $membershipService) {}

    public function index()
    {
        $membership = auth()->user()->membership;
        $applications = auth()->user()->membershipApplications()->with('tier')->latest()->get();
        return view('portal.membership.index', compact('membership', 'applications'));
    }

    public function apply()
    {
        $tiers = MembershipTier::where('is_active', true)->orderBy('sort_order')->get();
        return view('portal.membership.apply', compact('tiers'));
    }

    public function submitApplication(Request $request)
    {
        $validated = $request->validate([
            'membership_tier_id' => 'required|exists:membership_tiers,id',
            'ppb_registration_no' => 'nullable|string|max:50',
            'motivation' => 'nullable|string|max:1000',
            'agree_terms' => 'required|accepted',
            'documents' => 'nullable|array|max:5',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $this->membershipService->submitApplication(
            auth()->user(),
            $validated['membership_tier_id'],
            [
                'ppb_registration_no' => $validated['ppb_registration_no'] ?? null,
                'motivation' => $validated['motivation'] ?? null,
            ],
            $request->file('documents', []),
        );

        return redirect()->route('portal.membership')
            ->with('success', 'Your application has been submitted for review. You will be notified once it is processed.');
    }

    public function renew()
    {
        $membership = auth()->user()->membership;
        abort_unless($membership, 404, 'No active membership found.');
        $membership->load('tier');
        return view('portal.membership.renew', compact('membership'));
    }

    public function submitRenewal(Request $request)
    {
        $membership = auth()->user()->membership;
        abort_unless($membership, 404, 'No active membership found.');

        $renewal = $this->membershipService->initiateRenewal($membership);

        return redirect()->route('portal.invoices.show', $renewal->invoice_id)
            ->with('success', 'Renewal initiated. Please pay the invoice to complete your renewal.');
    }

    public function certificate(MembershipCertificate $certificate)
    {
        abort_unless($certificate->user_id === auth()->id(), 403);
        return response()->download(storage_path('app/' . $certificate->file_path));
    }
}
