<x-layouts.admin title="Edit {{ $member->first_name }} {{ $member->last_name }}">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Members', 'url' => route('admin.members.index')],
        ['label' => $member->first_name . ' ' . $member->last_name, 'url' => route('admin.members.show', $member)],
        ['label' => 'Edit'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Member</h1>
        <p class="text-sm text-gray-500">Update details for {{ $member->first_name }} {{ $member->last_name }}</p>
    </div>

    <form method="POST" action="{{ route('admin.members.update', $member) }}" class="mx-auto max-w-3xl">
        @csrf
        @method('PUT')

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Personal Information</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="first_name" label="First Name" required placeholder="Enter first name"
                              :value="$member->first_name" />
                <x-form.input name="last_name" label="Last Name" required placeholder="Enter last name"
                              :value="$member->last_name" />
                <x-form.input name="email" label="Email Address" type="email" required placeholder="member@example.com"
                              :value="$member->email" />
                <x-form.input name="phone" label="Phone Number" type="tel" placeholder="+254 7XX XXX XXX"
                              :value="$member->phone" />
                <x-form.input name="ppb_registration_no" label="PPB Registration No." placeholder="PPB/XXXX"
                              :value="$member->ppb_registration_no" />
                <x-form.input name="national_id" label="National ID" placeholder="Enter national ID number"
                              :value="$member->national_id" />
                <x-form.date-picker name="date_of_birth" label="Date of Birth"
                                    :value="$member->date_of_birth?->format('Y-m-d')" />
                <x-form.select name="gender" label="Gender" :options="[
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other',
                ]" :selected="$member->gender" placeholder="Select gender" />
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Membership</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.select name="membership_tier_id" label="Membership Tier"
                               :options="$tiers->pluck('name', 'id')->toArray()"
                               :selected="$member->membership?->membership_tier_id"
                               placeholder="Select a tier" />
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Change Password</h3>
            <p class="mb-4 text-sm text-gray-500">Leave blank to keep the current password.</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="password" label="New Password" type="password" placeholder="Minimum 8 characters"
                              hint="Leave blank to keep current password" />
                <x-form.input name="password_confirmation" label="Confirm Password" type="password" placeholder="Repeat new password" />
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.members.show', $member) }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Update Member
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
