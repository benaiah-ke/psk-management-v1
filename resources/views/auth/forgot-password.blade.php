<x-layouts.guest title="Forgot Password">
    <h2 class="mb-1 text-xl font-bold text-gray-900">Reset your password</h2>
    <p class="mb-6 text-sm text-gray-500">Enter your email and we'll send you a reset link</p>

    @if(session('status'))
        <x-ui.alert type="success" class="mb-4">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <x-form.input name="email" label="Email Address" type="email" required placeholder="john@example.com" />
        <x-ui.button type="submit" variant="primary" class="w-full">Send Reset Link</x-ui.button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">Back to sign in</a>
    </p>
</x-layouts.guest>
