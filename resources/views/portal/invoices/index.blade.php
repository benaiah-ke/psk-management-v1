<x-layouts.portal title="My Invoices">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Invoices</h1>
        <p class="text-sm text-gray-500">View and manage your invoices and payment history.</p>
    </div>

    {{-- Summary Cards --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-ui.stat-card label="Total Due" :value="'KES ' . number_format($totalDue ?? 0, 2)" color="red">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>

        <x-ui.stat-card label="Paid This Year" :value="'KES ' . number_format($paidThisYear ?? 0, 2)" color="green">
            <x-slot:icon>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot:icon>
        </x-ui.stat-card>
    </div>

    {{-- Invoices Table --}}
    @if($invoices->count())
        <x-table.table>
            <x-slot:head>
                <x-table.heading>Invoice #</x-table.heading>
                <x-table.heading>Type</x-table.heading>
                <x-table.heading>Amount</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading>Due Date</x-table.heading>
                <x-table.heading>Actions</x-table.heading>
            </x-slot:head>

            @foreach($invoices as $invoice)
                <tr>
                    <x-table.cell class="font-medium text-gray-900">{{ $invoice->invoice_number }}</x-table.cell>
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
                            <x-ui.button href="{{ route('portal.invoices.show', $invoice) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                View
                            </x-ui.button>
                            <x-ui.button href="{{ route('portal.invoices.pdf', $invoice) }}" variant="ghost" size="xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                PDF
                            </x-ui.button>
                        </div>
                    </x-table.cell>
                </tr>
            @endforeach

            <x-slot:pagination>{{ $invoices->links() }}</x-slot:pagination>
        </x-table.table>
    @else
        <x-ui.empty-state title="No invoices yet" description="Your invoices will appear here once they have been issued.">
            <x-slot:icon>
                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </x-slot:icon>
        </x-ui.empty-state>
    @endif
</x-layouts.portal>
