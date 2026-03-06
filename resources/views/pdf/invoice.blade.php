<x-layouts.pdf title="Invoice {{ $invoice->invoice_number }}">
    @push('styles')
    <style>
        .invoice-header { display: table; width: 100%; margin-bottom: 25px; }
        .invoice-header .left, .invoice-header .right { display: table-cell; vertical-align: top; }
        .invoice-header .right { text-align: right; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #1a237e; margin-bottom: 5px; }
        .invoice-meta { font-size: 11px; color: #666; line-height: 1.6; }
        .invoice-meta strong { color: #333; }

        .parties { display: table; width: 100%; margin-bottom: 25px; }
        .parties .from, .parties .to { display: table-cell; vertical-align: top; width: 50%; }
        .parties .label { font-size: 10px; font-weight: 600; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; margin-bottom: 6px; }
        .parties .name { font-weight: 600; font-size: 13px; color: #111; margin-bottom: 2px; }
        .parties .detail { font-size: 11px; color: #666; line-height: 1.6; }

        .items-table { margin-bottom: 20px; }
        .items-table th { background: #f0f1f5; font-weight: 600; font-size: 10px; text-transform: uppercase; color: #555; padding: 8px 10px; }
        .items-table td { padding: 10px 10px; font-size: 11px; border-bottom: 1px solid #eee; }
        .items-table .amount { text-align: right; }
        .items-table .qty { text-align: center; }

        .totals-table { width: 280px; margin-left: auto; margin-bottom: 25px; }
        .totals-table td { padding: 5px 10px; font-size: 11px; }
        .totals-table .label { color: #666; text-align: left; }
        .totals-table .value { text-align: right; font-weight: 500; color: #333; }
        .totals-table .total-row td { border-top: 2px solid #1a237e; font-size: 13px; font-weight: bold; color: #1a237e; padding-top: 8px; }

        .payment-info { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 15px; margin-bottom: 20px; }
        .payment-info h4 { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #6b7280; margin-bottom: 8px; }
        .payment-info table td { padding: 3px 8px; font-size: 11px; border: none; }
        .payment-info .method-label { color: #666; }
        .payment-info .method-value { font-weight: 500; color: #333; }

        .status-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; }
        .status-paid { background: #dcfce7; color: #166534; }
        .status-sent, .status-partially_paid { background: #dbeafe; color: #1e40af; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        .status-draft, .status-cancelled, .status-void { background: #f3f4f6; color: #374151; }

        .notes-section { border-top: 1px solid #e5e7eb; padding-top: 15px; margin-top: 20px; }
        .notes-section h4 { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #6b7280; margin-bottom: 5px; }
        .notes-section p { font-size: 11px; color: #666; line-height: 1.6; }
    </style>
    @endpush

    {{-- Invoice Header --}}
    <div class="invoice-header">
        <div class="left">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-meta">
                <span class="status-badge status-{{ $invoice->status->value }}">{{ $invoice->status->label() }}</span>
            </div>
        </div>
        <div class="right">
            <div class="invoice-meta">
                <strong>Invoice No:</strong> {{ $invoice->invoice_number }}<br>
                <strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}<br>
                <strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}<br>
                <strong>Type:</strong> {{ $invoice->type->label() }}
            </div>
        </div>
    </div>

    {{-- From / To --}}
    <div class="parties">
        <div class="from">
            <div class="label">From</div>
            <div class="name">Pharmaceutical Society of Kenya</div>
            <div class="detail">
                Pamstech House, Woodlands Road<br>
                P.O. Box 44290-00100, Nairobi<br>
                Tel: +254 20 2717077<br>
                Email: info@psk.or.ke
            </div>
        </div>
        <div class="to">
            <div class="label">Bill To</div>
            <div class="name">{{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</div>
            <div class="detail">
                {{ $invoice->user->email }}<br>
                @if($invoice->user->phone){{ $invoice->user->phone }}<br>@endif
                @if($invoice->user->address){{ $invoice->user->address }}@endif
            </div>
        </div>
    </div>

    {{-- Line Items --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align: left; width: 50%;">Description</th>
                <th class="qty" style="width: 15%;">Quantity</th>
                <th class="amount" style="width: 17.5%;">Unit Price</th>
                <th class="amount" style="width: 17.5%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="qty">{{ number_format($item->quantity) }}</td>
                    <td class="amount">KES {{ number_format($item->unit_price, 2) }}</td>
                    <td class="amount">KES {{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals-table">
        <tr>
            <td class="label">Subtotal</td>
            <td class="value">KES {{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Tax (16%)</td>
            <td class="value">KES {{ number_format($invoice->tax_amount, 2) }}</td>
        </tr>
        @if($invoice->discount_amount > 0)
            <tr>
                <td class="label">Discount</td>
                <td class="value">-KES {{ number_format($invoice->discount_amount, 2) }}</td>
            </tr>
        @endif
        <tr class="total-row">
            <td class="label">Total</td>
            <td class="value">KES {{ number_format($invoice->total_amount, 2) }}</td>
        </tr>
    </table>

    {{-- Payment Info --}}
    @if($invoice->amount_paid > 0)
        <div class="payment-info">
            <h4>Payment Information</h4>
            <table>
                <tr>
                    <td class="method-label">Amount Paid:</td>
                    <td class="method-value">KES {{ number_format($invoice->amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <td class="method-label">Balance Due:</td>
                    <td class="method-value">KES {{ number_format($invoice->balance_due, 2) }}</td>
                </tr>
            </table>

            @if($invoice->payments->count())
                <table style="width: 100%; margin-top: 10px;">
                    <thead>
                        <tr>
                            <th style="text-align: left; font-size: 10px; color: #666; padding: 4px 8px;">Date</th>
                            <th style="text-align: left; font-size: 10px; color: #666; padding: 4px 8px;">Method</th>
                            <th style="text-align: left; font-size: 10px; color: #666; padding: 4px 8px;">Reference</th>
                            <th style="text-align: right; font-size: 10px; color: #666; padding: 4px 8px;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                            <tr>
                                <td style="padding: 4px 8px; font-size: 11px;">{{ $payment->paid_at?->format('d M Y') ?? $payment->created_at->format('d M Y') }}</td>
                                <td style="padding: 4px 8px; font-size: 11px;">{{ $payment->payment_method->label() }}</td>
                                <td style="padding: 4px 8px; font-size: 11px;">{{ $payment->payment_reference ?? '-' }}</td>
                                <td style="padding: 4px 8px; font-size: 11px; text-align: right;">KES {{ number_format($payment->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @else
        <div class="payment-info">
            <h4>Payment Information</h4>
            <table>
                <tr>
                    <td class="method-label">Balance Due:</td>
                    <td class="method-value">KES {{ number_format($invoice->balance_due, 2) }}</td>
                </tr>
                <tr>
                    <td class="method-label">Due Date:</td>
                    <td class="method-value">{{ $invoice->due_date->format('d M Y') }}</td>
                </tr>
            </table>
        </div>
    @endif

    {{-- Notes --}}
    @if($invoice->notes)
        <div class="notes-section">
            <h4>Notes</h4>
            <p>{{ $invoice->notes }}</p>
        </div>
    @endif
</x-layouts.pdf>
