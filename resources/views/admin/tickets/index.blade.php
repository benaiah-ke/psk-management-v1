<x-layouts.admin title="Tickets">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Support Tickets</h1>
            <p class="text-sm text-gray-500">{{ $tickets->total() }} {{ Str::plural('ticket', $tickets->total()) }} total</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card label="Open" :value="$openCount ?? 0" color="blue">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="In Progress" :value="$inProgressCount ?? 0" color="yellow">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Awaiting Response" :value="$awaitingResponseCount ?? 0" color="purple">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Resolved This Month" :value="$resolvedThisMonthCount ?? 0" color="green">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <x-form.input name="search" label="Search" placeholder="Search by ticket #, subject, or member..."
                              :value="request('search')" />
            </div>
            <div class="w-full sm:w-40">
                <x-form.select name="status" label="Status" :options="[
                    'open' => 'Open',
                    'in_progress' => 'In Progress',
                    'awaiting_response' => 'Awaiting Response',
                    'resolved' => 'Resolved',
                    'closed' => 'Closed',
                ]" :selected="request('status')" placeholder="All Statuses" />
            </div>
            <div class="w-full sm:w-36">
                <x-form.select name="priority" label="Priority" :options="[
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'urgent' => 'Urgent',
                ]" :selected="request('priority')" placeholder="All Priorities" />
            </div>
            <div class="w-full sm:w-40">
                <x-form.select name="category" label="Category"
                               :options="$categories->pluck('name', 'id')->toArray()"
                               :selected="request('category')" placeholder="All Categories" />
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="secondary" size="md">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </x-ui.button>
                @if(request()->hasAny(['search', 'status', 'priority', 'category']))
                    <x-ui.button href="{{ route('admin.tickets.index') }}" variant="ghost" size="md">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- Tickets Table --}}
    @if($tickets->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="ticket_number" :direction="request('sort') === 'ticket_number' ? request('direction') : null">Ticket #</x-table.heading>
                <x-table.heading>Subject</x-table.heading>
                <x-table.heading>Member</x-table.heading>
                <x-table.heading>Category</x-table.heading>
                <x-table.heading>Priority</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Assigned To</x-table.heading>
                <x-table.heading sortable column="created_at" :direction="request('sort') === 'created_at' ? request('direction') : null">Created</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($tickets as $ticket)
                <tr>
                    <x-table.cell>
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="font-medium text-primary-600 hover:text-primary-700">
                            #{{ $ticket->ticket_number }}
                        </a>
                    </x-table.cell>
                    <x-table.cell>
                        <p class="max-w-xs truncate font-medium text-gray-900">{{ $ticket->subject }}</p>
                    </x-table.cell>
                    <x-table.cell>
                        @if($ticket->user)
                            <div class="flex items-center gap-2">
                                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary-100 text-xs font-medium text-primary-700">
                                    {{ strtoupper(substr($ticket->user->first_name, 0, 1) . substr($ticket->user->last_name, 0, 1)) }}
                                </div>
                                <span class="text-sm text-gray-900">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</span>
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
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
                    <x-table.cell>
                        @if($ticket->assignedTo)
                            <span class="text-sm text-gray-900">{{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}</span>
                        @else
                            <span class="text-gray-400">Unassigned</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>{{ $ticket->created_at->format('d M Y') }}</x-table.cell>
                    <x-table.cell>
                        <x-ui.button href="{{ route('admin.tickets.show', $ticket) }}" variant="ghost" size="xs">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View
                        </x-ui.button>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $tickets->withQueryString()->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No tickets found" description="No tickets match your current filters. Try adjusting your search criteria." />
    @endif
</x-layouts.admin>
