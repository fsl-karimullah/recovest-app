<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Recovest Finance — Enterprise Accounting & Bank Reconciliation')</title>

    <!-- Fonts & Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* Fix Browser Autofill Contrast */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #f8fafc !important;
            -webkit-box-shadow: 0 0 0px 1000px #020617 inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }
        html:not(.dark) input:-webkit-autofill,
        html:not(.dark) input:-webkit-autofill:hover, 
        html:not(.dark) input:-webkit-autofill:focus {
            -webkit-text-fill-color: #0f172a !important;
            -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex min-h-screen transition-colors duration-200">

    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col justify-between hidden md:flex shrink-0">
        <div>
            <!-- Brand -->
            <div class="h-16 flex items-center px-6 border-b border-slate-200 dark:border-slate-800">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-emerald-400 flex items-center justify-center font-bold text-white shadow-lg">
                        R
                    </div>
                    <div>
                        <span class="font-extrabold text-slate-900 dark:text-white tracking-wide text-lg">RECOVEST</span>
                        <span class="text-[10px] block text-blue-600 dark:text-blue-400 font-semibold uppercase tracking-wider">Enterprise ERP</span>
                    </div>
                </a>
            </div>

            <!-- Nav Links -->
            <nav class="p-4 space-y-1 text-xs">
                <div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Keuangan & Kas</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-500/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400 border border-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard Overview
                </a>

                <a href="{{ route('bank-mutations.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-colors {{ request()->routeIs('bank-mutations.*') ? 'bg-blue-500/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400 border border-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                    Rekening & Mutasi Bank
                </a>

                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-colors {{ request()->routeIs('transactions.*') ? 'bg-blue-500/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400 border border-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Transaksi Kas Utama
                </a>

                <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Modul Enterprise</div>

                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-colors {{ request()->routeIs('reports.*') ? 'bg-blue-500/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400 border border-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Laporan Keuangan (PSAK)
                </a>

                <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-colors {{ request()->routeIs('invoices.*') ? 'bg-blue-500/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400 border border-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Faktur Penjualan (Invoice)
                </a>

                <a href="{{ route('bills.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-colors {{ request()->routeIs('bills.*') ? 'bg-blue-500/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400 border border-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Tagihan Vendor (Bills)
                </a>

                <a href="{{ route('assets.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-colors {{ request()->routeIs('assets.*') ? 'bg-blue-500/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400 border border-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    Aset Tetap & Penyusutan
                </a>

                <a href="{{ route('audit-logs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition-colors {{ request()->routeIs('audit-logs.*') ? 'bg-blue-500/10 text-blue-600 dark:bg-blue-600/20 dark:text-blue-400 border border-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Audit Trail & Log
                </a>
            </nav>
        </div>

        <!-- Footer / Authenticated User Info -->
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
            <div class="bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 px-3 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800/40 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar / Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-16 bg-white/80 dark:bg-slate-900/60 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-6 flex items-center justify-between sticky top-0 z-20">
            <h1 class="text-lg font-bold text-slate-900 dark:text-white">@yield('page-title', 'Dashboard Overview')</h1>
            
            <div class="flex items-center gap-3">
                <!-- Theme Switcher Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" type="button" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-xs font-bold flex items-center gap-1.5">
                    <span x-show="!darkMode">🌙 Dark Mode</span>
                    <span x-show="darkMode">☀️ Light Mode</span>
                </button>

                <span class="text-xs text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 font-semibold">
                    🏢 {{ auth()->user()->organization->name ?? $organization->name ?? 'Perusahaan Saya' }} (IDR)
                </span>
            </div>
        </header>

        <!-- Flash Alert -->
        @if(session('success'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
