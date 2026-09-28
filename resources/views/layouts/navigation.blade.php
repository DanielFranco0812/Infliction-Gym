<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-white/10 bg-[#101010]/95 text-white backdrop-blur">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-[76px] justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 text-sm font-black tracking-[0.24em] text-white">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full border border-red-500/50 bg-red-500/10 text-red-500">I</span>
                        INFLICTION
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="ms-10 hidden items-center gap-7 xl:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')">
                        Classes
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard') . '#membership'" :active="false">
                        Membership
                    </x-nav-link>
                    <x-nav-link :href="route('member.profile')" :active="request()->routeIs('member.profile', 'profile.edit')">
                        My Profile
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard') . '#support'" :active="false">
                        Help
                    </x-nav-link>
                    <x-nav-link :href="url('/')" :active="false">
                        Gym site
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden items-center gap-4 xl:flex">
                <span class="max-w-40 truncate text-sm text-gray-300">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="border border-white/15 px-4 py-2 text-xs font-bold uppercase tracking-[0.12em] text-gray-300 transition hover:border-red-500 hover:text-white">Log out</button>
                </form>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center xl:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md border border-white/10 p-2 text-gray-300 transition hover:border-red-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden xl:hidden">
        <div class="space-y-1 border-t border-white/10 px-4 pb-4 pt-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')">
                Classes
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard') . '#membership'" :active="false">
                Membership
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('member.profile')" :active="request()->routeIs('member.profile', 'profile.edit')">
                My Profile
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard') . '#support'" :active="false">Help</x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/')" :active="false">Visit the gym site</x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-white/10 px-4 pb-4 pt-4">
            <div>
                <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Account settings
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        Log out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
