<x-layouts.admin title="{{ $emailTemplate->name }}">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Email Templates', 'url' => route('admin.email-templates.index')],
        ['label' => $emailTemplate->name],
    ]" />

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $emailTemplate->name }}</h1>
            <p class="text-sm text-gray-500">Email template details</p>
        </div>
        <div class="flex gap-2">
            <x-ui.button href="{{ route('admin.email-templates.edit', $emailTemplate) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Template
            </x-ui.button>
            <x-ui.button href="{{ route('admin.email-templates.index') }}" variant="ghost">Back to List</x-ui.button>
        </div>
    </div>

    <div class="mx-auto max-w-3xl space-y-6">
        {{-- Details --}}
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Template Details</h3>
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-500">Name</dt>
                    <dd class="mt-0.5 text-sm text-gray-900">{{ $emailTemplate->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-500">Slug</dt>
                    <dd class="mt-0.5 font-mono text-sm text-gray-900">{{ $emailTemplate->slug }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-500">Category</dt>
                    <dd class="mt-1">
                        @if($emailTemplate->category)
                            <x-ui.badge color="blue">{{ ucfirst($emailTemplate->category) }}</x-ui.badge>
                        @else
                            <span class="text-sm text-gray-400">Uncategorised</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <x-ui.badge :color="$emailTemplate->is_active ? 'green' : 'gray'">{{ $emailTemplate->is_active ? 'Active' : 'Inactive' }}</x-ui.badge>
                    </dd>
                </div>
                @if($emailTemplate->variables && count($emailTemplate->variables))
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase text-gray-500">Variables</dt>
                        <dd class="mt-1 flex flex-wrap gap-1">
                            @foreach($emailTemplate->variables as $var)
                                <code class="rounded bg-gray-100 px-1.5 py-0.5 text-xs font-mono text-gray-700">{{ $var }}</code>
                            @endforeach
                        </dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>

        {{-- Subject --}}
        <x-ui.card>
            <h3 class="mb-2 text-lg font-semibold text-gray-900">Subject Line</h3>
            <p class="text-sm text-gray-900">{{ $emailTemplate->subject }}</p>
        </x-ui.card>

        {{-- Body Preview --}}
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Body</h3>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <pre class="whitespace-pre-wrap text-sm text-gray-700">{{ $emailTemplate->body }}</pre>
            </div>
        </x-ui.card>
    </div>
</x-layouts.admin>
