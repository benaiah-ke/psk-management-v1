<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Membership;
use App\Models\MembershipApplication;
use App\Models\MembershipTier;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMembers = User::whereHas('memberships')->count();
        $pendingApplications = MembershipApplication::pending()->count();
        $totalRevenue = Payment::where('status', PaymentStatus::Completed)->sum('amount');
        $upcomingEvents = Event::where('start_date', '>=', now())->count();

        $openTickets = Ticket::whereIn('status', [TicketStatus::Open, TicketStatus::InProgress])->count();

        $expiringMemberships = Membership::expiringSoon()->count();

        $membersByTier = MembershipTier::withCount('activeMemberships')
            ->get()
            ->map(fn ($tier) => [
                'label' => $tier->name,
                'count' => $tier->active_memberships_count,
            ])
            ->toArray();

        $monthlyRevenue = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = Carbon::now()->subMonths($monthsAgo);

            return [
                'month' => $date->format('M'),
                'amount' => (float) Payment::where('status', PaymentStatus::Completed)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('amount'),
            ];
        })->toArray();

        $recentApplications = MembershipApplication::with(['user', 'tier'])
            ->latest('created_at')
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['invoice.user'])
            ->latest('created_at')
            ->take(5)
            ->get();

        $upcomingEventsList = Event::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMembers',
            'pendingApplications',
            'totalRevenue',
            'upcomingEvents',
            'openTickets',
            'expiringMemberships',
            'membersByTier',
            'monthlyRevenue',
            'recentApplications',
            'recentPayments',
            'upcomingEventsList',
        ));
    }
}
