<x-layouts.portal title="Ticket #{{ $ticket->ticket_number }}">
    {{-- Ticket Header --}}
    <div class="mb-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->subject }}</h1>
                <div class="mt-2 flex flex-wrap items-center gap-2">
                    <span class="text-sm text-gray-500">#{{ $ticket->ticket_number }}</span>
                    @php
                        $statusColors = ['open' => 'blue', 'in_progress' => 'yellow', 'awaiting_response' => 'purple', 'resolved' => 'green', 'closed' => 'gray'];
                        $sColor = $statusColors[$ticket->status->value ?? $ticket->status] ?? 'gray';
                        $priorityColors = ['low' => 'gray', 'medium' => 'blue', 'high' => 'yellow', 'urgent' => 'red'];
                        $pColor = $priorityColors[$ticket->priority->value ?? $ticket->priority] ?? 'gray';
                    @endphp
                    <x-ui.badge :color="$sColor">{{ ucfirst(str_replace('_', ' ', $ticket->status->value ?? $ticket->status)) }}</x-ui.badge>
                    <x-ui.badge :color="$pColor">{{ ucfirst($ticket->priority->value ?? $ticket->priority) }} Priority</x-ui.badge>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Submitted {{ $ticket->created_at->format('d M Y \a\t H:i') }}
                    @if($ticket->category)
                        &middot; {{ $ticket->category->name }}
                    @endif
                </p>
            </div>
            <x-ui.button href="{{ route('portal.tickets.index') }}" variant="secondary" size="sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Tickets
            </x-ui.button>
        </div>
    </div>

    <div class="mx-auto max-w-3xl space-y-6">
        {{-- Original Description --}}
        <x-ui.card>
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-medium text-primary-700">
                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-900">You</span>
                        <span class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="mt-2 prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>
                </div>
            </div>
        </x-ui.card>

        {{-- Responses (excluding internal notes) --}}
        @foreach($ticket->responses as $response)
            @if(!($response->is_internal ?? false))
                @php
                    $isStaff = $response->user && ($response->user->is_admin ?? false);
                    $isOwn = $response->user_id === auth()->id();
                @endphp
                <x-ui.card>
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $isStaff ? 'bg-green-100 text-green-700' : 'bg-primary-100 text-primary-700' }} text-sm font-medium">
                            {{ strtoupper(substr($response->user->first_name ?? '', 0, 1) . substr($response->user->last_name ?? '', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">
                                    @if($isOwn)
                                        You
                                    @else
                                        {{ $response->user->first_name ?? '' }} {{ $response->user->last_name ?? '' }}
                                    @endif
                                </span>
                                @if($isStaff)
                                    <x-ui.badge color="green" size="xs">Staff</x-ui.badge>
                                @endif
                                <span class="text-xs text-gray-500">{{ $response->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="mt-2 prose prose-sm max-w-none text-gray-700">
                                {!! nl2br(e($response->body)) !!}
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            @endif
        @endforeach

        {{-- Reply Form --}}
        @if(!in_array($ticket->status->value ?? $ticket->status, ['closed', 'resolved']))
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Reply</h3>
                <form method="POST" action="{{ route('portal.tickets.respond', $ticket) }}">
                    @csrf
                    <x-form.textarea name="body" label="Your Message" rows="4" required
                                     placeholder="Type your reply..." />
                    <div class="mt-4 flex justify-end">
                        <x-ui.button type="submit">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Send Reply
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        @else
            <x-ui.alert type="info" :dismissible="false">
                This ticket has been {{ $ticket->status->value ?? $ticket->status }}. If you need further assistance, please open a new ticket.
            </x-ui.alert>
        @endif
    </div>
</x-layouts.portal>
