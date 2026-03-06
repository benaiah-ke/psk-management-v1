<x-layouts.admin title="Dashboard">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->first_name }}. Here's your overview.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        <x-ui.stat-card label="Total Members" :value="$totalMembers ?? 0" color="primary">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Pending Applications" :value="$pendingApplications ?? 0" color="yellow">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Revenue (KES)" :value="number_format($totalRevenue ?? 0)" color="green">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Upcoming Events" :value="$upcomingEvents ?? 0" color="blue">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Open Tickets" :value="$openTickets ?? 0" color="purple">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Expiring Soon" :value="$expiringMemberships ?? 0" color="orange">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Charts Row --}}
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Members by Tier Doughnut Chart --}}
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Members by Tier</h3>
            <div class="flex items-center justify-center" style="height: 300px;">
                <canvas id="membersByTierChart"></canvas>
            </div>
        </x-ui.card>

        {{-- Monthly Revenue Bar Chart --}}
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Monthly Revenue (KES)</h3>
            <div style="height: 300px;">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </x-ui.card>
    </div>

    {{-- Three-column Bottom Section --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Recent Applications --}}
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Recent Applications</h3>
            @if($recentApplications->isEmpty())
                <x-ui.empty-state title="No applications" description="No membership applications have been submitted yet." />
            @else
                <div class="space-y-3">
                    @foreach($recentApplications as $application)
                        <div class="flex items-center justify-between rounded-lg border border-gray-100 p-3">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900">
                                    {{ $application->user->first_name }} {{ $application->user->last_name }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $application->tier->name }}</p>
                            </div>
                            <div class="ml-3 flex flex-col items-end gap-1">
                                <x-ui.badge :color="$application->status->color()" size="xs">
                                    {{ $application->status->label() }}
                                </x-ui.badge>
                                <span class="text-xs text-gray-400">{{ $application->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.card>

        {{-- Recent Payments --}}
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Recent Payments</h3>
            @if($recentPayments->isEmpty())
                <x-ui.empty-state title="No payments" description="No payments have been recorded yet." />
            @else
                <div class="space-y-3">
                    @foreach($recentPayments as $payment)
                        <div class="flex items-center justify-between rounded-lg border border-gray-100 p-3">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900">
                                    {{ $payment->invoice?->user?->first_name }} {{ $payment->invoice?->user?->last_name }}
                                </p>
                                <p class="text-xs font-semibold text-gray-700">KES {{ number_format($payment->amount, 2) }}</p>
                            </div>
                            <div class="ml-3 flex flex-col items-end gap-1">
                                <x-ui.badge :color="$payment->status->color()" size="xs">
                                    {{ $payment->status->label() }}
                                </x-ui.badge>
                                <span class="text-xs text-gray-400">{{ $payment->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-ui.card>

        {{-- Upcoming Events + Quick Actions --}}
        <div class="space-y-6">
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Upcoming Events</h3>
                @if($upcomingEventsList->isEmpty())
                    <x-ui.empty-state title="No upcoming events" description="There are no upcoming events scheduled." />
                @else
                    <div class="space-y-3">
                        @foreach($upcomingEventsList as $event)
                            <div class="flex items-center justify-between rounded-lg border border-gray-100 p-3">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900">{{ $event->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $event->start_date->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                                <div class="ml-3 text-right">
                                    <span class="text-sm font-semibold text-gray-700">{{ $event->registered_count }}</span>
                                    <p class="text-xs text-gray-400">registered</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>

            {{-- Quick Actions --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Quick Actions</h3>
                <div class="space-y-2">
                    <x-ui.button href="{{ route('admin.members.create') }}" variant="secondary" class="w-full justify-start">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Add Member
                    </x-ui.button>
                    <x-ui.button href="{{ route('admin.invoices.create') }}" variant="secondary" class="w-full justify-start">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Create Invoice
                    </x-ui.button>
                    <x-ui.button href="{{ route('admin.events.create') }}" variant="secondary" class="w-full justify-start">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Create Event
                    </x-ui.button>
                    <x-ui.button href="{{ route('admin.communications.create') }}" variant="secondary" class="w-full justify-start">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Send Message
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            // Chart color palette
            const colors = ['#3f51b5', '#4caf50', '#7986cb', '#43a047', '#303f9f', '#66bb6a'];

            // Members by Tier - Doughnut Chart
            const tierData = @json($membersByTier);
            const tierCtx = document.getElementById('membersByTierChart').getContext('2d');
            new Chart(tierCtx, {
                type: 'doughnut',
                data: {
                    labels: tierData.map(t => t.label),
                    datasets: [{
                        data: tierData.map(t => t.count),
                        backgroundColor: colors.slice(0, tierData.length),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 16,
                                usePointStyle: true,
                                pointStyleWidth: 10,
                                font: { size: 12 }
                            }
                        }
                    },
                    cutout: '60%',
                }
            });

            // Monthly Revenue - Bar Chart
            const revenueData = @json($monthlyRevenue);
            const revenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: revenueData.map(r => r.month),
                    datasets: [{
                        label: 'Revenue (KES)',
                        data: revenueData.map(r => r.amount),
                        backgroundColor: '#3949ab',
                        borderColor: '#3949ab',
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'KES ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'KES ' + value.toLocaleString();
                                },
                                font: { size: 11 }
                            },
                            grid: { color: '#f3f4f6' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 12 } }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.admin>
