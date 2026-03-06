<x-layouts.admin title="Membership Applications">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Membership Applications</h1>
            <p class="text-sm text-gray-500">{{ $applications->total() }} {{ Str::plural('application', $applications->total()) }} submitted</p>
        </div>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.applications.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <x-form.input name="search" label="Search" placeholder="Search by applicant name or email..."
                              :value="request('search')" />
            </div>
            <div class="w-full sm:w-48">
                <x-form.select name="status" label="Status" :options="[
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'under_review' => 'Under Review',
                ]" :selected="request('status')" placeholder="All Statuses" />
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="secondary" size="md">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </x-ui.button>
                @if(request()->hasAny(['search', 'status']))
                    <x-ui.button href="{{ route('admin.applications.index') }}" variant="ghost" size="md">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- Applications Table --}}
    @if($applications->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="created_at" :direction="request('sort') === 'created_at' ? request('direction') : null">Date Submitted</x-table.heading>
                <x-table.heading>Applicant</x-table.heading>
                <x-table.heading>Email</x-table.heading>
                <x-table.heading>Tier Applied For</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($applications as $application)
                <tr>
                    <x-table.cell>{{ $application->created_at->format('d M Y') }}</x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-100 text-xs font-medium text-primary-700">
                                {{ strtoupper(substr($application->user->first_name, 0, 1) . substr($application->user->last_name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $application->user->first_name }} {{ $application->user->last_name }}</span>
                        </div>
                    </x-table.cell>
                    <x-table.cell>{{ $application->user->email }}</x-table.cell>
                    <x-table.cell>
                        <span class="font-medium text-gray-900">{{ $application->tier->name }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        @php
                            $statusColors = [
                                'pending' => 'yellow',
                                'approved' => 'green',
                                'rejected' => 'red',
                                'under_review' => 'blue',
                            ];
                            $statusColor = $statusColors[$application->status->value ?? $application->status] ?? 'gray';
                        @endphp
                        <x-ui.badge :color="$statusColor">{{ ucfirst(str_replace('_', ' ', $application->status->value ?? $application->status)) }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        <x-ui.button href="{{ route('admin.applications.show', $application) }}" variant="ghost" size="xs">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Review
                        </x-ui.button>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $applications->withQueryString()->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No applications found" description="No membership applications match your current filters.">
            @if(request()->hasAny(['search', 'status']))
                <x-ui.button href="{{ route('admin.applications.index') }}" variant="secondary">Clear Filters</x-ui.button>
            @endif
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
