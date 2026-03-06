<x-layouts.portal title="Events">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Events</h1>
            <p class="text-sm text-gray-500">Browse and register for upcoming PSK events</p>
        </div>
        <x-ui.button href="{{ route('portal.events.my-registrations') }}" variant="secondary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            My Registrations
        </x-ui.button>
    </div>

    {{-- Filter by Type --}}
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <a href="{{ route('portal.events.index') }}"
           class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ !request('type') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            All
        </a>
        @foreach(\App\Enums\EventType::cases() as $type)
            <a href="{{ route('portal.events.index', ['type' => $type->value]) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ request('type') === $type->value ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $type->label() }}
            </a>
        @endforeach
    </div>

    {{-- Events Grid --}}
    @if($events->count())
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($events as $event)
                <x-ui.card :padding="false" class="flex flex-col overflow-hidden">
                    {{-- Event Image / Placeholder --}}
                    @php
                        $typeColors = [
                            'conference' => 'from-primary-500 to-primary-700',
                            'workshop' => 'from-blue-500 to-blue-700',
                            'seminar' => 'from-purple-500 to-purple-700',
                            'webinar' => 'from-indigo-500 to-indigo-700',
                            'agm' => 'from-green-500 to-green-700',
                            'social' => 'from-orange-500 to-orange-700',
                            'other' => 'from-gray-500 to-gray-700',
                        ];
                        $gradient = $typeColors[$event->type->value] ?? $typeColors['other'];
                    @endphp
                    <div class="relative flex h-40 items-center justify-center bg-gradient-to-br {{ $gradient }}">
                        @if($event->featured_image_path)
                            <img src="{{ Storage::url($event->featured_image_path) }}" alt="{{ $event->title }}" class="absolute inset-0 h-full w-full object-cover">
                        @else
                            <svg class="h-12 w-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                        <div class="absolute left-3 top-3">
                            <x-ui.badge color="indigo" size="sm">{{ $event->type->label() }}</x-ui.badge>
                        </div>
                    </div>

                    {{-- Card Content --}}
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="line-clamp-2 text-lg font-semibold text-gray-900">{{ $event->title }}</h3>

                        <div class="mt-3 space-y-2 text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>
                                    {{ $event->start_date->format('d M Y') }}
                                    @if($event->end_date && !$event->start_date->isSameDay($event->end_date))
                                        - {{ $event->end_date->format('d M Y') }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $event->venue_name ?? ($event->is_virtual ? 'Virtual Event' : 'TBA') }}</span>
                            </div>
                        </div>

                        {{-- Tags --}}
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            @if($event->cpd_points)
                                <x-ui.badge color="blue" size="xs">{{ $event->cpd_points }} CPD Points</x-ui.badge>
                            @endif
                            @if($event->is_virtual)
                                <x-ui.badge color="purple" size="xs">Virtual</x-ui.badge>
                            @endif
                        </div>

                        {{-- Price & Action --}}
                        <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-4 mt-4">
                            <div>
                                @php
                                    $prices = $event->ticketTypes->where('is_active', true)->pluck('price');
                                    $minPrice = $prices->min();
                                    $maxPrice = $prices->max();
                                @endphp
                                @if($prices->count())
                                    <span class="text-lg font-bold text-gray-900">
                                        @if($minPrice == 0 && $maxPrice == 0)
                                            Free
                                        @elseif($minPrice == $maxPrice)
                                            KES {{ number_format($minPrice) }}
                                        @else
                                            KES {{ number_format($minPrice) }} - {{ number_format($maxPrice) }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">No tickets</span>
                                @endif
                            </div>
                            <x-ui.button href="{{ route('portal.events.show', $event) }}" size="sm">
                                View Details
                            </x-ui.button>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $events->withQueryString()->links() }}
        </div>
    @else
        <x-ui.empty-state title="No events available" description="There are no upcoming events at the moment. Check back soon for new events.">
            <x-slot:icon>
                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </x-slot:icon>
        </x-ui.empty-state>
    @endif
</x-layouts.portal>
