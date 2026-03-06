<x-layouts.admin title="Payment {{ $payment->payment_number }}">
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $payment->payment_number }}</h1>
            <p class="text-sm text-gray-500">Payment details</p>
        </div>
        <div class="flex gap-2">
            <x-ui.button href="{{ route('admin.payments.edit', $payment) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </x-ui.button>
            <x-ui.button href="{{ route('admin.payments.index') }}" variant="ghost">Back to List</x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Payment Details --}}
        <div class="lg:col-span-2 space-y-6">
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Payment Details</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Payment Number</dt>
                        <dd class="mt-0.5 font-mono text-sm text-gray-900">{{ $payment->payment_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Amount</dt>
                        <dd class="mt-0.5 text-lg font-bold text-gray-900">KES {{ number_format($payment->amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Payment Method</dt>
                        <dd class="mt-1">
                            <x-ui.badge color="blue">{{ $payment->payment_method instanceof \BackedEnum ? $payment->payment_method->label() : ucfirst($payment->payment_method) }}</x-ui.badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <x-ui.badge :color="$payment->status === 'completed' ? 'green' : ($payment->status === 'failed' ? 'red' : 'yellow')">{{ ucfirst($payment->status) }}</x-ui.badge>
                        </dd>
                    </div>
                    @if($payment->payment_reference)
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Reference</dt>
                            <dd class="mt-0.5 font-mono text-sm text-gray-900">{{ $payment->payment_reference }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium uppercase text-gray-500">Payment Date</dt>
                        <dd class="mt-0.5 text-sm text-gray-900">{{ $payment->paid_at?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    @if($payment->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase text-gray-500">Notes</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">{{ $payment->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </x-ui.card>

            {{-- Invoice Information --}}
            @if($payment->invoice)
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Invoice Information</h3>
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Invoice Number</dt>
                            <dd class="mt-0.5 text-sm">
                                <a href="{{ route('admin.invoices.show', $payment->invoice) }}" class="font-mono text-primary-600 hover:underline">{{ $payment->invoice->invoice_number }}</a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase text-gray-500">Invoice Total</dt>
                            <dd class="mt-0.5 text-sm text-gray-900">KES {{ number_format($payment->invoice->total_amount, 2) }}</dd>
                        </div>
                        @if($payment->invoice->user)
                            <div>
                                <dt class="text-xs font-medium uppercase text-gray-500">Member</dt>
                                <dd class="mt-0.5 text-sm text-gray-900">{{ $payment->invoice->user->first_name }} {{ $payment->invoice->user->last_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium uppercase text-gray-500">Email</dt>
                                <dd class="mt-0.5 text-sm text-gray-900">{{ $payment->invoice->user->email }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-ui.card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            @if($payment->receivedBy)
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Received By</h3>
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-700">
                            {{ strtoupper(substr($payment->receivedBy->first_name, 0, 1) . substr($payment->receivedBy->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $payment->receivedBy->first_name }} {{ $payment->receivedBy->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->receivedBy->email }}</p>
                        </div>
                    </div>
                </x-ui.card>
            @endif

            @if($payment->receipt)
                <x-ui.card>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Receipt</h3>
                    <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $payment->receipt->receipt_number }}</p>
                            <p class="text-xs text-gray-500">Receipt generated</p>
                        </div>
                    </div>
                </x-ui.card>
            @endif
        </div>
    </div>
</x-layouts.admin>
