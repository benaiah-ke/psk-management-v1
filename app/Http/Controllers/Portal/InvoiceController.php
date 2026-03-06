<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('portal.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        abort_unless($invoice->user_id === auth()->id(), 403);
        $invoice->load('items', 'payments');
        return view('portal.invoices.show', compact('invoice'));
    }

    public function pdf(Invoice $invoice)
    {
        abort_unless($invoice->user_id === auth()->id(), 403);
        $invoice->load('items', 'user');
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}
