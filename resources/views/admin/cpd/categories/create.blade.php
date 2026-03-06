<x-layouts.admin title="Add CPD Category">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'CPD Categories', 'url' => route('admin.cpd.categories.index')],
        ['label' => 'Add Category'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add CPD Category</h1>
        <p class="text-sm text-gray-500">Create a new category for CPD activities</p>
    </div>

    <form method="POST" action="{{ route('admin.cpd.categories.store') }}" class="mx-auto max-w-3xl">
        @csrf

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Category Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-form.input name="name" label="Category Name" required placeholder="e.g. Workshops, Conferences, Self-Study" />
                </div>
                <div class="sm:col-span-2">
                    <x-form.textarea name="description" label="Description" rows="3"
                                     placeholder="Brief description of this CPD category..." />
                </div>
                <x-form.input name="max_points_per_year" label="Max Points Per Year" type="number"
                              placeholder="Leave blank for unlimited" min="1"
                              hint="Maximum points a member can earn in this category per year" />
                <div class="flex items-end">
                    <x-form.checkbox name="is_active" label="This category is active and available for logging" :checked="true" />
                </div>
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.cpd.categories.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Category
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
