<x-guest-layout>
    <div class="px-5 py-6 md:px-8 md:py-7">
        <div class="mb-4 text-center text-sm text-gray-400">Join the Infliction Gym community</div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="flex items-center gap-4 border-t border-[#3a3a3a] pt-3">
                <label for="name" class="w-[95px] text-[11px] font-medium uppercase tracking-[0.12em] text-gray-300">Name</label>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" class="flex-1 h-10 border border-[#4a4a4a] bg-[#1d1d1d] text-white placeholder:text-gray-400 focus:outline-none focus:ring-0 focus:border-red-500" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />

            <div class="flex items-center gap-4 border-t border-[#3a3a3a] pt-3">
                <label for="email" class="w-[95px] text-[11px] font-medium uppercase tracking-[0.12em] text-gray-300">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" class="flex-1 h-10 border border-[#4a4a4a] bg-[#1d1d1d] text-white placeholder:text-gray-400 focus:outline-none focus:ring-0 focus:border-red-500" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />

            <div class="flex items-center gap-4 border-t border-[#3a3a3a] pt-3">
                <label for="password" class="w-[95px] text-[11px] font-medium uppercase tracking-[0.12em] text-gray-300">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="flex-1 h-10 border border-[#4a4a4a] bg-[#1d1d1d] text-white placeholder:text-gray-400 focus:outline-none focus:ring-0 focus:border-red-500" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />

            <div class="flex items-center gap-4 border-t border-[#3a3a3a] pt-3">
                <label for="password_confirmation" class="w-[95px] text-[11px] font-medium uppercase tracking-[0.12em] text-gray-300">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="flex-1 h-10 border border-[#4a4a4a] bg-[#1d1d1d] text-white placeholder:text-gray-400 focus:outline-none focus:ring-0 focus:border-red-500" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />

            <div class="border-t border-[#3a3a3a] pt-3">
                <label for="terms" class="flex items-start gap-3 text-sm leading-6 text-gray-300">
                    <input id="terms" type="checkbox" name="terms" value="1" required @checked(old('terms')) class="mt-1 h-4 w-4 shrink-0 accent-red-600">
                    <span>I have read and agree to the <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener" class="text-red-400 underline hover:text-red-300">Terms of Service</a>.</span>
                </label>
                <x-input-error :messages="$errors->get('terms')" class="mt-1" />
                <p class="mt-2 pl-7 text-xs text-gray-400"><a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener" class="underline hover:text-white">Read the Privacy Notice</a></p>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a class="text-sm text-gray-400 hover:text-red-400 transition" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <button type="submit" class="bg-[#e63946] hover:bg-[#d62b3c] text-white font-bold uppercase tracking-[0.12em] px-5 py-3 text-[11px] rounded-sm border border-[#f05a68]">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
