<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Member portal for {{ config('psk.organization.name') }}. Manage your membership, events, CPD, and more.">
    <meta name="theme-color" content="#3f51b5">
    <title>{{ $title ?? 'Portal' }} - {{ config('app.name') }}</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.svg">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ mobileMenuOpen: false }">
    {{-- Portal Navigation --}}
    <nav class="border-b border-gray-200 bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                {{-- Logo --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-700 text-sm font-bold text-white">PSK</div>
                        <span class="hidden text-lg font-semibold text-gray-900 sm:block">Member Portal</span>
                    </a>
                </div>

                {{-- Desktop Nav --}}
                <div class="hidden items-center gap-1 md:flex">
                    <x-navigation.portal-link href="{{ route('portal.dashboard') }}" :active="request()->routeIs('portal.dashboard')">Dashboard</x-navigation.portal-link>
                    <x-navigation.portal-link href="{{ route('portal.membership') }}" :active="request()->routeIs('portal.membership*')">Membership</x-navigation.portal-link>
                    <x-navigation.portal-link href="{{ route('portal.events.index') }}" :active="request()->routeIs('portal.events*')">Events</x-navigation.portal-link>
                    <x-navigation.portal-link href="{{ route('portal.cpd.index') }}" :active="request()->routeIs('portal.cpd*')">CPD</x-navigation.portal-link>
                    <x-navigation.portal-link href="{{ route('portal.invoices.index') }}" :active="request()->routeIs('portal.invoices*')">Invoices</x-navigation.portal-link>
                    <x-navigation.portal-link href="{{ route('portal.tickets.index') }}" :active="request()->routeIs('portal.tickets*')">Support</x-navigation.portal-link>
                </div>

                {{-- User Menu --}}
                <div class="flex items-center gap-3">
                    {{-- Notifications --}}
                    <a href="{{ route('portal.notifications') }}" class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if(auth()->user()->unreadNotifications->count())
                            <span class="absolute right-1 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-xs font-medium text-primary-700">
                                {{ auth()->user()->initials }}
                            </div>
                            <span class="hidden sm:block">{{ auth()->user()->first_name }}</span>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 z-50 mt-2 w-48 rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                            <a href="{{ route('portal.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Sign Out</button>
                            </form>
                        </div>
                    </div>
                    {{-- Mobile menu button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 md:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Nav --}}
        <div x-show="mobileMenuOpen" x-transition class="border-t border-gray-200 md:hidden">
            <div class="space-y-1 px-4 py-3">
                <x-navigation.portal-link href="{{ route('portal.dashboard') }}" :active="request()->routeIs('portal.dashboard')" class="block">Dashboard</x-navigation.portal-link>
                <x-navigation.portal-link href="{{ route('portal.membership') }}" :active="request()->routeIs('portal.membership*')" class="block">Membership</x-navigation.portal-link>
                <x-navigation.portal-link href="{{ route('portal.events.index') }}" :active="request()->routeIs('portal.events*')" class="block">Events</x-navigation.portal-link>
                <x-navigation.portal-link href="{{ route('portal.cpd.index') }}" :active="request()->routeIs('portal.cpd*')" class="block">CPD</x-navigation.portal-link>
                <x-navigation.portal-link href="{{ route('portal.invoices.index') }}" :active="request()->routeIs('portal.invoices*')" class="block">Invoices</x-navigation.portal-link>
                <x-navigation.portal-link href="{{ route('portal.tickets.index') }}" :active="request()->routeIs('portal.tickets*')" class="block">Support</x-navigation.portal-link>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{-- Flash Messages --}}
        @if(session('success'))
            <x-ui.alert type="success" class="mb-4">{{ session('success') }}</x-ui.alert>
        @endif
        @if(session('error'))
            <x-ui.alert type="error" class="mb-4">{{ session('error') }}</x-ui.alert>
        @endif
        @if(session('warning'))
            <x-ui.alert type="warning" class="mb-4">{{ session('warning') }}</x-ui.alert>
        @endif

        {{ $slot }}
    </main>

    @stack('modals')
    @stack('scripts')
</body>
</html>
