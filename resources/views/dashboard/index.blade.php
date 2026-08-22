@extends('layouts.app')

@section('title', 'Dashboard Overview — Recovest Finance')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    <!-- BAGIAN 1: METRIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1: Total Saldo Bank -->
        <div class="bg-white dark:bg-slate-900/80 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm backdrop-blur-sm">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Total Saldo Bank Terhubung</p>
            <h3 class="text-2xl font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($totalBankBalance, 0, ',', '.') }}</h3>
            <div class="mt-2 flex items-center gap-1.5 text-[11px] text-emerald-600 dark:text-emerald-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $bankConnections->count() }} Rekening Bank Live</span>
            </div>
        </div>

        <!-- Metric 2: Total Pemasukan -->
        <div class="bg-white dark:bg-slate-900/80 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm backdrop-blur-sm">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Total Pemasukan (Income)</p>
            <h3 class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">Arus Kas Masuk Terverifikasi</div>
        </div>

        <!-- Metric 3: Total Pengeluaran -->
        <div class="bg-white dark:bg-slate-900/80 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm backdrop-blur-sm">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Total Pengeluaran (Expense)</p>
            <h3 class="text-2xl font-extrabold text-rose-600 dark:text-rose-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">Beban Operasional & Kas Keluar</div>
        </div>

        <!-- Metric 4: Net Cash Flow -->
        <div class="bg-white dark:bg-slate-900/80 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm backdrop-blur-sm">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Net Cash Flow</p>
            <h3 class="text-2xl font-extrabold {{ $netCashFlow >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-rose-600 dark:text-rose-400' }}">
                Rp {{ number_format($netCashFlow, 0, ',', '.') }}
            </h3>
            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">Surplus / Defisit Kas Netto</div>
        </div>
    </div>

    <!-- BAGIAN 2: BANK ACCOUNT STATUS CARDS -->
    <div>
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">Status Rekening Bank (User Connected)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($bankConnections as $conn)
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 dark:bg-blue-600/20 border border-blue-500/30 flex items-center justify-center font-bold text-blue-600 dark:text-blue-400 text-lg">
                            {{ substr($conn->bank_name, 0, 3) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-slate-900 dark:text-white text-base">{{ $conn->bank_name }}</h4>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Connected
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5">{{ $conn->account_number }} a.n. {{ $conn->account_holder_name }}</p>
                            <p class="text-xs text-slate-700 dark:text-slate-300 font-bold mt-1">Saldo: Rp {{ number_format($conn->chartOfAccount->balance ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('bank-mutations.sync', $conn->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-600/10 hover:bg-blue-100 dark:hover:bg-blue-600/20 border border-blue-200 dark:border-blue-500/30 transition-colors">
                            Sinkronkan
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 text-center text-slate-500">
                    Belum ada rekening bank terhubung. Silakan hubungkan rekening pada menu <a href="{{ route('bank-mutations.index') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">Rekening & Mutasi Bank</a>.
                </div>
            @endforelse
        </div>
    </div>

    <!-- BAGIAN 3: RECENT MUTATION FEEDS -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Feed Mutasi Bank Terbaru</h3>
            <a href="{{ route('bank-mutations.index') }}" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">Lihat Semua Mutasi →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">Tanggal</th>
                        <th class="p-3.5">Bank</th>
                        <th class="p-3.5">Deskripsi</th>
                        <th class="p-3.5">Tipe</th>
                        <th class="p-3.5 text-right">Nominal</th>
                        <th class="p-3.5 text-center">Status Rekonsiliasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                    @forelse($recentMutations as $mut)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="p-3.5 font-mono text-slate-500 dark:text-slate-400">{{ $mut->transaction_date->format('d/m/Y') }}</td>
                            <td class="p-3.5 font-bold text-slate-900 dark:text-slate-200">{{ $mut->bankConnection->bank_name ?? '-' }}</td>
                            <td class="p-3.5 text-slate-800 dark:text-slate-300">{{ $mut->description }}</td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded font-extrabold text-[10px] {{ $mut->mutation_type === 'CR' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' }}">
                                    {{ $mut->mutation_type === 'CR' ? 'CR (Masuk)' : 'DB (Keluar)' }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right font-bold text-slate-900 dark:text-white">Rp {{ number_format($mut->amount, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center">
                                @if($mut->is_reconciled)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-500/30">RECONCILED</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-500/30">UNMATCHED</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-500">Belum ada data mutasi bank terdaftar. Data dimulai dari input user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
