<header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 lg:px-6">
    {{-- Mobile menu button --}}
    <button @click="sidebarOpen = true" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    {{-- Page title (optional) --}}
    <div class="hidden lg:block">
        @isset($pageTitle)
            <h1 class="text-lg font-semibold text-gray-900">{{ $pageTitle }}</h1>
        @endisset
    </div>

    {{-- Right side --}}
    <div class="ml-auto flex items-center gap-3">
        {{-- Notifications --}}
        <a href="{{ route('admin.notifications') }}" class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            @if(auth()->user()->unreadNotifications->count())
                <span class="absolute right-1 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </a>

        {{-- User dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-xs font-medium text-primary-700">
                    {{ auth()->user()->initials }}
                </div>
                <span class="hidden sm:block">{{ auth()->user()->full_name }}</span>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition
                 class="absolute right-0 z-50 mt-2 w-48 rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                <div class="border-b border-gray-100 px-4 py-2">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Sign Out</button>
                </form>
            </div>
        </div>
    </div>
</header>
