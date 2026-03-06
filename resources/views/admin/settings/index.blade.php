<x-layouts.admin title="Settings">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-sm text-gray-500">Manage your organization's configuration and preferences.</p>
    </div>

    @if(session('success'))
        <x-ui.alert type="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif

    <div class="mx-auto max-w-3xl space-y-6">
        {{-- Organization Settings --}}
        @if(isset($settings['organization']))
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="group" value="organization">

                <x-ui.card>
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 text-primary-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Organization</h3>
                            <p class="text-sm text-gray-500">Basic organization information and branding</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($settings['organization'] as $setting)
                            <div class="{{ in_array($setting->type ?? 'text', ['textarea']) ? 'sm:col-span-2' : '' }}">
                                @if(($setting->type ?? 'text') === 'textarea')
                                    <x-form.textarea name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                     rows="3" :value="$setting->value" />
                                @elseif(($setting->type ?? 'text') === 'select' && $setting->options)
                                    <x-form.select name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                   :options="$setting->options" :selected="$setting->value" />
                                @elseif(($setting->type ?? 'text') === 'boolean')
                                    <x-form.checkbox name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                     :checked="(bool) $setting->value" />
                                @else
                                    <x-form.input name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                  :type="$setting->type ?? 'text'" :value="$setting->value" />
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex justify-end">
                        <x-ui.button type="submit" size="sm">Save Organization Settings</x-ui.button>
                    </div>
                </x-ui.card>
            </form>
        @endif

        {{-- Finance Settings --}}
        @if(isset($settings['finance']))
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="group" value="finance">

                <x-ui.card>
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Finance</h3>
                            <p class="text-sm text-gray-500">Payment and invoicing configuration</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($settings['finance'] as $setting)
                            <div class="{{ in_array($setting->type ?? 'text', ['textarea']) ? 'sm:col-span-2' : '' }}">
                                @if(($setting->type ?? 'text') === 'textarea')
                                    <x-form.textarea name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                     rows="3" :value="$setting->value" />
                                @elseif(($setting->type ?? 'text') === 'select' && $setting->options)
                                    <x-form.select name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                   :options="$setting->options" :selected="$setting->value" />
                                @elseif(($setting->type ?? 'text') === 'boolean')
                                    <x-form.checkbox name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                     :checked="(bool) $setting->value" />
                                @else
                                    <x-form.input name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                  :type="$setting->type ?? 'text'" :value="$setting->value" />
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex justify-end">
                        <x-ui.button type="submit" size="sm">Save Finance Settings</x-ui.button>
                    </div>
                </x-ui.card>
            </form>
        @endif

        {{-- Membership Settings --}}
        @if(isset($settings['membership']))
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="group" value="membership">

                <x-ui.card>
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Membership</h3>
                            <p class="text-sm text-gray-500">Membership rules and application settings</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($settings['membership'] as $setting)
                            <div class="{{ in_array($setting->type ?? 'text', ['textarea']) ? 'sm:col-span-2' : '' }}">
                                @if(($setting->type ?? 'text') === 'textarea')
                                    <x-form.textarea name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                     rows="3" :value="$setting->value" />
                                @elseif(($setting->type ?? 'text') === 'select' && $setting->options)
                                    <x-form.select name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                   :options="$setting->options" :selected="$setting->value" />
                                @elseif(($setting->type ?? 'text') === 'boolean')
                                    <x-form.checkbox name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                     :checked="(bool) $setting->value" />
                                @else
                                    <x-form.input name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                  :type="$setting->type ?? 'text'" :value="$setting->value" />
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex justify-end">
                        <x-ui.button type="submit" size="sm">Save Membership Settings</x-ui.button>
                    </div>
                </x-ui.card>
            </form>
        @endif

        {{-- CPD Settings --}}
        @if(isset($settings['cpd']))
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="group" value="cpd">

                <x-ui.card>
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">CPD (Continuing Professional Development)</h3>
                            <p class="text-sm text-gray-500">CPD requirements and point configurations</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($settings['cpd'] as $setting)
                            <div class="{{ in_array($setting->type ?? 'text', ['textarea']) ? 'sm:col-span-2' : '' }}">
                                @if(($setting->type ?? 'text') === 'textarea')
                                    <x-form.textarea name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                     rows="3" :value="$setting->value" />
                                @elseif(($setting->type ?? 'text') === 'select' && $setting->options)
                                    <x-form.select name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                   :options="$setting->options" :selected="$setting->value" />
                                @elseif(($setting->type ?? 'text') === 'boolean')
                                    <x-form.checkbox name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                     :checked="(bool) $setting->value" />
                                @else
                                    <x-form.input name="settings[{{ $setting->key }}]" :label="$setting->label ?? Str::title(str_replace('_', ' ', $setting->key))"
                                                  :type="$setting->type ?? 'text'" :value="$setting->value" />
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex justify-end">
                        <x-ui.button type="submit" size="sm">Save CPD Settings</x-ui.button>
                    </div>
                </x-ui.card>
            </form>
        @endif
    </div>
</x-layouts.admin>
