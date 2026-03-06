{{-- Desktop sidebar --}}
<aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-gray-200 bg-white lg:flex">
    <div class="flex h-16 items-center gap-3 border-b border-gray-200 px-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-700 text-sm font-bold text-white">PSK</div>
        <span class="text-lg font-semibold text-gray-900">Admin</span>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <x-navigation.sidebar-group label="Main">
            <x-navigation.sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="home">Dashboard</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Membership">
            <x-navigation.sidebar-link href="{{ route('admin.members.index') }}" :active="request()->routeIs('admin.members.*')" icon="users">Members</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.applications.index') }}" :active="request()->routeIs('admin.applications.*')" icon="clipboard">Applications</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.tiers.index') }}" :active="request()->routeIs('admin.tiers.*')" icon="layers">Tiers</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Finance">
            <x-navigation.sidebar-link href="{{ route('admin.invoices.index') }}" :active="request()->routeIs('admin.invoices.*')" icon="file-text">Invoices</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.payments.index') }}" :active="request()->routeIs('admin.payments.*')" icon="credit-card">Payments</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.cost-centers.index') }}" :active="request()->routeIs('admin.cost-centers.*')" icon="folder">Cost Centers</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.budgets.index') }}" :active="request()->routeIs('admin.budgets.*')" icon="bar-chart">Budgets</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Events">
            <x-navigation.sidebar-link href="{{ route('admin.events.index') }}" :active="request()->routeIs('admin.events.*')" icon="calendar">Events</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="CPD">
            <x-navigation.sidebar-link href="{{ route('admin.cpd.activities.index') }}" :active="request()->routeIs('admin.cpd.*')" icon="award">CPD Activities</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Organization">
            <x-navigation.sidebar-link href="{{ route('admin.branches.index') }}" :active="request()->routeIs('admin.branches.*')" icon="map-pin">Branches</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.committees.index') }}" :active="request()->routeIs('admin.committees.*')" icon="briefcase">Committees</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Communication">
            <x-navigation.sidebar-link href="{{ route('admin.communications.index') }}" :active="request()->routeIs('admin.communications.*')" icon="mail">Messages</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.email-templates.index') }}" :active="request()->routeIs('admin.email-templates.*')" icon="layout">Templates</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Support">
            <x-navigation.sidebar-link href="{{ route('admin.tickets.index') }}" :active="request()->routeIs('admin.tickets.*')" icon="help-circle">Tickets</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Community">
            <x-navigation.sidebar-link href="{{ route('admin.posts.index') }}" :active="request()->routeIs('admin.posts.*')" icon="message-square">Posts</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        @can('manage settings')
        <x-navigation.sidebar-group label="System">
            <x-navigation.sidebar-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')" icon="settings">Settings</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>
        @endcan
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
        {{-- Same nav links as desktop - duplicated for mobile --}}
        <x-navigation.sidebar-group label="Main">
            <x-navigation.sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="home">Dashboard</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Membership">
            <x-navigation.sidebar-link href="{{ route('admin.members.index') }}" :active="request()->routeIs('admin.members.*')" icon="users">Members</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.applications.index') }}" :active="request()->routeIs('admin.applications.*')" icon="clipboard">Applications</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.tiers.index') }}" :active="request()->routeIs('admin.tiers.*')" icon="layers">Tiers</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Finance">
            <x-navigation.sidebar-link href="{{ route('admin.invoices.index') }}" :active="request()->routeIs('admin.invoices.*')" icon="file-text">Invoices</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.payments.index') }}" :active="request()->routeIs('admin.payments.*')" icon="credit-card">Payments</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.cost-centers.index') }}" :active="request()->routeIs('admin.cost-centers.*')" icon="folder">Cost Centers</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.budgets.index') }}" :active="request()->routeIs('admin.budgets.*')" icon="bar-chart">Budgets</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Events">
            <x-navigation.sidebar-link href="{{ route('admin.events.index') }}" :active="request()->routeIs('admin.events.*')" icon="calendar">Events</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="CPD">
            <x-navigation.sidebar-link href="{{ route('admin.cpd.activities.index') }}" :active="request()->routeIs('admin.cpd.*')" icon="award">CPD Activities</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Organization">
            <x-navigation.sidebar-link href="{{ route('admin.branches.index') }}" :active="request()->routeIs('admin.branches.*')" icon="map-pin">Branches</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.committees.index') }}" :active="request()->routeIs('admin.committees.*')" icon="briefcase">Committees</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Communication">
            <x-navigation.sidebar-link href="{{ route('admin.communications.index') }}" :active="request()->routeIs('admin.communications.*')" icon="mail">Messages</x-navigation.sidebar-link>
            <x-navigation.sidebar-link href="{{ route('admin.email-templates.index') }}" :active="request()->routeIs('admin.email-templates.*')" icon="layout">Templates</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Support">
            <x-navigation.sidebar-link href="{{ route('admin.tickets.index') }}" :active="request()->routeIs('admin.tickets.*')" icon="help-circle">Tickets</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>

        <x-navigation.sidebar-group label="Community">
            <x-navigation.sidebar-link href="{{ route('admin.posts.index') }}" :active="request()->routeIs('admin.posts.*')" icon="message-square">Posts</x-navigation.sidebar-link>
        </x-navigation.sidebar-group>
    </nav>
</aside>
