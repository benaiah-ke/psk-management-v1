<x-layouts.admin title="New Post">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Posts', 'url' => route('admin.posts.index')],
        ['label' => 'New Post'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">New Post</h1>
        <p class="text-sm text-gray-500">Create a new community post</p>
    </div>

    <form method="POST" action="{{ route('admin.posts.store') }}" class="mx-auto max-w-3xl">
        @csrf

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Post Content</h3>

            <div class="space-y-4">
                <x-form.input name="title" label="Title" required placeholder="Enter post title" />

                <x-form.textarea name="body" label="Body" required rows="10"
                                 placeholder="Write your post content here..." />
            </div>
        </x-ui.card>

        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Post Settings</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.select name="type" label="Type" required :options="[
                    'announcement' => 'Announcement',
                    'discussion' => 'Discussion',
                    'news' => 'News',
                ]" placeholder="Select type" />

                <x-form.select name="status" label="Status" required :options="[
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ]" :selected="'draft'" placeholder="Select status" />

                <x-form.select name="branch_id" label="Branch (optional)"
                               :options="$branches->pluck('name', 'id')->toArray()"
                               placeholder="All branches" />

                <x-form.select name="committee_id" label="Committee (optional)"
                               :options="$committees->pluck('name', 'id')->toArray()"
                               placeholder="All committees" />
            </div>

            <div class="mt-4">
                <x-form.checkbox name="is_pinned" label="Pin this post to the top of the feed" />
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.posts.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Post
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
