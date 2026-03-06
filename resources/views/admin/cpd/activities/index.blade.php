<x-layouts.admin title="CPD Activities">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">CPD Activities</h1>
            <p class="text-sm text-gray-500">{{ $activities->total() }} {{ Str::plural('activity', $activities->total()) }} logged</p>
        </div>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.cpd.activities.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <x-form.input name="search" label="Search" placeholder="Search by member name or activity title..."
                              :value="request('search')" />
            </div>
            <div class="w-full sm:w-44">
                <x-form.select name="status" label="Status" :options="[
                    'verified' => 'Verified',
                    'unverified' => 'Unverified',
                ]" :selected="request('status')" placeholder="All Statuses" />
            </div>
            <div class="w-full sm:w-48">
                <x-form.select name="category" label="Category" :options="$categories->pluck('name', 'id')->toArray()"
                               :selected="request('category')" placeholder="All Categories" />
            </div>
            <div class="w-full sm:w-36">
                <x-form.select name="year" label="Year" :options="$years"
                               :selected="request('year')" placeholder="All Years" />
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="secondary" size="md">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </x-ui.button>
                @if(request()->hasAny(['search', 'status', 'category', 'year']))
                    <x-ui.button href="{{ route('admin.cpd.activities.index') }}" variant="ghost" size="md">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- Activities Table --}}
    @if($activities->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="user_name" :direction="request('sort') === 'user_name' ? request('direction') : null">Member Name</x-table.heading>
                <x-table.heading sortable column="title" :direction="request('sort') === 'title' ? request('direction') : null">Activity Title</x-table.heading>
                <x-table.heading>Category</x-table.heading>
                <x-table.heading sortable column="points" :direction="request('sort') === 'points' ? request('direction') : null">Points</x-table.heading>
                <x-table.heading sortable column="activity_date" :direction="request('sort') === 'activity_date' ? request('direction') : null">Date</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($activities as $activity)
                <tr>
                    <x-table.cell>
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-medium text-primary-700">
                                {{ strtoupper(substr($activity->user->first_name, 0, 1) . substr($activity->user->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $activity->user->last_name }}, {{ $activity->user->first_name }}</p>
                                <p class="text-xs text-gray-500">{{ $activity->user->email }}</p>
                            </div>
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <span class="font-medium text-gray-900">{{ $activity->title }}</span>
                    </x-table.cell>
                    <x-table.cell>{{ $activity->category->name ?? '-' }}</x-table.cell>
                    <x-table.cell>
                        <span class="font-semibold text-gray-900">{{ $activity->points }}</span>
                    </x-table.cell>
                    <x-table.cell>{{ $activity->activity_date->format('d M Y') }}</x-table.cell>
                    <x-table.cell>
                        <x-ui.badge :color="$activity->status->color()">{{ $activity->status->label() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.cpd.activities.show', $activity) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </x-ui.button>
                            @if($activity->status === \App\Enums\CpdActivityStatus::Pending)
                                <form method="POST" action="{{ route('admin.cpd.activities.verify', $activity) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <x-ui.button type="submit" variant="ghost" size="xs" onclick="return confirm('Approve this CPD activity?')">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </x-ui.button>
                                </form>
                            @endif
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $activities->withQueryString()->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No CPD activities found" description="No CPD activities match your current filters. Try adjusting your search criteria.">
            <x-slot:icon>
                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </x-slot:icon>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
