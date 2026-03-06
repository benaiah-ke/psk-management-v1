<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Enums\EventStatus;
use App\Enums\RegistrationStatus;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventTicketType;
use App\Services\Finance\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct(private InvoiceService $invoiceService) {}

    public function index(Request $request)
    {
        $events = Event::where(function ($q) {
                $q->where('status', EventStatus::Published)
                  ->orWhere('status', EventStatus::RegistrationOpen);
            })
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->paginate(12);

        return view('portal.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load(['ticketTypes', 'sessions', 'sponsors']);
        $registration = $event->registrations()->where('user_id', auth()->id())->first();
        return view('portal.events.show', compact('event', 'registration'));
    }

    public function register(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_type_id' => 'required|exists:event_ticket_types,id',
        ]);

        // Check if already registered
        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->whereNot('status', RegistrationStatus::Cancelled)
            ->first();

        if ($existing) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Check registration is open
        if (!$event->isRegistrationOpen()) {
            return back()->with('error', 'Registration is not currently open for this event.');
        }

        // Check capacity
        if ($event->isFull()) {
            return back()->with('error', 'This event is fully booked.');
        }

        $ticketType = EventTicketType::findOrFail($validated['ticket_type_id']);

        // Generate QR code data
        $qrData = json_encode([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'code' => Str::uuid()->toString(),
        ]);

        // Determine status based on ticket price
        $isPaid = $ticketType->price > 0;
        $status = $isPaid ? RegistrationStatus::Pending : RegistrationStatus::Confirmed;

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'ticket_type_id' => $ticketType->id,
            'registration_number' => 'REG-' . strtoupper(Str::random(8)),
            'status' => $status,
            'qr_code_data' => $qrData,
            'amount_paid' => 0,
        ]);

        // Create invoice for paid tickets
        if ($isPaid) {
            $invoice = $this->invoiceService->createEventInvoice(
                auth()->user(),
                $event->title . ' - ' . $ticketType->name,
                (float) $ticketType->price,
                $event->cost_center_id,
            );

            $registration->update(['invoice_id' => $invoice->id]);

            return redirect()->route('portal.invoices.show', $invoice)
                ->with('success', 'Registration submitted. Please pay the invoice to confirm your spot.');
        }

        return back()->with('success', 'Registration confirmed! Check your email for details.');
    }

    public function myRegistrations()
    {
        $registrations = EventRegistration::with('event')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('portal.events.my-registrations', compact('registrations'));
    }
}
