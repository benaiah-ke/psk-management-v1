<x-layouts.admin title="{{ $tier->name }}">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Membership Tiers', 'url' => route('admin.tiers.index')],
        ['label' => $tier->name],
    ]" />

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $tier->name }}</h1>
            <p class="text-sm text-gray-500">Membership tier details</p>
        </div>
        <div class="flex gap-2">
            <x-ui.button href="{{ route('admin.tiers.edit', $tier) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Tier
            </x-ui.button>
            <x-ui.button href="{{ route('admin.tiers.index') }}" variant="ghost">Back to List</x-ui.button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-ui.stat-card label="Annual Fee" :value="'KES ' . number_format($tier->annual_fee, 2)" color="primary" />
        <x-ui.stat-card label="Registration Fee" :value="'KES ' . number_format($tier->registration_fee, 2)" color="blue" />
        <x-ui.stat-card label="Active Members" :value="$tier->active_memberships_count" color="green" />
    </div>

    {{-- Details --}}
    <div class="mx-auto max-w-3xl">
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Tier Details</h3>
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <x-ui.badge :color="$tier->is_active ? 'green' : 'gray'">{{ $tier->is_active ? 'Active' : 'Inactive' }}</x-ui.badge>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-500">CPD Points Required</dt>
                    <dd class="mt-0.5 text-sm text-gray-900">{{ $tier->cpd_points_required ?? 'None' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-500">Sort Order</dt>
                    <dd class="mt-0.5 text-sm text-gray-900">{{ $tier->sort_order ?? 0 }}</dd>
                </div>
                @if($tier->description)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase text-gray-500">Description</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $tier->description }}</dd>
                    </div>
                @endif
                @if($tier->benefits)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase text-gray-500">Benefits</dt>
                        <dd class="mt-0.5 whitespace-pre-line text-sm text-gray-900">{{ $tier->benefits }}</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>
    </div>
</x-layouts.admin>
