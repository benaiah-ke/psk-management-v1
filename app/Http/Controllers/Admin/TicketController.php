<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketResponse;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'category', 'assignee']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();
        $categories = TicketCategory::where('is_active', true)->get();

        return view('admin.tickets.index', compact('tickets', 'categories'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'category', 'assignee', 'responses.user']);
        $staff = User::role('admin')->orderBy('first_name')->get();

        return view('admin.tickets.show', compact('ticket', 'staff'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'priority' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validated['status'] === 'resolved' && !$ticket->resolved_at) {
            $validated['resolved_at'] = now();
        }

        if ($validated['status'] === 'closed' && !$ticket->closed_at) {
            $validated['closed_at'] = now();
        }

        $ticket->update($validated);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    public function respond(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $validated['ticket_id'] = $ticket->id;
        $validated['user_id'] = auth()->id();

        TicketResponse::create($validated);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Response added successfully.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Ticket assigned successfully.');
    }
}
