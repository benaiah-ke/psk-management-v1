<x-layouts.admin title="{{ $budget->name }}">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $budget->name }}</h1>
            <p class="text-sm text-gray-500">FY {{ $budget->fiscal_year }} &middot; {{ $budget->costCenter->name ?? 'No Cost Center' }}</p>
        </div>
        <div class="flex gap-2">
            <x-ui.button href="{{ route('admin.budgets.edit', $budget) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </x-ui.button>
            <x-ui.button href="{{ route('admin.budgets.index') }}" variant="ghost">Back to List</x-ui.button>
        </div>
    </div>

    @php
        $statusColors = ['draft' => 'gray', 'pending' => 'yellow', 'approved' => 'green', 'active' => 'blue', 'closed' => 'gray'];
        $totalBudgeted = $budget->lines->sum('budgeted_amount');
        $totalActual = $budget->lines->sum('actual_amount');
        $variance = $totalBudgeted - $totalActual;
    @endphp

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Details --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Budget Details</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <x-ui.badge :color="$statusColors[$budget->status] ?? 'gray'">{{ ucfirst($budget->status) }}</x-ui.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Fiscal Year</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $budget->fiscal_year }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Cost Center</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">
                            @if($budget->costCenter)
                                <a href="{{ route('admin.cost-centers.show', $budget->costCenter) }}" class="text-primary-600 hover:underline">{{ $budget->costCenter->name }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    @if($budget->approvedBy)
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Approved By</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $budget->approvedBy->first_name }} {{ $budget->approvedBy->last_name }}</dd>
                        </div>
                    @endif
                    @if($budget->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase text-gray-500">Notes</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $budget->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </x-ui.card>

            {{-- Budget Lines --}}
            @if($budget->lines->count())
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Budget Lines</h3>
                    <x-table.table>
                        <x-slot:head>
                            <x-table.heading>Category</x-table.heading>
                            <x-table.heading>Description</x-table.heading>
                            <x-table.heading>Budgeted (KES)</x-table.heading>
                            <x-table.heading>Actual (KES)</x-table.heading>
                            <x-table.heading>Variance</x-table.heading>
                        </x-slot:head>
                        @foreach($budget->lines as $line)
                            @php $lineVariance = $line->budgeted_amount - $line->actual_amount; @endphp
                            <tr>
                                <x-table.cell><span class="font-medium text-gray-900">{{ $line->category }}</span></x-table.cell>
                                <x-table.cell>{{ $line->description ?? '-' }}</x-table.cell>
                                <x-table.cell>{{ number_format($line->budgeted_amount, 2) }}</x-table.cell>
                                <x-table.cell>{{ number_format($line->actual_amount, 2) }}</x-table.cell>
                                <x-table.cell>
                                    <span class="{{ $lineVariance >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        {{ number_format($lineVariance, 2) }}
                                    </span>
                                </x-table.cell>
                            </tr>
                        @endforeach
                    </x-table.table>
                </x-ui.card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Budget Summary</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Budgeted</span>
                        <span class="font-medium text-gray-900">KES {{ number_format($totalBudgeted, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Actual</span>
                        <span class="font-medium text-gray-900">KES {{ number_format($totalActual, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-3">
                        <span class="font-semibold text-gray-900">Variance</span>
                        <span class="text-lg font-bold {{ $variance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            KES {{ number_format($variance, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Budget Lines</span>
                        <span class="font-medium text-gray-900">{{ $budget->lines->count() }}</span>
                    </div>
                </dl>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
