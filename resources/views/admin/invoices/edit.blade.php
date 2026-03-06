<x-layouts.admin title="Edit Invoice {{ $invoice->invoice_number }}">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Invoice</h1>
        <p class="text-sm text-gray-500">Editing {{ $invoice->invoice_number }}.</p>
    </div>

    <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}"
          x-data="{
              items: {{ json_encode($invoice->items->map(fn($item) => ['description' => $item->description, 'quantity' => (float)$item->quantity, 'unit_price' => (float)$item->unit_price])->values()) }},
              addItem() {
                  this.items.push({ description: '', quantity: 1, unit_price: 0 });
              },
              removeItem(index) {
                  if (this.items.length > 1) {
                      this.items.splice(index, 1);
                  }
              },
              get subtotal() {
                  return this.items.reduce((sum, item) => sum + (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0), 0);
              },
              get tax() {
                  return this.subtotal * 0.16;
              },
              get total() {
                  return this.subtotal + this.tax;
              },
              formatNumber(num) {
                  return num.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
              }
          }">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Invoice Details --}}
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Invoice Details</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form.select name="user_id" label="Member" :options="$users->mapWithKeys(fn($u) => [$u->id => $u->first_name . ' ' . $u->last_name . ' (' . $u->email . ')'])->all()" :selected="old('user_id', $invoice->user_id)" required placeholder="Select a member..." />

                        <x-form.select name="type" label="Invoice Type" :options="collect(\App\Enums\InvoiceType::cases())->mapWithKeys(fn($t) => [$t->value => $t->label()])->all()" :selected="old('type', $invoice->type->value)" required placeholder="Select type..." />

                        <x-form.date-picker name="due_date" label="Due Date" :value="old('due_date', $invoice->due_date->format('Y-m-d'))" required />

                        <x-form.select name="cost_center_id" label="Cost Center" :options="$costCenters->mapWithKeys(fn($cc) => [$cc->id => $cc->name])->all()" :selected="old('cost_center_id', $invoice->cost_center_id)" placeholder="Select cost center (optional)..." />

                        <x-form.select name="status" label="Status" :options="collect(\App\Enums\InvoiceStatus::cases())->mapWithKeys(fn($s) => [$s->value => $s->label()])->all()" :selected="old('status', $invoice->status->value)" required placeholder="Select status..." />
                    </div>
                </x-ui.card>

                {{-- Line Items --}}
                <x-ui.card>
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Line Items</h3>
                        <x-ui.button type="button" variant="secondary" size="sm" @click="addItem()">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Add Item
                        </x-ui.button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-12">
                                    <div class="sm:col-span-6">
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                                        <input type="text" x-model="item.description" :name="'items[' + index + '][description]'"
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                               placeholder="Item description" required>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Quantity <span class="text-red-500">*</span></label>
                                        <input type="number" x-model.number="item.quantity" :name="'items[' + index + '][quantity]'"
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                               min="1" step="1" required>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Unit Price (KES) <span class="text-red-500">*</span></label>
                                        <input type="number" x-model.number="item.unit_price" :name="'items[' + index + '][unit_price]'"
                                               class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                               min="0" step="0.01" required>
                                    </div>
                                    <div class="flex items-end sm:col-span-1">
                                        <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                class="rounded-lg p-2.5 text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2 text-right text-sm text-gray-500">
                                    Line Total: <span class="font-medium text-gray-900" x-text="'KES ' + formatNumber((parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0))"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </x-ui.card>

                {{-- Notes --}}
                <x-ui.card>
                    <x-form.textarea name="notes" label="Notes" placeholder="Additional notes for this invoice..." :rows="3">{{ old('notes', $invoice->notes) }}</x-form.textarea>
                </x-ui.card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Totals --}}
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Invoice Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium text-gray-900" x-text="'KES ' + formatNumber(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Tax (16%)</span>
                            <span class="font-medium text-gray-900" x-text="'KES ' + formatNumber(tax)"></span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-gray-900" x-text="'KES ' + formatNumber(total)"></span>
                        </div>
                    </div>
                </x-ui.card>

                {{-- Actions --}}
                <x-ui.card>
                    <div class="space-y-3">
                        <x-ui.button type="submit" class="w-full">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Update Invoice
                        </x-ui.button>
                        <x-ui.button href="{{ route('admin.invoices.show', $invoice) }}" variant="secondary" class="w-full">Cancel</x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </form>
</x-layouts.admin>
