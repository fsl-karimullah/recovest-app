@extends('layouts.app')

@section('title', 'Tagihan Vendor — Recovest Finance')
@section('page-title', 'Modul Tagihan Vendor & Pengeluaran Pembelian')

@section('content')
<div x-data="{ modalOpen: false }" class="space-y-6">

    <!-- HEADER & MODAL TRIGGER -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-900 p-5 rounded-2xl border border-slate-800">
        <div>
            <h3 class="text-base font-bold text-white">Tagihan Vendor & Utang Usaha (Bills)</h3>
            <p class="text-xs text-slate-400">Pencatatan & Pelunasan Tagihan Pemasok / Vendor Operasional.</p>
        </div>

        <button @click="modalOpen = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-xs bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
            <span>+ Catat Tagihan Vendor</span>
        </button>
    </div>

    <!-- BILLS TABLE -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">No Tagihan</th>
                        <th class="p-3.5">Nama Vendor</th>
                        <th class="p-3.5">Kategori</th>
                        <th class="p-3.5">Tgl Tagihan</th>
                        <th class="p-3.5">Jatuh Tempo</th>
                        <th class="p-3.5 text-right">Total Tagihan</th>
                        <th class="p-3.5 text-center">Status</th>
                        <th class="p-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($bills as $bill)
                        <tr class="hover:bg-slate-800/40">
                            <td class="p-3.5 font-mono text-blue-400 font-bold">{{ $bill->bill_number }}</td>
                            <td class="p-3.5 font-bold text-white">{{ $bill->vendor_name }}</td>
                            <td class="p-3.5 font-semibold text-slate-300">{{ $bill->category }}</td>
                            <td class="p-3.5 font-mono text-slate-400">{{ $bill->bill_date->format('d/m/Y') }}</td>
                            <td class="p-3.5 font-mono text-slate-400">{{ $bill->due_date->format('d/m/Y') }}</td>
                            <td class="p-3.5 text-right font-bold text-white">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $bill->status === 'PAID' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' }}">
                                    {{ $bill->status }}
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                @if($bill->status === 'PENDING')
                                    <form action="{{ route('bills.pay', $bill->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold text-emerald-400 bg-emerald-600/10 hover:bg-emerald-600/20 border border-emerald-500/30">
                                            ✓ Pelunasan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-500 text-[10px]">Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-slate-500">Belum ada tagihan vendor. Tekan tombol + Catat Tagihan Vendor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-800">
            {{ $bills->links() }}
        </div>
    </div>

    <!-- MODAL CREATE BILL -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="modalOpen = false" class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white">Catat Tagihan Vendor Baru</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('bills.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Nama Vendor / Pemasok</label>
                    <input type="text" name="vendor_name" required placeholder="PT AWS Cloud Indonesia" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Kategori Pengeluaran</label>
                        <input type="text" name="category" required placeholder="Server Cloud / Gaji / Operasional" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Total Tagihan (Rp)</label>
                        <input type="number" name="total_amount" required placeholder="5000000" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Tanggal Tagihan</label>
                        <input type="date" name="bill_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Jatuh Tempo</label>
                        <input type="date" name="due_date" required value="{{ date('Y-m-d', strtotime('+14 days')) }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-500">Simpan Tagihan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
