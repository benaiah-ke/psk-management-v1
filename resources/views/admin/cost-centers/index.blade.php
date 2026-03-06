<x-layouts.admin title="Cost Centers">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cost Centers</h1>
            <p class="text-sm text-gray-500">Manage cost centers for financial tracking.</p>
        </div>
        <x-ui.button href="{{ route('admin.cost-centers.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Cost Center
        </x-ui.button>
    </div>

    @if($costCenters->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="code" :direction="request('sort') === 'code' ? request('direction') : null">Code</x-table.heading>
                <x-table.heading sortable column="name" :direction="request('sort') === 'name' ? request('direction') : null">Name</x-table.heading>
                <x-table.heading>Parent</x-table.heading>
                <x-table.heading>Type</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($costCenters as $costCenter)
                <tr>
                    <x-table.cell class="font-mono font-medium text-gray-900">{{ $costCenter->code }}</x-table.cell>
                    <x-table.cell>
                        <p class="font-medium text-gray-900">{{ $costCenter->name }}</p>
                        @if($costCenter->description)
                            <p class="text-xs text-gray-500">{{ Str::limit($costCenter->description, 60) }}</p>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        @if($costCenter->parent)
                            <span class="text-gray-700">{{ $costCenter->parent->name }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <x-ui.badge color="purple">{{ ucfirst($costCenter->type ?? 'General') }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        @if($costCenter->is_active)
                            <x-ui.badge color="green">Active</x-ui.badge>
                        @else
                            <x-ui.badge color="gray">Inactive</x-ui.badge>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.cost-centers.edit', $costCenter) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-ui.button>
                            <form method="POST" action="{{ route('admin.cost-centers.destroy', $costCenter) }}" onsubmit="return confirm('Are you sure you want to delete this cost center?')">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" variant="ghost" size="xs" class="text-red-500 hover:text-red-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </x-ui.button>
                            </form>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach

            @if($costCenters instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <x-slot:pagination>{{ $costCenters->links() }}</x-slot:pagination>
            @endif
        </x-table.table>
    @else
        <x-ui.empty-state title="No cost centers" description="Cost centers help you categorize and track expenses across different areas.">
            <x-ui.button href="{{ route('admin.cost-centers.create') }}" size="sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Cost Center
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
