<x-layouts.admin title="Members">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Members</h1>
            <p class="text-sm text-gray-500">{{ $members->total() }} {{ Str::plural('member', $members->total()) }} registered</p>
        </div>
        <x-ui.button href="{{ route('admin.members.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Member
        </x-ui.button>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.members.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <x-form.input name="search" label="Search" placeholder="Search by name, email or phone..."
                              :value="request('search')" />
            </div>
            <div class="w-full sm:w-48">
                <x-form.select name="tier" label="Membership Tier" :options="$tiers->pluck('name', 'id')->toArray()"
                               :selected="request('tier')" placeholder="All Tiers" />
            </div>
            <div class="w-full sm:w-44">
                <x-form.select name="status" label="Status" :options="[
                    'active' => 'Active',
                    'expired' => 'Expired',
                    'suspended' => 'Suspended',
                    'pending' => 'Pending',
                ]" :selected="request('status')" placeholder="All Statuses" />
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="secondary" size="md">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </x-ui.button>
                @if(request()->hasAny(['search', 'tier', 'status']))
                    <x-ui.button href="{{ route('admin.members.index') }}" variant="ghost" size="md">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- Members Table --}}
    @if($members->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="last_name" :direction="request('sort') === 'last_name' ? request('direction') : null">Name</x-table.heading>
                <x-table.heading>Email</x-table.heading>
                <x-table.heading>Phone</x-table.heading>
                <x-table.heading sortable column="tier" :direction="request('sort') === 'tier' ? request('direction') : null">Membership Tier</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading sortable column="created_at" :direction="request('sort') === 'created_at' ? request('direction') : null">Joined</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($members as $member)
                <tr>
                    <x-table.cell>
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-medium text-primary-700">
                                {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $member->last_name }}, {{ $member->first_name }}</p>
                            </div>
                        </div>
                    </x-table.cell>
                    <x-table.cell>{{ $member->email }}</x-table.cell>
                    <x-table.cell>{{ $member->phone ?? '-' }}</x-table.cell>
                    <x-table.cell>
                        @if($member->membership?->tier)
                            <span class="font-medium text-gray-900">{{ $member->membership->tier->name }}</span>
                        @else
                            <span class="text-gray-400">None</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        @if($member->membership)
                            @php
                                $statusColors = [
                                    'active' => 'green',
                                    'expired' => 'red',
                                    'suspended' => 'yellow',
                                    'pending' => 'blue',
                                ];
                                $statusColor = $statusColors[$member->membership->status->value ?? $member->membership->status] ?? 'gray';
                            @endphp
                            <x-ui.badge :color="$statusColor">{{ ucfirst($member->membership->status->value ?? $member->membership->status) }}</x-ui.badge>
                        @else
                            <x-ui.badge color="gray">No Membership</x-ui.badge>
                        @endif
                    </x-table.cell>
                    <x-table.cell>{{ $member->created_at->format('d M Y') }}</x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.members.show', $member) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </x-ui.button>
                            <x-ui.button href="{{ route('admin.members.edit', $member) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-ui.button>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $members->withQueryString()->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No members found" description="No members match your current filters. Try adjusting your search criteria.">
            <x-ui.button href="{{ route('admin.members.create') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Member
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
