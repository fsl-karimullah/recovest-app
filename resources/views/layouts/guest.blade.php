<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Recovest Finance') }} — Authentication</title>

    <!-- Fonts & Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* Fix Browser Autofill Background & Text Contrast */
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
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased selection:bg-blue-600 selection:text-white transition-colors duration-200">
    <div class="min-h-screen flex flex-col justify-center items-center p-6 relative">
        
        <!-- Theme Toggle Button -->
        <div class="absolute top-6 right-6 z-20">
            <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" type="button" class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors shadow-sm flex items-center gap-2 text-xs font-bold">
                <span x-show="!darkMode">🌙 Dark Mode</span>
                <span x-show="darkMode">☀️ Light Mode</span>
            </button>
        </div>

        <!-- Ambient Gradient Background -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Brand Logo Header -->
        <div class="mb-8 text-center space-y-2 z-10">
            <a href="/" class="inline-flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-emerald-400 flex items-center justify-center font-black text-white text-xl shadow-lg shadow-blue-500/20">
                    R
                </div>
                <div class="text-left">
                    <span class="font-extrabold text-slate-900 dark:text-white tracking-wide text-xl block">RECOVEST</span>
                    <span class="text-[10px] block text-blue-600 dark:text-blue-400 font-semibold uppercase tracking-wider">Accounting & Reconciliation</span>
                </div>
            </a>
        </div>

        <!-- Auth Card Container -->
        <div class="w-full sm:max-w-md bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 backdrop-blur-md p-8 rounded-3xl shadow-xl dark:shadow-2xl z-10">
            {{ $slot }}
        </div>

        <!-- Footer Note -->
        <div class="mt-8 text-center text-xs text-slate-500">
            © 2026 Recovest — Akademi UMKM & PT Astra Solusi Digital.
        </div>
    </div>
</body>
</html>
