<x-layouts.portal title="{{ $event->title }}">
    {{-- Event Banner / Header --}}
    @php
        $typeColors = [
            'conference' => 'from-primary-600 to-primary-800',
            'workshop' => 'from-blue-600 to-blue-800',
            'seminar' => 'from-purple-600 to-purple-800',
            'webinar' => 'from-indigo-600 to-indigo-800',
            'agm' => 'from-green-600 to-green-800',
            'social' => 'from-orange-600 to-orange-800',
            'other' => 'from-gray-600 to-gray-800',
        ];
        $gradient = $typeColors[$event->type->value] ?? $typeColors['other'];
    @endphp
    <div class="relative -mx-4 -mt-6 mb-8 overflow-hidden bg-gradient-to-br {{ $gradient }} px-6 py-12 text-white sm:-mx-6 lg:-mx-8 lg:px-12">
        @if($event->featured_image_path)
            <img src="{{ Storage::url($event->featured_image_path) }}" alt="{{ $event->title }}"
                 class="absolute inset-0 h-full w-full object-cover opacity-30">
        @endif
        <div class="relative max-w-4xl">
            <div class="mb-3 flex flex-wrap items-center gap-2">
                <x-ui.badge color="indigo">{{ $event->type->label() }}</x-ui.badge>
                @if($event->cpd_points)
                    <x-ui.badge color="blue">{{ $event->cpd_points }} CPD Points</x-ui.badge>
                @endif
                @if($event->is_virtual)
                    <x-ui.badge color="purple">Virtual</x-ui.badge>
                @endif
            </div>
            <h1 class="text-3xl font-bold sm:text-4xl">{{ $event->title }}</h1>
            <div class="mt-4 flex flex-col gap-3 text-white/80 sm:flex-row sm:items-center sm:gap-6">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>
                        {{ $event->start_date->format('d M Y, H:i') }}
                        @if($event->end_date)
                            - {{ $event->end_date->format('d M Y, H:i') }}
                        @endif
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ $event->venue_name ?? ($event->is_virtual ? 'Virtual Event' : 'TBA') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Description --}}
            @if($event->description)
                <x-ui.card>
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">About This Event</h2>
                    <div class="prose prose-sm max-w-none text-gray-700">{!! nl2br(e($event->description)) !!}</div>
                </x-ui.card>
            @endif

            {{-- Sessions Schedule --}}
            @if($event->sessions->count())
                <x-ui.card>
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Schedule</h2>
                    <div class="space-y-0">
                        @foreach($event->sessions as $session)
                            <div class="relative flex gap-4 pb-6 last:pb-0 {{ !$loop->last ? 'border-l-2 border-gray-200 ml-3' : 'ml-3' }}">
                                {{-- Timeline Dot --}}
                                <div class="absolute -left-[7px] top-1 h-3 w-3 rounded-full border-2 border-primary-600 bg-white"></div>

                                <div class="ml-6 flex-1">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <h3 class="font-medium text-gray-900">{{ $session->title }}</h3>
                                        @if($session->cpd_points)
                                            <x-ui.badge color="blue" size="xs">{{ $session->cpd_points }} CPD</x-ui.badge>
                                        @endif
                                    </div>
                                    <div class="mt-1 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                        @if($session->start_time && $session->end_time)
                                            <span class="flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}
                                            </span>
                                        @endif
                                        @if($session->speaker)
                                            <span class="flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                {{ $session->speaker }}
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
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            {{-- Sponsors --}}
            @if($event->sponsors->count())
                <x-ui.card>
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Sponsors</h2>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                        @foreach($event->sponsors as $sponsor)
                            <div class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 p-4 text-center">
                                @if($sponsor->logo_path)
                                    <img src="{{ Storage::url($sponsor->logo_path) }}" alt="{{ $sponsor->company }}" class="h-12 w-auto object-contain">
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 text-sm font-bold text-gray-500">
                                        {{ strtoupper(substr($sponsor->company ?? $sponsor->name, 0, 2)) }}
                                    </div>
                                @endif
                                <span class="text-sm font-medium text-gray-700">{{ $sponsor->company ?? $sponsor->name }}</span>
                                @if($sponsor->tier)
                                    <span class="text-xs text-gray-400">{{ ucfirst($sponsor->tier->value) }} Sponsor</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif
        </div>

        {{-- Sidebar: Registration --}}
        <div class="space-y-6">
            {{-- Registration Status (if already registered) --}}
            @if($registration)
                <x-ui.card>
                    <h3 class="mb-3 text-lg font-semibold text-gray-900">Your Registration</h3>
                    <div class="rounded-lg border border-green-200 bg-green-50 p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">You are registered</p>
                                <p class="text-sm text-green-700">Status: {{ $registration->status->label() }}</p>
                            </div>
                        </div>
                    </div>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Registration #</dt>
                            <dd class="font-medium text-gray-900">{{ $registration->registration_number ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Ticket</dt>
                            <dd class="font-medium text-gray-900">{{ $registration->ticketType?->name ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Registered on</dt>
                            <dd class="font-medium text-gray-900">{{ $registration->created_at->format('d M Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Status</dt>
                            <dd><x-ui.badge :color="$registration->status->color()">{{ $registration->status->label() }}</x-ui.badge></dd>
                        </div>
                    </dl>
                </x-ui.card>
            @else
                {{-- Ticket Types & Register --}}
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Register</h3>

                    @if(!$event->isRegistrationOpen())
                        <x-ui.alert type="warning" :dismissible="false">
                            Registration is currently closed for this event.
                        </x-ui.alert>
                    @elseif($event->isFull())
                        <x-ui.alert type="warning" :dismissible="false">
                            This event is at full capacity.
                        </x-ui.alert>
                    @elseif($event->ticketTypes->where('is_active', true)->count())
                        <form method="POST" action="{{ route('portal.events.register', $event) }}">
                            @csrf
                            <div class="space-y-3">
                                @foreach($event->ticketTypes->where('is_active', true) as $ticket)
                                    <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-4 transition hover:border-primary-300 hover:bg-primary-50 has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                                        <input type="radio" name="ticket_type_id" value="{{ $ticket->id }}"
                                               class="mt-0.5 h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500"
                                               {{ $loop->first ? 'checked' : '' }}
                                               {{ !$ticket->isAvailable() ? 'disabled' : '' }}>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-900">{{ $ticket->name }}</span>
                                                <span class="font-semibold text-gray-900">
                                                    {{ $ticket->price > 0 ? 'KES ' . number_format($ticket->price) : 'Free' }}
                                                </span>
                                            </div>
                                            @if($ticket->description)
                                                <p class="mt-0.5 text-xs text-gray-500">{{ $ticket->description }}</p>
                                            @endif
                                            @if($ticket->quantity)
                                                <p class="mt-1 text-xs {{ $ticket->remaining <= 5 ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                                                    {{ $ticket->remaining ?? 0 }} spots remaining
                                                </p>
                                            @endif
                                            @unless($ticket->isAvailable())
                                                <p class="mt-1 text-xs font-medium text-red-500">Sold out</p>
                                            @endunless
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('ticket_type_id')
                                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                            @enderror

                            <x-ui.button type="submit" class="mt-4 w-full">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Register Now
                            </x-ui.button>
                        </form>
                    @else
                        <x-ui.alert type="info" :dismissible="false">
                            No tickets are currently available for this event.
                        </x-ui.alert>
                    @endif
                </x-ui.card>
            @endif

            {{-- Event Info Summary --}}
            <x-ui.card>
                <h3 class="mb-3 text-lg font-semibold text-gray-900">Event Details</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div>
                            <dt class="font-medium text-gray-700">Date & Time</dt>
                            <dd class="text-gray-500">
                                {{ $event->start_date->format('d M Y, H:i') }}
                                @if($event->end_date)
                                    <br>to {{ $event->end_date->format('d M Y, H:i') }}
                                @endif
                            </dd>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div>
                            <dt class="font-medium text-gray-700">Venue</dt>
                            <dd class="text-gray-500">
                                {{ $event->venue_name ?? 'TBA' }}
                                @if($event->venue_address)
                                    <br>{{ $event->venue_address }}
                                @endif
                                @if($event->is_virtual)
                                    <br><span class="text-indigo-600">Virtual / Online</span>
                                @endif
                            </dd>
                        </div>
                    </div>
                    @if($event->cpd_points)
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <div>
                                <dt class="font-medium text-gray-700">CPD Points</dt>
                                <dd class="text-gray-500">{{ $event->cpd_points }} points upon attendance</dd>
                            </div>
                        </div>
                    @endif
                    @if($event->max_attendees)
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div>
                                <dt class="font-medium text-gray-700">Capacity</dt>
                                <dd class="text-gray-500">{{ $event->registered_count }} / {{ $event->max_attendees }} registered</dd>
                            </div>
                        </div>
                    @endif
                </dl>
            </x-ui.card>
        </div>
    </div>
</x-layouts.portal>
