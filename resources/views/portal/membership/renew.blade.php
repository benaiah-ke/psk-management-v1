<x-layouts.portal title="Renew Membership">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Renew Membership</h1>
        <p class="text-sm text-gray-500">Renew your membership to continue enjoying PSK benefits</p>
    </div>

    <div class="mx-auto max-w-2xl">
        {{-- Current Membership Summary --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Current Membership</h3>

            @php
                $statusColors = [
                    'active' => 'green',
                    'expired' => 'red',
                    'suspended' => 'yellow',
                    'pending' => 'blue',
                ];
                $statusValue = $membership->status->value ?? $membership->status;
                $statusColor = $statusColors[$statusValue] ?? 'gray';
            @endphp

            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Membership Tier</p>
                        <p class="mt-0.5 text-lg font-semibold text-gray-900">{{ $membership->tier->name }}</p>
                    </div>
                    <x-ui.badge :color="$statusColor" size="md">{{ ucfirst($statusValue) }}</x-ui.badge>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-500">Membership Number</p>
                        <p class="mt-0.5 text-sm text-gray-900">{{ $membership->membership_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-500">Current Expiry</p>
                        <p class="mt-0.5 text-sm text-gray-900">
                            {{ $membership->expires_at?->format('d M Y') ?? '-' }}
                            @if($membership->expires_at?->isPast())
                                <span class="ml-1 text-xs font-medium text-red-600">(Expired)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-500">Member Since</p>
                        <p class="mt-0.5 text-sm text-gray-900">{{ $membership->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase text-gray-500">New Expiry Date</p>
                        <p class="mt-0.5 text-sm font-semibold text-green-600">
                            @if($membership->expires_at?->isFuture())
                                {{ $membership->expires_at->addYear()->format('d M Y') }}
                            @else
                                {{ now()->addYear()->format('d M Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </x-ui.card>

        {{-- Renewal Fee --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Renewal Fee</h3>

            <div class="space-y-3">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <span class="text-sm text-gray-600">Annual membership fee ({{ $membership->tier->name }})</span>
                    <span class="text-sm font-medium text-gray-900">KES {{ number_format($membership->tier->annual_fee, 2) }}</span>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <span class="text-base font-semibold text-gray-900">Total Due</span>
                    <span class="text-lg font-bold text-primary-600">KES {{ number_format($membership->tier->annual_fee, 2) }}</span>
                </div>
            </div>
        </x-ui.card>

        {{-- Renewal Benefits Reminder --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Renewal Benefits</h3>
            <ul class="space-y-2">
                <li class="flex items-start gap-2 text-sm text-gray-700">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Continued access to CPD opportunities and events
                </li>
                <li class="flex items-start gap-2 text-sm text-gray-700">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Maintain your membership number and seniority
                </li>
                <li class="flex items-start gap-2 text-sm text-gray-700">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Access to the PSK professional network and resources
                </li>
                <li class="flex items-start gap-2 text-sm text-gray-700">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Eligibility for member-only discounts and opportunities
                </li>
            </ul>
        </x-ui.card>

        {{-- Action Buttons --}}
        <form method="POST" action="{{ route('portal.membership.renew.submit') }}">
            @csrf
            <div class="flex items-center justify-end gap-3">
                <x-ui.button href="{{ route('portal.membership') }}" variant="secondary">Cancel</x-ui.button>
                <x-ui.button type="submit">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Proceed to Renew - KES {{ number_format($membership->tier->annual_fee, 2) }}
                </x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.portal>
