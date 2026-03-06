<x-layouts.admin title="Create Event">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create Event</h1>
        <p class="text-sm text-gray-500">Set up a new event with tickets and sessions</p>
    </div>

    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data"
          x-data="{
              ticketTypes: [{ name: '', price: '', quantity: '' }],
              sessions: [{ title: '', start_time: '', end_time: '', cpd_points: '', speaker: '', venue: '' }],
              addTicketType() {
                  this.ticketTypes.push({ name: '', price: '', quantity: '' });
              },
              removeTicketType(index) {
                  if (this.ticketTypes.length > 1) this.ticketTypes.splice(index, 1);
              },
              addSession() {
                  this.sessions.push({ title: '', start_time: '', end_time: '', cpd_points: '', speaker: '', venue: '' });
              },
              removeSession(index) {
                  if (this.sessions.length > 1) this.sessions.splice(index, 1);
              }
          }">
        @csrf

        <div class="space-y-6">
            {{-- Basic Information --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Basic Information</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-form.input name="title" label="Event Title" required placeholder="Enter event title" />
                    </div>
                    <x-form.select name="type" label="Event Type" required
                                   :options="collect(\App\Enums\EventType::cases())->mapWithKeys(fn($t) => [$t->value => $t->label()])->toArray()"
                                   placeholder="Select type" />
                    <x-form.input name="max_attendees" label="Max Attendees" type="number" placeholder="Leave blank for unlimited" />
                    <div class="sm:col-span-2">
                        <x-form.textarea name="description" label="Description" rows="5" placeholder="Describe the event..." />
                    </div>
                    <div class="sm:col-span-2">
                        <x-form.file-upload name="featured_image" label="Featured Image" accept="image/*" hint="Recommended size: 1200x630px. Max 2MB." />
                    </div>
                </div>
            </x-ui.card>

            {{-- Venue & Schedule --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Venue & Schedule</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-form.input name="venue_name" label="Venue Name" placeholder="e.g. KICC, Nairobi" />
                    <x-form.input name="venue_address" label="Venue Address" placeholder="Full address" />
                    <div class="sm:col-span-2">
                        <x-form.checkbox name="is_virtual" label="This is a virtual/online event" />
                    </div>
                    <x-form.date-picker name="start_date" label="Start Date & Time" required :enableTime="true" />
                    <x-form.date-picker name="end_date" label="End Date & Time" :enableTime="true" />
                    <x-form.date-picker name="registration_opens" label="Registration Opens" :enableTime="true" />
                    <x-form.date-picker name="registration_closes" label="Registration Closes" :enableTime="true" />
                </div>
            </x-ui.card>

            {{-- Additional Settings --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Additional Settings</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-form.input name="cpd_points" label="CPD Points" type="number" placeholder="0" />
                    <x-form.select name="cost_center_id" label="Cost Center"
                                   :options="($costCenters ?? collect())->pluck('name', 'id')->toArray()"
                                   placeholder="Select cost center" />
                </div>
            </x-ui.card>

            {{-- Ticket Types --}}
            <x-ui.card>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Ticket Types</h3>
                    <x-ui.button type="button" variant="secondary" size="sm" @click="addTicketType()">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Add Ticket
                    </x-ui.button>
                </div>
                <div class="space-y-4">
                    <template x-for="(ticket, index) in ticketTypes" :key="index">
                        <div class="rounded-lg border border-gray-200 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700" x-text="'Ticket #' + (index + 1)"></span>
                                <button type="button" @click="removeTicketType(index)" x-show="ticketTypes.length > 1"
                                        class="text-sm text-red-600 hover:text-red-800">Remove</button>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                    <input type="text" :name="'ticket_types[' + index + '][name]'" x-model="ticket.name"
                                           placeholder="e.g. Early Bird, Standard"
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Price (KES)</label>
                                    <input type="number" :name="'ticket_types[' + index + '][price]'" x-model="ticket.price"
                                           placeholder="0 for free" step="0.01" min="0"
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" :name="'ticket_types[' + index + '][quantity]'" x-model="ticket.quantity"
                                           placeholder="Unlimited" min="1"
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </x-ui.card>

            {{-- Sessions --}}
            <x-ui.card>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Sessions</h3>
                    <x-ui.button type="button" variant="secondary" size="sm" @click="addSession()">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Add Session
                    </x-ui.button>
                </div>
                <div class="space-y-4">
                    <template x-for="(session, index) in sessions" :key="index">
                        <div class="rounded-lg border border-gray-200 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700" x-text="'Session #' + (index + 1)"></span>
                                <button type="button" @click="removeSession(index)" x-show="sessions.length > 1"
                                        class="text-sm text-red-600 hover:text-red-800">Remove</button>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                                    <input type="text" :name="'sessions[' + index + '][title]'" x-model="session.title"
                                           placeholder="Session title"
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Start Time</label>
                                    <input type="text" :name="'sessions[' + index + '][start_time]'" x-model="session.start_time"
                                           placeholder="YYYY-MM-DD HH:MM"
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                           x-init="flatpickr($el, { dateFormat: 'Y-m-d H:i', enableTime: true })">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">End Time</label>
                                    <input type="text" :name="'sessions[' + index + '][end_time]'" x-model="session.end_time"
                                           placeholder="YYYY-MM-DD HH:MM"
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                           x-init="flatpickr($el, { dateFormat: 'Y-m-d H:i', enableTime: true })">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Speaker</label>
                                    <input type="text" :name="'sessions[' + index + '][speaker]'" x-model="session.speaker"
                                           placeholder="Speaker name"
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Room / Venue</label>
                                    <input type="text" :name="'sessions[' + index + '][venue]'" x-model="session.venue"
                                           placeholder="Room or venue name"
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">CPD Points</label>
                                    <input type="number" :name="'sessions[' + index + '][cpd_points]'" x-model="session.cpd_points"
                                           placeholder="0" min="0"
                                           class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </x-ui.card>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3">
                <x-ui.button href="{{ route('admin.events.index') }}" variant="secondary">Cancel</x-ui.button>
                <x-ui.button type="submit">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Create Event
                </x-ui.button>
            </div>
        </div>
    </form>
</x-layouts.admin>
