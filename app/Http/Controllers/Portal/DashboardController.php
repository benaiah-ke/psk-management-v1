<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $membership = $user->membership;

        $cpdPoints = $user->getCpdPointsForYear(now()->year);
        $cpdTarget = 40;
        $cpdPercentage = min(100, ($cpdPoints / $cpdTarget) * 100);

        $pendingInvoices = $user->invoices()->unpaid()->count();
        $openTickets = $user->tickets()->open()->count();

        $upcomingRegistrations = $user->eventRegistrations()
            ->whereHas('event', fn($q) => $q->where('start_date', '>=', now()))
            ->with('event')
            ->take(3)
            ->get();

        $upcomingEventsCount = $user->eventRegistrations()
            ->whereHas('event', fn($q) => $q->where('start_date', '>=', now()))
            ->count();

        $recentCpdActivities = $user->cpdActivities()->with('category')->latest()->take(5)->get();

        $membershipExpiringSoon = false;
        $membershipExpired = false;
        if ($membership && $membership->expiry_date) {
            $membershipExpired = $membership->expiry_date->isPast();
            $membershipExpiringSoon = !$membershipExpired && $membership->expiry_date->diffInDays(now()) <= 30;
        }

        return view('portal.dashboard', compact(
            'user', 'membership', 'cpdPoints', 'cpdTarget', 'cpdPercentage',
            'pendingInvoices', 'openTickets', 'upcomingRegistrations',
            'upcomingEventsCount', 'recentCpdActivities',
            'membershipExpiringSoon', 'membershipExpired',
        ));
    }
}
