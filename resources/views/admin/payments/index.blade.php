<x-layouts.admin title="Payments">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
            <p class="text-sm text-gray-500">View and manage all payment records.</p>
        </div>
        <x-ui.button href="{{ route('admin.payments.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Record Payment
        </x-ui.button>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-form.input name="search" label="Search" placeholder="Reference or invoice #..." :value="request('search')" />

            <x-form.select name="payment_method" label="Payment Method" :options="collect(\App\Enums\PaymentMethod::cases())->mapWithKeys(fn($m) => [$m->value => $m->label()])->all()" :selected="request('payment_method')" placeholder="All Methods" />

            <x-form.select name="status" label="Status" :options="collect(\App\Enums\PaymentStatus::cases())->mapWithKeys(fn($s) => [$s->value => $s->label()])->all()" :selected="request('status')" placeholder="All Statuses" />

            <div class="flex items-end gap-2">
                <x-ui.button type="submit" variant="secondary" class="flex-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </x-ui.button>
                @if(request()->hasAny(['search', 'payment_method', 'status']))
                    <x-ui.button href="{{ route('admin.payments.index') }}" variant="ghost" size="sm">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- Payments Table --}}
    @if($payments->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="paid_at" :direction="request('sort') === 'paid_at' ? request('direction') : null">Date</x-table.heading>
                <x-table.heading>Invoice #</x-table.heading>
                <x-table.heading>Member</x-table.heading>
                <x-table.heading sortable column="amount" :direction="request('sort') === 'amount' ? request('direction') : null">Amount</x-table.heading>
                <x-table.heading>Method</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Reference</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($payments as $payment)
                <tr>
                    <x-table.cell>{{ $payment->paid_at?->format('d M Y') ?? $payment->created_at->format('d M Y') }}</x-table.cell>
                    <x-table.cell>
                        <a href="{{ route('admin.invoices.show', $payment->invoice) }}" class="font-medium text-primary-600 hover:text-primary-800">
                            {{ $payment->invoice->invoice_number }}
                        </a>
                    </x-table.cell>
                    <x-table.cell>
                        <div>
                            <p class="font-medium text-gray-900">{{ $payment->invoice->user->first_name }} {{ $payment->invoice->user->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->invoice->user->email }}</p>
                        </div>
                    </x-table.cell>
                    <x-table.cell class="font-medium">KES {{ number_format($payment->amount, 2) }}</x-table.cell>
                    <x-table.cell>
                        <x-ui.badge color="blue">{{ $payment->payment_method->label() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        <x-ui.badge :color="$payment->status->color()">{{ $payment->status->label() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>{{ $payment->payment_reference ?? '-' }}</x-table.cell>
                    <x-table.cell>
                        <x-ui.button href="{{ route('admin.invoices.show', $payment->invoice) }}" variant="ghost" size="xs">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </x-ui.button>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>{{ $payments->withQueryString()->links() }}</x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No payments found" description="No payments match your current filters, or none have been recorded yet.">
            <x-ui.button href="{{ route('admin.payments.create') }}" size="sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Record Payment
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
