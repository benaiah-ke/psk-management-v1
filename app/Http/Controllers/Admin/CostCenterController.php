<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    public function index(Request $request)
    {
        $query = CostCenter::with('parent');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $costCenters = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.cost-centers.index', compact('costCenters'));
    }

    public function create()
    {
        $parentCenters = CostCenter::where('is_active', true)->orderBy('name')->get();

        return view('admin.cost-centers.create', compact('parentCenters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:cost_centers,code',
            'type' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:cost_centers,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        CostCenter::create($validated);

        return redirect()->route('admin.cost-centers.index')
            ->with('success', 'Cost center created successfully.');
    }

    public function show(CostCenter $costCenter)
    {
        $costCenter->load(['parent', 'children', 'budgets', 'invoices']);

        return view('admin.cost-centers.show', compact('costCenter'));
    }

    public function edit(CostCenter $costCenter)
    {
        $parentCenters = CostCenter::where('is_active', true)
            ->where('id', '!=', $costCenter->id)
            ->orderBy('name')
            ->get();

        return view('admin.cost-centers.edit', compact('costCenter', 'parentCenters'));
    }

    public function update(Request $request, CostCenter $costCenter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:cost_centers,code,' . $costCenter->id,
            'type' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:cost_centers,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $costCenter->update($validated);

        return redirect()->route('admin.cost-centers.show', $costCenter)
            ->with('success', 'Cost center updated successfully.');
    }

    public function destroy(CostCenter $costCenter)
    {
        $costCenter->delete();

        return redirect()->route('admin.cost-centers.index')
            ->with('success', 'Cost center deleted successfully.');
    }
}
