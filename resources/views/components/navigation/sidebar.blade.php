{{-- Desktop sidebar --}}
<aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-gray-200 bg-white lg:flex">
    <div class="flex h-16 items-center gap-3 border-b border-gray-200 px-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-700 text-sm font-bold text-white">PSK</div>
        <span class="text-lg font-semibold text-gray-900">Admin</span>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <x-navigation.sidebar-nav />
    </nav>
</aside>

{{-- Mobile sidebar --}}
<aside x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
       x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in-out duration-300 transform"
       x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
       class="fixed inset-y-0 left-0 z-40 w-64 flex-col border-r border-gray-200 bg-white lg:hidden" style="display: none;">
    <div class="flex h-16 items-center justify-between border-b border-gray-200 px-5">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-700 text-sm font-bold text-white">PSK</div>
            <span class="text-lg font-semibold text-gray-900">Admin</span>
        </div>
        <button @click="sidebarOpen = false" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <x-navigation.sidebar-nav />
    </nav>
</aside>
