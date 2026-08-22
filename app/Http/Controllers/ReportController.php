<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\Organization;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $organization = Organization::first();
        if (!$organization) {
            return redirect()->route('dashboard');
        }

        // 1. Profit & Loss (Laba Rugi)
        $revenueCoas = ChartOfAccount::where('organization_id', $organization->id)
            ->where('account_type', 'REVENUE')
            ->get();
        $totalRevenue = Transaction::where('organization_id', $organization->id)
            ->where('type', 'INCOME')
            ->where('status', 'COMPLETED')
            ->sum('amount');

        $expenseCoas = ChartOfAccount::where('organization_id', $organization->id)
            ->where('account_type', 'EXPENSE')
            ->get();
        $totalExpense = Transaction::where('organization_id', $organization->id)
            ->where('type', 'EXPENSE')
            ->where('status', 'COMPLETED')
            ->sum('amount');

        $netProfit = $totalRevenue - $totalExpense;

        // 2. Balance Sheet (Neraca)
        $assetCoas = ChartOfAccount::where('organization_id', $organization->id)
            ->where('account_type', 'ASSET')
            ->get();
        $totalAssets = $assetCoas->sum('balance');

        $liabilityCoas = ChartOfAccount::where('organization_id', $organization->id)
            ->where('account_type', 'LIABILITY')
            ->get();
        $totalLiabilities = $liabilityCoas->sum('balance');

        $equityCoas = ChartOfAccount::where('organization_id', $organization->id)
            ->where('account_type', 'EQUITY')
            ->get();
        $totalEquity = $equityCoas->sum('balance') + $netProfit;

        // 3. Trial Balance (Neraca Saldo)
        $allAccounts = ChartOfAccount::where('organization_id', $organization->id)->get();
        $trialBalance = $allAccounts->map(function ($acc) {
            $debitSum = JournalEntry::where('chart_of_account_id', $acc->id)->sum('debit');
            $creditSum = JournalEntry::where('chart_of_account_id', $acc->id)->sum('credit');
            return [
                'code' => $acc->account_code,
                'name' => $acc->account_name,
                'type' => $acc->account_type,
                'debit' => $debitSum,
                'credit' => $creditSum,
                'balance' => $acc->balance,
            ];
        });

        return view('dashboard.reports', compact(
            'organization',
            'totalRevenue',
            'totalExpense',
            'netProfit',
            'assetCoas',
            'totalAssets',
            'liabilityCoas',
            'totalLiabilities',
            'equityCoas',
            'totalEquity',
            'trialBalance'
        ));
    }
}
