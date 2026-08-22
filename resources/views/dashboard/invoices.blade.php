@extends('layouts.app')

@section('title', 'Faktur Penjualan (Invoice) — Recovest Finance')
@section('page-title', 'Modul Faktur Penjualan & Perhitungan PPN 11%')

@section('content')
<div x-data="{ modalOpen: false, editModalOpen: false, editInv: {} }" class="space-y-6">

    <!-- HEADER & MODAL TRIGGER -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Kelola Faktur Penjualan (Invoice B2B)</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Penerbitan & CRUD Invoice Klien dengan Perhitungan PPN 11% Otomatis.</p>
        </div>

        <button @click="modalOpen = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-xs bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
            <span>+ Buat Invoice Baru</span>
        </button>
    </div>

    <!-- INVOICES TABLE -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">No Invoice</th>
                        <th class="p-3.5">Nama Klien</th>
                        <th class="p-3.5">Tgl Terbit</th>
                        <th class="p-3.5">Jatuh Tempo</th>
                        <th class="p-3.5 text-right">Subtotal</th>
                        <th class="p-3.5 text-right">PPN (11%)</th>
                        <th class="p-3.5 text-right">Total Tagihan</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-center">Aksi CRUD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="p-3.5 font-mono text-blue-600 dark:text-blue-400 font-bold">{{ $inv->invoice_number }}</td>
                            <td class="p-3.5 font-bold text-slate-900 dark:text-white">{{ $inv->client_name }}</td>
                            <td class="p-3.5 font-mono text-slate-500 dark:text-slate-400">{{ $inv->issue_date->format('d/m/Y') }}</td>
                            <td class="p-3.5 font-mono text-slate-500 dark:text-slate-400">{{ $inv->due_date->format('d/m/Y') }}</td>
                            <td class="p-3.5 text-right text-slate-700 dark:text-slate-300">Rp {{ number_format($inv->subtotal, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-right text-emerald-600 dark:text-emerald-400">Rp {{ number_format($inv->tax_amount, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-right font-bold text-slate-900 dark:text-white">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $inv->status === 'PAID' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-500/30' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-500/30' }}">
                                    {{ $inv->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center flex items-center justify-center gap-2">
                                @if($inv->status === 'UNPAID')
                                    <form action="{{ route('invoices.pay', $inv->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 rounded text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-600/10 hover:bg-emerald-100 dark:hover:bg-emerald-600/20 border border-emerald-500/30">
                                            ✓ Lunas
                                        </button>
                                    </form>
                                @endif

                                <button @click="editInv = {{ json_encode($inv) }}; editModalOpen = true" class="px-2 py-1 rounded text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40">
                                    Edit
                                </button>

                                <form action="{{ route('invoices.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice {{ $inv->invoice_number }}?');">
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
                            <td colspan="9" class="p-6 text-center text-slate-500">Belum ada invoice diterbitkan. Tekan tombol + Buat Invoice Baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- MODAL CREATE INVOICE -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="modalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-lg rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Terbitkan Invoice Baru</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('invoices.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Klien / Perusahaan B2B</label>
                    <input type="text" name="client_name" required placeholder="PT Nusantara Jaya" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Terbit</label>
                        <input type="date" name="issue_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Jatuh Tempo</label>
                        <input type="date" name="due_date" required value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Subtotal (Rp)</label>
                        <input type="number" name="subtotal" required placeholder="10000000" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tarif PPN (%)</label>
                        <input type="number" name="tax_rate" value="11" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-100 dark:bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-500">Terbitkan Invoice</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT INVOICE -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="editModalOpen = false" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 w-full max-w-lg rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Edit Invoice <span x-text="editInv.invoice_number"></span></h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form :action="'/dashboard/invoices/' + editInv.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Klien / Perusahaan B2B</label>
                    <input type="text" name="client_name" x-model="editInv.client_name" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Terbit</label>
                        <input type="date" name="issue_date" x-model="editInv.issue_date" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Jatuh Tempo</label>
                        <input type="date" name="due_date" x-model="editInv.due_date" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Subtotal (Rp)</label>
                        <input type="number" name="subtotal" x-model="editInv.subtotal" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Tarif PPN (%)</label>
                        <input type="number" name="tax_rate" x-model="editInv.tax_rate" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 bg-slate-100 dark:bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-500">Perbarui Invoice</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
