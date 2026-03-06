<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Enums\CpdActivitySource;
use App\Models\CpdActivity;
use App\Models\CpdCategory;
use App\Models\CpdCertificate;
use Illuminate\Http\Request;

class CpdController extends Controller
{
    public function index()
    {
        $currentYear = now()->year;
        $activities = CpdActivity::where('user_id', auth()->id())
            ->whereYear('activity_date', $currentYear)
            ->with('category')
            ->latest('activity_date')
            ->get();

        $totalPoints = $activities->where('is_verified', true)->sum('points');
        $requiredPoints = config('psk.cpd.annual_requirement', 40);
        $certificates = CpdCertificate::where('user_id', auth()->id())->latest('year')->get();

        return view('portal.cpd.index', compact('activities', 'totalPoints', 'requiredPoints', 'certificates', 'currentYear'));
    }

    public function create()
    {
        $categories = CpdCategory::where('is_active', true)->get();
        return view('portal.cpd.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cpd_category_id' => 'required|exists:cpd_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'points' => 'required|numeric|min:0.5|max:20',
            'activity_date' => 'required|date|before_or_equal:today',
            'evidence' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('cpd-evidence', 'local');
        }

        CpdActivity::create([
            'user_id' => auth()->id(),
            'cpd_category_id' => $validated['cpd_category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'points' => $validated['points'],
            'activity_date' => $validated['activity_date'],
            'source' => CpdActivitySource::Manual,
            'evidence_path' => $evidencePath,
            'is_verified' => false,
        ]);

        return redirect()->route('portal.cpd.index')->with('success', 'CPD activity logged successfully. It will be reviewed shortly.');
    }

    public function certificate(CpdCertificate $certificate)
    {
        abort_unless($certificate->user_id === auth()->id(), 403);
        return response()->download(storage_path('app/' . $certificate->file_path));
    }
}
