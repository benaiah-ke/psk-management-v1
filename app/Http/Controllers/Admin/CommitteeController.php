<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use App\Models\CostCenter;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index(Request $request)
    {
        $query = Committee::with(['parent', 'costCenter'])->withCount('members');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $committees = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.committees.index', compact('committees'));
    }

    public function create()
    {
        $parentCommittees = Committee::where('is_active', true)->orderBy('name')->get();
        $costCenters = CostCenter::where('is_active', true)->orderBy('name')->get();

        return view('admin.committees.create', compact('parentCommittees', 'costCenters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:committees,name',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:committees,id',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'is_active' => 'boolean',
        ]);

        Committee::create($validated);

        return redirect()->route('admin.committees.index')
            ->with('success', 'Committee created successfully.');
    }

    public function show(Committee $committee)
    {
        $committee->load(['parent', 'children', 'costCenter', 'members', 'posts']);
        $committee->loadCount('members');

        return view('admin.committees.show', compact('committee'));
    }

    public function edit(Committee $committee)
    {
        $parentCommittees = Committee::where('is_active', true)
            ->where('id', '!=', $committee->id)
            ->orderBy('name')
            ->get();
        $costCenters = CostCenter::where('is_active', true)->orderBy('name')->get();

        return view('admin.committees.edit', compact('committee', 'parentCommittees', 'costCenters'));
    }

    public function update(Request $request, Committee $committee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:committees,name,' . $committee->id,
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:committees,id',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'is_active' => 'boolean',
        ]);

        $committee->update($validated);

        return redirect()->route('admin.committees.show', $committee)
            ->with('success', 'Committee updated successfully.');
    }

    public function destroy(Committee $committee)
    {
        $committee->delete();

        return redirect()->route('admin.committees.index')
            ->with('success', 'Committee deleted successfully.');
    }
}
