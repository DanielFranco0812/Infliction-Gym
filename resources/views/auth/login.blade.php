<x-guest-layout>
    <div>
        <div class="mb-8">
            <p class="mb-3 text-xs font-bold uppercase tracking-[0.22em] text-red-400">Member access</p>
            <h1 class="text-3xl font-black leading-tight text-white sm:text-4xl">Welcome back.</h1>
            <p class="mt-3 text-sm leading-relaxed text-gray-400">Sign in for group fitness bookings, member services, and online purchases.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-gray-200">Email address</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" class="h-12 w-full rounded-sm border border-white/15 bg-[#1a1a1a] px-4 text-white placeholder:text-gray-500 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <div class="mb-2 flex items-center justify-between gap-3">
                    <label for="password" class="text-sm font-semibold text-gray-200">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs font-medium text-gray-400 transition hover:text-red-400" href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" class="h-12 w-full rounded-sm border border-white/15 bg-[#1a1a1a] px-4 text-white placeholder:text-gray-500 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <label for="remember_me" class="flex cursor-pointer items-center gap-3 text-sm text-gray-400">
                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-white/20 bg-[#1a1a1a] text-red-500 focus:ring-red-500">
                Keep me signed in
            </label>

            <div class="space-y-5 pt-1">
                <button type="submit" class="w-full bg-red-600 px-5 py-4 text-sm font-bold uppercase tracking-[0.12em] text-white shadow-[0_0_24px_rgba(239,68,68,0.2)] transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-[#111]">
                    Sign in
                </button>
                <p class="text-center text-sm text-gray-400">New to Infliction Gym? <a class="font-semibold text-white transition hover:text-red-400" href="{{ route('register') }}">Create an account</a></p>
            </div>
        </form>
    </div>
</x-guest-layout>
