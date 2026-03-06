<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\NumberSequence;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('portal.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = TicketCategory::where('is_active', true)->get();
        return view('portal.tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:ticket_categories,id',
            'type' => 'required|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'required|string',
        ]);

        $sequence = NumberSequence::where('type', 'ticket_number')->first();

        Ticket::create([
            'ticket_number' => $sequence ? $sequence->generateNext() : 'TKT-' . strtoupper(uniqid()),
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'type' => $validated['type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => TicketStatus::Open->value,
        ]);

        return redirect()->route('portal.tickets.index')->with('success', 'Ticket submitted successfully.');
    }

    public function show(Ticket $ticket)
    {
        abort_unless($ticket->user_id === auth()->id(), 403);
        $ticket->load(['responses.user', 'category']);
        return view('portal.tickets.show', compact('ticket'));
    }

    public function respond(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->user_id === auth()->id(), 403);

        $request->validate(['message' => 'required|string|max:5000']);

        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal' => false,
        ]);

        return back()->with('success', 'Response added successfully.');
    }
}
