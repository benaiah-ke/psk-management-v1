<x-layouts.guest title="Reset Password">
    <h2 class="mb-1 text-xl font-bold text-gray-900">Set new password</h2>
    <p class="mb-6 text-sm text-gray-500">Enter your new password below</p>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <x-form.input name="email" label="Email Address" type="email" required :value="$email ?? old('email')" />
        <x-form.input name="password" label="New Password" type="password" required placeholder="Min 8 characters" />
        <x-form.input name="password_confirmation" label="Confirm New Password" type="password" required />
        <x-ui.button type="submit" variant="primary" class="w-full">Reset Password</x-ui.button>
    </form>
</x-layouts.guest>
