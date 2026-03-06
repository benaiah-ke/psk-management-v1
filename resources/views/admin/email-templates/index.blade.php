<x-layouts.admin title="Email Templates">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Email Templates</h1>
            <p class="text-sm text-gray-500">Manage reusable email templates for communications</p>
        </div>
        <x-ui.button href="{{ route('admin.email-templates.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Template
        </x-ui.button>
    </div>

    {{-- Templates Table --}}
    @if($templates->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading>Name</x-table.heading>
                <x-table.heading>Subject</x-table.heading>
                <x-table.heading>Category</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($templates as $template)
                <tr>
                    <x-table.cell>
                        <div>
                            <p class="font-medium text-gray-900">{{ $template->name }}</p>
                            <p class="text-xs text-gray-500">{{ $template->slug }}</p>
                        </div>
                    </x-table.cell>
                    <x-table.cell>{{ $template->subject }}</x-table.cell>
                    <x-table.cell>
                        @if($template->category)
                            <x-ui.badge color="indigo">{{ ucfirst($template->category) }}</x-ui.badge>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        @if($template->is_active)
                            <x-ui.badge color="green">Active</x-ui.badge>
                        @else
                            <x-ui.badge color="gray">Inactive</x-ui.badge>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.email-templates.edit', $template) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-ui.button>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach
        </x-table.table>
    @else
        <x-ui.empty-state title="No email templates" description="Create reusable templates to speed up your communications.">
            <x-ui.button href="{{ route('admin.email-templates.create') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Template
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
