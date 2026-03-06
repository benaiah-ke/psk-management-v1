<x-layouts.admin title="Committees">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Committees</h1>
            <p class="text-sm text-gray-500">Manage association committees and their members.</p>
        </div>
        <x-ui.button href="{{ route('admin.committees.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Committee
        </x-ui.button>
    </div>

    {{-- Committees Table --}}
    @if($committees->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="name" :direction="request('sort') === 'name' ? request('direction') : null">Name</x-table.heading>
                <x-table.heading>Type</x-table.heading>
                <x-table.heading>Parent</x-table.heading>
                <x-table.heading>Members</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($committees as $committee)
                <tr>
                    <x-table.cell>
                        <a href="{{ route('admin.committees.show', $committee) }}" class="font-medium text-primary-600 hover:text-primary-700">
                            {{ $committee->name }}
                        </a>
                    </x-table.cell>
                    <x-table.cell>
                        @php
                            $typeColors = ['standing' => 'blue', 'ad_hoc' => 'yellow', 'special' => 'purple'];
                            $tColor = $typeColors[$committee->type->value ?? $committee->type] ?? 'gray';
                        @endphp
                        <x-ui.badge :color="$tColor">{{ ucfirst(str_replace('_', ' ', $committee->type->value ?? $committee->type)) }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        @if($committee->parent)
                            <a href="{{ route('admin.committees.show', $committee->parent) }}" class="text-primary-600 hover:text-primary-700">
                                {{ $committee->parent->name }}
                            </a>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <span class="font-medium text-gray-900">{{ $committee->members_count ?? $committee->members->count() }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        @if($committee->is_active)
                            <x-ui.badge color="green">Active</x-ui.badge>
                        @else
                            <x-ui.badge color="gray">Inactive</x-ui.badge>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.committees.show', $committee) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </x-ui.button>
                            <x-ui.button href="{{ route('admin.committees.edit', $committee) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-ui.button>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach

            @if($committees instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <x-slot:pagination>
                    {{ $committees->withQueryString()->links() }}
                </x-slot:pagination>
            @endif
        </x-table.table>
    @else
        <x-ui.empty-state title="No committees found" description="Get started by creating your first committee.">
            <x-ui.button href="{{ route('admin.committees.create') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Committee
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
