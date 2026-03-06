<x-layouts.portal title="Submit a Ticket">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Submit a Ticket</h1>
        <p class="text-sm text-gray-500">Describe your issue and we'll get back to you as soon as possible.</p>
    </div>

    <form method="POST" action="{{ route('portal.tickets.store') }}" class="mx-auto max-w-3xl">
        @csrf

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Ticket Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.select name="category_id" label="Category" required
                               :options="$categories->pluck('name', 'id')->toArray()"
                               placeholder="Select a category" />
                <x-form.select name="type" label="Type" required :options="[
                    'question' => 'Question',
                    'issue' => 'Issue',
                    'request' => 'Request',
                    'feedback' => 'Feedback',
                ]" placeholder="Select type" />
                <x-form.select name="priority" label="Priority" required :options="[
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'urgent' => 'Urgent',
                ]" :selected="'medium'" placeholder="Select priority" />
            </div>

            <div class="mt-4">
                <x-form.input name="subject" label="Subject" required
                              placeholder="Brief description of your issue" />
            </div>

            <div class="mt-4">
                <x-form.textarea name="description" label="Description" required rows="6"
                                 placeholder="Please provide as much detail as possible about your issue..." />
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('portal.tickets.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Submit Ticket
            </x-ui.button>
        </div>
    </form>
</x-layouts.portal>
