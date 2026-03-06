<x-layouts.admin title="Add Email Template">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Email Templates', 'url' => route('admin.email-templates.index')],
        ['label' => 'Add Template'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Email Template</h1>
        <p class="text-sm text-gray-500">Create a new reusable email template</p>
    </div>

    <form method="POST" action="{{ route('admin.email-templates.store') }}" class="mx-auto max-w-3xl">
        @csrf

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Template Details</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="name" label="Template Name" required
                              placeholder="e.g. Welcome Email, Renewal Reminder" />
                <x-form.input name="slug" label="Slug" required
                              placeholder="e.g. welcome-email, renewal-reminder"
                              hint="Unique identifier used in code" />
                <div class="sm:col-span-2">
                    <x-form.input name="subject" label="Subject Line" required
                                  placeholder="e.g. Welcome to PSK, {{ $member->first_name }}!" />
                </div>
                <x-form.select name="category" label="Category"
                               :options="[
                                   'membership' => 'Membership',
                                   'events' => 'Events',
                                   'cpd' => 'CPD',
                                   'billing' => 'Billing',
                                   'general' => 'General',
                               ]"
                               placeholder="Select a category..." />
                <div class="flex items-end">
                    <x-form.checkbox name="is_active" label="This template is active and available for use" :checked="true" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Template Body</h3>
            <div class="space-y-4">
                <x-form.textarea name="body" label="Body" required rows="12"
                                 placeholder="Write your template content here...">{{ old('body') }}</x-form.textarea>
                <x-ui.alert type="info" :dismissible="false">
                    <p class="font-medium mb-1">Available merge fields:</p>
                    <div class="flex flex-wrap gap-2">
                        <code class="rounded bg-blue-100 px-1.5 py-0.5 text-xs font-mono">@{{ member_name }}</code>
                        <code class="rounded bg-blue-100 px-1.5 py-0.5 text-xs font-mono">@{{ member_email }}</code>
                        <code class="rounded bg-blue-100 px-1.5 py-0.5 text-xs font-mono">@{{ first_name }}</code>
                        <code class="rounded bg-blue-100 px-1.5 py-0.5 text-xs font-mono">@{{ last_name }}</code>
                        <code class="rounded bg-blue-100 px-1.5 py-0.5 text-xs font-mono">@{{ membership_tier }}</code>
                        <code class="rounded bg-blue-100 px-1.5 py-0.5 text-xs font-mono">@{{ membership_number }}</code>
                        <code class="rounded bg-blue-100 px-1.5 py-0.5 text-xs font-mono">@{{ expiry_date }}</code>
                        <code class="rounded bg-blue-100 px-1.5 py-0.5 text-xs font-mono">@{{ organization_name }}</code>
                    </div>
                </x-ui.alert>
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.email-templates.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Template
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
