<x-layouts.portal title="Log CPD Activity">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'My CPD', 'url' => route('portal.cpd.index')],
        ['label' => 'Log Activity'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Log CPD Activity</h1>
        <p class="text-sm text-gray-500">Record a new continuing professional development activity</p>
    </div>

    <form method="POST" action="{{ route('portal.cpd.store') }}" enctype="multipart/form-data" class="mx-auto max-w-3xl">
        @csrf

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Activity Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-form.select name="cpd_category_id" label="Category" required
                                   :options="$categories->pluck('name', 'id')->toArray()"
                                   placeholder="Select a category..." />
                </div>
                <div class="sm:col-span-2">
                    <x-form.input name="title" label="Activity Title" required
                                  placeholder="e.g. Attended Pharmacy Conference 2026" />
                </div>
                <div class="sm:col-span-2">
                    <x-form.textarea name="description" label="Description" rows="3"
                                     placeholder="Briefly describe the activity and what you learned..." />
                </div>
                <x-form.input name="points" label="Points" type="number" required
                              placeholder="e.g. 5" min="1"
                              hint="Enter the CPD points for this activity" />
                <x-form.date-picker name="activity_date" label="Activity Date" required
                                    :value="old('activity_date', now()->format('Y-m-d'))" />
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Evidence</h3>
            <x-form.file-upload name="evidence" label="Upload Evidence"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                hint="Upload a certificate, attendance record, or other proof of completion (PDF, JPG, PNG, DOC - max 10MB)" />
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('portal.cpd.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Log Activity
            </x-ui.button>
        </div>
    </form>
</x-layouts.portal>
