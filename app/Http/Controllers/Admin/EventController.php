<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EventRegistrationsExport;
use App\Http\Controllers\Controller;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount('registrations');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $events = $query->latest()->paginate(15)->withQueryString();

        $totalEvents = Event::count();
        $upcomingEvents = Event::where('start_date', '>=', now())->count();
        $totalRegistrations = EventRegistration::count();
        $totalRevenue = \App\Models\Payment::whereHas('invoice', function ($q) {
            $q->where('type', \App\Enums\InvoiceType::Event);
        })->where('status', \App\Enums\PaymentStatus::Completed)->sum('amount');

        return view('admin.events.index', compact('events', 'totalEvents', 'upcomingEvents', 'totalRegistrations', 'totalRevenue'));
    }

    public function create()
    {
        $costCenters = CostCenter::where('is_active', true)->orderBy('name')->get();

        return view('admin.events.create', compact('costCenters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'type' => 'required|string',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string',
            'is_virtual' => 'boolean',
            'virtual_link' => 'nullable|url|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_opens' => 'nullable|date',
            'registration_closes' => 'nullable|date',
            'max_attendees' => 'nullable|integer|min:1',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'cpd_points' => 'nullable|integer|min:0',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = EventStatus::Draft;

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load(['creator', 'costCenter', 'ticketTypes', 'sessions', 'sponsors']);
        $event->loadCount('registrations');

        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $costCenters = CostCenter::where('is_active', true)->orderBy('name')->get();

        return view('admin.events.edit', compact('event', 'costCenters'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'type' => 'required|string',
            'status' => 'required|string',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string',
            'is_virtual' => 'boolean',
            'virtual_link' => 'nullable|url|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_opens' => 'nullable|date',
            'registration_closes' => 'nullable|date',
            'max_attendees' => 'nullable|integer|min:1',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'cpd_points' => 'nullable|integer|min:0',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function publish(Event $event)
    {
        if ($event->status === EventStatus::Published) {
            $event->update([
                'status' => EventStatus::Draft,
                'published_at' => null,
            ]);
            $message = 'Event unpublished.';
        } else {
            $event->update([
                'status' => EventStatus::Published,
                'published_at' => now(),
            ]);
            $message = 'Event published successfully.';
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', $message);
    }

    public function registrations(Event $event, Request $request)
    {
        $query = $event->registrations()->with(['user', 'ticketType']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $registrations = $query->latest()->paginate(15)->withQueryString();

        return view('admin.events.registrations', compact('event', 'registrations'));
    }

    public function checkIn(Event $event, EventRegistration $registration)
    {
        $registration->update([
            'checked_in_at' => now(),
            'checked_in_by' => auth()->id(),
        ]);

        return redirect()->route('admin.events.registrations', $event)
            ->with('success', 'Attendee checked in successfully.');
    }

    public function exportRegistrations(Event $event)
    {
        return Excel::download(
            new EventRegistrationsExport($event->id),
            'event-' . $event->id . '-registrations-' . date('Y-m-d') . '.xlsx'
        );
    }
}
