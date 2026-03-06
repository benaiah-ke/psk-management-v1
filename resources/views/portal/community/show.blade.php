<x-layouts.portal title="{{ $post->title }}">
    {{-- Back Link --}}
    <div class="mb-6">
        <x-ui.button href="{{ route('portal.community.index') }}" variant="ghost" size="sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Community
        </x-ui.button>
    </div>

    <div class="mx-auto max-w-3xl">
        {{-- Post Content --}}
        <x-ui.card class="mb-6">
            {{-- Post Header --}}
            <div class="mb-4 flex flex-wrap items-center gap-2">
                @php
                    $typeColors = ['announcement' => 'red', 'discussion' => 'blue', 'news' => 'indigo'];
                    $tyColor = $typeColors[$post->type->value ?? $post->type] ?? 'gray';
                @endphp
                <x-ui.badge :color="$tyColor">{{ ucfirst($post->type->value ?? $post->type) }}</x-ui.badge>
                @if($post->is_pinned)
                    <x-ui.badge color="yellow">Pinned</x-ui.badge>
                @endif
                @if($post->branch)
                    <x-ui.badge color="gray">{{ $post->branch->name }}</x-ui.badge>
                @endif
                @if($post->committee)
                    <x-ui.badge color="gray">{{ $post->committee->name }}</x-ui.badge>
                @endif
            </div>

            <h1 class="text-2xl font-bold text-gray-900">{{ $post->title }}</h1>

            {{-- Author Info --}}
            <div class="mt-4 flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-700">
                    {{ strtoupper(substr($post->author->first_name ?? '', 0, 1) . substr($post->author->last_name ?? '', 0, 1)) }}
                </div>
                <div>
                    <p class="font-medium text-gray-900">{{ $post->author->first_name ?? '' }} {{ $post->author->last_name ?? '' }}</p>
                    <p class="text-sm text-gray-500">{{ $post->published_at?->format('d M Y, H:i') ?? $post->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            {{-- Post Body --}}
            <div class="mt-6 prose prose-sm max-w-none text-gray-700">
                {!! nl2br(e($post->body)) !!}
            </div>
        </x-ui.card>

        {{-- Comments Section --}}
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">
                Comments ({{ $post->comments->count() }})
            </h2>
        </div>

        {{-- Comment Form --}}
        <x-ui.card class="mb-6">
            <form method="POST" action="{{ route('portal.community.comment', $post) }}">
                @csrf
                <x-form.textarea name="body" label="Leave a Comment" rows="3" required
                                 placeholder="Share your thoughts..." />
                <div class="mt-3 flex justify-end">
                    <x-ui.button type="submit" size="sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Post Comment
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>

        {{-- Comments Thread --}}
        @if($post->comments->count())
            <div class="space-y-4">
                @foreach($post->comments->whereNull('parent_id') as $comment)
                    {{-- Top-level Comment --}}
                    <x-ui.card>
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-medium text-gray-700">
                                {{ strtoupper(substr($comment->user->first_name ?? '', 0, 1) . substr($comment->user->last_name ?? '', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $comment->user->first_name ?? '' }} {{ $comment->user->last_name ?? '' }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="mt-1 text-sm text-gray-700">
                                    {!! nl2br(e($comment->body)) !!}
                                </div>
                            </div>
                        </div>

                        {{-- Threaded Replies --}}
                        @if($post->comments->where('parent_id', $comment->id)->count())
                            <div class="ml-11 mt-4 space-y-4 border-l-2 border-gray-100 pl-4">
                                @foreach($post->comments->where('parent_id', $comment->id) as $reply)
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-medium text-gray-600">
                                            {{ strtoupper(substr($reply->user->first_name ?? '', 0, 1) . substr($reply->user->last_name ?? '', 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ $reply->user->first_name ?? '' }} {{ $reply->user->last_name ?? '' }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="mt-1 text-sm text-gray-700">
                                                {!! nl2br(e($reply->body)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Reply Form (toggle) --}}
                        <div class="ml-11 mt-3" x-data="{ showReply: false }">
                            <button @click="showReply = !showReply" type="button"
                                    class="text-xs font-medium text-primary-600 hover:text-primary-700 transition">
                                Reply
                            </button>
                            <div x-show="showReply" x-cloak class="mt-2">
                                <form method="POST" action="{{ route('portal.community.comment', $post) }}">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <x-form.textarea name="body" rows="2" required
                                                     placeholder="Write a reply..." />
                                    <div class="mt-2 flex justify-end gap-2">
                                        <button @click="showReply = false" type="button"
                                                class="rounded-md px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                                            Cancel
                                        </button>
                                        <x-ui.button type="submit" size="xs">Reply</x-ui.button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        @else
            <x-ui.empty-state title="No comments yet" description="Be the first to share your thoughts on this post." />
        @endif
    </div>
</x-layouts.portal>
