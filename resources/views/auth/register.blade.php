<x-layouts.guest title="Create Account">
    <h2 class="mb-1 text-xl font-bold text-gray-900">Create your account</h2>
    <p class="mb-6 text-sm text-gray-500">Join the Pharmaceutical Society of Kenya</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <x-form.input name="first_name" label="First Name" required placeholder="John" />
            <x-form.input name="last_name" label="Last Name" required placeholder="Doe" />
        </div>
        <x-form.input name="email" label="Email Address" type="email" required placeholder="john@example.com" />
        <x-form.input name="phone" label="Phone Number" required placeholder="+254700000000" />
        <x-form.input name="password" label="Password" type="password" required placeholder="Min 8 characters" hint="Must include uppercase, lowercase and numbers" />
        <x-form.input name="password_confirmation" label="Confirm Password" type="password" required placeholder="Repeat your password" />

        <x-ui.button type="submit" variant="primary" class="w-full">Create Account</x-ui.button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">Sign in</a>
    </p>
</x-layouts.guest>
