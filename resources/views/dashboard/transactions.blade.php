@extends('layouts.app')

@section('title', 'Kelola Transaksi Kas — Recovest Finance')
@section('page-title', 'Modul Kelola Transaksi Kas & Bookkeeping')

@section('content')
<div x-data="{ 
    createModal: false, 
    editModal: false, 
    deleteModal: false,
    editTrx: { id: '', transaction_date: '', type: 'INCOME', amount: 0, chart_of_account_id: '', category: '', contact_name: '' },
    deleteId: '',
    deleteNumber: ''
}" class="space-y-6">

    <!-- HEADER & MODAL TRIGGER -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Catatan Arus Kas Utama</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pencatatan & CRUD Pemasukan / Pengeluaran Kas dengan Double-Entry Journal balance otomatis.</p>
        </div>

        <button @click="createModal = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-xs bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
            <span>+ Catat Transaksi Baru</span>
        </button>
    </div>

    <!-- TAB FILTER & DATE RANGE -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Tabs -->
        <div class="flex items-center gap-2 w-full md:w-auto overflow-x-auto">
            <a href="{{ route('transactions.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ !request('type') ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                [Semua]
            </a>
            <a href="{{ route('transactions.index', ['type' => 'INCOME']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('type') === 'INCOME' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                [Pemasukan / Income]
            </a>
            <a href="{{ route('transactions.index', ['type' => 'EXPENSE']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('type') === 'EXPENSE' ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                [Pengeluaran / Expense]
            </a>
        </div>

        <!-- Date Range Filter -->
        <form action="{{ route('transactions.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
            @if(request('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-slate-300 focus:outline-none focus:border-blue-500">
            <span class="text-xs text-slate-500">s/d</span>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-900 dark:text-slate-300 focus:outline-none focus:border-blue-500">
            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700">Filter</button>
        </form>
    </div>

    <!-- TRANSACTIONS TABLE -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">No Transaksi</th>
                        <th class="p-3.5">Tanggal</th>
                        <th class="p-3.5">Tipe</th>
                        <th class="p-3.5">Kategori</th>
                        <th class="p-3.5">Rekening / CoA</th>
                        <th class="p-3.5 text-right">Nominal</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-center">Bukti Bayar</th>
                        <th class="p-3.5 text-center">Aksi (Update / Delete)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="p-3.5 font-mono text-blue-600 dark:text-blue-400 font-bold">{{ $trx->transaction_number }}</td>
                            <td class="p-3.5 font-mono text-slate-500 dark:text-slate-400">{{ $trx->transaction_date->format('d/m/Y') }}</td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-0.5 rounded font-extrabold text-[10px] {{ $trx->type === 'INCOME' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400' }}">
                                    {{ $trx->type }}
                                </span>
                            </td>
                            <td class="p-3.5 font-semibold text-slate-900 dark:text-slate-200">{{ $trx->category }}</td>
                            <td class="p-3.5 text-slate-500 dark:text-slate-400">{{ $trx->chartOfAccount->account_name ?? '-' }}</td>
                            <td class="p-3.5 text-right font-bold text-slate-900 dark:text-white">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 border border-blue-500/30">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                @if($trx->proof_attachment_path)
                                    <a href="{{ asset('storage/' . $trx->proof_attachment_path) }}" target="_blank" class="text-blue-600 dark:text-blue-400 underline font-semibold text-[11px]">Lihat Nota</a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="
                                        editTrx = {
                                            id: '{{ $trx->id }}',
                                            transaction_date: '{{ $trx->transaction_date->format('Y-m-d') }}',
                                            type: '{{ $trx->type }}',
                                            amount: {{ $trx->amount }},
                                            chart_of_account_id: '{{ $trx->chart_of_account_id }}',
                                            category: '{{ addslashes($trx->category) }}',
                                            contact_name: '{{ addslashes($trx->contact_name ?? '') }}'
                                        };
                                        editModal = true;
                                    " class="px-2 py-1 rounded text-[10px] font-bold text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/40">
                                        ✏️ Edit
                                    </button>

                                    <button @click="
                                        deleteId = '{{ $trx->id }}';
                                        deleteNumber = '{{ $trx->transaction_number }}';
                                        deleteModal = true;
                                    " class="px-2 py-1 rounded text-[10px] font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40">
                                        🗑️ Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-slate-500">Belum ada data transaksi. Tekan tombol + Catat Transaksi Baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- MODAL CREATE TRANSAKSI -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="createModal = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-lg rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Input Transaksi Kas Baru</h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Transaksi</label>
                        <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tipe Transaksi</label>
                        <select name="type" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                            <option value="INCOME">Pemasukan (Income)</option>
                            <option value="EXPENSE">Pengeluaran (Expense)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" required placeholder="Contoh: 5000000" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Rekening / Account (CoA)</label>
                    <select name="chart_of_account_id" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                        @foreach($chartOfAccounts as $coa)
                            <option value="{{ $coa->id }}">{{ $coa->account_code }} — {{ $coa->account_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori</label>
                        <input type="text" name="category" required placeholder="Penjualan / Operasional" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kontak (Klien / Vendor)</label>
                        <input type="text" name="contact_name" placeholder="PT Sumber Mas" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Upload Bukti Nota / Struk (Optional)</label>
                    <input type="file" name="proof_attachment" accept="image/*,.pdf" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs text-slate-500">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-100 dark:bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-500">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT TRANSAKSI -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="editModal = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-lg rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Edit Transaksi Kas</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form :action="'{{ url('/dashboard/transactions') }}/' + editTrx.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Transaksi</label>
                        <input type="date" name="transaction_date" required x-model="editTrx.transaction_date" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tipe Transaksi</label>
                        <select name="type" required x-model="editTrx.type" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                            <option value="INCOME">Pemasukan (Income)</option>
                            <option value="EXPENSE">Pengeluaran (Expense)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" required x-model="editTrx.amount" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Rekening / Account (CoA)</label>
                    <select name="chart_of_account_id" required x-model="editTrx.chart_of_account_id" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                        @foreach($chartOfAccounts as $coa)
                            <option value="{{ $coa->id }}">{{ $coa->account_code }} — {{ $coa->account_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori</label>
                        <input type="text" name="category" required x-model="editTrx.category" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kontak (Klien / Vendor)</label>
                        <input type="text" name="contact_name" x-model="editTrx.contact_name" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-100 dark:bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-500">Update Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DELETE TRANSAKSI -->
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="deleteModal = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-md rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Konfirmasi Hapus Transaksi</h3>
                <button @click="deleteModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <p class="text-xs text-slate-700 dark:text-slate-300">
                Apakah Anda yakin ingin menghapus transaksi <strong class="text-blue-600 dark:text-blue-400" x-text="deleteNumber"></strong>?
                Dampak saldo pada buku besar akan dikembalikan secara otomatis.
            </p>

            <form :action="'{{ url('/dashboard/transactions') }}/' + deleteId" method="POST" class="pt-2 flex justify-end gap-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-100 dark:bg-slate-800">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-500">Hapus Transaksi</button>
            </form>
        </div>
    </div>

</div>
@endsection
