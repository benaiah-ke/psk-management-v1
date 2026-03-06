<x-layouts.portal title="Invoice {{ $invoice->invoice_number }}">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">{{ $invoice->invoice_number }}</h1>
                <x-ui.badge :color="$invoice->status->color()" size="md">{{ $invoice->status->label() }}</x-ui.badge>
            </div>
            <p class="text-sm text-gray-500">Issued on {{ $invoice->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <x-ui.button href="{{ route('portal.invoices.pdf', $invoice) }}" variant="secondary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF
            </x-ui.button>
            <x-ui.button href="{{ route('portal.invoices.index') }}" variant="ghost">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Back
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Invoice Header --}}
            <x-ui.card>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    {{-- From --}}
                    <div>
                        <h3 class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">From</h3>
                        <p class="font-semibold text-gray-900">Pharmaceutical Society of Kenya</p>
                        <p class="text-sm text-gray-600">Pamstech House, Woodlands Road</p>
                        <p class="text-sm text-gray-600">Nairobi, Kenya</p>
                        <p class="text-sm text-gray-600">info@psk.or.ke</p>
                    </div>
                    {{-- To --}}
                    <div>
                        <h3 class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Bill To</h3>
                        <p class="font-semibold text-gray-900">{{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</p>
                        <p class="text-sm text-gray-600">{{ $invoice->user->email }}</p>
                        @if($invoice->user->phone)
                            <p class="text-sm text-gray-600">{{ $invoice->user->phone }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4 border-t border-gray-200 pt-4 sm:grid-cols-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Invoice Number</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Invoice Date</p>
                        <p class="mt-1 font-medium text-gray-900">{{ $invoice->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Due Date</p>
                        <p class="mt-1 font-medium {{ $invoice->due_date->isPast() && !$invoice->isPaid() ? 'text-red-600' : 'text-gray-900' }}">{{ $invoice->due_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Type</p>
                        <p class="mt-1"><x-ui.badge color="indigo">{{ $invoice->type->label() }}</x-ui.badge></p>
                    </div>
                </div>
            </x-ui.card>

            {{-- Line Items --}}
            <x-ui.card :padding="false">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Description</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Qty</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Unit Price</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-5 py-3 text-sm text-gray-900">{{ $item->description }}</td>
                                    <td class="px-5 py-3 text-right text-sm text-gray-700">{{ number_format($item->quantity) }}</td>
                                    <td class="px-5 py-3 text-right text-sm text-gray-700">KES {{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-5 py-3 text-right text-sm font-medium text-gray-900">KES {{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 bg-gray-50 px-5 py-4">
                    <div class="flex flex-col items-end space-y-1">
                        <div class="flex w-full max-w-xs justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium text-gray-900">KES {{ number_format($invoice->subtotal, 2) }}</span>
                        </div>
                        <div class="flex w-full max-w-xs justify-between text-sm">
                            <span class="text-gray-500">Tax</span>
                            <span class="font-medium text-gray-900">KES {{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                        @if($invoice->discount_amount > 0)
                            <div class="flex w-full max-w-xs justify-between text-sm">
                                <span class="text-gray-500">Discount</span>
                                <span class="font-medium text-green-600">-KES {{ number_format($invoice->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex w-full max-w-xs justify-between border-t border-gray-300 pt-2 text-base">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="font-bold text-gray-900">KES {{ number_format($invoice->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            {{-- Payments --}}
            @if($invoice->payments->count())
                <x-ui.card :padding="false">
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">Payment History</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Method</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Reference</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($invoice->payments as $payment)
                                    <tr>
                                        <td class="px-5 py-3 text-sm text-gray-700">{{ $payment->paid_at?->format('d M Y') ?? $payment->created_at->format('d M Y') }}</td>
                                        <td class="px-5 py-3 text-sm"><x-ui.badge color="blue">{{ $payment->payment_method->label() }}</x-ui.badge></td>
                                        <td class="px-5 py-3 text-sm text-gray-700">{{ $payment->payment_reference ?? '-' }}</td>
                                        <td class="px-5 py-3 text-sm"><x-ui.badge :color="$payment->status->color()">{{ $payment->status->label() }}</x-ui.badge></td>
                                        <td class="px-5 py-3 text-right text-sm font-medium text-gray-900">KES {{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-ui.card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Payment Summary --}}
            <x-ui.card>
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Payment Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Amount</span>
                        <span class="font-semibold text-gray-900">KES {{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Amount Paid</span>
                        <span class="font-semibold text-green-600">KES {{ number_format($invoice->amount_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-3 text-sm">
                        <span class="font-medium text-gray-700">Balance Due</span>
                        <span class="font-bold {{ $invoice->balance_due > 0 ? 'text-red-600' : 'text-gray-900' }}">KES {{ number_format($invoice->balance_due, 2) }}</span>
                    </div>
                </div>

                @if($invoice->balance_due > 0 && !$invoice->isPaid())
                    <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-3">
                        <p class="text-sm text-yellow-800">
                            <strong>Payment Required.</strong> Please settle the outstanding balance of KES {{ number_format($invoice->balance_due, 2) }} before {{ $invoice->due_date->format('d M Y') }}.
                        </p>
                    </div>
                @endif
            </x-ui.card>

            {{-- Notes --}}
            @if($invoice->notes)
                <x-ui.card>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">Notes</h3>
                    <p class="text-sm text-gray-600">{{ $invoice->notes }}</p>
                </x-ui.card>
            @endif
        </div>
    </div>
</x-layouts.portal>
