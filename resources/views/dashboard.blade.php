<x-app-layout>
    @php
        $displayName = trim(Auth::user()->name);
        $firstName = $displayName !== '' ? explode(' ', $displayName)[0] : 'Member';
        $status = ucfirst(Auth::user()->membership_status ?? 'inactive');
        $plan = ucfirst(Auth::user()->membership_plan ?? 'none');
        $isActive = strtolower(Auth::user()->membership_status ?? 'inactive') === 'active';
    @endphp

    <div class="min-h-screen bg-[#111111] text-white">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <p role="status" class="mb-6 border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-200">{{ session('status') }}</p>
            @endif
            @error('payment')
                <p role="alert" class="mb-6 border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">{{ $message }}</p>
            @enderror
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="mb-2 text-sm uppercase tracking-[0.28em] text-red-500">Member Portal</p>
                    <h1 class="text-3xl font-black text-white sm:text-4xl">Hi, {{ $firstName }}.</h1>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full border {{ $isActive ? 'border-green-500/40 bg-green-500/10 text-green-300' : 'border-red-500/40 bg-red-500/10 text-red-300' }} px-4 py-2 text-sm">
                    <span class="h-2.5 w-2.5 rounded-full {{ $isActive ? 'bg-green-400' : 'bg-red-500' }}"></span>
                    {{ $isActive ? 'Active member' : 'Inactive member' }}
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3 mb-10">
                <div class="rounded-3xl border border-white/10 bg-[#171717] p-6 shadow-[0_20px_50px_rgba(0,0,0,0.25)]">
                    <p class="mb-2 text-xs uppercase tracking-[0.3em] text-red-500">Current Status</p>
                    <h2 class="text-3xl font-black text-white">{{ $plan }}</h2>
                    <p class="mt-3 text-gray-300">Status: <span class="font-semibold text-red-400">{{ $status }}</span></p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-[#171717] p-6 shadow-[0_20px_50px_rgba(0,0,0,0.25)]">
                    <p class="mb-2 text-xs uppercase tracking-[0.3em] text-red-500">FITPASS Credits</p>
                    <h2 class="text-3xl font-black text-white">{{ Auth::user()->fitpass_credits ?? 0 }}</h2>
                    <p class="mt-3 text-gray-300">Available credits</p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-[#171717] p-6 shadow-[0_20px_50px_rgba(0,0,0,0.25)]">
                    <p class="mb-2 text-xs uppercase tracking-[0.3em] text-red-500">Account</p>
                    <h2 class="text-xl font-bold text-white break-all">{{ Auth::user()->email }}</h2>
                    <p class="mt-3 text-gray-300">Member since {{ Auth::user()->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            @if(Auth::user()->membership_status === 'inactive')
                <section id="membership" class="mb-10 scroll-mt-28">
                    <div class="mb-6">
                        <p class="mb-3 text-sm uppercase tracking-[0.25em] text-red-500">Choose your plan</p>
                        <h2 class="text-3xl font-black text-white sm:text-4xl">Activate your membership today.</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-3">
                        <div class="rounded-3xl border-2 border-red-500 bg-[#1d1d1d] p-6 shadow-[0_0_30px_rgba(239,68,68,0.2)]">
                            <p class="mb-3 text-xs uppercase tracking-[0.28em] text-red-500">Base</p>
                            <h3 class="mb-2 text-2xl font-black text-white">Base Membership</h3>
                            <div class="mb-5 flex items-end gap-2">
                                <span class="text-4xl font-black text-white">PHP 2,650</span>
                                <span class="text-gray-400">/month</span>
                            </div>
                            <ul class="mb-6 space-y-2 text-gray-300">
                                <li>• Zero Joining Fee</li>
                                <li>• Access to all equipment</li>
                                <li>• Locker room access</li>
                            </ul>
                            <form method="POST" action="{{ route('membership.upgrade') }}" data-loading-label="Opening secure checkout...">
                                @csrf
                                <input type="hidden" name="plan" value="Base">
                                <button type="submit" class="w-full rounded-full bg-red-600 px-4 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-red-500">
                                    Get Started
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-[#1a1a1a] p-6">
                            <p class="mb-3 text-xs uppercase tracking-[0.28em] text-red-500">Premium</p>
                            <h3 class="mb-2 text-2xl font-black text-white">Premium Membership</h3>
                            <div class="mb-5 flex items-end gap-2">
                                <span class="text-4xl font-black text-white">PHP 2,800</span>
                                <span class="text-gray-400">/month</span>
                            </div>
                            <ul class="mb-6 space-y-2 text-gray-300">
                                <li>• Premium equipment access</li>
                                <li>• Priority coaching</li>
                                <li>• Member perks</li>
                            </ul>
                            <form method="POST" action="{{ route('membership.upgrade') }}" data-loading-label="Opening secure checkout...">
                                @csrf
                                <input type="hidden" name="plan" value="Premium">
                                <button type="submit" class="w-full rounded-full bg-red-600 px-4 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-red-500">
                                    Upgrade
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-[#1a1a1a] p-6">
                            <p class="mb-3 text-xs uppercase tracking-[0.28em] text-red-500">Flexible</p>
                            <h3 class="mb-2 text-2xl font-black text-white">FITPASS</h3>
                            <div class="mb-5 flex items-end gap-2">
                                <span class="text-4xl font-black text-white">PHP 483</span>
                                <span class="text-gray-400">/credit</span>
                            </div>
                            <ul class="mb-6 space-y-2 text-gray-300">
                                <li>• No contracts</li>
                                <li>• Pay-as-you-go</li>
                                <li>• Flexible workout access</li>
                            </ul>
                            <form method="POST" action="{{ route('membership.fitpass') }}" data-loading-label="Opening secure checkout...">
                                @csrf
                                <input type="hidden" name="credits" value="1">
                                <button type="submit" class="w-full rounded-full border border-white/30 px-4 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:border-red-500 hover:text-red-500">
                                    Buy Credit
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            @else
                <section id="membership" class="mb-10 scroll-mt-28 border border-white/10 bg-[#171717] p-6 sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-[0.2em] text-red-400">Current membership</p>
                            <h2 class="text-2xl font-black">{{ $plan }} plan</h2>
                            <p class="mt-2 text-sm text-gray-400">Your membership is active. Browse classes and coaching sessions below.</p>
                        </div>
                        <a href="{{ route('classes.index') }}" class="inline-flex items-center justify-center border border-white/20 px-5 py-3 text-sm font-bold text-white transition hover:border-red-500 hover:text-red-300">Find a class</a>
                    </div>
                </section>
            @endif

            <section id="classes" class="mb-8 scroll-mt-28">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="mb-2 text-xs font-bold uppercase tracking-[0.24em] text-red-500">Train with a coach</p>
                        <h2 class="text-2xl font-black text-white sm:text-3xl">Classes & coaching</h2>
                    </div>
                    <a href="{{ route('classes.index') }}" class="text-sm font-semibold text-gray-300 transition hover:text-red-400">View full schedule <span aria-hidden="true">→</span></a>
                </div>

                @if($schedules->isNotEmpty())
                    <div class="grid gap-4 md:grid-cols-3">
                        @foreach($schedules as $schedule)
                            <article class="border border-white/10 bg-[#171717] p-5">
                                <p class="mb-3 text-xs font-bold uppercase tracking-[0.18em] text-red-400">{{ $schedule->day }}</p>
                                <h3 class="text-lg font-bold text-white">{{ $schedule->class_name }}</h3>
                                <p class="mt-2 text-sm text-gray-300">{{ $schedule->time_from }}–{{ $schedule->time_to }}</p>
                                <p class="mt-3 text-sm text-gray-400">{{ $schedule->coach->name }} · {{ $schedule->coach->specialty }}</p>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="border border-white/10 bg-[#171717] p-6 text-sm text-gray-300">
                        The class schedule is being updated. Check back soon or <a href="{{ route('classes.index') }}" class="font-semibold text-red-400 hover:text-red-300">view the schedule page</a>.
                    </div>
                @endif
            </section>

            <section id="support" class="border border-white/10 bg-[#171717] p-6 sm:p-8">
                <h3 class="mb-3 text-2xl font-black text-white">Need Help?</h3>
                <p class="text-gray-300">For billing issues or account problems, please email <a href="mailto:InflictionGym@gmail.com" class="text-red-400 hover:text-red-300">InflictionGym@gmail.com</a>.</p>
            </section>
        </div>
    </div>
</x-app-layout>
