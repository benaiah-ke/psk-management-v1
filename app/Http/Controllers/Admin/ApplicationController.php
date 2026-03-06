<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\ApplicationStatus;
use App\Models\MembershipApplication;
use App\Services\Membership\MembershipService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function __construct(private MembershipService $membershipService) {}

    public function index(Request $request)
    {
        $query = MembershipApplication::with(['user', 'tier', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->pending();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        return view('admin.applications.index', compact('applications'));
    }

    public function show(MembershipApplication $application)
    {
        $application->load(['user', 'tier', 'reviewer', 'documents']);

        return view('admin.applications.show', compact('application'));
    }

    public function approve(Request $request, MembershipApplication $application)
    {
        $request->validate([
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $this->membershipService->approveApplication($application, $request->review_notes);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Application approved. Membership created and invoice generated.');
    }

    public function reject(Request $request, MembershipApplication $application)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $this->membershipService->rejectApplication($application, $request->rejection_reason, $request->review_notes);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Application rejected. The applicant has been notified.');
    }
}
