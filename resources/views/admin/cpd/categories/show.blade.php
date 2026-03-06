<x-layouts.admin title="{{ $cpdCategory->name }}">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'CPD Categories', 'url' => route('admin.cpd.categories.index')],
        ['label' => $cpdCategory->name],
    ]" />

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $cpdCategory->name }}</h1>
            <p class="text-sm text-gray-500">CPD category details</p>
        </div>
        <div class="flex gap-2">
            <x-ui.button href="{{ route('admin.cpd.categories.edit', $cpdCategory) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Category
            </x-ui.button>
            <x-ui.button href="{{ route('admin.cpd.categories.index') }}" variant="ghost">Back to List</x-ui.button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-ui.stat-card label="Activities Logged" :value="$cpdCategory->activities_count" color="primary" />
        <x-ui.stat-card label="Max Points Per Year" :value="$cpdCategory->max_points_per_year ?? 'Unlimited'" color="blue" />
    </div>

    {{-- Details --}}
    <div class="mx-auto max-w-3xl">
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Category Details</h3>
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-500">Name</dt>
                    <dd class="mt-0.5 text-sm text-gray-900">{{ $cpdCategory->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <x-ui.badge :color="$cpdCategory->is_active ? 'green' : 'gray'">{{ $cpdCategory->is_active ? 'Active' : 'Inactive' }}</x-ui.badge>
                    </dd>
                </div>
                @if($cpdCategory->description)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase text-gray-500">Description</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $cpdCategory->description }}</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>
    </div>
</x-layouts.admin>
