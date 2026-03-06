<x-layouts.admin title="Review Application">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Applications', 'url' => route('admin.applications.index')],
        ['label' => $application->user->first_name . ' ' . $application->user->last_name],
    ]" />

    @php
        $statusColors = [
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'under_review' => 'blue',
        ];
        $statusValue = $application->status->value ?? $application->status;
        $statusColor = $statusColors[$statusValue] ?? 'gray';
        $isPending = in_array($statusValue, ['pending', 'under_review']);
    @endphp

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Review Application</h1>
            <p class="text-sm text-gray-500">Submitted {{ $application->created_at->format('d M Y \a\t H:i') }}</p>
        </div>
        <x-ui.badge :color="$statusColor" size="md">{{ ucfirst(str_replace('_', ' ', $statusValue)) }}</x-ui.badge>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Left Column: Applicant Details --}}
        <div class="space-y-6">
            {{-- Applicant Info --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Applicant Details</h3>
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-100 text-lg font-bold text-primary-700">
                        {{ strtoupper(substr($application->user->first_name, 0, 1) . substr($application->user->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $application->user->first_name }} {{ $application->user->last_name }}</p>
                        <p class="text-sm text-gray-500">{{ $application->user->email }}</p>
                    </div>
                </div>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Phone</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $application->user->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">PPB Registration No.</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $application->user->ppb_registration_no ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">National ID</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $application->user->national_id ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Account Created</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $application->user->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </x-ui.card>

            {{-- Quick Actions --}}
            @if($isPending)
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Actions</h3>
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('admin.applications.approve', $application) }}">
                            @csrf
                            <x-ui.button type="submit" variant="success" class="w-full">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Approve Application
                            </x-ui.button>
                        </form>
                        <x-ui.button variant="danger" class="w-full"
                                     @click="$dispatch('open-modal', 'reject-application')">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reject Application
                        </x-ui.button>
                    </div>
                </x-ui.card>
            @endif
        </div>

        {{-- Right Column: Application Details --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Application Details --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Application Details</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Tier Applied For</dt>
                        <dd class="mt-0.5">
                            <span class="text-sm font-medium text-gray-900">{{ $application->tier->name }}</span>
                            <span class="ml-1 text-sm text-gray-500">(KES {{ number_format($application->tier->annual_fee, 2) }}/year)</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Registration Fee</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">KES {{ number_format($application->tier->registration_fee, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Date Submitted</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $application->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Last Updated</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $application->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>

                {{-- Application Data --}}
                @if($application->application_data)
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <h4 class="mb-3 text-sm font-semibold text-gray-900">Additional Information</h4>
                        @foreach($application->application_data as $key => $value)
                            <div class="mb-3">
                                <dt class="text-xs font-medium uppercase text-gray-500">{{ Str::title(str_replace('_', ' ', $key)) }}</dt>
                                <dd class="mt-0.5 text-sm text-gray-900">{{ is_array($value) ? implode(', ', $value) : $value }}</dd>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>

            {{-- Documents --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Uploaded Documents</h3>
                @if($application->documents && count($application->documents) > 0)
                    <div class="space-y-3">
                        @foreach($application->documents as $document)
                            <div class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $document->original_name ?? $document->name ?? basename($document->path ?? $document) }}</p>
                                        @if(isset($document->size))
                                            <p class="text-xs text-gray-500">{{ number_format($document->size / 1024, 1) }} KB</p>
                                        @endif
                                    </div>
                                </div>
                                <x-ui.button href="{{ is_string($document) ? Storage::url($document) : Storage::url($document->path) }}" variant="ghost" size="xs" target="_blank">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    View
                                </x-ui.button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No documents were uploaded with this application.</p>
                @endif
            </x-ui.card>

            {{-- Rejection Reason (if rejected) --}}
            @if($statusValue === 'rejected' && $application->rejection_reason)
                <x-ui.alert type="error" :dismissible="false">
                    <p class="font-medium">Application Rejected</p>
                    <p class="mt-1">{{ $application->rejection_reason }}</p>
                    @if($application->reviewed_by)
                        <p class="mt-2 text-xs opacity-75">Reviewed by {{ $application->reviewer?->first_name }} {{ $application->reviewer?->last_name }} on {{ $application->reviewed_at?->format('d M Y, H:i') }}</p>
                    @endif
                </x-ui.alert>
            @endif

            {{-- Approval Info (if approved) --}}
            @if($statusValue === 'approved')
                <x-ui.alert type="success" :dismissible="false">
                    <p class="font-medium">Application Approved</p>
                    @if($application->reviewed_by)
                        <p class="mt-1 text-xs opacity-75">Approved by {{ $application->reviewer?->first_name }} {{ $application->reviewer?->last_name }} on {{ $application->reviewed_at?->format('d M Y, H:i') }}</p>
                    @endif
                </x-ui.alert>
            @endif
        </div>
    </div>

    {{-- Reject Confirmation Modal --}}
    @if($isPending)
        @push('modals')
            <x-ui.modal name="reject-application" maxWidth="md">
                <form method="POST" action="{{ route('admin.applications.reject', $application) }}">
                    @csrf
                    <h3 class="text-lg font-semibold text-gray-900">Reject Application</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Are you sure you want to reject the application from {{ $application->user->first_name }} {{ $application->user->last_name }}?
                        This action will notify the applicant.
                    </p>

                    <div class="mt-4">
                        <x-form.textarea name="rejection_reason" label="Reason for Rejection" required
                                         placeholder="Please provide a reason for rejecting this application..." rows="3" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-ui.button variant="secondary" @click="$dispatch('close-modal', 'reject-application')">Cancel</x-ui.button>
                        <x-ui.button type="submit" variant="danger">Reject Application</x-ui.button>
                    </div>
                </form>
            </x-ui.modal>
        @endpush
    @endif
</x-layouts.admin>
