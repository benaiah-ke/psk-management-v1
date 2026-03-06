<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CpdCategory;
use Illuminate\Http\Request;

class CpdCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = CpdCategory::withCount('activities');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.cpd-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.cpd-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cpd_categories,name',
            'description' => 'nullable|string',
            'max_points_per_year' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        CpdCategory::create($validated);

        return redirect()->route('admin.cpd-categories.index')
            ->with('success', 'CPD category created successfully.');
    }

    public function show(CpdCategory $cpdCategory)
    {
        $cpdCategory->loadCount('activities');

        return view('admin.cpd-categories.show', compact('cpdCategory'));
    }

    public function edit(CpdCategory $cpdCategory)
    {
        return view('admin.cpd-categories.edit', compact('cpdCategory'));
    }

    public function update(Request $request, CpdCategory $cpdCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cpd_categories,name,' . $cpdCategory->id,
            'description' => 'nullable|string',
            'max_points_per_year' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $cpdCategory->update($validated);

        return redirect()->route('admin.cpd-categories.show', $cpdCategory)
            ->with('success', 'CPD category updated successfully.');
    }

    public function destroy(CpdCategory $cpdCategory)
    {
        $cpdCategory->delete();

        return redirect()->route('admin.cpd-categories.index')
            ->with('success', 'CPD category deleted successfully.');
    }
}
