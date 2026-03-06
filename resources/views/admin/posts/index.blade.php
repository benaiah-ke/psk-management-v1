<x-layouts.admin title="Posts">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Community Posts</h1>
            <p class="text-sm text-gray-500">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} total</p>
        </div>
        <x-ui.button href="{{ route('admin.posts.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            New Post
        </x-ui.button>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <x-form.input name="search" label="Search" placeholder="Search by title or author..."
                              :value="request('search')" />
            </div>
            <div class="w-full sm:w-40">
                <x-form.select name="status" label="Status" :options="[
                    'published' => 'Published',
                    'draft' => 'Draft',
                    'archived' => 'Archived',
                ]" :selected="request('status')" placeholder="All Statuses" />
            </div>
            <div class="w-full sm:w-40">
                <x-form.select name="type" label="Type" :options="[
                    'announcement' => 'Announcement',
                    'discussion' => 'Discussion',
                    'news' => 'News',
                ]" :selected="request('type')" placeholder="All Types" />
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="secondary" size="md">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </x-ui.button>
                @if(request()->hasAny(['search', 'status', 'type']))
                    <x-ui.button href="{{ route('admin.posts.index') }}" variant="ghost" size="md">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- Posts Table --}}
    @if($posts->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="title" :direction="request('sort') === 'title' ? request('direction') : null">Title</x-table.heading>
                <x-table.heading>Author</x-table.heading>
                <x-table.heading>Type</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Comments</x-table.heading>
                <x-table.heading sortable column="published_at" :direction="request('sort') === 'published_at' ? request('direction') : null">Published</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($posts as $post)
                <tr>
                    <x-table.cell>
                        <div>
                            <p class="max-w-xs truncate font-medium text-gray-900">{{ $post->title }}</p>
                            @if($post->is_pinned)
                                <span class="inline-flex items-center gap-1 text-xs text-yellow-600">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 5a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2H7a2 2 0 01-2-2V5z"/><path d="M9 14a1 1 0 011-1h0a1 1 0 011 1v3a1 1 0 01-1 1h0a1 1 0 01-1-1v-3z"/></svg>
                                    Pinned
                                </span>
                            @endif
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        @if($post->author)
                            <span class="text-sm text-gray-900">{{ $post->author->first_name }} {{ $post->author->last_name }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        @php
                            $typeColors = ['announcement' => 'red', 'discussion' => 'blue', 'news' => 'indigo'];
                            $tyColor = $typeColors[$post->type->value ?? $post->type] ?? 'gray';
                        @endphp
                        <x-ui.badge :color="$tyColor">{{ ucfirst($post->type->value ?? $post->type) }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        @php
                            $statusColors = ['published' => 'green', 'draft' => 'gray', 'archived' => 'yellow'];
                            $stColor = $statusColors[$post->status->value ?? $post->status] ?? 'gray';
                        @endphp
                        <x-ui.badge :color="$stColor">{{ ucfirst($post->status->value ?? $post->status) }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        <span class="text-sm text-gray-900">{{ $post->comments_count ?? $post->comments->count() }}</span>
                    </x-table.cell>
                    <x-table.cell>
                        {{ $post->published_at?->format('d M Y') ?? '-' }}
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.posts.edit', $post) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-ui.button>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-md p-1.5 text-gray-400 transition hover:bg-red-50 hover:text-red-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $posts->withQueryString()->links() }}
            </x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No posts found" description="No posts match your current filters. Create your first community post.">
            <x-ui.button href="{{ route('admin.posts.create') }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                New Post
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
