<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InvoicesExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\CostCenter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['user', 'costCenter']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $invoices = $query->latest()->paginate(15)->withQueryString();

        $totalInvoices = Invoice::count();
        $paidAmount = Invoice::where('status', \App\Enums\InvoiceStatus::Paid)->sum('total_amount');
        $outstandingAmount = Invoice::whereIn('status', [\App\Enums\InvoiceStatus::Sent, \App\Enums\InvoiceStatus::PartiallyPaid])->sum('balance_due');
        $overdueCount = Invoice::overdue()->count();

        return view('admin.invoices.index', compact('invoices', 'totalInvoices', 'paidAmount', 'outstandingAmount', 'overdueCount'));
    }

    public function create()
    {
        $users = User::orderBy('first_name')->get();
        $costCenters = CostCenter::where('is_active', true)->get();

        return view('admin.invoices.create', compact('users', 'costCenters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'type' => 'required|string',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
            'currency' => 'nullable|string|max:3',
        ]);

        $invoice = Invoice::create($validated);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['user', 'costCenter', 'items', 'payments', 'receipts']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $users = User::orderBy('first_name')->get();
        $costCenters = CostCenter::where('is_active', true)->get();

        return view('admin.invoices.edit', compact('invoice', 'users', 'costCenters'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'cost_center_id' => 'nullable|exists:cost_centers,id',
            'type' => 'required|string',
            'status' => 'required|string',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
            'currency' => 'nullable|string|max:3',
        ]);

        $invoice->update($validated);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load(['user', 'items', 'costCenter']);

        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices-' . date('Y-m-d') . '.xlsx');
    }
}
