<x-layouts.portal title="Dashboard">
    {{-- Welcome Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->first_name }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
    </div>

    {{-- Membership Status Card --}}
    <div class="mb-6">
        @if($membership)
            <x-ui.card>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full
                            {{ $membershipExpired ? 'bg-red-100' : ($membershipExpiringSoon ? 'bg-yellow-100' : 'bg-green-100') }}">
                            <svg class="h-6 w-6 {{ $membershipExpired ? 'text-red-600' : ($membershipExpiringSoon ? 'text-yellow-600' : 'text-green-600') }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-lg font-semibold text-gray-900">{{ $membership->tier?->name ?? 'Member' }}</h2>
                                <x-ui.badge :color="$membership->status->color()" size="xs">{{ $membership->status->label() }}</x-ui.badge>
                            </div>
                            <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500">
                                <span>No: <span class="font-medium text-gray-700">{{ $membership->membership_number }}</span></span>
                                <span>Expires: <span class="font-medium {{ $membershipExpired ? 'text-red-600' : ($membershipExpiringSoon ? 'text-yellow-600' : 'text-gray-700') }}">
                                    {{ $membership->expiry_date?->format('M j, Y') ?? 'N/A' }}
                                </span></span>
                            </div>
                        </div>
                    </div>
                    @if($membershipExpiringSoon || $membershipExpired)
                        <x-ui.button href="{{ route('portal.membership.renew') }}" variant="primary" size="sm">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Renew Membership
                        </x-ui.button>
                    @else
                        <x-ui.button href="{{ route('portal.membership') }}" variant="secondary" size="sm">
                            View Membership
                        </x-ui.button>
                    @endif
                </div>
            </x-ui.card>
        @else
            <x-ui.card class="border-primary-200 bg-primary-50">
                <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-100">
                            <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Become a PSK Member</h2>
                            <p class="mt-1 text-sm text-gray-600">Join the Pharmaceutical Society of Kenya and access exclusive benefits, CPD tracking, and more.</p>
                        </div>
                    </div>
                    <x-ui.button href="{{ route('portal.membership.apply') }}" variant="primary" size="sm">
                        Apply Now
                    </x-ui.button>
                </div>
            </x-ui.card>
        @endif
    </div>

    {{-- Stat Cards Row --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- CPD Points Card (custom layout for progress bar) --}}
        <x-ui.card>
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">CPD Points ({{ now()->year }})</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $cpdPoints }} <span class="text-sm font-normal text-gray-400">/ {{ $cpdTarget }}</span></p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                    <div class="h-2 rounded-full transition-all duration-500 {{ $cpdPoints >= $cpdTarget ? 'bg-green-500' : ($cpdPoints >= 20 ? 'bg-yellow-500' : 'bg-red-500') }}"
                         style="width: {{ $cpdPercentage }}%"></div>
                </div>
                <p class="mt-1 text-xs text-gray-400">{{ round($cpdPercentage) }}% complete</p>
            </div>
        </x-ui.card>

        {{-- Pending Invoices --}}
        <x-ui.stat-card
            label="Pending Invoices"
            :value="$pendingInvoices"
            color="yellow"
        >
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Upcoming Events --}}
        <x-ui.stat-card
            label="Upcoming Events"
            :value="$upcomingEventsCount"
            color="blue"
        >
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        {{-- Open Tickets --}}
        <x-ui.stat-card
            label="Open Tickets"
            :value="$openTickets"
            color="red"
        >
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Two Column Section: Upcoming Events + Quick Actions --}}
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Left: Upcoming Events (2/3) --}}
        <div class="lg:col-span-2">
            <x-ui.card>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900">Upcoming Events</h3>
                    <x-ui.button href="{{ route('portal.events.index') }}" variant="ghost" size="xs">
                        View All
                    </x-ui.button>
                </div>

                @if($upcomingRegistrations->isNotEmpty())
                    <div class="divide-y divide-gray-100">
                        @foreach($upcomingRegistrations as $registration)
                            <div class="flex items-start gap-4 py-3 first:pt-0 last:pb-0">
                                <div class="flex h-10 w-10 flex-shrink-0 flex-col items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                                    <span class="text-xs font-bold leading-none">{{ $registration->event->start_date->format('d') }}</span>
                                    <span class="text-[10px] uppercase leading-none">{{ $registration->event->start_date->format('M') }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900">{{ $registration->event->title }}</p>
                                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $registration->event->start_date->format('g:i A') }}
                                        </span>
                                        @if($registration->event->venue)
                                            <span class="flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $registration->event->venue }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state
                        title="No upcoming events"
                        description="You haven't registered for any upcoming events yet."
                    >
                        <x-ui.button href="{{ route('portal.events.index') }}" variant="secondary" size="sm">
                            Browse Events
                        </x-ui.button>
                    </x-ui.empty-state>
                @endif
            </x-ui.card>
        </div>

        {{-- Right: Quick Actions (1/3) --}}
        <div>
            <x-ui.card>
                <h3 class="mb-4 text-base font-semibold text-gray-900">Quick Actions</h3>
                <div class="flex flex-col gap-2">
                    <x-ui.button href="{{ route('portal.cpd.create') }}" variant="secondary" size="sm" class="w-full justify-start">
                        <svg class="h-4 w-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Log CPD Activity
                    </x-ui.button>
                    <x-ui.button href="{{ route('portal.events.index') }}" variant="secondary" size="sm" class="w-full justify-start">
                        <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Browse Events
                    </x-ui.button>
                    <x-ui.button href="{{ route('portal.tickets.create') }}" variant="secondary" size="sm" class="w-full justify-start">
                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Submit Support Ticket
                    </x-ui.button>
                    <x-ui.button href="{{ route('portal.invoices.index') }}" variant="secondary" size="sm" class="w-full justify-start">
                        <svg class="h-4 w-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        View Invoices
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </div>

    {{-- Recent CPD Activities --}}
    <div>
        <x-ui.card>
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">Recent CPD Activities</h3>
                <x-ui.button href="{{ route('portal.cpd.index') }}" variant="ghost" size="xs">
                    View All
                </x-ui.button>
            </div>

            @if($recentCpdActivities->isNotEmpty())
                <x-table.table>
                    <x-slot:head>
                        <x-table.heading>Activity</x-table.heading>
                        <x-table.heading>Category</x-table.heading>
                        <x-table.heading>Points</x-table.heading>
                        <x-table.heading>Status</x-table.heading>
                        <x-table.heading>Date</x-table.heading>
                    </x-slot:head>
                    @foreach($recentCpdActivities as $activity)
                        <tr>
                            <x-table.cell><span class="font-medium text-gray-900">{{ Str::limit($activity->title ?? $activity->description ?? '-', 40) }}</span></x-table.cell>
                            <x-table.cell>{{ $activity->category?->name ?? '-' }}</x-table.cell>
                            <x-table.cell><span class="font-semibold text-gray-900">{{ $activity->points }}</span></x-table.cell>
                            <x-table.cell>
                                <x-ui.badge :color="$activity->status->color()" size="xs">{{ $activity->status->label() }}</x-ui.badge>
                            </x-table.cell>
                            <x-table.cell>{{ $activity->created_at->format('M j, Y') }}</x-table.cell>
                        </tr>
                    @endforeach
                </x-table.table>
            @else
                <x-ui.empty-state
                    title="No CPD activities yet"
                    description="Start logging your continuing professional development activities to track your progress."
                >
                    <x-ui.button href="{{ route('portal.cpd.create') }}" variant="secondary" size="sm">
                        Log CPD Activity
                    </x-ui.button>
                </x-ui.empty-state>
            @endif
        </x-ui.card>
    </div>
</x-layouts.portal>
