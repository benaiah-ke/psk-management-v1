<x-layouts.admin title="Communication Details">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Communications', 'url' => route('admin.communications.index')],
        ['label' => $communication->subject],
    ]" />

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $communication->subject }}</h1>
            <p class="text-sm text-gray-500">Sent on {{ $communication->created_at->format('d M Y \a\t H:i') }}</p>
        </div>
        <x-ui.button href="{{ route('admin.communications.index') }}" variant="secondary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to List
        </x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Message Details --}}
        <div class="lg:col-span-2">
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Message Details</h3>
                <dl class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Type</dt>
                            <dd class="mt-1">
                                @if($communication->type === 'email')
                                    <x-ui.badge color="blue">Email</x-ui.badge>
                                @elseif($communication->type === 'sms')
                                    <x-ui.badge color="purple">SMS</x-ui.badge>
                                @else
                                    <x-ui.badge color="gray">{{ ucfirst($communication->type) }}</x-ui.badge>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Sent By</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">
                                {{ $communication->sentBy->first_name ?? '' }} {{ $communication->sentBy->last_name ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Subject</dt>
                            <dd class="mt-0.5 text-sm font-medium text-gray-900">{{ $communication->subject }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Sent At</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $communication->created_at->format('d M Y H:i') }}</dd>
                        </div>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Body</dt>
                        <dd class="mt-1 rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-900 prose prose-sm max-w-none">
                            {!! nl2br(e($communication->body)) !!}
                        </dd>
                    </div>
                </dl>
            </x-ui.card>
        </div>

        {{-- Delivery Summary --}}
        <div>
            <x-ui.card class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Delivery Summary</h3>
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500">Total Recipients</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $communication->recipients->count() }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500">Sent</dt>
                        <dd class="text-sm font-semibold text-green-600">{{ $communication->recipients->where('status', 'sent')->count() }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500">Failed</dt>
                        <dd class="text-sm font-semibold text-red-600">{{ $communication->recipients->where('status', 'failed')->count() }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-500">Pending</dt>
                        <dd class="text-sm font-semibold text-yellow-600">{{ $communication->recipients->where('status', 'pending')->count() }}</dd>
                    </div>
                </dl>
            </x-ui.card>
        </div>
    </div>

    {{-- Recipient List --}}
    <div class="mt-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Recipients</h3>
        @if($communication->recipients->count())
            <x-table.table>
                <x-slot:head>
                    <x-table.heading>Name</x-table.heading>
                    <x-table.heading>Email</x-table.heading>
                    <x-table.heading>Status</x-table.heading>
                    <x-table.heading>Delivered At</x-table.heading>
                </x-slot:head>

                @foreach($communication->recipients as $recipient)
                    <tr>
                        <x-table.cell>
                            <span class="font-medium text-gray-900">
                                {{ $recipient->user->first_name ?? '' }} {{ $recipient->user->last_name ?? '-' }}
                            </span>
                        </x-table.cell>
                        <x-table.cell>{{ $recipient->user->email ?? $recipient->email ?? '-' }}</x-table.cell>
                        <x-table.cell>
                            @php
                                $recipientColors = [
                                    'sent' => 'green',
                                    'delivered' => 'green',
                                    'failed' => 'red',
                                    'pending' => 'yellow',
                                    'bounced' => 'red',
                                ];
                                $recipientColor = $recipientColors[$recipient->status] ?? 'gray';
                            @endphp
                            <x-ui.badge :color="$recipientColor">{{ ucfirst($recipient->status) }}</x-ui.badge>
                        </x-table.cell>
                        <x-table.cell>{{ $recipient->delivered_at?->format('d M Y H:i') ?? '-' }}</x-table.cell>
                    </tr>
                @endforeach
            </x-table.table>
        @else
            <x-ui.empty-state title="No recipients" description="No recipient data available for this communication." />
        @endif
    </div>
</x-layouts.admin>
