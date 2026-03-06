<x-layouts.admin title="{{ $costCenter->name }}">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $costCenter->name }}</h1>
            <p class="text-sm text-gray-500">Cost center details</p>
        </div>
        <div class="flex gap-2">
            <x-ui.button href="{{ route('admin.cost-centers.edit', $costCenter) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </x-ui.button>
            <x-ui.button href="{{ route('admin.cost-centers.index') }}" variant="ghost">Back to List</x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Details --}}
        <div class="lg:col-span-2 space-y-6">
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Cost Center Details</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Code</dt>
                        <dd class="mt-0.5 font-mono text-sm text-gray-900">{{ $costCenter->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Type</dt>
                        <dd class="mt-1">
                            <x-ui.badge color="purple">{{ ucfirst($costCenter->type ?? 'N/A') }}</x-ui.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <x-ui.badge :color="$costCenter->is_active ? 'green' : 'gray'">{{ $costCenter->is_active ? 'Active' : 'Inactive' }}</x-ui.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Parent</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">
                            @if($costCenter->parent)
                                <a href="{{ route('admin.cost-centers.show', $costCenter->parent) }}" class="text-primary-600 hover:underline">{{ $costCenter->parent->name }}</a>
                            @else
                                <span class="text-gray-400">None (top-level)</span>
                            @endif
                        </dd>
                    </div>
                    @if($costCenter->description)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase text-gray-500">Description</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $costCenter->description }}</dd>
                        </div>
                    @endif
                </dl>
            </x-ui.card>

            {{-- Children --}}
            @if($costCenter->children->count())
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Child Cost Centers</h3>
                    <x-table.table>
                        <x-slot:head>
                            <x-table.heading>Code</x-table.heading>
                            <x-table.heading>Name</x-table.heading>
                            <x-table.heading>Status</x-table.heading>
                        </x-slot:head>
                        @foreach($costCenter->children as $child)
                            <tr>
                                <x-table.cell><span class="font-mono">{{ $child->code }}</span></x-table.cell>
                                <x-table.cell>
                                    <a href="{{ route('admin.cost-centers.show', $child) }}" class="font-medium text-primary-600 hover:underline">{{ $child->name }}</a>
                                </x-table.cell>
                                <x-table.cell>
                                    <x-ui.badge :color="$child->is_active ? 'green' : 'gray'">{{ $child->is_active ? 'Active' : 'Inactive' }}</x-ui.badge>
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
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Summary</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Budgets</span>
                        <span class="font-medium text-gray-900">{{ $costCenter->budgets->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Invoices</span>
                        <span class="font-medium text-gray-900">{{ $costCenter->invoices->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Child Centers</span>
                        <span class="font-medium text-gray-900">{{ $costCenter->children->count() }}</span>
                    </div>
                </dl>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
