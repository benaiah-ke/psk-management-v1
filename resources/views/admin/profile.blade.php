<x-layouts.admin title="Profile">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-ui.card>
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Profile Information</h3>
            <form method="POST" action="{{ route('admin.profile') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <x-form.input name="first_name" label="First Name" :value="auth()->user()->first_name" required />
                    <x-form.input name="last_name" label="Last Name" :value="auth()->user()->last_name" required />
                </div>
                <x-form.input name="email" label="Email" type="email" :value="auth()->user()->email" disabled />
                <x-form.input name="phone" label="Phone" :value="auth()->user()->phone" required />
                <x-ui.button type="submit">Save Changes</x-ui.button>
            </form>
        </x-ui.card>
    </div>
</x-layouts.admin>
