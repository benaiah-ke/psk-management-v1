<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('psk.organization.name') }} — Membership management, events, CPD tracking, and more.">
    <meta name="theme-color" content="#1d4ed8">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="Association management platform for the {{ config('psk.organization.name') }}.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <title>{{ $title ?? 'Welcome' }} - {{ config('app.name') }}</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.svg">
    <link rel="manifest" href="/site.webmanifest">
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
