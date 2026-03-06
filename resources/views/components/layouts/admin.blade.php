<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Admin panel for {{ config('psk.organization.name') }} management platform.">
    <meta name="theme-color" content="#1d4ed8">
    <title>{{ $title ?? 'Admin' }} - {{ config('app.name') }}</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.svg">
    <link rel="manifest" href="/site.webmanifest">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <x-navigation.sidebar />

        {{-- Main Content --}}
        <div class="flex flex-1 flex-col lg:pl-64">
            {{-- Top Bar --}}
            <x-navigation.topbar />

            {{-- Page Content --}}
            <main class="flex-1 p-4 lg:p-6">
                {{-- Breadcrumbs --}}
                @isset($breadcrumbs)
                    <x-navigation.breadcrumbs :items="$breadcrumbs" />
                @endisset

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
        </div>
    </div>

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 bg-gray-600/75 lg:hidden" @click="sidebarOpen = false"></div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
