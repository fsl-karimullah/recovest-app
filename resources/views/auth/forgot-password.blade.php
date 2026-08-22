<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Lupa Kata Sandi (Password)</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
            Masukkan alamat email terdaftar Anda. Kami akan mengirimkan tautan reset kata sandi langsung ke email Anda.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/30">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat Email Terdaftar</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@recovest.id" class="w-full bg-white dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            @if ($errors->get('email'))
                <p class="mt-1 text-xs text-rose-500 dark:text-rose-400 font-semibold">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 rounded-xl font-extrabold text-xs text-white bg-blue-600 hover:bg-blue-500 shadow-lg shadow-blue-600/30 transition-all">
                Kirim Tautan Reset Password →
            </button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
        <a href="{{ route('login') }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">
            ← Kembali ke Halaman Login
        </a>
    </div>
</x-guest-layout>
