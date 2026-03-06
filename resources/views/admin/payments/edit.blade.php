<x-layouts.admin title="Edit Payment">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Payment</h1>
        <p class="text-sm text-gray-500">Update payment details for {{ $payment->payment_number }}.</p>
    </div>

    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('admin.payments.update', $payment) }}">
            @csrf
            @method('PUT')

            <x-ui.card class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Payment Details</h3>

                <div class="space-y-4">
                    {{-- Invoice (read-only) --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Invoice</label>
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">
                            {{ $payment->invoice->invoice_number ?? 'N/A' }} - {{ $payment->invoice?->user?->first_name }} {{ $payment->invoice?->user?->last_name }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form.input name="amount" label="Amount (KES)" type="number" required
                                      :value="old('amount', $payment->amount)" min="0.01" step="0.01" />

                        <x-form.select name="payment_method" label="Payment Method"
                                       :options="collect(\App\Enums\PaymentMethod::cases())->mapWithKeys(fn($m) => [$m->value => $m->label()])->all()"
                                       :selected="old('payment_method', $payment->payment_method instanceof \BackedEnum ? $payment->payment_method->value : $payment->payment_method)"
                                       required placeholder="Select method..." />

                        <x-form.input name="payment_reference" label="Payment Reference"
                                      placeholder="e.g. M-Pesa code, cheque number..."
                                      :value="old('payment_reference', $payment->payment_reference)" />

                        <x-form.select name="status" label="Status"
                                       :options="['pending' => 'Pending', 'completed' => 'Completed', 'failed' => 'Failed', 'refunded' => 'Refunded']"
                                       :selected="old('status', $payment->status)"
                                       required />

                        <x-form.date-picker name="paid_at" label="Payment Date" required
                                            :value="old('paid_at', $payment->paid_at?->format('Y-m-d'))" />
                    </div>

                    <x-form.textarea name="notes" label="Notes" placeholder="Additional notes about this payment..." :rows="3">{{ old('notes', $payment->notes) }}</x-form.textarea>
                </div>
            </x-ui.card>

            <div class="flex items-center justify-end gap-3">
                <x-ui.button href="{{ route('admin.payments.index') }}" variant="secondary">Cancel</x-ui.button>
                <x-ui.button type="submit">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update Payment
                </x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.admin>
