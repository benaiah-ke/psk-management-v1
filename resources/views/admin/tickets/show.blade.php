<x-layouts.admin title="Ticket #{{ $ticket->ticket_number }}">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Tickets', 'url' => route('admin.tickets.index')],
        ['label' => '#' . $ticket->ticket_number],
    ]" />

    {{-- Ticket Header --}}
    <div class="mb-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->subject }}</h1>
                </div>
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
                    @if($ticket->type)
                        <x-ui.badge color="indigo">{{ ucfirst($ticket->type->value ?? $ticket->type) }}</x-ui.badge>
                    @endif
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Opened {{ $ticket->created_at->format('d M Y \a\t H:i') }} by
                    {{ $ticket->user->first_name ?? '' }} {{ $ticket->user->last_name ?? '' }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main Content: Conversation Thread --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Original Description --}}
            <x-ui.card>
                <div class="flex items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-medium text-primary-700">
                        {{ strtoupper(substr($ticket->user->first_name ?? '', 0, 1) . substr($ticket->user->last_name ?? '', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-900">{{ $ticket->user->first_name ?? '' }} {{ $ticket->user->last_name ?? '' }}</span>
                            <x-ui.badge color="blue" size="xs">Author</x-ui.badge>
                            <span class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="mt-2 prose prose-sm max-w-none text-gray-700">
                            {!! nl2br(e($ticket->description)) !!}
                        </div>
                    </div>
                </div>
            </x-ui.card>

            {{-- Responses --}}
            @foreach($ticket->responses as $response)
                @php
                    $isInternal = $response->is_internal ?? false;
                    $isStaff = $response->user && ($response->user->is_admin ?? false);
                @endphp
                <x-ui.card class="{{ $isInternal ? 'border-yellow-200 bg-yellow-50' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $isStaff ? 'bg-green-100 text-green-700' : 'bg-primary-100 text-primary-700' }} text-sm font-medium">
                            {{ strtoupper(substr($response->user->first_name ?? '', 0, 1) . substr($response->user->last_name ?? '', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-medium text-gray-900">{{ $response->user->first_name ?? '' }} {{ $response->user->last_name ?? '' }}</span>
                                @if($isStaff)
                                    <x-ui.badge color="green" size="xs">Staff</x-ui.badge>
                                @endif
                                @if($isInternal)
                                    <x-ui.badge color="yellow" size="xs">Internal Note</x-ui.badge>
                                @endif
                                <span class="text-xs text-gray-500">{{ $response->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="mt-2 prose prose-sm max-w-none text-gray-700">
                                {!! nl2br(e($response->body)) !!}
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach

            {{-- Response Form --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Add Response</h3>
                <form method="POST" action="{{ route('admin.tickets.respond', $ticket) }}">
                    @csrf
                    <x-form.textarea name="body" label="Response" rows="4" required
                                     placeholder="Type your response..." />

                    <div class="mt-4 flex items-center justify-between">
                        <x-form.checkbox name="is_internal" label="Internal note (not visible to member)" />
                        <x-ui.button type="submit">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Send Response
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Member Info --}}
            <x-ui.card>
                <h3 class="mb-3 text-lg font-semibold text-gray-900">Member</h3>
                @if($ticket->user)
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-700">
                            {{ strtoupper(substr($ticket->user->first_name, 0, 1) . substr($ticket->user->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</p>
                            <p class="text-sm text-gray-500">{{ $ticket->user->email }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <x-ui.button href="{{ route('admin.members.show', $ticket->user) }}" variant="secondary" size="sm" class="w-full">
                            View Member Profile
                        </x-ui.button>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No member associated.</p>
                @endif
            </x-ui.card>

            {{-- Assignment --}}
            <x-ui.card>
                <h3 class="mb-3 text-lg font-semibold text-gray-900">Assignment</h3>
                <form method="POST" action="{{ route('admin.tickets.assign', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <x-form.select name="assigned_to" label="Assign To"
                                   :options="$admins->pluck('full_name', 'id')->toArray()"
                                   :selected="$ticket->assigned_to"
                                   placeholder="Unassigned" />
                    <x-ui.button type="submit" variant="secondary" size="sm" class="mt-3 w-full">
                        Update Assignment
                    </x-ui.button>
                </form>
            </x-ui.card>

            {{-- Status Update --}}
            <x-ui.card>
                <h3 class="mb-3 text-lg font-semibold text-gray-900">Update Status</h3>
                <form method="POST" action="{{ route('admin.tickets.update-status', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <x-form.select name="status" label="Status" :options="[
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'awaiting_response' => 'Awaiting Response',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ]" :selected="$ticket->status->value ?? $ticket->status" />
                    <x-ui.button type="submit" variant="secondary" size="sm" class="mt-3 w-full">
                        Update Status
                    </x-ui.button>
                </form>
            </x-ui.card>

            {{-- Priority Update --}}
            <x-ui.card>
                <h3 class="mb-3 text-lg font-semibold text-gray-900">Update Priority</h3>
                <form method="POST" action="{{ route('admin.tickets.update-priority', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <x-form.select name="priority" label="Priority" :options="[
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ]" :selected="$ticket->priority->value ?? $ticket->priority" />
                    <x-ui.button type="submit" variant="secondary" size="sm" class="mt-3 w-full">
                        Update Priority
                    </x-ui.button>
                </form>
            </x-ui.card>

            {{-- Ticket Details --}}
            <x-ui.card>
                <h3 class="mb-3 text-lg font-semibold text-gray-900">Details</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Category</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $ticket->category->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Created</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $ticket->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Last Updated</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $ticket->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($ticket->resolved_at)
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Resolved</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $ticket->resolved_at->format('d M Y, H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
