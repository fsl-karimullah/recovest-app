@extends('layouts.app')

@section('title', 'Aset Tetap & Penyusutan — Recovest Finance')
@section('page-title', 'Modul Manajemen Aset Tetap & Penyusutan Garis Lurus')

@section('content')
<div x-data="{ modalOpen: false }" class="space-y-6">

    <!-- METRICS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Harga Perolehan Aset</p>
            <h3 class="text-2xl font-extrabold text-white">Rp {{ number_format($totalAssetCost, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800">
            <p class="text-xs font-semibold text-slate-400 uppercase">Akumulasi Penyusutan</p>
            <h3 class="text-2xl font-extrabold text-rose-400">Rp {{ number_format($totalDepreciation, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800">
            <p class="text-xs font-semibold text-slate-400 uppercase">Nilai Buku Bersih (Book Value)</p>
            <h3 class="text-2xl font-extrabold text-emerald-400">Rp {{ number_format($totalBookValue, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- HEADER & MODAL TRIGGER -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-900 p-5 rounded-2xl border border-slate-800">
        <div>
            <h3 class="text-base font-bold text-white">Daftar Aset Tetap Perusahaan</h3>
            <p class="text-xs text-slate-400">Kalkulasi Penyusutan Metode Garis Lurus (Straight-Line Method) Otomatis.</p>
        </div>

        <button @click="modalOpen = true" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-xs bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
            <span>+ Tambah Aset Tetap</span>
        </button>
    </div>

    <!-- ASSETS TABLE -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-950 text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">Kode Aset</th>
                        <th class="p-3.5">Nama Aset</th>
                        <th class="p-3.5">Tgl Pembelian</th>
                        <th class="p-3.5 text-right">Harga Perolehan</th>
                        <th class="p-3.5 text-center">Umur (Thn)</th>
                        <th class="p-3.5 text-right">Akumulasi Penyusutan</th>
                        <th class="p-3.5 text-right">Nilai Buku</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-slate-800/40">
                            <td class="p-3.5 font-mono text-blue-400 font-bold">{{ $asset->asset_code }}</td>
                            <td class="p-3.5 font-bold text-white">{{ $asset->asset_name }}</td>
                            <td class="p-3.5 font-mono text-slate-400">{{ $asset->purchase_date->format('d/m/Y') }}</td>
                            <td class="p-3.5 text-right text-slate-200">Rp {{ number_format($asset->purchase_cost, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-center font-bold text-slate-300">{{ $asset->useful_life_years }} Tahun</td>
                            <td class="p-3.5 text-right text-rose-400">Rp {{ number_format($asset->accumulated_depreciation, 0, ',', '.') }}</td>
                            <td class="p-3.5 text-right font-bold text-emerald-400">Rp {{ number_format($asset->book_value, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-500">Belum ada aset tetap terdaftar. Tekan tombol + Tambah Aset Tetap.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL CREATE ASSET -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div @click.away="modalOpen = false" class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-2xl p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white">Registrasi Aset Tetap Baru</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-white">✕</button>
            </div>

            <form action="{{ route('assets.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Nama Aset / Perangkat</label>
                    <input type="text" name="asset_name" required placeholder="Komputer Server Kasir / Kendaraan Operasional" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Harga Perolehan (Rp)</label>
                        <input type="number" name="purchase_cost" required placeholder="15000000" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Tanggal Pembelian</label>
                        <input type="date" name="purchase_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Umur Ekonomis (Tahun)</label>
                        <input type="number" name="useful_life_years" required value="5" min="1" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Nilai Sisa / Residu (Rp)</label>
                        <input type="number" name="salvage_value" value="0" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white">
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 bg-slate-800">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-500">Daftarkan Aset</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
