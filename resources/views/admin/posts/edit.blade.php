<x-layouts.admin title="Edit Post">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Posts', 'url' => route('admin.posts.index')],
        ['label' => Str::limit($post->title, 30)],
        ['label' => 'Edit'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Post</h1>
        <p class="text-sm text-gray-500">Update "{{ $post->title }}"</p>
    </div>

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="mx-auto max-w-3xl">
        @csrf
        @method('PUT')

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Post Content</h3>

            <div class="space-y-4">
                <x-form.input name="title" label="Title" required placeholder="Enter post title"
                              :value="$post->title" />

                <x-form.textarea name="body" label="Body" required rows="10"
                                 placeholder="Write your post content here..."
                                 :value="$post->body" />
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Post Settings</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.select name="type" label="Type" required :options="[
                    'announcement' => 'Announcement',
                    'discussion' => 'Discussion',
                    'news' => 'News',
                ]" :selected="$post->type->value ?? $post->type" placeholder="Select type" />

                <x-form.select name="status" label="Status" required :options="[
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ]" :selected="$post->status->value ?? $post->status" placeholder="Select status" />

                <x-form.select name="branch_id" label="Branch (optional)"
                               :options="$branches->pluck('name', 'id')->toArray()"
                               :selected="$post->branch_id"
                               placeholder="All branches" />

                <x-form.select name="committee_id" label="Committee (optional)"
                               :options="$committees->pluck('name', 'id')->toArray()"
                               :selected="$post->committee_id"
                               placeholder="All committees" />
            </div>

            <div class="mt-4">
                <x-form.checkbox name="is_pinned" label="Pin this post to the top of the feed"
                                 :checked="$post->is_pinned" />
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.posts.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Update Post
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
