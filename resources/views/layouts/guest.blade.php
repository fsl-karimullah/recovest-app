<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Recovest Finance') }} — Authentication</title>

    <!-- Fonts & Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans antialiased selection:bg-blue-600 selection:text-white">
    <div class="min-h-screen flex flex-col justify-center items-center p-6 relative">
        
        <!-- Ambient Gradient Background -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Brand Logo Header -->
        <div class="mb-8 text-center space-y-2 z-10">
            <a href="/" class="inline-flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-emerald-400 flex items-center justify-center font-black text-white text-xl shadow-lg shadow-blue-500/20">
                    R
                </div>
                <div class="text-left">
                    <span class="font-extrabold text-white tracking-wide text-xl block">RECOVEST</span>
                    <span class="text-[10px] block text-blue-400 font-semibold uppercase tracking-wider">Accounting & Reconciliation</span>
                </div>
            </a>
        </div>

        <!-- Auth Card Container -->
        <div class="w-full sm:max-w-md bg-slate-900/90 border border-slate-800 backdrop-blur-md p-8 rounded-3xl shadow-2xl z-10">
            {{ $slot }}
        </div>

        <!-- Footer Note -->
        <div class="mt-8 text-center text-xs text-slate-500">
            © 2026 Recovest — Akademi UMKM & PT Astra Solusi Digital.
        </div>
    </div>
</body>
</html>
