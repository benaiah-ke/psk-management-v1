<x-layouts.portal title="Membership">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Membership</h1>
        <p class="text-sm text-gray-500">View and manage your membership status</p>
    </div>

    @if($membership)
        {{-- Active Membership Card --}}
        @php
            $statusColors = [
                'active' => 'green',
                'expired' => 'red',
                'suspended' => 'yellow',
                'pending' => 'blue',
            ];
            $statusValue = $membership->status->value ?? $membership->status;
            $statusColor = $statusColors[$statusValue] ?? 'gray';
            $isExpiringSoon = $membership->expires_at && $membership->expires_at->diffInDays(now()) <= 30 && $membership->expires_at->isFuture();
        @endphp

        {{-- Membership Card --}}
        <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-gradient-to-br from-primary-700 to-primary-900 shadow-sm">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                    <div class="text-white">
                        <p class="text-sm font-medium uppercase tracking-wider text-primary-200">{{ $membership->tier->name }}</p>
                        <h2 class="mt-1 text-2xl font-bold">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
                        <p class="mt-1 text-sm text-primary-200">Member No: {{ $membership->membership_number ?? 'Pending' }}</p>
                    </div>
                    <div class="text-right">
                        <x-ui.badge :color="$statusColor" size="md">{{ ucfirst($statusValue) }}</x-ui.badge>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-6 border-t border-primary-600 pt-6 sm:grid-cols-4">
                    <div>
                        <p class="text-xs font-medium uppercase text-primary-300">Tier</p>
                        <p class="mt-0.5 text-sm font-medium text-white">{{ $membership->tier->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-primary-300">Status</p>
                        <p class="mt-0.5 text-sm font-medium text-white">{{ ucfirst($statusValue) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-primary-300">Member Since</p>
                        <p class="mt-0.5 text-sm font-medium text-white">{{ $membership->created_at->format('M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-primary-300">Expires</p>
                        <p class="mt-0.5 text-sm font-medium text-white">
                            {{ $membership->expires_at?->format('d M Y') ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Expiry Warning --}}
        @if($isExpiringSoon)
            <x-ui.alert type="warning" class="mb-6">
                Your membership expires on {{ $membership->expires_at->format('d M Y') }} ({{ $membership->expires_at->diffForHumans() }}).
                <a href="{{ route('portal.membership.renew') }}" class="font-semibold underline">Renew now</a> to maintain your membership benefits.
            </x-ui.alert>
        @endif

        @if($statusValue === 'expired')
            <x-ui.alert type="error" class="mb-6">
                Your membership has expired. Please <a href="{{ route('portal.membership.renew') }}" class="font-semibold underline">renew your membership</a> to regain access to member benefits.
            </x-ui.alert>
        @endif

        {{-- Quick Actions --}}
        <div class="mb-8 flex flex-wrap gap-3">
            @if(in_array($statusValue, ['active', 'expired']))
                <x-ui.button href="{{ route('portal.membership.renew') }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Renew Membership
                </x-ui.button>
            @endif
            <x-ui.button href="{{ route('portal.profile') }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Update Profile
            </x-ui.button>
        </div>
    @else
        {{-- No Active Membership --}}
        <x-ui.card class="mb-8">
            <div class="py-8 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary-100">
                    <svg class="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Not a Member Yet</h3>
                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                    Join PSK to access exclusive benefits, CPD opportunities, events, and connect with fellow professionals in the pharmaceutical sector.
                </p>
                <div class="mt-6">
                    <x-ui.button href="{{ route('portal.membership.apply') }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Apply for Membership
                    </x-ui.button>
                </div>
            </div>
        </x-ui.card>
    @endif

    {{-- Recent Applications --}}
    <div>
        <h2 class="mb-4 text-lg font-semibold text-gray-900">Application History</h2>

        @if($applications->count())
            <x-table.table>
                <x-slot:head>
                    <x-table.heading>Date</x-table.heading>
                    <x-table.heading>Tier</x-table.heading>
                    <x-table.heading>Status</x-table.heading>
                    <x-table.heading>Notes</x-table.heading>
                </x-slot:head>

                @foreach($applications as $application)
                    <tr>
                        <x-table.cell>{{ $application->created_at->format('d M Y') }}</x-table.cell>
                        <x-table.cell>
                            <span class="font-medium text-gray-900">{{ $application->tier->name }}</span>
                        </x-table.cell>
                        <x-table.cell>
                            @php
                                $appStatusColors = [
                                    'pending' => 'yellow',
                                    'approved' => 'green',
                                    'rejected' => 'red',
                                    'under_review' => 'blue',
                                ];
                                $appStatusValue = $application->status->value ?? $application->status;
                                $appStatusColor = $appStatusColors[$appStatusValue] ?? 'gray';
                            @endphp
                            <x-ui.badge :color="$appStatusColor">{{ ucfirst(str_replace('_', ' ', $appStatusValue)) }}</x-ui.badge>
                        </x-table.cell>
                        <x-table.cell>
                            @if($appStatusValue === 'rejected' && $application->rejection_reason)
                                <span class="text-sm text-red-600">{{ Str::limit($application->rejection_reason, 60) }}</span>
                            @elseif($appStatusValue === 'pending')
                                <span class="text-sm text-gray-500">Under review</span>
                            @elseif($appStatusValue === 'approved')
                                <span class="text-sm text-green-600">Approved {{ $application->reviewed_at?->format('d M Y') }}</span>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </x-table.cell>
                    </tr>
                @endforeach
            </x-table.table>
        @else
            <x-ui.empty-state title="No applications yet" description="You haven't submitted any membership applications.">
                @unless($membership)
                    <x-ui.button href="{{ route('portal.membership.apply') }}" variant="secondary">Apply Now</x-ui.button>
                @endunless
            </x-ui.empty-state>
        @endif
    </div>
</x-layouts.portal>
