<x-layouts.admin title="{{ $branch->name }}">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Branches', 'url' => route('admin.branches.index')],
        ['label' => $branch->name],
    ]" />

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">{{ $branch->name }}</h1>
                @if($branch->is_active)
                    <x-ui.badge color="green">Active</x-ui.badge>
                @else
                    <x-ui.badge color="gray">Inactive</x-ui.badge>
                @endif
            </div>
            <p class="text-sm text-gray-500">
                {{ $branch->county ?? '' }}{{ $branch->county && $branch->region ? ', ' : '' }}{{ $branch->region ?? '' }}
            </p>
        </div>
        <x-ui.button href="{{ route('admin.branches.edit', $branch) }}" variant="secondary">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Branch
        </x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Branch Info Sidebar --}}
        <div class="space-y-6">
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Branch Details</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">County</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $branch->county ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Region</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $branch->region ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Cost Center</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $branch->costCenter->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Total Members</dt>
                        <dd class="mt-0.5 text-sm font-medium text-gray-900">{{ $branch->members->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Created</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $branch->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>

                @if($branch->description)
                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <dt class="text-xs font-medium uppercase text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $branch->description }}</dd>
                    </div>
                @endif
            </x-ui.card>
        </div>

        {{-- Main Content --}}
        <div class="space-y-6 lg:col-span-2" x-data="{ activeTab: 'members' }">
            {{-- Tab Navigation --}}
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex gap-6">
                    <button @click="activeTab = 'members'"
                            :class="activeTab === 'members' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        Members
                        <span class="ml-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $branch->members->count() }}</span>
                    </button>
                    <button @click="activeTab = 'posts'"
                            :class="activeTab === 'posts' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="whitespace-nowrap border-b-2 px-1 py-3 text-sm font-medium transition">
                        Posts
                        @if($branch->posts->count())
                            <span class="ml-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $branch->posts->count() }}</span>
                        @endif
                    </button>
                </nav>
            </div>

            {{-- Members Tab --}}
            <div x-show="activeTab === 'members'" x-cloak>
                @if($branch->members->count())
                    <x-table.table>
                        <x-slot:head>
                            <x-table.heading>Name</x-table.heading>
                            <x-table.heading>Email</x-table.heading>
                            <x-table.heading>Role</x-table.heading>
                            <x-table.heading>Joined</x-table.heading>
                        </x-slot:head>
                        @foreach($branch->members as $member)
                            <tr>
                                <x-table.cell>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-100 text-xs font-medium text-primary-700">
                                            {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                        </div>
                                        <a href="{{ route('admin.members.show', $member) }}" class="font-medium text-primary-600 hover:text-primary-700">
                                            {{ $member->first_name }} {{ $member->last_name }}
                                        </a>
                                    </div>
                                </x-table.cell>
                                <x-table.cell>{{ $member->email }}</x-table.cell>
                                <x-table.cell>
                                    <x-ui.badge color="indigo">{{ ucfirst($member->pivot->role ?? 'Member') }}</x-ui.badge>
                                </x-table.cell>
                                <x-table.cell>{{ $member->pivot->created_at ? \Carbon\Carbon::parse($member->pivot->created_at)->format('d M Y') : '-' }}</x-table.cell>
                            </tr>
                        @endforeach
                    </x-table.table>
                @else
                    <x-ui.empty-state title="No members" description="This branch has no members yet." />
                @endif
            </div>

            {{-- Posts Tab --}}
            <div x-show="activeTab === 'posts'" x-cloak>
                @if($branch->posts->count())
                    <div class="space-y-4">
                        @foreach($branch->posts as $post)
                            <x-ui.card>
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $post->title }}</h4>
                                        <p class="mt-1 text-sm text-gray-500">
                                            By {{ $post->author->first_name ?? '' }} {{ $post->author->last_name ?? '' }}
                                            &middot; {{ $post->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    @php
                                        $postStatusColors = ['published' => 'green', 'draft' => 'gray', 'archived' => 'yellow'];
                                        $psColor = $postStatusColors[$post->status->value ?? $post->status] ?? 'gray';
                                    @endphp
                                    <x-ui.badge :color="$psColor">{{ ucfirst($post->status->value ?? $post->status) }}</x-ui.badge>
                                </div>
                            </x-ui.card>
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state title="No posts" description="No posts have been associated with this branch." />
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
