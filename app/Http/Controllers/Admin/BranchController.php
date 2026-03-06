<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CostCenter;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::with('costCenter')->withCount('members');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('county', 'like', '%' . $request->search . '%')
                  ->orWhere('region', 'like', '%' . $request->search . '%');
            });
        }

        $branches = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $costCenters = CostCenter::where('is_active', true)->orderBy('name')->get();

        return view('admin.branches.create', compact('costCenters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:branches,name',
            'county' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'is_active' => 'boolean',
        ]);

        Branch::create($validated);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        $branch->load(['costCenter', 'members', 'posts']);
        $branch->loadCount('members');

        return view('admin.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $costCenters = CostCenter::where('is_active', true)->orderBy('name')->get();

        return view('admin.branches.edit', compact('branch', 'costCenters'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:branches,name,' . $branch->id,
            'county' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return redirect()->route('admin.branches.show', $branch)
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch deleted successfully.');
    }
}
