<x-layouts.admin title="Add Member">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Members', 'url' => route('admin.members.index')],
        ['label' => 'Add Member'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Member</h1>
        <p class="text-sm text-gray-500">Create a new member account</p>
    </div>

    <form method="POST" action="{{ route('admin.members.store') }}" class="mx-auto max-w-3xl">
        @csrf

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Personal Information</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="first_name" label="First Name" required placeholder="Enter first name" />
                <x-form.input name="last_name" label="Last Name" required placeholder="Enter last name" />
                <x-form.input name="email" label="Email Address" type="email" required placeholder="member@example.com" />
                <x-form.input name="phone" label="Phone Number" type="tel" placeholder="+254 7XX XXX XXX" />
                <x-form.input name="ppb_registration_no" label="PPB Registration No." placeholder="PPB/XXXX" />
                <x-form.input name="national_id" label="National ID" placeholder="Enter national ID number" />
                <x-form.date-picker name="date_of_birth" label="Date of Birth" />
                <x-form.select name="gender" label="Gender" :options="[
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other',
                ]" placeholder="Select gender" />
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Membership</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.select name="membership_tier_id" label="Membership Tier" :options="$tiers->pluck('name', 'id')->toArray()" placeholder="Select a tier" />
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Account Credentials</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.input name="password" label="Password" type="password" required placeholder="Minimum 8 characters"
                              hint="Must be at least 8 characters long" />
                <x-form.input name="password_confirmation" label="Confirm Password" type="password" required placeholder="Repeat password" />
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.members.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Member
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
