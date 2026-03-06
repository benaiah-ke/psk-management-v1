<x-layouts.admin title="Membership Tiers">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Membership Tiers</h1>
            <p class="text-sm text-gray-500">Manage membership tiers and their pricing</p>
        </div>
        <x-ui.button href="{{ route('admin.tiers.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Tier
        </x-ui.button>
    </div>

    {{-- Tiers Table --}}
    @if($tiers->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading>Name</x-table.heading>
                <x-table.heading>Annual Fee</x-table.heading>
                <x-table.heading>Registration Fee</x-table.heading>
                <x-table.heading>CPD Required</x-table.heading>
                <x-table.heading>Active Members</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($tiers as $tier)
                <tr>
                    <x-table.cell>
                        <div>
                            <p class="font-medium text-gray-900">{{ $tier->name }}</p>
                            @if($tier->description)
                                <p class="mt-0.5 text-xs text-gray-500 line-clamp-1">{{ $tier->description }}</p>
                            @endif
                        </div>
                    </x-table.cell>
                    <x-table.cell>KES {{ number_format($tier->annual_fee, 2) }}</x-table.cell>
                    <x-table.cell>KES {{ number_format($tier->registration_fee, 2) }}</x-table.cell>
                    <x-table.cell>{{ $tier->cpd_points_required ?? '-' }} pts</x-table.cell>
                    <x-table.cell>
                        <span class="font-medium text-gray-900">{{ $tier->memberships_count ?? $tier->memberships->count() }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        @if($tier->is_active)
                            <x-ui.badge color="green">Active</x-ui.badge>
                        @else
                            <x-ui.badge color="gray">Inactive</x-ui.badge>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.tiers.edit', $tier) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </x-ui.button>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach
        </x-table.table>
    @else
        <x-ui.empty-state title="No membership tiers" description="Create your first membership tier to start accepting applications.">
            <x-ui.button href="{{ route('admin.tiers.create') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Tier
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
