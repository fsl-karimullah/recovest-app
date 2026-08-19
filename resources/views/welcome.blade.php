<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recovest — Automated Sales Reconciliation & AI Financial Audit Engine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 selection:bg-blue-600 selection:text-white">

    <!-- TOP ANNOUNCEMENT BAR -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 border-b border-blue-500/20 px-4 py-2 text-center text-xs font-medium text-slate-200">
        <span class="inline-flex items-center gap-1.5 font-bold text-blue-400">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Ekosistem Resmi:
        </span>
        Akademi UMKM (PT Tri Sinergi Digital) & PT Astra Solusi Digital
    </div>

    <!-- NAVBAR -->
    <nav class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between sticky top-0 z-50 bg-slate-950/80 backdrop-blur-md border-b border-slate-900">
        <a href="#" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-500 to-emerald-400 p-[1px] shadow-lg shadow-blue-500/20">
                <div class="w-full h-full bg-slate-950 rounded-[11px] flex items-center justify-center font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300 text-xl">
                    R
                </div>
            </div>
            <div>
                <div class="flex items-center gap-1.5">
                    <span class="font-extrabold text-white tracking-tight text-xl">Recovest</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-900/60 text-blue-300 border border-blue-500/30">AI Audit</span>
                </div>
                <span class="text-[10px] block text-slate-400 font-medium">Accounting & Bank Reconciliation</span>
            </div>
        </a>

        <!-- Nav Links -->
        <div class="hidden md:flex items-center space-x-8 text-xs font-semibold text-slate-300">
            <a href="#fitur" class="hover:text-blue-400 transition-colors">Fitur Rekonsiliasi</a>
            <a href="#produk" class="hover:text-blue-400 transition-colors">Produk Ecosystem</a>
            <a href="#tier" class="hover:text-blue-400 transition-colors">Tier Layanan</a>
            <a href="#faq" class="hover:text-blue-400 transition-colors">FAQ</a>
        </div>

        <!-- Auth CTAs -->
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-blue-600 hover:bg-blue-500 shadow-lg shadow-blue-600/30 transition-all">
                    Akses Dashboard →
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:text-white transition-colors">
                    Masuk / Login
                </a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-blue-600 hover:bg-blue-500 shadow-lg shadow-blue-600/30 transition-all">
                    Daftar Gratis
                </a>
            @endauth
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="max-w-5xl mx-auto px-6 pt-20 pb-16 text-center space-y-8 relative">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20">
            <span>✨ Platform Otomasi Rekonsiliasi Penjualan & Bank No. 1 di Indonesia</span>
        </div>

        <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-[1.15]">
            Otomasi Rekonsiliasi Penjualan & <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-300 to-emerald-400">
                AI Financial Audit Engine
            </span>
        </h1>

        <p class="text-base sm:text-lg text-slate-300 max-w-3xl mx-auto leading-relaxed">
            Eliminasi kebocoran kas, selisih QRIS, dan biaya MDR tersembunyi. Bandingkan laporan POS Kasir dengan Mutasi Bank BCA, Mandiri, & BRI secara otomatis dalam hitungan detik.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-blue-600 to-emerald-500 hover:from-blue-500 hover:to-emerald-400 shadow-xl shadow-blue-600/30 transition-all">
                    Masuk ke Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-blue-600 to-emerald-500 hover:from-blue-500 hover:to-emerald-400 shadow-xl shadow-blue-600/30 transition-all">
                    Mulai Uji Coba Gratis →
                </a>
            @endauth

            <a href="https://wa.me/6281234567890?text=Halo%20Recovest,%20saya%20ingin%20konsultasi%20mengenai%20otomasi%20rekonsiliasi%20penjualan." target="_blank" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold text-sm text-emerald-400 bg-emerald-950/60 hover:bg-emerald-900/80 border border-emerald-500/40 transition-all flex items-center justify-center gap-2">
                <span>💬 Konsultasi WA Sales</span>
            </a>
        </div>
    </section>

    <!-- SUPPORTED INTEGRATIONS BAR -->
    <section class="max-w-6xl mx-auto px-6 py-8 border-y border-slate-900">
        <p class="text-center text-xs font-semibold text-slate-500 uppercase tracking-widest mb-6">Mendukung Integrasi Multi-Channel Pembayaran & Bank Indonesia</p>
        <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-12 opacity-80">
            <span class="text-sm font-bold text-slate-300">🏦 Bank BCA</span>
            <span class="text-sm font-bold text-slate-300">🏦 Bank Mandiri</span>
            <span class="text-sm font-bold text-slate-300">🏦 Bank BRI</span>
            <span class="text-sm font-bold text-slate-300">🏦 Bank BNI</span>
            <span class="text-sm font-bold text-slate-300">📲 QRIS All Issuer</span>
            <span class="text-sm font-bold text-slate-300">💳 EDC Credit Card</span>
            <span class="text-sm font-bold text-slate-300">💵 Cash Kasir Toko</span>
        </div>
    </section>

    <!-- PRODUCT ECOSYSTEM CARDS -->
    <section id="produk" class="max-w-6xl mx-auto px-6 py-20">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold text-blue-400 uppercase tracking-wider">Dual Core SaaS Platform</span>
            <h2 class="text-3xl font-extrabold text-white">2 Ekosistem Utama Recovest</h2>
            <p class="text-xs text-slate-400">Dirancang khusus untuk UMKM & B2B yang menginginkan laporan keuangan bersih, akurat, dan siap diaudit.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Product 1: Recovest Recon -->
            <div class="bg-slate-900/90 p-8 rounded-3xl border border-blue-500/30 relative overflow-hidden flex flex-col justify-between hover:border-blue-500/60 transition-all shadow-xl">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 text-2xl font-black mb-6">
                        ⚡
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Recovest Recon</h3>
                    <p class="text-xs text-blue-400 font-bold uppercase tracking-wider mb-4">AI Bank Reconciliation & Instant Matching Engine</p>
                    <p class="text-xs text-slate-300 leading-relaxed mb-6">
                        Engine pemroses data otomatis yang membandingkan laporan POS Kasir (Moka, Olsera, Majoo, Pawoon) dengan e-Statement mutasi BCA/Mandiri dalam hitungan detik.
                    </p>

                    <ul class="space-y-3 text-xs text-slate-300 mb-8">
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-400 font-bold">✓</span> Auto-match POS Sales CSV vs BCA Bank Mutation
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-400 font-bold">✓</span> Deteksi Otomatis Potongan MDR QRIS 0.7%
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-400 font-bold">✓</span> Peringatan Selisih Fisik Kas Kasir & Settlement EDC CC
                        </li>
                    </ul>
                </div>

                <a href="#tier" class="w-full py-3 rounded-xl text-xs font-bold text-center text-white bg-blue-600 hover:bg-blue-500 transition-colors">
                    Lihat Fitur Rekonsiliasi →
                </a>
            </div>

            <!-- Product 2: Recovest Finance -->
            <div class="bg-slate-900/90 p-8 rounded-3xl border border-emerald-500/30 relative overflow-hidden flex flex-col justify-between hover:border-emerald-500/60 transition-all shadow-xl">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-2xl font-black mb-6">
                        📈
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Recovest Finance</h3>
                    <p class="text-xs text-emerald-400 font-bold uppercase tracking-wider mb-4">Multi-Account Bookkeeping & Cashflow Health Check</p>
                    <p class="text-xs text-slate-300 leading-relaxed mb-6">
                        Sistem pembukuan dan akuntansi double-entry berbasis ACID. Menyajikan pencatatan kas masuk/keluar, ringkasan saldo multi-rekening, serta proyeksi kesehatan arus kas powered by Gemini AI.
                    </p>

                    <ul class="space-y-3 text-xs text-slate-300 mb-8">
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-400 font-bold">✓</span> Double-Entry General Ledger System (Debet/Kredit Balanced)
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-400 font-bold">✓</span> Live Sync Multi-Rekening Bank dalam 1 Dashboard
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-emerald-400 font-bold">✓</span> Laporan Keuangan Siap Diaudit (PSAK Audit-Ready)
                        </li>
                    </ul>
                </div>

                <a href="#tier" class="w-full py-3 rounded-xl text-xs font-bold text-center text-slate-950 bg-emerald-400 hover:bg-emerald-300 transition-colors">
                    Pelajari Recovest Finance →
                </a>
            </div>
        </div>
    </section>

    <!-- TIERED SERVICE ROADMAP & PRICING -->
    <section id="tier" class="max-w-6xl mx-auto px-6 py-20 border-t border-slate-900">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
            <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Monetization & Growth Roadmap</span>
            <h2 class="text-3xl font-extrabold text-white">Tier Layanan Recovest</h2>
            <p class="text-xs text-slate-400">Pilih tingkat solusi yang sesuai dengan skala bisnis Anda — dari pencocokan gratis hingga audit resmi Akuntan Publik (CPA).</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Tier 1: Starter -->
            <div class="bg-slate-900 p-8 rounded-3xl border border-slate-800 flex flex-col justify-between">
                <div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-slate-800 text-slate-300 uppercase">Tahap 1 — Starter</span>
                    <h3 class="text-xl font-bold text-white mt-4 mb-1">Rekonsiliasi Penjualan</h3>
                    <p class="text-xs text-slate-400 mb-6">Pencocokan CSV POS Kasir vs BCA untuk mendeteksi selisih kasir mandiri.</p>
                    <div class="mb-6 pb-6 border-b border-slate-800">
                        <span class="text-3xl font-black text-white">Rp 0</span>
                        <span class="text-xs text-slate-400 ml-1">/ selamanya (Free MVP)</span>
                    </div>

                    <ul class="space-y-3 text-xs text-slate-300 mb-8">
                        <li>✓ Upload CSV POS vs BCA Mutation</li>
                        <li>✓ Verifikasi QRIS, Cash, CC, Transfer</li>
                        <li>✓ Deteksi Selisih & Estimasi MDR 0.7%</li>
                    </ul>
                </div>

                <a href="{{ route('register') }}" class="w-full py-3 text-center rounded-xl text-xs font-bold text-slate-200 bg-slate-800 hover:bg-slate-700 border border-slate-700">
                    Gunakan Gratis
                </a>
            </div>

            <!-- Tier 2: Paid License (Highlighted) -->
            <div class="bg-slate-900 p-8 rounded-3xl border-2 border-blue-500 relative flex flex-col justify-between shadow-2xl shadow-blue-500/20">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-600 text-white uppercase tracking-wider">Most Popular</span>
                <div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-blue-950 text-blue-300 border border-blue-500/40 uppercase">Tahap 2 — Paid License</span>
                    <h3 class="text-xl font-bold text-white mt-4 mb-1">Analisis Keuangan AI & Pak Donny</h3>
                    <p class="text-xs text-slate-300 mb-6">Cashflow Health Check Gemini AI & sesi penasihat keuangan senior Pak Donny.</p>
                    <div class="mb-6 pb-6 border-b border-slate-800">
                        <span class="text-3xl font-black text-white">Lisensi B2B</span>
                        <span class="text-xs text-blue-300 block mt-1">Pengawasan Kas & AI Advisory</span>
                    </div>

                    <ul class="space-y-3 text-xs text-slate-200 mb-8">
                        <li>✓ <strong>Semua Fitur Tahap 1</strong></li>
                        <li>✓ <strong>Cashflow Health Check Powered by Gemini AI</strong></li>
                        <li>✓ Analisis Tren Kebocoran Kas & Kebijakan Kasir</li>
                        <li>✓ <strong>Sesi Konsultasi Eksklusif bersama Pak Donny</strong></li>
                    </ul>
                </div>

                <a href="https://wa.me/6281234567890?text=Halo%20Recovest,%20saya%20tertarik%20paket%20Tahap%202%20Analisis%20AI%20%26%20Pak%20Donny." target="_blank" class="w-full py-3 text-center rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 shadow-lg shadow-blue-600/30">
                    Konsultasi Paket Tahap 2 →
                </a>
            </div>

            <!-- Tier 3: Enterprise Audit -->
            <div class="bg-slate-900 p-8 rounded-3xl border border-slate-800 flex flex-col justify-between">
                <div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-950 text-indigo-300 border border-indigo-500/30 uppercase">Tahap 3 — Enterprise Audit</span>
                    <h3 class="text-xl font-bold text-white mt-4 mb-1">Certified Audit Statement</h3>
                    <p class="text-xs text-slate-400 mb-6">Pemeriksaan independen oleh Certified Public Accountant (CPA) untuk perbankan & investor.</p>
                    <div class="mb-6 pb-6 border-b border-slate-800">
                        <span class="text-3xl font-black text-white">Custom</span>
                        <span class="text-xs text-slate-400 block mt-1">Official Certified CPA Audit</span>
                    </div>

                    <ul class="space-y-3 text-xs text-slate-300 mb-8">
                        <li>✓ Penerbitan Official Financial Statement</li>
                        <li>✓ Audit Laporan Laba Rugi & Neraca PSAK</li>
                        <li>✓ Pemeriksaan Akuntan Publik Terdaftar (CPA)</li>
                    </ul>
                </div>

                <a href="https://wa.me/6281234567890?text=Halo%20Recovest,%20saya%20ingin%20konsultasi%20Tahap%203%20Enterprise%20Audit%20CPA." target="_blank" class="w-full py-3 text-center rounded-xl text-xs font-bold text-slate-200 bg-slate-800 hover:bg-slate-700 border border-slate-700">
                    Hubungi Tim Audit
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="max-w-4xl mx-auto px-6 py-20 border-t border-slate-900">
        <div class="text-center mb-12 space-y-2">
            <h2 class="text-2xl font-extrabold text-white">Pertanyaan Sering Diajukan (FAQ)</h2>
            <p class="text-xs text-slate-400">Informasi penting mengenai keamanan data, akuntansi, dan kompatibilitas bank.</p>
        </div>

        <div x-data="{ active: null }" class="space-y-4 text-xs">
            <div class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
                <button @click="active = active === 1 ? null : 1" class="w-full font-bold text-left text-white flex justify-between items-center text-sm">
                    <span>Apakah data transaksi dan mutasi bank kami aman di Recovest?</span>
                    <span x-text="active === 1 ? '−' : '+'" class="text-blue-400 font-bold text-base"></span>
                </button>
                <div x-show="active === 1" class="mt-3 text-slate-300 leading-relaxed border-t border-slate-800/60 pt-3">
                    Ya, sangat aman. Recovest menggunakan enkripsi data standar industri 256-bit SSL/TLS baik pada saat pengiriman data maupun penyimpanan. Seluruh database diisolasi berbasis organisasi (multi-tenancy) sehingga data keuangan bisnis Anda terlindungi secara penuh.
                </div>
            </div>

            <div class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
                <button @click="active = active === 2 ? null : 2" class="w-full font-bold text-left text-white flex justify-between items-center text-sm">
                    <span>Sistem POS (Kasir) apa saja yang bisa terhubung dengan Recovest?</span>
                    <span x-text="active === 2 ? '−' : '+'" class="text-blue-400 font-bold text-base"></span>
                </button>
                <div x-show="active === 2" class="mt-3 text-slate-300 leading-relaxed border-t border-slate-800/60 pt-3">
                    Recovest mendukung format export CSV/XLSX dari berbagai aplikasi POS terkemuka seperti Moka POS, Olsera, Majoo, Pawoon, iSeller, hingga sistem kasir kustom perusahaan B2B Anda.
                </div>
            </div>

            <div class="bg-slate-900 rounded-2xl border border-slate-800 p-5">
                <button @click="active = active === 3 ? null : 3" class="w-full font-bold text-left text-white flex justify-between items-center text-sm">
                    <span>Apakah hasil otomasi engine ini sah untuk audit perbankan/pajak?</span>
                    <span x-text="active === 3 ? '−' : '+'" class="text-blue-400 font-bold text-base"></span>
                </button>
                <div x-show="active === 3" class="mt-3 text-slate-300 leading-relaxed border-t border-slate-800/60 pt-3">
                    Hasil otomasi engine Recovest berfungsi sebagai verifikasi internal dan penyusunan pembukuan harian yang rapi (audit-ready). Untuk penerbitan Laporan Audit Resmi yang sah secara hukum dan perbankan, Anda dapat mengambil paket <strong>Tahap 3 Enterprise Audit</strong> yang melibatkan Akuntan Publik Terdaftar (CPA).
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="max-w-7xl mx-auto px-6 py-12 border-t border-slate-900 text-center text-xs text-slate-500 space-y-2">
        <p class="font-semibold text-slate-400">© 2026 Recovest — Developed by Akademi UMKM (PT Tri Sinergi Digital) & PT Astra Solusi Digital.</p>
        <p>Solusi Otomasi Rekonsiliasi Penjualan & Sistem Pembukuan Akuntansi Modern Indonesia.</p>
    </footer>

</body>
</html>
