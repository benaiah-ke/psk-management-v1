<x-layouts.admin title="{{ $member->first_name }} {{ $member->last_name }}">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Members', 'url' => route('admin.members.index')],
        ['label' => $member->first_name . ' ' . $member->last_name],
    ]" />

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-primary-100 text-xl font-bold text-primary-700">
                {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $member->first_name }} {{ $member->last_name }}</h1>
                <p class="text-sm text-gray-500">Member since {{ $member->created_at->format('F Y') }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <x-ui.button href="{{ route('admin.members.edit', $member) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Member
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Left Column: Profile & Membership --}}
        <div class="space-y-6">
            {{-- Profile Information --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Profile Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Email</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">
                            <a href="mailto:{{ $member->email }}" class="text-primary-600 hover:text-primary-700">{{ $member->email }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Phone</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $member->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">PPB Registration No.</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $member->ppb_registration_no ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">National ID</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $member->national_id ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Gender</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ ucfirst($member->gender ?? '-') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Date of Birth</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $member->date_of_birth?->format('d M Y') ?? '-' }}</dd>
                    </div>
                </dl>
            </x-ui.card>

            {{-- Membership Card --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Membership</h3>
                @if($member->membership)
                    @php
                        $statusColors = [
                            'active' => 'green',
                            'expired' => 'red',
                            'suspended' => 'yellow',
                            'pending' => 'blue',
                        ];
                        $statusColor = $statusColors[$member->membership->status->value ?? $member->membership->status] ?? 'gray';
                    @endphp
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Tier</dt>
                            <dd class="mt-0.5 text-sm font-medium text-gray-900">{{ $member->membership->tier->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <x-ui.badge :color="$statusColor">{{ ucfirst($member->membership->status->value ?? $member->membership->status) }}</x-ui.badge>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Membership Number</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $member->membership->membership_number ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Expiry Date</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">
                                {{ $member->membership->expires_at?->format('d M Y') ?? '-' }}
                                @if($member->membership->expires_at?->isPast())
                                    <span class="ml-1 text-xs text-red-600">(Expired)</span>
                                @elseif($member->membership->expires_at?->diffInDays(now()) <= 30)
                                    <span class="ml-1 text-xs text-yellow-600">(Expiring soon)</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-gray-500">This user does not have an active membership.</p>
                @endif
            </x-ui.card>
        </div>

        {{-- Right Column: Activity Sections --}}
        <div class="space-y-6 lg:col-span-2" x-data="{ activeTab: 'invoices' }">
            {{-- Tab Navigation --}}
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex gap-6">
                    <button @click="activeTab = 'invoices'"
                            :class="activeTab === 'invoices' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        Invoices
                        @if($member->invoices->count())
                            <span class="ml-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $member->invoices->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'cpd'"
                            :class="activeTab === 'cpd' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        CPD Activities
                        @if($member->cpdActivities->count())
                            <span class="ml-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $member->cpdActivities->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'events'"
                            :class="activeTab === 'events' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        Events
                        @if($member->eventRegistrations->count())
                            <span class="ml-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $member->eventRegistrations->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'activity'"
                            :class="activeTab === 'activity' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        Activity Log
                    </button>
                </nav>
            </div>

            {{-- Invoices Tab --}}
            <div x-show="activeTab === 'invoices'" x-cloak>
                @if($member->invoices->count())
                    <x-table.table>
                        <x-slot:head>
                            <x-table.heading>Invoice #</x-table.heading>
                            <x-table.heading>Description</x-table.heading>
                            <x-table.heading>Amount</x-table.heading>
                            <x-table.heading>Status</x-table.heading>
                            <x-table.heading>Date</x-table.heading>
                        </x-slot:head>
                        @foreach($member->invoices as $invoice)
                            <tr>
                                <x-table.cell>
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="font-medium text-primary-600 hover:text-primary-700">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </x-table.cell>
                                <x-table.cell>{{ $invoice->description ?? '-' }}</x-table.cell>
                                <x-table.cell>KES {{ number_format($invoice->total_amount, 2) }}</x-table.cell>
                                <x-table.cell>
                                    @php
                                        $invStatusColors = ['paid' => 'green', 'unpaid' => 'red', 'partial' => 'yellow', 'overdue' => 'orange'];
                                        $invColor = $invStatusColors[$invoice->status->value ?? $invoice->status] ?? 'gray';
                                    @endphp
                                    <x-ui.badge :color="$invColor">{{ ucfirst($invoice->status->value ?? $invoice->status) }}</x-ui.badge>
                                </x-table.cell>
                                <x-table.cell>{{ $invoice->created_at->format('d M Y') }}</x-table.cell>
                            </tr>
                        @endforeach
                    </x-table.table>
                @else
                    <x-ui.empty-state title="No invoices" description="This member has no invoices yet." />
                @endif
            </div>

            {{-- CPD Activities Tab --}}
            <div x-show="activeTab === 'cpd'" x-cloak>
                @if($member->cpdActivities->count())
                    <x-table.table>
                        <x-slot:head>
                            <x-table.heading>Activity</x-table.heading>
                            <x-table.heading>Category</x-table.heading>
                            <x-table.heading>Points</x-table.heading>
                            <x-table.heading>Status</x-table.heading>
                            <x-table.heading>Date</x-table.heading>
                        </x-slot:head>
                        @foreach($member->cpdActivities as $cpd)
                            <tr>
                                <x-table.cell>
                                    <span class="font-medium text-gray-900">{{ $cpd->title }}</span>
                                </x-table.cell>
                                <x-table.cell>{{ $cpd->category ?? '-' }}</x-table.cell>
                                <x-table.cell>{{ $cpd->points }}</x-table.cell>
                                <x-table.cell>
                                    @php
                                        $cpdColors = ['approved' => 'green', 'pending' => 'blue', 'rejected' => 'red'];
                                        $cpdColor = $cpdColors[$cpd->status->value ?? $cpd->status] ?? 'gray';
                                    @endphp
                                    <x-ui.badge :color="$cpdColor">{{ ucfirst($cpd->status->value ?? $cpd->status) }}</x-ui.badge>
                                </x-table.cell>
                                <x-table.cell>{{ $cpd->activity_date?->format('d M Y') ?? $cpd->created_at->format('d M Y') }}</x-table.cell>
                            </tr>
                        @endforeach
                    </x-table.table>
                @else
                    <x-ui.empty-state title="No CPD activities" description="This member has not logged any CPD activities." />
                @endif
            </div>

            {{-- Events Tab --}}
            <div x-show="activeTab === 'events'" x-cloak>
                @if($member->eventRegistrations->count())
                    <x-table.table>
                        <x-slot:head>
                            <x-table.heading>Event</x-table.heading>
                            <x-table.heading>Date</x-table.heading>
                            <x-table.heading>Status</x-table.heading>
                            <x-table.heading>Registered On</x-table.heading>
                        </x-slot:head>
                        @foreach($member->eventRegistrations as $registration)
                            <tr>
                                <x-table.cell>
                                    <a href="{{ route('admin.events.show', $registration->event) }}" class="font-medium text-primary-600 hover:text-primary-700">
                                        {{ $registration->event->title }}
                                    </a>
                                </x-table.cell>
                                <x-table.cell>{{ $registration->event->start_date?->format('d M Y') ?? '-' }}</x-table.cell>
                                <x-table.cell>
                                    @php
                                        $regColors = ['confirmed' => 'green', 'pending' => 'blue', 'cancelled' => 'red', 'attended' => 'purple'];
                                        $regColor = $regColors[$registration->status->value ?? $registration->status] ?? 'gray';
                                    @endphp
                                    <x-ui.badge :color="$regColor">{{ ucfirst($registration->status->value ?? $registration->status) }}</x-ui.badge>
                                </x-table.cell>
                                <x-table.cell>{{ $registration->created_at->format('d M Y') }}</x-table.cell>
                            </tr>
                        @endforeach
                    </x-table.table>
                @else
                    <x-ui.empty-state title="No event registrations" description="This member has not registered for any events." />
                @endif
            </div>

            {{-- Activity Log Tab --}}
            <div x-show="activeTab === 'activity'" x-cloak>
                @if(isset($activities) && $activities->count())
                    <div class="space-y-4">
                        @foreach($activities as $activity)
                            <div class="flex gap-3">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state title="No activity recorded" description="Activity for this member will appear here." />
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
