<x-layouts.admin title="Budgets">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Budgets</h1>
            <p class="text-sm text-gray-500">Plan and track budgets against cost centers.</p>
        </div>
        <x-ui.button href="{{ route('admin.budgets.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Create Budget
        </x-ui.button>
    </div>

    @if($budgets->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="name" :direction="request('sort') === 'name' ? request('direction') : null">Name</x-table.heading>
                <x-table.heading>Cost Center</x-table.heading>
                <x-table.heading sortable column="fiscal_year" :direction="request('sort') === 'fiscal_year' ? request('direction') : null">Fiscal Year</x-table.heading>
                <x-table.heading>Budgeted</x-table.heading>
                <x-table.heading>Actual</x-table.heading>
                <x-table.heading>Variance</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($budgets as $budget)
                @php
                    $budgeted = $budget->total_budgeted;
                    $actual = $budget->total_actual;
                    $variance = $budgeted - $actual;
                @endphp
                <tr>
                    <x-table.cell class="font-medium text-gray-900">{{ $budget->name }}</x-table.cell>
                    <x-table.cell>
                        @if($budget->costCenter)
                            <span class="text-gray-700">{{ $budget->costCenter->name }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>{{ $budget->fiscal_year }}</x-table.cell>
                    <x-table.cell class="font-medium">KES {{ number_format($budgeted, 2) }}</x-table.cell>
                    <x-table.cell class="font-medium">KES {{ number_format($actual, 2) }}</x-table.cell>
                    <x-table.cell>
                        <span class="{{ $variance >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                            {{ $variance >= 0 ? '+' : '' }}KES {{ number_format($variance, 2) }}
                        </span>
                    </x-table.cell>
                    <x-table.cell>
                        @php
                            $statusColors = ['draft' => 'gray', 'pending' => 'yellow', 'approved' => 'green', 'active' => 'blue', 'closed' => 'gray'];
                        @endphp
                        <x-ui.badge :color="$statusColors[$budget->status] ?? 'gray'">{{ ucfirst($budget->status ?? 'Draft') }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.budgets.edit', $budget) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-ui.button>
                            <form method="POST" action="{{ route('admin.budgets.destroy', $budget) }}" onsubmit="return confirm('Are you sure you want to delete this budget?')">
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

            @if($budgets instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <x-slot:pagination>{{ $budgets->links() }}</x-slot:pagination>
            @endif
        </x-table.table>
    @else
        <x-ui.empty-state title="No budgets found" description="Create budgets to plan and track your financial allocations.">
            <x-ui.button href="{{ route('admin.budgets.create') }}" size="sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Budget
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
