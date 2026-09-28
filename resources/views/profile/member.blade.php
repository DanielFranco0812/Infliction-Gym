<x-app-layout>
    @php
        $isActive = $user->membership_status === 'active';
    @endphp

    <div class="min-h-[calc(100vh-76px)] bg-[#111] text-white">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-5 border-b border-white/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.24em] text-red-500">Member profile</p>
                    <h1 class="text-3xl font-black sm:text-4xl">Your account</h1>
                    <p class="mt-2 text-gray-400">Manage your membership and personal details.</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center border border-white/20 px-5 py-3 text-sm font-bold text-white transition hover:border-red-500 hover:text-red-300">Edit account details</a>
            </div>

            <div class="grid gap-5 lg:grid-cols-[1.2fr_0.8fr]">
                <section class="border border-white/10 bg-[#171717] p-6 sm:p-8">
                    <div class="mb-8 flex items-center gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border border-red-500/40 bg-red-500/10 text-xl font-black text-red-400">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="min-w-0">
                            <h2 class="truncate text-2xl font-black">{{ $user->name }}</h2>
                            <p class="truncate text-sm text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                    <dl class="grid gap-5 border-t border-white/10 pt-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-[0.16em] text-gray-500">Member since</dt>
                            <dd class="mt-2 text-white">{{ $user->created_at->format('F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-[0.16em] text-gray-500">Email status</dt>
                            <dd class="mt-2 text-white">{{ $user->email_verified_at ? 'Verified' : 'Not verified' }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="border border-white/10 bg-[#171717] p-6 sm:p-8">
                    <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-red-400">Membership</p>
                    <h2 class="text-2xl font-black">{{ ucfirst($user->membership_plan ?? 'No plan selected') }}</h2>
                    <div class="mt-4 inline-flex items-center gap-2 border px-3 py-2 text-sm {{ $isActive ? 'border-green-500/30 bg-green-500/10 text-green-300' : 'border-red-500/30 bg-red-500/10 text-red-300' }}">
                        <span class="h-2 w-2 rounded-full {{ $isActive ? 'bg-green-400' : 'bg-red-400' }}"></span>
                        {{ ucfirst($user->membership_status ?? 'inactive') }}
                    </div>
                    <div class="mt-6 border-t border-white/10 pt-5">
                        <p class="text-sm text-gray-400">FITPASS credits</p>
                        <p class="mt-1 text-3xl font-black">{{ $user->fitpass_credits ?? 0 }}</p>
                    </div>
                    @if (! $isActive)
                        <a href="{{ route('dashboard') }}#membership" class="mt-6 inline-flex w-full items-center justify-center bg-red-600 px-5 py-3 text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:bg-red-500">Explore memberships</a>
                    @endif
                </section>
            </div>

            <section class="mt-5 flex flex-col gap-4 border border-white/10 bg-[#171717] p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
                <div>
                    <p class="text-lg font-bold">Ready for your next session?</p>
                    <p class="mt-1 text-sm text-gray-400">Browse group classes and coaching times.</p>
                </div>
                <a href="{{ route('classes.index') }}" class="inline-flex items-center justify-center border border-white/25 px-5 py-3 text-sm font-bold text-white transition hover:border-red-500 hover:text-red-300">Browse classes</a>
            </section>
        </div>
    </div>
</x-app-layout>
