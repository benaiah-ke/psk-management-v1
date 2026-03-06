<x-layouts.admin title="Add Cost Center">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Cost Center</h1>
        <p class="text-sm text-gray-500">Create a new cost center for financial tracking.</p>
    </div>

    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('admin.cost-centers.store') }}">
            @csrf

            <x-ui.card class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Cost Center Details</h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form.input name="name" label="Name" required placeholder="e.g. Membership Revenue" />
                        <x-form.input name="code" label="Code" required placeholder="e.g. MEM-REV" />
                    </div>

                    <x-form.textarea name="description" label="Description" placeholder="Brief description of this cost center..." :rows="3" />

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form.select name="parent_id" label="Parent Cost Center" :options="$costCenters->mapWithKeys(fn($cc) => [$cc->id => $cc->code . ' - ' . $cc->name])->all()" placeholder="None (top-level)" />

                        <x-form.select name="type" label="Type" :options="['revenue' => 'Revenue', 'expense' => 'Expense', 'asset' => 'Asset', 'liability' => 'Liability']" placeholder="Select type..." />
                    </div>

                    <x-form.checkbox name="is_active" label="Active" :checked="old('is_active', true)" />
                </div>
            </x-ui.card>

            <div class="flex items-center justify-end gap-3">
                <x-ui.button href="{{ route('admin.cost-centers.index') }}" variant="secondary">Cancel</x-ui.button>
                <x-ui.button type="submit">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Create Cost Center
                </x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
