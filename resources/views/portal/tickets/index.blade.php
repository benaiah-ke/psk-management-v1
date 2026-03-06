<x-layouts.portal title="My Tickets">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Tickets</h1>
            <p class="text-sm text-gray-500">Track and manage your support requests.</p>
        </div>
        <x-ui.button href="{{ route('portal.tickets.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            New Ticket
        </x-ui.button>
    </div>

    @if($tickets->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading>Ticket #</x-table.heading>
                <x-table.heading>Subject</x-table.heading>
                <x-table.heading>Category</x-table.heading>
                <x-table.heading>Priority</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Last Updated</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($tickets as $ticket)
                <tr>
                    <x-table.cell>
                        <a href="{{ route('portal.tickets.show', $ticket) }}" class="font-medium text-primary-600 hover:text-primary-700">
                            #{{ $ticket->ticket_number }}
                        </a>
                    </x-table.cell>
                    <x-table.cell>
                        <p class="max-w-xs truncate font-medium text-gray-900">{{ $ticket->subject }}</p>
                    </x-table.cell>
                    <x-table.cell>{{ $ticket->category->name ?? '-' }}</x-table.cell>
                    <x-table.cell>
                        @php
                            $priorityColors = ['low' => 'gray', 'medium' => 'blue', 'high' => 'yellow', 'urgent' => 'red'];
                            $pColor = $priorityColors[$ticket->priority->value ?? $ticket->priority] ?? 'gray';
                        @endphp
                        <x-ui.badge :color="$pColor">{{ ucfirst($ticket->priority->value ?? $ticket->priority) }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        @php
                            $statusColors = ['open' => 'blue', 'in_progress' => 'yellow', 'awaiting_response' => 'purple', 'resolved' => 'green', 'closed' => 'gray'];
                            $sColor = $statusColors[$ticket->status->value ?? $ticket->status] ?? 'gray';
                        @endphp
                        <x-ui.badge :color="$sColor">{{ ucfirst(str_replace('_', ' ', $ticket->status->value ?? $ticket->status)) }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>{{ $ticket->updated_at->diffForHumans() }}</x-table.cell>
                    <x-table.cell>
                        <x-ui.button href="{{ route('portal.tickets.show', $ticket) }}" variant="ghost" size="xs">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View
                        </x-ui.button>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $tickets->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No tickets yet" description="You haven't submitted any support tickets. Need help? Create a new ticket.">
            <x-ui.button href="{{ route('portal.tickets.create') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                New Ticket
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.portal>
