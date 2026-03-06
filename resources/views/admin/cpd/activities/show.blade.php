<x-layouts.admin title="CPD Activity Details">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'CPD Activities', 'url' => route('admin.cpd.activities.index')],
        ['label' => $activity->title],
    ]" />

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $activity->title }}</h1>
            <p class="text-sm text-gray-500">Logged on {{ $activity->created_at->format('d M Y \a\t H:i') }}</p>
        </div>
        <div class="flex gap-2">
            @if($activity->status === \App\Enums\CpdActivityStatus::Pending)
                <form method="POST" action="{{ route('admin.cpd.activities.verify', $activity) }}">
                    @csrf
                    @method('PATCH')
                    <x-ui.button type="submit" variant="success" onclick="return confirm('Approve this CPD activity?')">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Approve Activity
                    </x-ui.button>
                </form>
            @endif
            <x-ui.button href="{{ route('admin.cpd.activities.index') }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to List
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Activity Details --}}
        <div class="lg:col-span-2">
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Activity Details</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Title</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $activity->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Category</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $activity->category->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Points</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-gray-900">{{ $activity->points }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Activity Date</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $activity->activity_date->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <x-ui.badge :color="$activity->status->color()">{{ $activity->status->label() }}</x-ui.badge>
                        </dd>
                    </div>
                    @if($activity->status === \App\Enums\CpdActivityStatus::Approved && $activity->approved_at)
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Approved At</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $activity->approved_at->format('d M Y H:i') }}</dd>
                        </div>
                    @endif
                    @if($activity->description)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase text-gray-500">Description</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $activity->description }}</dd>
                        </div>
                    @endif
                </dl>
            </x-ui.card>

            {{-- Evidence --}}
            @if($activity->evidence_file_path)
                <x-ui.card class="mt-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Evidence</h3>
                    <div class="flex items-center gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 text-primary-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ basename($activity->evidence_file_path) }}</p>
                            <p class="text-xs text-gray-500">Uploaded evidence file</p>
                        </div>
                        <x-ui.button href="{{ Storage::url($activity->evidence_file_path) }}" variant="secondary" size="sm" target="_blank">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download
                        </x-ui.button>
                    </div>
                </x-ui.card>
            @endif
        </div>

        {{-- Member Information --}}
        <div>
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Member Information</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-700">
                        {{ strtoupper(substr($activity->user->first_name, 0, 1) . substr($activity->user->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $activity->user->first_name }} {{ $activity->user->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $activity->user->email }}</p>
                    </div>
                </div>
                <dl class="space-y-3">
                    @if($activity->user->membership)
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Membership Tier</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $activity->user->membership->tier->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Membership Number</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $activity->user->membership->membership_number ?? '-' }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Phone</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $activity->user->phone ?? '-' }}</dd>
                    </div>
                </dl>
                <div class="mt-4 border-t border-gray-200 pt-4">
                    <x-ui.button href="{{ route('admin.members.show', $activity->user) }}" variant="secondary" size="sm" class="w-full">
                        View Member Profile
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
