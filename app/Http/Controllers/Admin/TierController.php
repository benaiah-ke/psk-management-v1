<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipTier;
use Illuminate\Http\Request;

class TierController extends Controller
{
    public function index(Request $request)
    {
        $query = MembershipTier::withCount('activeMemberships');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $tiers = $query->orderBy('sort_order')->paginate(15)->withQueryString();

        return view('admin.tiers.index', compact('tiers'));
    }

    public function create()
    {
        return view('admin.tiers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:membership_tiers,name',
            'description' => 'nullable|string',
            'annual_fee' => 'required|numeric|min:0',
            'registration_fee' => 'required|numeric|min:0',
            'cpd_points_required' => 'nullable|integer|min:0',
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        MembershipTier::create($validated);

        return redirect()->route('admin.tiers.index')
            ->with('success', 'Membership tier created successfully.');
    }

    public function show(MembershipTier $tier)
    {
        $tier->loadCount('activeMemberships');

        return view('admin.tiers.show', compact('tier'));
    }

    public function edit(MembershipTier $tier)
    {
        return view('admin.tiers.edit', compact('tier'));
    }

    public function update(Request $request, MembershipTier $tier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:membership_tiers,name,' . $tier->id,
            'description' => 'nullable|string',
            'annual_fee' => 'required|numeric|min:0',
            'registration_fee' => 'required|numeric|min:0',
            'cpd_points_required' => 'nullable|integer|min:0',
            'requirements' => 'nullable|array',
            'benefits' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $tier->update($validated);

        return redirect()->route('admin.tiers.show', $tier)
            ->with('success', 'Membership tier updated successfully.');
    }

    public function destroy(MembershipTier $tier)
    {
        $tier->delete();

        return redirect()->route('admin.tiers.index')
            ->with('success', 'Membership tier deleted successfully.');
    }
}
