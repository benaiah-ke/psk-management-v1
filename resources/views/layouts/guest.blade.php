<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Welcome' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        {{-- Logo --}}
        <div class="mb-8 text-center">
            <a href="/" class="inline-flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-700 text-lg font-bold text-white">PSK</div>
                <span class="text-xl font-semibold text-gray-900">{{ config('app.name') }}</span>
            </a>
        </div>

        {{-- Card --}}
        <div class="w-full max-w-md">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
