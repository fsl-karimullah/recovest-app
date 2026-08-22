<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\VendorBill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorBillController extends Controller
{
    public function index()
    {
        $organization = Organization::first();
        $bills = VendorBill::where('organization_id', $organization->id ?? '')
            ->latest('bill_date')
            ->paginate(15);

        return view('dashboard.vendor-bills', compact('bills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'bill_date' => 'required|date',
            'due_date' => 'required|date',
            'total_amount' => 'required|numeric|min:1',
            'category' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $organization = Organization::first();
        $billNumber = 'BILL-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        VendorBill::create([
            'organization_id' => $organization->id,
            'bill_number' => $billNumber,
            'vendor_name' => $request->vendor_name,
            'bill_date' => $request->bill_date,
            'due_date' => $request->due_date,
            'total_amount' => $request->total_amount,
            'category' => $request->category,
            'status' => 'PENDING',
            'notes' => $request->notes ?? null,
        ]);

        return back()->with('success', 'Tagihan Pembelian (Vendor Bill) berhasil dicatat!');
    }

    public function markAsPaid(string $id)
    {
        $bill = VendorBill::findOrFail($id);
        $bill->update(['status' => 'PAID']);

        return back()->with('success', 'Tagihan Vendor ' . $bill->bill_number . ' telah dilunasi!');
    }
}
