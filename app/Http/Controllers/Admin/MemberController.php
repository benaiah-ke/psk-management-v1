<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MembersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MembershipTier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['membership.tier'])
            ->whereHas('memberships');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ppb_registration_no', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tier')) {
            $query->whereHas('membership', function ($q) use ($request) {
                $q->where('membership_tier_id', $request->tier);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('membership', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $members = $query->latest()->paginate(15)->withQueryString();
        $tiers = MembershipTier::where('is_active', true)->get();

        return view('admin.members.index', compact('members', 'tiers'));
    }

    public function create()
    {
        $tiers = MembershipTier::where('is_active', true)->get();

        return view('admin.members.create', compact('tiers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:50',
            'ppb_registration_no' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = $validated['password'];
        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member created successfully.');
    }

    public function show(User $member)
    {
        $member->load([
            'membership.tier',
            'memberships',
            'addresses',
            'invoices',
            'cpdActivities.category',
            'eventRegistrations.event',
        ]);

        return view('admin.members.show', compact('member'));
    }

    public function edit(User $member)
    {
        $tiers = MembershipTier::where('is_active', true)->get();

        return view('admin.members.edit', compact('member', 'tiers'));
    }

    public function update(Request $request, User $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:50',
            'ppb_registration_no' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'is_active' => 'boolean',
        ]);

        $member->update($validated);

        return redirect()->route('admin.members.show', $member)
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(User $member)
    {
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member deleted successfully.');
    }

    public function export()
    {
        return Excel::download(new MembersExport, 'members-' . date('Y-m-d') . '.xlsx');
    }
}
