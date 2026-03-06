<x-layouts.admin title="Record Payment">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Record Payment</h1>
        <p class="text-sm text-gray-500">Record a payment against an invoice.</p>
    </div>

    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('admin.payments.store') }}"
              x-data="{
                  invoiceId: '{{ old('invoice_id', request('invoice_id', '')) }}',
                  invoices: {{ $invoices->map(fn($inv) => ['id' => $inv->id, 'number' => $inv->invoice_number, 'member' => $inv->user->first_name . ' ' . $inv->user->last_name, 'balance' => (float)$inv->balance_due])->values()->toJson() }},
                  get selectedInvoice() {
                      return this.invoices.find(i => i.id == this.invoiceId) || null;
                  },
                  formatNumber(num) {
                      return num.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                  }
              }">
            @csrf

            <x-ui.card class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Payment Details</h3>

                <div class="space-y-4">
                    {{-- Invoice Select --}}
                    <div>
                        <label for="invoice_id" class="mb-1 block text-sm font-medium text-gray-700">Invoice <span class="text-red-500">*</span></label>
                        <select name="invoice_id" id="invoice_id" x-model="invoiceId" required
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                            <option value="">Select an invoice...</option>
                            @foreach($invoices as $inv)
                                <option value="{{ $inv->id }}" @selected(old('invoice_id', request('invoice_id')) == $inv->id)>
                                    {{ $inv->invoice_number }} - {{ $inv->user->first_name }} {{ $inv->user->last_name }} (Balance: KES {{ number_format($inv->balance_due, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Balance Info --}}
                    <div x-show="selectedInvoice" x-transition class="rounded-lg border border-blue-200 bg-blue-50 p-3">
                        <div class="flex items-center gap-2 text-sm text-blue-800">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Outstanding balance: <strong x-text="selectedInvoice ? 'KES ' + formatNumber(selectedInvoice.balance) : ''"></strong></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form.input name="amount" label="Amount (KES)" type="number" required :value="old('amount')" min="0.01" step="0.01" />

                        <x-form.select name="payment_method" label="Payment Method" :options="collect(\App\Enums\PaymentMethod::cases())->mapWithKeys(fn($m) => [$m->value => $m->label()])->all()" :selected="old('payment_method')" required placeholder="Select method..." />

                        <x-form.input name="payment_reference" label="Payment Reference" placeholder="e.g. M-Pesa code, cheque number..." :value="old('payment_reference')" />

                        <x-form.date-picker name="payment_date" label="Payment Date" required :value="old('payment_date', now()->format('Y-m-d'))" />
                    </div>

                    <x-form.textarea name="notes" label="Notes" placeholder="Additional notes about this payment..." :rows="3">{{ old('notes') }}</x-form.textarea>
                </div>
            </x-ui.card>

            <div class="flex items-center justify-end gap-3">
                <x-ui.button href="{{ route('admin.payments.index') }}" variant="secondary">Cancel</x-ui.button>
                <x-ui.button type="submit">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Record Payment
                </x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
