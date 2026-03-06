<x-layouts.admin title="Add Membership Tier">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Membership Tiers', 'url' => route('admin.tiers.index')],
        ['label' => 'Add Tier'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Membership Tier</h1>
        <p class="text-sm text-gray-500">Create a new membership tier with pricing and requirements</p>
    </div>

    <form method="POST" action="{{ route('admin.tiers.store') }}" class="mx-auto max-w-3xl">
        @csrf

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Tier Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-form.input name="name" label="Tier Name" required placeholder="e.g. Full Member, Associate Member" />
                </div>
                <div class="sm:col-span-2">
                    <x-form.textarea name="description" label="Description" rows="3"
                                     placeholder="Brief description of this membership tier..." />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Pricing</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="annual_fee" label="Annual Fee (KES)" type="number" required placeholder="0.00"
                              min="0" step="0.01" />
                <x-form.input name="registration_fee" label="Registration Fee (KES)" type="number" required placeholder="0.00"
                              min="0" step="0.01" />
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Requirements & Benefits</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="cpd_points_required" label="CPD Points Required (Annual)" type="number"
                              placeholder="e.g. 40" min="0" />
                <x-form.input name="sort_order" label="Sort Order" type="number" placeholder="0" min="0"
                              hint="Lower numbers appear first" />
                <div class="sm:col-span-2">
                    <x-form.textarea name="benefits" label="Benefits" rows="4"
                                     placeholder="List the benefits of this tier, one per line..." />
                </div>
                <div class="sm:col-span-2">
                    <x-form.checkbox name="is_active" label="This tier is active and available for applications" :checked="true" />
                </div>
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.tiers.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Tier
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
