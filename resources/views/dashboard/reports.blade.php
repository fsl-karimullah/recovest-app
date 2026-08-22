@extends('layouts.app')

@section('title', 'Laporan Keuangan — Recovest Finance')
@section('page-title', 'Laporan Keuangan & PSAK Audit-Ready Statements')

@section('content')
<div x-data="{ tab: 'pnl' }" class="space-y-6">

    <!-- REPORT SELECTION TABS -->
    <div class="bg-white dark:bg-slate-900 p-2 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-2 overflow-x-auto">
        <button @click="tab = 'pnl'" :class="tab === 'pnl' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all">
            Laporan Laba Rugi (Profit & Loss)
        </button>
        <button @click="tab = 'balance_sheet'" :class="tab === 'balance_sheet' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all">
            Laporan Neraca (Balance Sheet)
        </button>
        <button @click="tab = 'trial_balance'" :class="tab === 'trial_balance' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="px-5 py-2.5 rounded-xl font-bold text-xs transition-all">
            Neraca Saldo (Trial Balance)
        </button>
    </div>

    <!-- TAB 1: PROFIT & LOSS (LABA RUGI) -->
    <div x-show="tab === 'pnl'" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-6">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-4 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Laporan Laba Rugi (P&L)</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Periode Berjalan — {{ $organization->name }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-500/30">PSAK Verified</span>
        </div>

        <div class="space-y-4 text-xs">
            <!-- Pendapatan -->
            <div class="space-y-2">
                <div class="flex justify-between font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-200 dark:border-slate-800">
                    <span>Pendapatan Usaha (Revenue)</span>
                    <span class="text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Beban -->
            <div class="space-y-2">
                <div class="flex justify-between font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-200 dark:border-slate-800">
                    <span>Beban Operasional & Server (Expenses)</span>
                    <span class="text-rose-600 dark:text-rose-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Laba Bersih -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-between items-center text-sm font-extrabold p-3.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-500/30">
                <span class="text-slate-900 dark:text-white">LABA / (RUGI) BERSIH NETTO</span>
                <span class="{{ $netProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                    Rp {{ number_format($netProfit, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <!-- TAB 2: BALANCE SHEET (NERACA) -->
    <div x-show="tab === 'balance_sheet'" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-6">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Laporan Neraca (Balance Sheet)</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Posisi Keuangan — Assets = Liabilities + Equity</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
            <!-- Aset -->
            <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                <h4 class="font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-800 pb-2">ASET (ASSETS)</h4>
                @foreach($assetCoas as $asset)
                    <div class="flex justify-between text-slate-700 dark:text-slate-300">
                        <span>{{ $asset->account_name }}</span>
                        <span class="font-bold">Rp {{ number_format($asset->balance, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-between font-extrabold text-slate-900 dark:text-white text-sm">
                    <span>TOTAL ASET</span>
                    <span class="text-blue-600 dark:text-blue-400">Rp {{ number_format($totalAssets, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Liabilitas & Ekuitas -->
            <div class="bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-200 dark:border-slate-800 space-y-3">
                <h4 class="font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-800 pb-2">LIABILITAS & EKUITAS</h4>
                <div class="text-slate-500 font-semibold">Liabilitas (Kewajiban)</div>
                @forelse($liabilityCoas as $liab)
                    <div class="flex justify-between text-slate-700 dark:text-slate-300">
                        <span>{{ $liab->account_name }}</span>
                        <span class="font-bold">Rp {{ number_format($liab->balance, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="text-slate-400 text-[11px]">- Tidak ada liabilitas -</div>
                @endforelse

                <div class="text-slate-500 font-semibold pt-2">Ekuitas (Modal)</div>
                <div class="flex justify-between text-slate-700 dark:text-slate-300">
                    <span>Laba Ditahan / Berjalan</span>
                    <span class="font-bold">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
                </div>

                <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-between font-extrabold text-slate-900 dark:text-white text-sm">
                    <span>TOTAL LIABILITAS & EKUITAS</span>
                    <span class="text-indigo-600 dark:text-indigo-400">Rp {{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: TRIAL BALANCE (NERACA SALDO) -->
    <div x-show="tab === 'trial_balance'" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Neraca Saldo (Trial Balance)</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Rincian Debet vs Kredit Seluruh Akun Buku Besar</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">Kode Akun</th>
                        <th class="p-3.5">Nama Akun</th>
                        <th class="p-3.5">Tipe</th>
                        <th class="p-3.5 text-right">Debet</th>
                        <th class="p-3.5 text-right">Kredit</th>
                        <th class="p-3.5 text-right">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                    @foreach($trialBalance as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="p-3.5 font-mono text-blue-600 dark:text-blue-400 font-bold">{{ $item['code'] }}</td>
                            <td class="p-3.5 font-semibold text-slate-900 dark:text-white">{{ $item['name'] }}</td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ $item['type'] }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right text-slate-700 dark:text-slate-300">Rp {{ number_format($item['debit'], 0, ',', '.') }}</td>
                            <td class="p-3.5 text-right text-slate-700 dark:text-slate-300">Rp {{ number_format($item['credit'], 0, ',', '.') }}</td>
                            <td class="p-3.5 text-right font-bold text-slate-900 dark:text-white">Rp {{ number_format($item['balance'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
