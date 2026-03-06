<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CpdActivity;
use App\Models\CpdCategory;
use Illuminate\Http\Request;

class CpdActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = CpdActivity::with(['user', 'category', 'event']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->boolean('verified'));
        }

        if ($request->filled('category')) {
            $query->where('cpd_category_id', $request->category);
        }

        if ($request->filled('year')) {
            $query->forYear($request->year);
        }

        $activities = $query->latest()->paginate(15)->withQueryString();

        $categories = CpdCategory::orderBy('name')->get();
        $currentYear = (int) date('Y');
        $years = collect(range($currentYear, $currentYear - 5))->mapWithKeys(fn ($y) => [$y => (string) $y])->all();

        return view('admin.cpd.activities.index', compact('activities', 'categories', 'years'));
    }

    public function show(CpdActivity $cpdActivity)
    {
        $cpdActivity->load(['user', 'category', 'event', 'verifier']);

        return view('admin.cpd.activities.show', compact('cpdActivity'));
    }

    public function verify(Request $request, CpdActivity $cpdActivity)
    {
        $cpdActivity->update([
            'is_verified' => true,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return redirect()->route('admin.cpd.activities.show', $cpdActivity)
            ->with('success', 'CPD activity verified successfully.');
    }
}
