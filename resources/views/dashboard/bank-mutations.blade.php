@extends('layouts.app')

@section('title', 'Rekening & Mutasi Bank — Recovest Finance')
@section('page-title', 'Rekening & Mutasi Bank (User Managed Feed)')

@section('content')
<div x-data="{ connModalOpen: false, mutationModalOpen: false, matchModalOpen: false, selectedMutId: null, selectedMutAmount: 0, selectedMutDesc: '' }" class="space-y-6">

    <!-- REKENING BANK CARDS & ADD BUTTON -->
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Rekening Bank Terhubung</h3>
            <button @click="connModalOpen = true" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 shadow-sm transition-all">
                + Tambah Rekening Bank
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($connections as $conn)
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
                                    Active
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5">{{ $conn->account_number }} a.n. {{ $conn->account_holder_name }}</p>
                            <p class="text-xs text-slate-700 dark:text-slate-300 font-bold mt-1">Saldo: Rp {{ number_format($conn->chartOfAccount->balance ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <form action="{{ route('bank-mutations.connection.destroy', $conn->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening bank ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 text-center text-slate-500">
                    Belum ada rekening bank terdaftar. Tekan tombol <strong>+ Tambah Rekening Bank</strong> di atas.
                </div>
            @endforelse
        </div>
    </div>

    <!-- MUTATIONS HEADER & FILTERS -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Feed Mutasi Bank</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Data mutasi murni berasal dari input manual atau simulasi webhook pengguna.</p>
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <button @click="mutationModalOpen = true" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500 shadow-sm transition-all">
                + Input Mutasi Manual
            </button>
        </div>
    </div>

    <!-- MUTATIONS TABLE -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">Tanggal</th>
                        <th class="p-3.5">Rekening Bank</th>
                        <th class="p-3.5">Deskripsi Mutasi</th>
                        <th class="p-3.5">Tipe</th>
                        <th class="p-3.5 text-right">Nominal</th>
                        <th class="p-3.5 text-right">Saldo Sesudah</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-center">Aksi CRUD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                    @forelse($mutations as $mut)
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
                            <td class="p-3.5 text-right font-mono text-slate-500 dark:text-slate-400">Rp {{ number_format($mut->balance_after, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center">
                                @if($mut->is_reconciled)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-500/30">RECONCILED</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-500/30">UNMATCHED</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center flex items-center justify-center gap-2">
                                <form action="{{ route('bank-mutations.mutation.destroy', $mut->id) }}" method="POST" onsubmit="return confirm('Hapus catatan mutasi bank ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 rounded text-[10px] font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-500">Belum ada mutasi bank. Data akan muncul dari input pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $mutations->links() }}
        </div>
    </div>

    <!-- MODAL CREATE CONNECTION -->
    <div x-show="connModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="connModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Tambah Rekening Bank Baru</h3>
                <button @click="connModalOpen = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('bank-mutations.connection.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Bank</label>
                    <input type="text" name="bank_name" required placeholder="BCA / Mandiri / BNI / BRI" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Rekening</label>
                    <input type="text" name="account_number" required placeholder="8820192771" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Pemilik Rekening</label>
                    <input type="text" name="account_holder_name" required placeholder="PT Perusahaan Jaya" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Pilih Akun Kas/Bank (CoA)</label>
                    <select name="chart_of_account_id" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                        @foreach($chartOfAccounts as $coa)
                            <option value="{{ $coa->id }}">{{ $coa->account_code }} — {{ $coa->account_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="connModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-100 dark:bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-500">Simpan Rekening</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CREATE MUTATION -->
    <div x-show="mutationModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="mutationModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Input Mutasi Bank Manual</h3>
                <button @click="mutationModalOpen = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('bank-mutations.mutation.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Rekening Bank</label>
                    <select name="bank_connection_id" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                        @foreach($connections as $conn)
                            <option value="{{ $conn->id }}">{{ $conn->bank_name }} ({{ $conn->account_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tipe Mutasi</label>
                        <select name="mutation_type" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                            <option value="CR">CR (Kredit / Masuk)</option>
                            <option value="DB">DB (Debet / Keluar)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nominal (Rp)</label>
                        <input type="number" name="amount" required placeholder="500000" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Transaksi</label>
                    <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Mutasi</label>
                    <input type="text" name="description" required placeholder="Pembayaran Invoice #1021 Klien" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="mutationModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-100 dark:bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-500">Simpan Mutasi</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
