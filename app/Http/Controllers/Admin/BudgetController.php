<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\CostCenter;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::with(['costCenter', 'approvedBy']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('fiscal_year')) {
            $query->where('fiscal_year', $request->fiscal_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $budgets = $query->latest()->paginate(15)->withQueryString();

        return view('admin.budgets.index', compact('budgets'));
    }

    public function create()
    {
        $costCenters = CostCenter::where('is_active', true)->orderBy('name')->get();

        return view('admin.budgets.create', compact('costCenters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cost_center_id' => 'required|exists:cost_centers,id',
            'fiscal_year' => 'required|integer|min:2000|max:2100',
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Budget::create($validated);

        return redirect()->route('admin.budgets.index')
            ->with('success', 'Budget created successfully.');
    }

    public function show(Budget $budget)
    {
        $budget->load(['costCenter', 'approvedBy', 'lines']);

        return view('admin.budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $costCenters = CostCenter::where('is_active', true)->orderBy('name')->get();

        return view('admin.budgets.edit', compact('budget', 'costCenters'));
    }

    public function update(Request $request, Budget $budget)
    {
        $validated = $request->validate([
            'cost_center_id' => 'required|exists:cost_centers,id',
            'fiscal_year' => 'required|integer|min:2000|max:2100',
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $budget->update($validated);

        return redirect()->route('admin.budgets.show', $budget)
            ->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return redirect()->route('admin.budgets.index')
            ->with('success', 'Budget deleted successfully.');
    }
}
