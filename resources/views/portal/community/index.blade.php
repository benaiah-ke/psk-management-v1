<x-layouts.portal title="Community">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Community</h1>
            <p class="text-sm text-gray-500">Stay connected with announcements, discussions, and news.</p>
        </div>
    </div>

    {{-- Type Filter --}}
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('portal.community.index') }}"
           class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-medium transition {{ !request('type') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            All
        </a>
        <a href="{{ route('portal.community.index', ['type' => 'announcement']) }}"
           class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-medium transition {{ request('type') === 'announcement' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Announcements
        </a>
        <a href="{{ route('portal.community.index', ['type' => 'discussion']) }}"
           class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-medium transition {{ request('type') === 'discussion' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Discussions
        </a>
        <a href="{{ route('portal.community.index', ['type' => 'news']) }}"
           class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-medium transition {{ request('type') === 'news' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            News
        </a>
    </div>

    {{-- Posts Feed --}}
    @if($posts->count())
        <div class="space-y-4">
            @foreach($posts as $post)
                <x-ui.card class="transition hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            {{-- Badges --}}
                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                @php
                                    $typeColors = ['announcement' => 'red', 'discussion' => 'blue', 'news' => 'indigo'];
                                    $tyColor = $typeColors[$post->type->value ?? $post->type] ?? 'gray';
                                @endphp
                                <x-ui.badge :color="$tyColor">{{ ucfirst($post->type->value ?? $post->type) }}</x-ui.badge>
                                @if($post->is_pinned)
                                    <x-ui.badge color="yellow">Pinned</x-ui.badge>
                                @endif
                            </div>

                            {{-- Title --}}
                            <a href="{{ route('portal.community.show', $post) }}" class="block">
                                <h3 class="text-lg font-semibold text-gray-900 hover:text-primary-600 transition">
                                    {{ $post->title }}
                                </h3>
                            </a>

                            {{-- Excerpt --}}
                            <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                {{ Str::limit(strip_tags($post->body), 200) }}
                            </p>

                            {{-- Meta --}}
                            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary-100 text-xs font-medium text-primary-700">
                                        {{ strtoupper(substr($post->author->first_name ?? '', 0, 1) . substr($post->author->last_name ?? '', 0, 1)) }}
                                    </div>
                                    <span>{{ $post->author->first_name ?? '' }} {{ $post->author->last_name ?? '' }}</span>
                                </div>
                                <span class="flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ $post->comments_count ?? $post->comments->count() }} {{ Str::plural('comment', $post->comments_count ?? $post->comments->count()) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $posts->withQueryString()->links() }}
        </div>
    @else
        <x-ui.empty-state title="No posts yet" description="There are no community posts to display at this time. Check back later for updates." />
    @endif
</x-layouts.portal>
