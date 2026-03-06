<x-layouts.admin title="Registrations - {{ $event->title }}">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.events.show', $event) }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Registrations</h1>
            </div>
            <p class="mt-1 text-sm text-gray-500">{{ $event->title }} &mdash; {{ $registrations->total() }} {{ Str::plural('registration', $registrations->total()) }}</p>
        </div>
        <div class="flex items-center gap-2">
            <x-ui.button href="{{ route('admin.events.registrations', ['event' => $event, 'export' => 'csv']) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export
            </x-ui.button>
            <x-ui.button href="{{ route('admin.events.show', $event) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View Event
            </x-ui.button>
        </div>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.events.registrations', $event) }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <x-form.input name="search" label="Search" placeholder="Search by member name or email..."
                              :value="request('search')" />
            </div>
            <div class="w-full sm:w-48">
                <x-form.select name="status" label="Status" :options="collect(\App\Enums\RegistrationStatus::cases())->mapWithKeys(fn($s) => [$s->value => $s->label()])->toArray()"
                               :selected="request('status')" placeholder="All Statuses" />
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="secondary" size="md">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </x-ui.button>
                @if(request()->hasAny(['search', 'status']))
                    <x-ui.button href="{{ route('admin.events.registrations', $event) }}" variant="ghost" size="md">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- Registrations Table --}}
    @if($registrations->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading>Member Name</x-table.heading>
                <x-table.heading>Ticket Type</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading sortable column="created_at" :direction="request('sort') === 'created_at' ? request('direction') : null">Registration Date</x-table.heading>
                <x-table.heading>Checked In</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($registrations as $registration)
                <tr>
                    <x-table.cell>
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-medium text-primary-700">
                                {{ strtoupper(substr($registration->user->first_name, 0, 1) . substr($registration->user->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $registration->user->last_name }}, {{ $registration->user->first_name }}</p>
                                <p class="text-xs text-gray-500">{{ $registration->user->email }}</p>
                            </div>
                        </div>
                    </x-table.cell>
                    <x-table.cell>{{ $registration->ticketType?->name ?? '-' }}</x-table.cell>
                    <x-table.cell>
                        <x-ui.badge :color="$registration->status->color()">{{ $registration->status->label() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>{{ $registration->created_at->format('d M Y, H:i') }}</x-table.cell>
                    <x-table.cell>
                        @if($registration->isCheckedIn())
                            <span class="inline-flex items-center gap-1 text-green-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Yes
                            </span>
                            <p class="text-xs text-gray-400">{{ $registration->checked_in_at->format('d M Y, H:i') }}</p>
                        @else
                            <span class="text-gray-400">No</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        @if(!$registration->isCheckedIn() && in_array($registration->status, [\App\Enums\RegistrationStatus::Confirmed, \App\Enums\RegistrationStatus::Pending]))
                            <form method="POST" action="{{ route('admin.events.check-in', [$event, $registration]) }}">
                                @csrf
                                <x-ui.button type="submit" variant="success" size="xs">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Check In
                                </x-ui.button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400">&mdash;</span>
                        @endif
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $registrations->withQueryString()->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No registrations found" description="No registrations match your current filters, or no one has registered for this event yet." />
    @endif
</x-layouts.admin>
