@extends('layouts.app')

@section('title', 'Rekening & Mutasi Bank — Recovest Finance')
@section('page-title', 'Modul Rekening Bank & Live Feed Mutasi')

@section('content')
<div x-data="{ matchModal: false, convertModal: false, selectedMutation: null, mutationDesc: '', mutationAmount: 0 }" class="space-y-6">

    <!-- DEMO SIMULATION HEADER ACTION -->
    <div class="bg-gradient-to-r from-blue-900/40 via-indigo-900/40 to-slate-900 p-5 rounded-2xl border border-blue-500/30 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-500/20 text-blue-400 border border-blue-500/40 uppercase tracking-wide">Sandbox Integration Engine</span>
                <h3 class="text-base font-bold text-white">Simulasi Bank Open Finance Webhook</h3>
            </div>
            <p class="text-xs text-slate-400 mt-1">Uji coba simulasi transaksi instan dari API Bank resmi (BCA, Mandiri, BRI) langsung ke feed mutasi.</p>
        </div>

        @if($connections->first())
            <form action="{{ route('bank-mutations.simulate-webhook', $connections->first()->id) }}" method="POST" class="flex gap-2">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-xs bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
                    <span>[⚡ Simulasi Mutasi Baru Masuk]</span>
                </button>
            </form>
        @endif
    </div>

    <!-- BANK ACCOUNTS LIST -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($connections as $conn)
            <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-white text-sm">{{ $conn->bank_name }} — {{ $conn->account_number }}</h4>
                    <p class="text-xs text-slate-400">a.n. {{ $conn->account_holder_name }}</p>
                    <p class="text-xs text-blue-400 font-semibold mt-1">Saldo Buku (Real Transactions): Rp {{ number_format($conn->chartOfAccount->balance ?? 0, 0, ',', '.') }}</p>
                </div>

                <form action="{{ route('bank-mutations.sync', $conn->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-300 bg-slate-800 hover:bg-slate-700 border border-slate-700">
                        🔄 Sync Feed
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <!-- REAL-TIME BANK MUTATIONS TABLE -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-sm font-bold text-white">Tabel Live Feed Mutasi Bank</h3>
            <span class="text-xs text-slate-400">Total Records: {{ $mutations->total() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">Tanggal</th>
                        <th class="p-3.5">Bank</th>
                        <th class="p-3.5">Deskripsi Mutasi</th>
                        <th class="p-3.5">Tipe</th>
                        <th class="p-3.5 text-right">Nominal</th>
                        <th class="p-3.5 text-right">Saldo Mutasi</th>
                        <th class="p-3.5 text-center">Status Rekonsiliasi</th>
                        <th class="p-3.5 text-center">Aksi Matching / Catat Kas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($mutations as $mut)
                        <tr class="hover:bg-slate-800/40">
                            <td class="p-3.5 font-mono text-slate-400">{{ $mut->transaction_date->format('Y-m-d') }}</td>
                            <td class="p-3.5 font-bold text-white">{{ $mut->bankConnection->bank_name ?? '-' }}</td>
                            <td class="p-3.5 text-slate-300 max-w-xs truncate">{{ $mut->description }}</td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded font-extrabold text-[10px] {{ $mut->mutation_type === 'CR' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                                    {{ $mut->mutation_type }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right font-bold text-white">Rp {{ number_format($mut->amount, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-right text-slate-400">Rp {{ number_format($mut->balance_after ?? 0, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center">
                                @if($mut->is_reconciled)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">RECONCILED</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">UNMATCHED</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                @if($mut->is_reconciled)
                                    <span class="text-slate-500 text-[10px] font-mono">Matched: {{ $mut->reconciledTransaction->transaction_number ?? 'Internal' }}</span>
                                @else
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button @click="selectedMutation = '{{ $mut->id }}'; matchModal = true" class="px-2 py-1 rounded-lg text-[10px] font-bold text-blue-400 bg-blue-600/10 hover:bg-blue-600/20 border border-blue-500/30">
                                            🔗 Match
                                        </button>
                                        <button @click="selectedMutation = '{{ $mut->id }}'; mutationDesc = '{{ addslashes($mut->description) }}'; mutationAmount = {{ $mut->amount }}; convertModal = true" class="px-2 py-1 rounded-lg text-[10px] font-bold text-emerald-400 bg-emerald-600/10 hover:bg-emerald-600/20 border border-emerald-500/30">
                                            + Catat ke Kas
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-500">Belum ada mutasi bank. Hit tombol sync di atas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-800">
            {{ $mutations->links() }}
        </div>
    </div>

    <!-- MODAL MATCH MUTATION WITH EXISTING TRANSACTION -->
    <div x-show="matchModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="matchModal = false" class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white">Hubungkan Mutasi dengan Transaksi Internal</h3>
                <button @click="matchModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('transactions.match') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="mutation_id" :value="selectedMutation">

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Pilih Transaksi Internal</label>
                    <select name="transaction_id" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                        @foreach($transactions as $trx)
                            <option value="{{ $trx->id }}">
                                {{ $trx->transaction_number }} — {{ $trx->type }} (Rp {{ number_format($trx->amount, 0, ',', '.') }}) - {{ $trx->category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="matchModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-500">Hubungkan Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONVERT MUTATION TO REAL TRANSACTION -->
    <div x-show="convertModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="convertModal = false" class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white">Buat Transaksi Kas dari Mutasi Bank</h3>
                <button @click="convertModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('transactions.create-from-mutation') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="mutation_id" :value="selectedMutation">

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Deskripsi Mutasi</label>
                    <p class="text-xs text-slate-200 bg-slate-950 p-2.5 rounded-xl border border-slate-800" x-text="mutationDesc"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Pilih Kategori Transaksi</label>
                    <input type="text" name="category" required placeholder="Contoh: Penjualan QRIS / Beban Operasional" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="convertModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500">Catat & Rekonsiliasi</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
