<x-layouts.admin title="Add Committee">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Committees', 'url' => route('admin.committees.index')],
        ['label' => 'Add Committee'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Committee</h1>
        <p class="text-sm text-gray-500">Create a new committee</p>
    </div>

    <form method="POST" action="{{ route('admin.committees.store') }}" class="mx-auto max-w-3xl">
        @csrf

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Committee Information</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="name" label="Committee Name" required placeholder="Enter committee name" />
                <x-form.select name="type" label="Type" required :options="[
                    'standing' => 'Standing',
                    'ad_hoc' => 'Ad Hoc',
                    'special' => 'Special',
                ]" placeholder="Select type" />
                <x-form.select name="parent_id" label="Parent Committee"
                               :options="$committees->pluck('name', 'id')->toArray()"
                               placeholder="None (top-level committee)" />
                <x-form.select name="cost_center_id" label="Cost Center"
                               :options="$costCenters->pluck('name', 'id')->toArray()"
                               placeholder="Select cost center" />
            </div>

            <div class="mt-4">
                <x-form.textarea name="description" label="Description" rows="3"
                                 placeholder="Brief description of this committee's purpose..." />
            </div>

            <div class="mt-4">
                <x-form.checkbox name="is_active" label="Committee is active" :checked="true" />
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.committees.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Committee
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
