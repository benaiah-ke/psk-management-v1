<x-layouts.admin title="Add Branch">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Branches', 'url' => route('admin.branches.index')],
        ['label' => 'Add Branch'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Branch</h1>
        <p class="text-sm text-gray-500">Create a new association branch</p>
    </div>

    <form method="POST" action="{{ route('admin.branches.store') }}" class="mx-auto max-w-3xl">
        @csrf

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Branch Information</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="name" label="Branch Name" required placeholder="Enter branch name" />
                <x-form.input name="county" label="County" placeholder="e.g. Nairobi" />
                <x-form.input name="region" label="Region" placeholder="e.g. Central" />
                <x-form.select name="cost_center_id" label="Cost Center"
                               :options="$costCenters->pluck('name', 'id')->toArray()"
                               placeholder="Select cost center" />
            </div>

            <div class="mt-4">
                <x-form.textarea name="description" label="Description" rows="3"
                                 placeholder="Brief description of this branch..." />
            </div>

            <div class="mt-4">
                <x-form.checkbox name="is_active" label="Branch is active" :checked="true" />
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.branches.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Branch
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
