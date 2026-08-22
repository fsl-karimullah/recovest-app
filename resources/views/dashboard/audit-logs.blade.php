@extends('layouts.app')

@section('title', 'Audit Trail & Log — Recovest Finance')
@section('page-title', 'Modul Audit Trail & Log Riwayat Aktivitas')

@section('content')
<div class="space-y-6">

    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h3 class="text-base font-bold text-slate-900 dark:text-white">Log Otorisasi & Kepatuhan Keuangan</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400">Pencatatan riwayat setiap perubahan data transaksi kas, faktur, dan rekonsiliasi untuk pencegahan *fraud* internal.</p>
    </div>

    <!-- LOGS TABLE -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-950 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="p-3.5">Waktu (Timestamp)</th>
                        <th class="p-3.5">User</th>
                        <th class="p-3.5">Modul</th>
                        <th class="p-3.5">Tindakan (Action)</th>
                        <th class="p-3.5">Deskripsi Perubahan</th>
                        <th class="p-3.5">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                            <td class="p-3.5 font-mono text-slate-500 dark:text-slate-400">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="p-3.5 font-bold text-slate-900 dark:text-white">{{ $log->user->name ?? 'System' }}</td>
                            <td class="p-3.5 font-semibold text-blue-600 dark:text-blue-400">{{ $log->module }}</td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-700 dark:text-slate-300">{{ $log->description }}</td>
                            <td class="p-3.5 font-mono text-slate-500">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-500">Belum ada catatan log aktivitas. Catatan akan terisi otomatis saat pengguna bertransaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $logs->links() }}
        </div>
    </div>

</div>
@endsection
