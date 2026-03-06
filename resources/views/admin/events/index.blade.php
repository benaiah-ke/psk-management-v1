<x-layouts.admin title="Events">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Events</h1>
            <p class="text-sm text-gray-500">Manage association events, conferences and workshops</p>
        </div>
        <x-ui.button href="{{ route('admin.events.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Create Event
        </x-ui.button>
    </div>

    {{-- Stat Cards --}}
    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card label="Total Events" :value="$totalEvents ?? 0" color="primary">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Upcoming" :value="$upcomingEvents ?? 0" color="blue">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Registrations" :value="number_format($totalRegistrations ?? 0)" color="green">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Revenue (KES)" :value="number_format($totalRevenue ?? 0)" color="green">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.events.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <x-form.input name="search" label="Search" placeholder="Search by event title..."
                              :value="request('search')" />
            </div>
            <div class="w-full sm:w-48">
                <x-form.select name="status" label="Status" :options="collect(\App\Enums\EventStatus::cases())->mapWithKeys(fn($s) => [$s->value => $s->label()])->toArray()"
                               :selected="request('status')" placeholder="All Statuses" />
            </div>
            <div class="w-full sm:w-44">
                <x-form.select name="type" label="Type" :options="collect(\App\Enums\EventType::cases())->mapWithKeys(fn($t) => [$t->value => $t->label()])->toArray()"
                               :selected="request('type')" placeholder="All Types" />
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="secondary" size="md">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </x-ui.button>
                @if(request()->hasAny(['search', 'status', 'type']))
                    <x-ui.button href="{{ route('admin.events.index') }}" variant="ghost" size="md">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- Events Table --}}
    @if($events->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="title" :direction="request('sort') === 'title' ? request('direction') : null">Title</x-table.heading>
                <x-table.heading>Type</x-table.heading>
                <x-table.heading sortable column="start_date" :direction="request('sort') === 'start_date' ? request('direction') : null">Dates</x-table.heading>
                <x-table.heading>Venue</x-table.heading>
                <x-table.heading>Registrations</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($events as $event)
                <tr>
                    <x-table.cell>
                        <a href="{{ route('admin.events.show', $event) }}" class="font-medium text-gray-900 hover:text-primary-600">
                            {{ $event->title }}
                        </a>
                    </x-table.cell>
                    <x-table.cell>
                        <x-ui.badge color="indigo">{{ $event->type->label() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="text-sm">
                            <p>{{ $event->start_date->format('d M Y') }}</p>
                            @if($event->end_date && !$event->start_date->isSameDay($event->end_date))
                                <p class="text-gray-400">to {{ $event->end_date->format('d M Y') }}</p>
                            @endif
                        </div>
                    </x-table.cell>
                    <x-table.cell>{{ $event->venue_name ?? ($event->is_virtual ? 'Virtual' : '-') }}</x-table.cell>
                    <x-table.cell>
                        <span class="font-medium text-gray-900">{{ $event->registrations_count ?? 0 }}</span>
                        @if($event->max_attendees)
                            <span class="text-gray-400">/ {{ $event->max_attendees }}</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <x-ui.badge :color="$event->status->color()">{{ $event->status->label() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.events.show', $event) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </x-ui.button>
                            <x-ui.button href="{{ route('admin.events.edit', $event) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-ui.button>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $events->withQueryString()->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No events found" description="No events match your current filters. Try adjusting your search or create a new event.">
            <x-ui.button href="{{ route('admin.events.create') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Event
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
