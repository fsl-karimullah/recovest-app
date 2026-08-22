<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Daftar Akun Recovest Finance</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Buat akun baru untuk mengelola pembukuan & rekonsiliasi bank perusahaan Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap Pemilik Bisnis</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Faisal Karimullah" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            @if ($errors->get('name'))
                <p class="mt-1 text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Company / Organization Name -->
        <div>
            <label for="company_name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Perusahaan / Perhitungan Usaha</label>
            <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required placeholder="PT Sumber Berkah Mandiri / Toko Maju Jaya" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            @if ($errors->get('company_name'))
                <p class="mt-1 text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $errors->first('company_name') }}</p>
            @endif
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Email Bisnis</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="faisal@perusahaan.com" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            @if ($errors->get('email'))
                <p class="mt-1 text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi (Minimal 8 Karakter)</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            @if ($errors->get('password'))
                <p class="mt-1 text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            @if ($errors->get('password_confirmation'))
                <p class="mt-1 text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $errors->first('password_confirmation') }}</p>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full py-3 rounded-xl font-extrabold text-xs text-white bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-600/30 transition-all">
                Daftar Akun Sekarang →
            </button>
        </div>
    </form>

    <!-- Login Link Section -->
    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Sudah memiliki akun Recovest?</p>
        <a href="{{ route('login') }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">
            Masuk / Login ke Akun Anda →
        </a>
    </div>
</x-guest-layout>
