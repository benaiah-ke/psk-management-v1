<x-layouts.admin title="CPD Categories">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">CPD Categories</h1>
            <p class="text-sm text-gray-500">Manage categories for continuing professional development activities</p>
        </div>
        <x-ui.button href="{{ route('admin.cpd.categories.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Category
        </x-ui.button>
    </div>

    {{-- Categories Table --}}
    @if($categories->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading>Name</x-table.heading>
                <x-table.heading>Description</x-table.heading>
                <x-table.heading>Max Points/Year</x-table.heading>
                <x-table.heading>Activities</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($categories as $category)
                <tr>
                    <x-table.cell>
                        <span class="font-medium text-gray-900">{{ $category->name }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        <span class="line-clamp-2 max-w-xs">{{ $category->description ?? '-' }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        <span class="font-semibold text-gray-900">{{ $category->max_points_per_year ?? 'Unlimited' }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        <x-ui.badge color="blue">{{ $category->activities_count ?? $category->activities->count() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        @if($category->is_active)
                            <x-ui.badge color="green">Active</x-ui.badge>
                        @else
                            <x-ui.badge color="gray">Inactive</x-ui.badge>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.cpd.categories.edit', $category) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-ui.button>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach
        </x-table.table>
    @else
        <x-ui.empty-state title="No CPD categories" description="Create your first CPD category to start tracking professional development activities.">
            <x-ui.button href="{{ route('admin.cpd.categories.create') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Category
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
