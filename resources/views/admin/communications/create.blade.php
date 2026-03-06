<x-layouts.admin title="Compose Message">
    {{-- Breadcrumbs --}}
    <x-navigation.breadcrumbs :items="[
        ['label' => 'Communications', 'url' => route('admin.communications.index')],
        ['label' => 'Compose Message'],
    ]" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Compose Message</h1>
        <p class="text-sm text-gray-500">Send a message to your members</p>
    </div>

    <form method="POST" action="{{ route('admin.communications.store') }}" class="mx-auto max-w-4xl"
          x-data="{
              type: '{{ old('type', 'email') }}',
              recipientFilter: '{{ old('recipient_filter', 'all') }}',
              selectedTemplate: '{{ old('template_id', '') }}'
          }">
        @csrf

        {{-- Message Type & Template --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Message Settings</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-form.select name="type" label="Type" required
                               :options="['email' => 'Email']"
                               :selected="old('type', 'email')"
                               x-model="type" />
                <x-form.select name="template_id" label="Template (Optional)"
                               :options="$templates->pluck('name', 'id')->toArray()"
                               :selected="old('template_id')"
                               placeholder="No template - write from scratch"
                               x-model="selectedTemplate" />
            </div>
        </x-ui.card>

        {{-- Recipients --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Recipients</h3>
            <div class="space-y-4">
                <x-form.select name="recipient_filter" label="Send To" required
                               :options="[
                                   'all' => 'All Members',
                                   'tier' => 'By Membership Tier',
                                   'branch' => 'By Branch',
                                   'status' => 'By Status',
                               ]"
                               :selected="old('recipient_filter', 'all')"
                               x-model="recipientFilter"
                               placeholder="" />

                {{-- Tier Selection --}}
                <div x-show="recipientFilter === 'tier'" x-cloak>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Select Tiers</label>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($tiers as $tier)
                            <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 hover:bg-gray-50">
                                <input type="checkbox" name="tier_ids[]" value="{{ $tier->id }}"
                                       class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                       @checked(is_array(old('tier_ids')) && in_array($tier->id, old('tier_ids')))>
                                <span class="text-sm text-gray-700">{{ $tier->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('tier_ids')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Branch Selection --}}
                <div x-show="recipientFilter === 'branch'" x-cloak>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Select Branches</label>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($branches as $branch)
                            <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 hover:bg-gray-50">
                                <input type="checkbox" name="branch_ids[]" value="{{ $branch->id }}"
                                       class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                       @checked(is_array(old('branch_ids')) && in_array($branch->id, old('branch_ids')))>
                                <span class="text-sm text-gray-700">{{ $branch->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('branch_ids')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Selection --}}
                <div x-show="recipientFilter === 'status'" x-cloak>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Select Statuses</label>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach(['active' => 'Active', 'expired' => 'Expired', 'suspended' => 'Suspended', 'pending' => 'Pending'] as $value => $label)
                            <label class="flex items-center gap-2 rounded-lg border border-gray-200 p-3 hover:bg-gray-50">
                                <input type="checkbox" name="status_ids[]" value="{{ $value }}"
                                       class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                       @checked(is_array(old('status_ids')) && in_array($value, old('status_ids')))>
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('status_ids')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-ui.card>

        {{-- Message Content --}}
        <x-ui.card class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Message Content</h3>
            <div class="space-y-4">
                <x-form.input name="subject" label="Subject" required
                              placeholder="Enter message subject..." />
                <x-form.textarea name="body" label="Body" required rows="10"
                                 placeholder="Write your message here...">{{ old('body') }}</x-form.textarea>
                <x-ui.alert type="info" :dismissible="false">
                    You can use merge fields in your message: <code class="rounded bg-blue-100 px-1 py-0.5 text-xs font-mono">@{{ member_name }}</code>, <code class="rounded bg-blue-100 px-1 py-0.5 text-xs font-mono">@{{ member_email }}</code>, <code class="rounded bg-blue-100 px-1 py-0.5 text-xs font-mono">@{{ membership_tier }}</code>, <code class="rounded bg-blue-100 px-1 py-0.5 text-xs font-mono">@{{ membership_number }}</code>
                </x-ui.alert>
            </div>
        </x-ui.card>

        <div class="flex items-center justify-end gap-3">
            <x-ui.button href="{{ route('admin.communications.index') }}" variant="secondary">Cancel</x-ui.button>
            <x-ui.button type="submit" name="action" value="preview" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Preview
            </x-ui.button>
            <x-ui.button type="submit" name="action" value="send" onclick="return confirm('Are you sure you want to send this message? This action cannot be undone.')">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Send Message
            </x-ui.button>
        </div>
    </form>
</x-layouts.admin>
