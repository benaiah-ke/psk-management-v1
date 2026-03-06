<x-layouts.guest title="Sign In">
    <h2 class="mb-1 text-xl font-bold text-gray-900">Welcome back</h2>
    <p class="mb-6 text-sm text-gray-500">Sign in to your PSK account</p>

    @if(session('status'))
        <x-ui.alert type="success" class="mb-4">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <x-form.input name="login" label="Email, Phone or PPB Reg. No." required placeholder="Enter your email, phone or PPB number" />
        <x-form.input name="password" label="Password" type="password" required placeholder="Enter your password" />

        <div class="flex items-center justify-between">
            <x-form.checkbox name="remember" label="Remember me" />
            <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">Forgot password?</a>
        </div>

        <x-ui.button type="submit" variant="primary" class="w-full">Sign In</x-ui.button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">Create one</a>
    </p>
</x-layouts.guest>
