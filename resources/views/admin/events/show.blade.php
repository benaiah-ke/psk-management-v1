<x-layouts.admin title="{{ $event->title }}">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h1>
                <x-ui.badge color="indigo">{{ $event->type->label() }}</x-ui.badge>
                <x-ui.badge :color="$event->status->color()">{{ $event->status->label() }}</x-ui.badge>
            </div>
            <p class="mt-1 text-sm text-gray-500">
                Created {{ $event->created_at->format('d M Y') }}
                @if($event->creator)
                    by {{ $event->creator->first_name }} {{ $event->creator->last_name }}
                @endif
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($event->status === \App\Enums\EventStatus::Draft)
                <form method="POST" action="{{ route('admin.events.publish', $event) }}">
                    @csrf
                    <x-ui.button type="submit" variant="success">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Publish
                    </x-ui.button>
                </form>
            @elseif(in_array($event->status, [\App\Enums\EventStatus::Published, \App\Enums\EventStatus::RegistrationOpen]))
                <form method="POST" action="{{ route('admin.events.publish', $event) }}">
                    @csrf
                    <x-ui.button type="submit" variant="secondary">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Unpublish
                    </x-ui.button>
                </form>
            @endif
            <x-ui.button href="{{ route('admin.events.edit', $event) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </x-ui.button>
            <x-ui.button href="{{ route('admin.events.registrations', $event) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Registrations
            </x-ui.button>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-ui.stat-card label="Registrations" color="blue">
            <x-slot:value>
                {{ $event->registered_count }}@if($event->max_attendees) / {{ $event->max_attendees }}@endif
            </x-slot:value>
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Revenue (KES)" :value="number_format($revenue ?? 0)" color="green">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Check-ins" :value="$checkIns ?? 0" color="purple">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Capacity Bar --}}
    @if($event->max_attendees)
        <x-ui.card class="mb-6">
            <div class="flex items-center justify-between text-sm">
                <span class="font-medium text-gray-700">Registration Capacity</span>
                <span class="text-gray-500">{{ $event->registered_count }} of {{ $event->max_attendees }} spots filled</span>
            </div>
            @php
                $percentage = min(100, round(($event->registered_count / $event->max_attendees) * 100));
            @endphp
            <div class="mt-2 h-3 w-full overflow-hidden rounded-full bg-gray-200">
                <div class="h-full rounded-full transition-all {{ $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-primary-600') }}"
                     style="width: {{ $percentage }}%"></div>
            </div>
            <p class="mt-1 text-xs text-gray-500">{{ $percentage }}% full</p>
        </x-ui.card>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Event Details --}}
        <div class="lg:col-span-2 space-y-6">
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Event Details</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->start_date->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">End Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->end_date ? $event->end_date->format('d M Y, H:i') : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Venue</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $event->venue_name ?? '-' }}
                            @if($event->is_virtual)
                                <x-ui.badge color="blue" size="xs" class="ml-1">Virtual</x-ui.badge>
                            @endif
                        </dd>
                    </div>
                    @if($event->venue_address)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $event->venue_address }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">CPD Points</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->cpd_points ?? 0 }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cost Center</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->costCenter?->name ?? '-' }}</dd>
                    </div>
                    @if($event->registration_opens)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Opens</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $event->registration_opens->format('d M Y, H:i') }}</dd>
                        </div>
                    @endif
                    @if($event->registration_closes)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Closes</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $event->registration_closes->format('d M Y, H:i') }}</dd>
                        </div>
                    @endif
                </dl>

                @if($event->description)
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-2 prose prose-sm max-w-none text-gray-700">{!! nl2br(e($event->description)) !!}</dd>
                    </div>
                @endif
            </x-ui.card>

            {{-- Sessions --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Sessions</h3>
                @if($event->sessions->count())
                    <div class="space-y-3">
                        @foreach($event->sessions as $session)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $session->title }}</h4>
                                        @if($session->speaker)
                                            <p class="mt-0.5 text-sm text-gray-500">Speaker: {{ $session->speaker }}</p>
                                        @endif
                                    </div>
                                    @if($session->cpd_points)
                                        <x-ui.badge color="blue" size="xs">{{ $session->cpd_points }} CPD</x-ui.badge>
                                    @endif
                                </div>
                                <div class="mt-2 flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                    @if($session->start_time && $session->end_time)
                                        <span class="flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}
                                        </span>
                                    @endif
                                    @if($session->venue)
                                        <span class="flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $session->venue }}
                                        </span>
                                    @endif
                                </div>
                                @if($session->description)
                                    <p class="mt-2 text-sm text-gray-600">{{ $session->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state title="No sessions" description="No sessions have been added to this event yet." />
                @endif
            </x-ui.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Ticket Types --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Ticket Types</h3>
                @if($event->ticketTypes->count())
                    <div class="space-y-3">
                        @foreach($event->ticketTypes as $ticket)
                            <div class="rounded-lg border border-gray-200 p-3">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-medium text-gray-900">{{ $ticket->name }}</h4>
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $ticket->price > 0 ? 'KES ' . number_format($ticket->price) : 'Free' }}
                                    </span>
                                </div>
                                <div class="mt-1 flex items-center gap-3 text-xs text-gray-500">
                                    @if($ticket->quantity)
                                        <span>{{ $ticket->sold_count ?? 0 }} / {{ $ticket->quantity }} sold</span>
                                    @else
                                        <span>{{ $ticket->sold_count ?? 0 }} sold</span>
                                    @endif
                                    <x-ui.badge :color="$ticket->is_active ? 'green' : 'gray'" size="xs">
                                        {{ $ticket->is_active ? 'Active' : 'Inactive' }}
                                    </x-ui.badge>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state title="No ticket types" description="Add ticket types to enable registrations." />
                @endif
            </x-ui.card>

            {{-- Sponsors --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Sponsors</h3>
                @if($event->sponsors->count())
                    <div class="space-y-3">
                        @foreach($event->sponsors as $sponsor)
                            <div class="flex items-center gap-3 rounded-lg border border-gray-200 p-3">
                                @if($sponsor->logo_path)
                                    <img src="{{ Storage::url($sponsor->logo_path) }}" alt="{{ $sponsor->company }}" class="h-10 w-10 rounded object-contain">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded bg-gray-100 text-xs font-medium text-gray-500">
                                        {{ strtoupper(substr($sponsor->company ?? $sponsor->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium text-gray-900">{{ $sponsor->company ?? $sponsor->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $sponsor->tier?->value ? ucfirst($sponsor->tier->value) : '-' }} Sponsor</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state title="No sponsors" description="No sponsors have been added yet." />
                @endif
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
