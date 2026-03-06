<x-layouts.admin title="Invoices">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
            <p class="text-sm text-gray-500">Manage and track all invoices.</p>
        </div>
        <x-ui.button href="{{ route('admin.invoices.create') }}">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Create Invoice
        </x-ui.button>
    </div>

    {{-- Stat Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card label="Total Invoices" :value="number_format($totalInvoices ?? 0)" color="primary">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Paid" :value="'KES ' . number_format($paidAmount ?? 0, 2)" color="green">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Outstanding" :value="'KES ' . number_format($outstandingAmount ?? 0, 2)" color="yellow">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Overdue" :value="number_format($overdueCount ?? 0)" color="red">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.invoices.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-form.input name="search" label="Search" placeholder="Invoice # or member name..." :value="request('search')" />

            <x-form.select name="status" label="Status" :options="collect(\App\Enums\InvoiceStatus::cases())->mapWithKeys(fn($s) => [$s->value => $s->label()])->all()" :selected="request('status')" placeholder="All Statuses" />

            <x-form.select name="type" label="Type" :options="collect(\App\Enums\InvoiceType::cases())->mapWithKeys(fn($t) => [$t->value => $t->label()])->all()" :selected="request('type')" placeholder="All Types" />

            <div class="flex items-end gap-2">
                <x-ui.button type="submit" variant="secondary" class="flex-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </x-ui.button>
                @if(request()->hasAny(['search', 'status', 'type']))
                    <x-ui.button href="{{ route('admin.invoices.index') }}" variant="ghost" size="sm">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- Invoices Table --}}
    @if($invoices->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading sortable column="invoice_number" :direction="request('sort') === 'invoice_number' ? request('direction') : null">Invoice #</x-table.heading>
                <x-table.heading>Member</x-table.heading>
                <x-table.heading>Type</x-table.heading>
                <x-table.heading sortable column="total_amount" :direction="request('sort') === 'total_amount' ? request('direction') : null">Amount</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading sortable column="due_date" :direction="request('sort') === 'due_date' ? request('direction') : null">Due Date</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($invoices as $invoice)
                <tr>
                    <x-table.cell class="font-medium text-gray-900">{{ $invoice->invoice_number }}</x-table.cell>
                    <x-table.cell>
                        <div>
                            <p class="font-medium text-gray-900">{{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ $invoice->user->email }}</p>
                        </div>
                    </x-table.cell>
                    <x-table.cell>
                        <x-ui.badge color="indigo">{{ $invoice->type->label() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell class="font-medium">KES {{ number_format($invoice->total_amount, 2) }}</x-table.cell>
                    <x-table.cell>
                        <x-ui.badge :color="$invoice->status->color()">{{ $invoice->status->label() }}</x-ui.badge>
                    </x-table.cell>
                    <x-table.cell>
                        <span class="{{ $invoice->due_date->isPast() && !$invoice->isPaid() ? 'text-red-600 font-medium' : '' }}">
                            {{ $invoice->due_date->format('d M Y') }}
                        </span>
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-1">
                            <x-ui.button href="{{ route('admin.invoices.show', $invoice) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </x-ui.button>
                            <x-ui.button href="{{ route('admin.invoices.edit', $invoice) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-ui.button>
                            <x-ui.button href="{{ route('admin.invoices.pdf', $invoice) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </x-ui.button>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>{{ $invoices->withQueryString()->links() }}</x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No invoices found" description="No invoices match your current filters, or none have been created yet.">
            <x-ui.button href="{{ route('admin.invoices.create') }}" size="sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Invoice
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-layouts.admin>
