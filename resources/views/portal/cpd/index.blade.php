<x-layouts.portal title="My CPD">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My CPD</h1>
            <p class="text-sm text-gray-500">Track your Continuing Professional Development for {{ $currentYear }}</p>
        </div>
        <x-ui.button href="{{ route('portal.cpd.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Log Activity
        </x-ui.button>
    </div>

    {{-- Progress Bar --}}
    <x-ui.card class="mb-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-gray-900">Annual Progress</h3>
            <span class="text-sm font-medium text-gray-700">
                {{ $totalPoints }} / {{ $requiredPoints }} points
                ({{ $requiredPoints > 0 ? round(($totalPoints / $requiredPoints) * 100) : 0 }}%)
            </span>
        </div>
        <div class="h-4 w-full overflow-hidden rounded-full bg-gray-200">
            @php
                $percentage = $requiredPoints > 0 ? min(100, round(($totalPoints / $requiredPoints) * 100)) : 0;
                $barColor = $percentage >= 100 ? 'bg-green-500' : ($percentage >= 50 ? 'bg-primary-500' : 'bg-yellow-500');
            @endphp
            <div class="h-full rounded-full transition-all duration-500 {{ $barColor }}" style="width: {{ $percentage }}%"></div>
        </div>
        @if($percentage >= 100)
            <p class="mt-2 text-sm text-green-600 font-medium">You have met your CPD requirements for {{ $currentYear }}.</p>
        @else
            <p class="mt-2 text-sm text-gray-500">You need {{ $requiredPoints - $totalPoints }} more {{ Str::plural('point', $requiredPoints - $totalPoints) }} to meet your annual requirement.</p>
        @endif
    </x-ui.card>

    {{-- Stat Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card label="Points Earned" :value="$totalPoints" color="primary">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Points Required" :value="$requiredPoints" color="blue">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Verified Points" :value="$activities->where('is_verified', true)->sum('points')" color="green">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Pending Points" :value="$activities->where('is_verified', false)->sum('points')" color="yellow">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Activities Table --}}
    <div class="mb-6">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Activities ({{ $currentYear }})</h3>
        @if($activities->count())
            <x-table.table>
                <x-slot:head>
                    <x-table.heading>Activity</x-table.heading>
                    <x-table.heading>Category</x-table.heading>
                    <x-table.heading>Points</x-table.heading>
                    <x-table.heading>Date</x-table.heading>
                    <x-table.heading>Status</x-table.heading>
                </x-slot:head>

                @foreach($activities as $activity)
                    <tr>
                        <x-table.cell>
                            <span class="font-medium text-gray-900">{{ $activity->title }}</span>
                            @if($activity->description)
                                <p class="mt-0.5 text-xs text-gray-500 line-clamp-1">{{ $activity->description }}</p>
                            @endif
                        </x-table.cell>
                        <x-table.cell>{{ $activity->category->name ?? '-' }}</x-table.cell>
                        <x-table.cell>
                            <span class="font-semibold text-gray-900">{{ $activity->points }}</span>
                        </x-table.cell>
                        <x-table.cell>{{ $activity->activity_date->format('d M Y') }}</x-table.cell>
                        <x-table.cell>
                            @if($activity->is_verified)
                                <x-ui.badge color="green">Verified</x-ui.badge>
                            @else
                                <x-ui.badge color="yellow">Pending</x-ui.badge>
                            @endif
                        </x-table.cell>
                    </tr>
                @endforeach
            </x-table.table>
        @else
            <x-ui.empty-state title="No CPD activities logged" description="Start logging your professional development activities to track your progress.">
                <x-ui.button href="{{ route('portal.cpd.create') }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Log Activity
                </x-ui.button>
            </x-ui.empty-state>
        @endif
    </div>

    {{-- Certificates --}}
    @if(isset($certificates) && $certificates->count())
        <div>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">CPD Certificates</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($certificates as $certificate)
                    <x-ui.card>
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $certificate->year }} CPD Certificate</p>
                                <p class="text-sm text-gray-500">{{ $certificate->points }} points earned</p>
                                <x-ui.button href="{{ route('portal.cpd.certificate.download', $certificate) }}" variant="ghost" size="xs" class="mt-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Download
                                </x-ui.button>
                            </div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    @endif
</x-layouts.portal>
