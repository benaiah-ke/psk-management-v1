<x-layouts.admin title="Communications">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Communications</h1>
            <p class="text-sm text-gray-500">{{ $communications->total() }} {{ Str::plural('message', $communications->total()) }} sent</p>
        </div>
        <x-ui.button href="{{ route('admin.communications.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Compose Message
        </x-ui.button>
    </div>

    {{-- Communications Table --}}
    @if($communications->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="created_at" :direction="request('sort') === 'created_at' ? request('direction') : null">Date</x-table.heading>
                <x-table.heading>Subject</x-table.heading>
                <x-table.heading>Type</x-table.heading>
                <x-table.heading>Recipients</x-table.heading>
                <x-table.heading>Sent</x-table.heading>
                <x-table.heading>Failed</x-table.heading>
                <x-table.heading>Sent By</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($communications as $communication)
                <tr>
                    <x-table.cell>{{ $communication->created_at->format('d M Y H:i') }}</x-table.cell>
                    <x-table.cell>
                        <span class="font-medium text-gray-900">{{ $communication->subject }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        @if($communication->type === 'email')
                            <x-ui.badge color="blue">Email</x-ui.badge>
                        @elseif($communication->type === 'sms')
                            <x-ui.badge color="purple">SMS</x-ui.badge>
                        @else
                            <x-ui.badge color="gray">{{ ucfirst($communication->type) }}</x-ui.badge>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <span class="font-medium text-gray-900">{{ $communication->recipients_count ?? $communication->recipients->count() }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        <span class="font-medium text-green-600">{{ $communication->sent_count ?? $communication->recipients->where('status', 'sent')->count() }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        @php $failedCount = $communication->failed_count ?? $communication->recipients->where('status', 'failed')->count(); @endphp
                        <span class="{{ $failedCount > 0 ? 'font-medium text-red-600' : 'text-gray-500' }}">{{ $failedCount }}</span>
                    </x-table.cell>
                    <x-table.cell>{{ $communication->sentBy->first_name ?? '-' }} {{ $communication->sentBy->last_name ?? '' }}</x-table.cell>
                    <x-table.cell>
                        <x-ui.button href="{{ route('admin.communications.show', $communication) }}" variant="ghost" size="xs">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </x-ui.button>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $communications->withQueryString()->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No communications sent" description="Send your first communication to members.">
            <x-ui.button href="{{ route('admin.communications.create') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Compose Message
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
