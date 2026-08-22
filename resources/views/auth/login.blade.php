<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-white">Masuk ke Recovest Finance</h2>
        <p class="text-xs text-slate-400 mt-1">Masukkan kredensial akun Anda untuk mengelola kas & rekonsiliasi.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-xs font-semibold text-emerald-400 bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/30">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@recovest.id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            @if ($errors->get('email'))
                <p class="mt-1 text-xs text-rose-400 font-semibold">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-300 mb-1">Kata Sandi (Password)</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            @if ($errors->get('password'))
                <p class="mt-1 text-xs text-rose-400 font-semibold">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 text-xs text-slate-400 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-800 bg-slate-950 text-blue-600 focus:ring-blue-500">
                <span>Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-blue-400 hover:underline" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full py-3 rounded-xl font-extrabold text-xs text-white bg-blue-600 hover:bg-blue-500 shadow-lg shadow-blue-600/30 transition-all">
                Masuk / Login →
            </button>
        </div>
    </form>

    <!-- Register Link Section -->
    <div class="mt-6 pt-6 border-t border-slate-800 text-center">
        <p class="text-xs text-slate-400 mb-3">Belum memiliki akun Recovest Finance?</p>
        <a href="{{ route('register') }}" class="inline-block w-full py-2.5 rounded-xl font-bold text-xs text-emerald-400 bg-emerald-950/60 hover:bg-emerald-900/80 border border-emerald-500/40 transition-colors">
            Daftar Akun Baru (Register)
        </a>
    </div>
</x-guest-layout>
