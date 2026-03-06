<x-layouts.portal title="Profile">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Profile Information</h3>
            <form method="POST" action="{{ route('portal.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <x-form.input name="first_name" label="First Name" :value="$user->first_name" required />
                    <x-form.input name="last_name" label="Last Name" :value="$user->last_name" required />
                </div>
                <x-form.input name="email" label="Email" type="email" :value="$user->email" disabled />
                <x-form.input name="phone" label="Phone" :value="$user->phone" required />
                <x-form.date-picker name="date_of_birth" label="Date of Birth" :value="$user->date_of_birth?->format('Y-m-d')" />
                <x-form.select name="gender" label="Gender" :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" :selected="$user->gender" placeholder="Select gender" />
                <x-ui.button type="submit">Save Changes</x-ui.button>
            </form>
        </x-ui.card>

        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Change Password</h3>
            <form method="POST" action="{{ route('portal.profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <x-form.input name="current_password" label="Current Password" type="password" required />
                <x-form.input name="password" label="New Password" type="password" required />
                <x-form.input name="password_confirmation" label="Confirm New Password" type="password" required />
                <x-ui.button type="submit">Update Password</x-ui.button>
            </form>
        </x-ui.card>
    </div>
</x-layouts.portal>
