<x-layouts.portal title="My Registrations">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Registrations</h1>
            <p class="text-sm text-gray-500">Your event registrations and attendance history</p>
        </div>
        <x-ui.button href="{{ route('portal.events.index') }}" variant="secondary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Browse Events
        </x-ui.button>
    </div>

    {{-- Registrations Table --}}
    @if($registrations->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading>Event</x-table.heading>
                <x-table.heading>Date</x-table.heading>
                <x-table.heading>Ticket Type</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($registrations as $registration)
                <tr>
                    <x-table.cell>
                        <a href="{{ route('portal.events.show', $registration->event) }}" class="font-medium text-gray-900 hover:text-primary-600">
                            {{ $registration->event->title }}
                        </a>
                        <p class="text-xs text-gray-500">
                            {{ $registration->event->venue_name ?? ($registration->event->is_virtual ? 'Virtual' : '') }}
                        </p>
                    </x-table.cell>
                    <x-table.cell>
                        <p>{{ $registration->event->start_date->format('d M Y') }}</p>
                        @if($registration->event->end_date && !$registration->event->start_date->isSameDay($registration->event->end_date))
                            <p class="text-xs text-gray-400">to {{ $registration->event->end_date->format('d M Y') }}</p>
                        @endif
                    </x-table.cell>
                    <x-table.cell>{{ $registration->ticketType?->name ?? '-' }}</x-table.cell>
                    <x-table.cell>
                        <x-ui.badge :color="$registration->status->color()">{{ $registration->status->label() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-2">
                            <x-ui.button href="{{ route('portal.events.show', $registration->event) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                View
                            </x-ui.button>
                            @if($registration->status === \App\Enums\RegistrationStatus::Pending)
                                <form method="POST" action="{{ route('portal.events.register', $registration->event) }}"
                                      x-data
                                      @submit.prevent="if (confirm('Are you sure you want to cancel this registration?')) $el.submit()">
                                    @csrf
                                    <input type="hidden" name="_cancel" value="1">
                                    <x-ui.button type="submit" variant="danger" size="xs">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Cancel
                                    </x-ui.button>
                                </form>
                            @endif
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $registrations->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No registrations yet" description="You haven't registered for any events. Browse upcoming events to find something interesting.">
            <x-slot:icon>
                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </x-slot:icon>
            <x-ui.button href="{{ route('portal.events.index') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Browse Events
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.portal>
