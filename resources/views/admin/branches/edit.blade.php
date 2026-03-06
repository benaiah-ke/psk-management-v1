<x-layouts.admin title="Edit {{ $branch->name }}">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Branches', 'url' => route('admin.branches.index')],
        ['label' => $branch->name, 'url' => route('admin.branches.show', $branch)],
        ['label' => 'Edit'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Branch</h1>
        <p class="text-sm text-gray-500">Update details for {{ $branch->name }}</p>
    </div>

    <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="mx-auto max-w-3xl">
        @csrf
        @method('PUT')

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Branch Information</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="name" label="Branch Name" required placeholder="Enter branch name"
                              :value="$branch->name" />
                <x-form.input name="county" label="County" placeholder="e.g. Nairobi"
                              :value="$branch->county" />
                <x-form.input name="region" label="Region" placeholder="e.g. Central"
                              :value="$branch->region" />
                <x-form.select name="cost_center_id" label="Cost Center"
                               :options="$costCenters->pluck('name', 'id')->toArray()"
                               :selected="$branch->cost_center_id"
                               placeholder="Select cost center" />
            </div>

            <div class="mt-4">
                <x-form.textarea name="description" label="Description" rows="3"
                                 placeholder="Brief description of this branch..."
                                 :value="$branch->description" />
            </div>

            <div class="mt-4">
                <x-form.checkbox name="is_active" label="Branch is active" :checked="$branch->is_active" />
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.branches.show', $branch) }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Update Branch
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
