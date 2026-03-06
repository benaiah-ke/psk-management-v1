<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Communication;
use App\Models\EmailTemplate;
use App\Models\MembershipTier;
use App\Services\Communication\CommunicationService;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    public function __construct(private CommunicationService $communicationService) {}

    public function index(Request $request)
    {
        $query = Communication::with('sender');

        if ($request->filled('search')) {
            $query->where('subject', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $communications = $query->latest()->paginate(15)->withQueryString();

        return view('admin.communications.index', compact('communications'));
    }

    public function create()
    {
        $templates = EmailTemplate::where('is_active', true)->orderBy('name')->get();
        $tiers = MembershipTier::where('is_active', true)->orderBy('name')->get();
        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        return view('admin.communications.create', compact('templates', 'tiers', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'tier_ids' => 'nullable|array',
            'tier_ids.*' => 'exists:membership_tiers,id',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => 'exists:branches,id',
        ]);

        $communication = $this->communicationService->send(
            [
                'type' => $validated['type'],
                'subject' => $validated['subject'],
                'body' => $validated['body'],
            ],
            [
                'tier_ids' => $validated['tier_ids'] ?? [],
                'branch_ids' => $validated['branch_ids'] ?? [],
            ],
        );

        return redirect()->route('admin.communications.show', $communication)
            ->with('success', "Communication sent to {$communication->recipient_count} recipients.");
    }

    public function show(Communication $communication)
    {
        $communication->load(['sender', 'recipients.user']);

        return view('admin.communications.show', compact('communication'));
    }
}
