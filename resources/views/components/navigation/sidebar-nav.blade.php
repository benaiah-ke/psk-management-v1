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
