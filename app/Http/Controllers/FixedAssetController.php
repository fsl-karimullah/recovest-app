<?php

namespace App\Http\Controllers;

use App\Models\FixedAsset;
use App\Models\Organization;
use Illuminate\Http\Request;

class FixedAssetController extends Controller
{
    public function index()
    {
        $organization = Organization::first();
        $assets = FixedAsset::where('organization_id', $organization->id ?? '')
            ->latest('purchase_date')
            ->get();

        $totalAssetCost = $assets->sum('purchase_cost');
        $totalDepreciation = $assets->sum('accumulated_depreciation');
        $totalBookValue = $assets->sum('book_value');

        return view('dashboard.assets', compact('assets', 'totalAssetCost', 'totalDepreciation', 'totalBookValue'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:1',
            'useful_life_years' => 'required|integer|min:1',
            'salvage_value' => 'nullable|numeric|min:0',
        ]);

        $organization = Organization::first();
        $assetCode = 'AST-' . rand(100, 999);

        $cost = (float) $request->purchase_cost;
        $salvage = (float) ($request->salvage_value ?? 0);
        $usefulLife = (int) $request->useful_life_years;

        // Calculate 1-year straight line depreciation
        $annualDepreciation = ($cost - $salvage) / $usefulLife;
        $bookValue = $cost - $annualDepreciation;

        FixedAsset::create([
            'organization_id' => $organization->id,
            'asset_code' => $assetCode,
            'asset_name' => $request->asset_name,
            'purchase_date' => $request->purchase_date,
            'purchase_cost' => $cost,
            'salvage_value' => $salvage,
            'useful_life_years' => $usefulLife,
            'accumulated_depreciation' => $annualDepreciation,
            'book_value' => $bookValue,
        ]);

        return back()->with('success', 'Aset Tetap baru berhasil didaftarkan dan nilai penyusutan dikalkulasi otomatis!');
    }
}
