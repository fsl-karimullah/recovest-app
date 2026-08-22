<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $organization = Organization::first();
        $invoices = Invoice::where('organization_id', $organization->id ?? '')
            ->latest('issue_date')
            ->paginate(15);

        return view('dashboard.invoices', compact('invoices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'subtotal' => 'required|numeric|min:1',
            'tax_rate' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $organization = Organization::first();

        $subtotal = (float) $request->subtotal;
        $taxRate = (float) ($request->tax_rate ?? 11.00); // Default PPN 11%
        $taxAmount = ($subtotal * $taxRate) / 100;
        $totalAmount = $subtotal + $taxAmount;

        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        Invoice::create([
            'organization_id' => $organization->id,
            'invoice_number' => $invoiceNumber,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email ?? null,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'status' => 'UNPAID',
            'notes' => $request->notes ?? null,
        ]);

        return back()->with('success', 'Faktur Penjualan (Invoice) dengan PPN 11% berhasil diterbitkan!');
    }

    public function markAsPaid(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => 'PAID']);

        return back()->with('success', 'Status Invoice ' . $invoice->invoice_number . ' diperbarui menjadi PAID!');
    }
}
