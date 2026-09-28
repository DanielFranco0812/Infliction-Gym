<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Infliction Gym - Magalang, Pampanga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #121212; color: #fff; }
        #chat-launcher { position: fixed; bottom: 20px; right: 20px; background: #e63946; color: #fff; border: none; padding: 15px 22px; border-radius: 30px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
        #chat-modal { display: none; position: fixed; bottom: 80px; right: 20px; width: 340px; height: 460px; background: #1e1e1e; border-radius: 12px; border: 1px solid #333; flex-direction: column; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,0.5); }
        .chat-header { background: #e63946; padding: 14px; font-weight: bold; }
        .chat-body { flex: 1; padding: 12px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
        .msg { padding: 8px 12px; border-radius: 8px; max-width: 80%; font-size: 14px; line-height: 1.4; }
        .user { background: #e63946; align-self: flex-end; }
        .assistant { background: #2a2a2a; border: 1px solid #333; align-self: flex-start; }
        .chat-footer { padding: 10px; display: flex; gap: 6px; background: #181818; }
        .chat-footer input { flex: 1; padding: 8px; border-radius: 4px; border: 1px solid #444; background: #222; color: #fff; }
        .chat-footer button { padding: 8px 14px; background: #e63946; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body id="top" class="bg-[#121212] text-white antialiased">
    @php
        $displayName = Auth::check() ? trim(Auth::user()->name) : '';
        $firstName = $displayName !== '' ? explode(' ', $displayName)[0] : 'Member';
    @endphp

    <div class="min-h-screen bg-[#121212] text-white">
        <header class="sticky top-0 z-50 border-b border-white/10 bg-[#121212]/95 backdrop-blur-sm shadow-[0_10px_30px_rgba(0,0,0,0.25)]">
            <nav class="mx-auto max-w-[1500px] px-5 sm:px-6 lg:px-8">
                <div class="flex h-24 items-center justify-between gap-4 lg:h-28">
                    <div class="flex shrink-0 items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full border border-red-500/40 bg-red-500/10 text-sm font-black text-red-500">I</div>
                        <div class="text-[1.1rem] font-black tracking-[0.28em] text-white sm:text-[1.25rem]">INFLICTION</div>
                    </div>

                    <div class="hidden w-[650px] shrink-0 items-center justify-center gap-6 text-[12px] font-semibold uppercase tracking-[0.08em] text-gray-300 2xl:flex">
                        <a href="#memberships" class="whitespace-nowrap transition hover:text-red-500" data-i18n="nav.memberships">Memberships</a>
                        <a href="#personal-training" class="whitespace-nowrap transition hover:text-red-500" data-i18n="nav.personal">Personal Training</a>
                        <a href="#fitness-first" class="whitespace-nowrap transition hover:text-red-500" data-i18n="nav.fitness">Why Fitness First</a>
                        <a href="#classes" class="whitespace-nowrap transition hover:text-red-500" data-i18n="nav.classes">Classes</a>
                        <a href="#highlights" class="whitespace-nowrap transition hover:text-red-500" data-i18n="nav.highlights">Highlights</a>
                    </div>

                    <div class="hidden shrink-0 items-center gap-3 2xl:flex">
                        <button class="group relative flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-[#1d1d1d] text-white transition hover:border-red-500 hover:text-red-500" aria-label="Favorites">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s-6-4.35-9-9.25C1.01 9.58 3.12 5 7.5 5c2.2 0 3.5 1.03 4.5 2.2C13 6.03 14.3 5 16.5 5 20.88 5 22.99 9.58 21 11.75 18 16.65 12 21 12 21Z" /></svg>
                            <span role="tooltip" class="pointer-events-none invisible absolute left-1/2 top-full z-[60] mt-2 -translate-x-1/2 whitespace-nowrap rounded-md border border-white/10 bg-[#252525] px-3 py-2 text-xs font-medium normal-case tracking-normal text-white opacity-0 shadow-xl transition-opacity group-hover:visible group-hover:opacity-100 group-focus-visible:visible group-focus-visible:opacity-100">Favorites</span>
                        </button>
                        <a href="{{ auth()->check() ? route('profile.edit') : route('login') }}" class="group relative flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-[#1d1d1d] text-white transition hover:border-red-500 hover:text-red-500" aria-label="{{ auth()->check() ? 'My profile' : 'Sign in' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Zm-8.25 12c.5-2.25 2.56-4 5-4s4.5 1.75 5 4" /></svg>
                            <span role="tooltip" class="pointer-events-none invisible absolute left-1/2 top-full z-[60] mt-2 -translate-x-1/2 whitespace-nowrap rounded-md border border-white/10 bg-[#252525] px-3 py-2 text-xs font-medium normal-case tracking-normal text-white opacity-0 shadow-xl transition-opacity group-hover:visible group-hover:opacity-100 group-focus-visible:visible group-focus-visible:opacity-100">{{ auth()->check() ? 'My profile' : 'Sign in' }}</span>
                        </a>

                        @guest
                            <a href="{{ route('register') }}" class="whitespace-nowrap bg-red-600 px-7 py-3.5 text-[12px] font-bold uppercase tracking-[0.12em] text-white shadow-[0_0_20px_rgba(239,68,68,0.35)] transition hover:bg-red-500 lg:px-8" data-i18n="nav.join">Join Online</a>
                        @endguest

                        <div class="relative ml-1">
                            <select id="language-switcher" class="w-36 appearance-none rounded-xl border border-white/10 bg-[#1d1d1d] px-3 py-2.5 pr-8 text-sm font-semibold text-white outline-none transition focus:border-red-500">
                                <option value="en">English</option>
                                <option value="id">Bahasa Indonesia</option>
                                <option value="th">ภาษาไทย</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" /></svg>
                            </div>
                        </div>
                    </div>

                    <button id="mobile-menu-button" class="group relative inline-flex items-center justify-center rounded-full border border-white/10 bg-[#1d1d1d] p-2 text-white 2xl:hidden" aria-label="Toggle menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span role="tooltip" class="pointer-events-none invisible absolute left-1/2 top-full z-[60] mt-2 -translate-x-1/2 whitespace-nowrap rounded-md border border-white/10 bg-[#252525] px-3 py-2 text-xs font-medium normal-case tracking-normal text-white opacity-0 shadow-xl transition-opacity group-hover:visible group-hover:opacity-100 group-focus-visible:visible group-focus-visible:opacity-100">Toggle navigation menu</span>
                    </button>
                </div>

                <div id="mobile-menu" class="hidden border-t border-white/10 pb-4 pt-3 2xl:hidden">
                    <div class="space-y-2 text-sm uppercase tracking-[0.15em] text-gray-300">
                        <a href="#memberships" class="block rounded-xl px-3 py-2 hover:bg-white/5" data-i18n="nav.memberships">Memberships</a>
                        <a href="#personal-training" class="block rounded-xl px-3 py-2 hover:bg-white/5" data-i18n="nav.personal">Personal Training</a>
                        <a href="#fitness-first" class="block rounded-xl px-3 py-2 hover:bg-white/5" data-i18n="nav.fitness">Why Fitness First</a>
                        <a href="#classes" class="block rounded-xl px-3 py-2 hover:bg-white/5" data-i18n="nav.classes">Classes</a>
                        <a href="#highlights" class="block rounded-xl px-3 py-2 hover:bg-white/5" data-i18n="nav.highlights">Highlights</a>
                        @guest
                            <a href="{{ route('register') }}" class="mt-2 block w-full rounded-xl bg-red-600 px-4 py-3 text-white" data-i18n="nav.join">Join Online</a>
                        @endguest
                    </div>
                </div>
            </nav>
        </header>

        <main>
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1600&q=80')] bg-cover bg-center opacity-30"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/80 to-black/30"></div>
                <div class="relative max-w-7xl mx-auto px-6 py-24 md:py-32 grid md:grid-cols-2 gap-10 items-center">
                    <div>
                        <p class="text-red-500 uppercase tracking-[0.25em] text-sm font-bold mb-5" data-hero-tag>Magalang's Premium Fitness Destination</p>
                        <h1 class="text-5xl md:text-7xl font-black leading-none tracking-tight mb-6">
                            <span data-hero-title>Push Your Limits at</span><br>
                            <span class="text-red-500" data-hero-highlight>Magalang's</span> <span data-hero-end>Premium Gym</span>
                        </h1>
                        <p class="text-lg text-gray-300 max-w-xl mb-8" data-hero-subtitle>
                            World-class facilities, expert coaching, and zero joining fees.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            @guest
                                <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-500 text-white font-bold px-7 py-3 rounded-full uppercase tracking-wide transition" data-hero-primary>
                                    Claim 1-Day Free Trial
                                </a>
                            @endguest

                            @auth
                                <a href="{{ route('dashboard') }}" class="bg-red-600 hover:bg-red-500 text-white font-bold px-7 py-3 rounded-full uppercase tracking-wide transition" data-hero-primary>
                                    Go to My Dashboard
                                </a>
                            @endauth

                            <a href="#memberships" class="border border-white/40 text-white hover:border-red-500 hover:text-red-500 px-7 py-3 rounded-full uppercase tracking-wide transition" data-hero-secondary>
                                View Memberships
                            </a>
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5 shadow-2xl backdrop-blur-sm">
                            <div class="rounded-2xl overflow-hidden border border-red-500/30">
                                <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=80" alt="Gym members training" fetchpriority="high" decoding="async" class="w-full h-[420px] object-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="personal-training" class="scroll-mt-24 border-y border-white/10 bg-[#101010] py-20 md:py-24">
                <div class="mx-auto grid max-w-7xl items-center gap-10 px-6 lg:grid-cols-2 lg:gap-16">
                    <div class="relative min-h-[360px] overflow-hidden border border-white/10 md:min-h-[500px]">
                        <img src="https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=1100&q=85" alt="Personal coach guiding a gym member through a strength workout" loading="lazy" decoding="async" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/10"></div>
                        <p class="absolute bottom-6 left-6 text-xs font-bold uppercase tracking-[0.2em] text-white">One coach. Your goals. Your pace.</p>
                        <span class="absolute right-5 top-5 border border-white/30 bg-black/40 px-3 py-2 text-xs font-bold uppercase tracking-[0.16em] text-white" data-i18n="personal.imageLabel">Personal training</span>
                    </div>

                    <div class="py-2">
                        <p class="mb-4 text-xs font-bold uppercase tracking-[0.25em] text-red-500" data-i18n="personal.label">Personal coaching</p>
                        <h2 class="max-w-xl text-4xl font-black leading-tight sm:text-5xl" data-i18n="personal.title">A plan shaped around you.</h2>
                        <p class="mt-6 max-w-xl text-base leading-relaxed text-gray-300" data-i18n="personal.description">Work one-on-one with a coach to improve your technique, build a routine, and stay focused on the goals that matter to you. Each session can meet you at your experience level and pace.</p>

                        <div class="mt-8 grid gap-5 border-y border-white/10 py-6 sm:grid-cols-3">
                            <div>
                                <p class="mb-2 text-xs font-bold tracking-[0.14em] text-red-400">01</p>
                                <h3 class="font-bold text-white" data-i18n="personal.point1.title">Goal-led sessions</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-400" data-i18n="personal.point1.text">Keep each workout connected to your next milestone.</p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs font-bold tracking-[0.14em] text-red-400">02</p>
                                <h3 class="font-bold text-white" data-i18n="personal.point2.title">Confident technique</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-400" data-i18n="personal.point2.text">Get practical coaching on form and movement.</p>
                            </div>
                            <div>
                                <p class="mb-2 text-xs font-bold tracking-[0.14em] text-red-400">03</p>
                                <h3 class="font-bold text-white" data-i18n="personal.point3.title">Progress you can build on</h3>
                                <p class="mt-2 text-sm leading-relaxed text-gray-400" data-i18n="personal.point3.text">Adjust your training as your strength grows.</p>
                            </div>
                        </div>

                        <div class="mt-7 flex flex-wrap gap-3">
                            <button onclick="triggerChat('Hi, I would like to ask about personal training.')" class="bg-red-600 px-6 py-3.5 text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:bg-red-500" data-i18n="personal.cta">Talk to a coach</button>
                            <a href="#memberships" class="border border-white/25 px-6 py-3.5 text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:border-red-500 hover:text-red-400" data-i18n="personal.secondary">Compare plans</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="training-goals" class="scroll-mt-24 bg-[#141414] py-20 md:py-24">
                <div class="mx-auto max-w-7xl px-6">
                    <div class="mb-12 text-center">
                        <p class="mb-3 text-xs font-bold uppercase tracking-[0.24em] text-red-500" data-i18n="goals.label">Personal training</p>
                        <h2 class="text-4xl font-black sm:text-5xl" data-i18n="goals.title">Reach your fitness goals.</h2>
                        <p class="mx-auto mt-4 max-w-2xl leading-relaxed text-gray-400" data-i18n="goals.subtitle">Different goals call for different kinds of training. Find a direction that feels right for you.</p>
                    </div>

                    <div class="grid gap-x-5 gap-y-8 sm:grid-cols-2 xl:grid-cols-4">
                        <article class="group">
                            <div class="relative h-64 overflow-hidden bg-[#202020]" style="clip-path: polygon(8% 0, 100% 0, 92% 100%, 0 100%)">
                                <img src="https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=700&q=85" alt="" data-i18n-alt="goals.leaner.alt" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/55 to-transparent"></div>
                            </div>
                            <div class="relative -mt-12 mx-3 min-h-52 border-t-2 border-red-500 bg-[#202020] p-6 shadow-xl" style="clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%)">
                                <p class="mb-3 text-xs font-bold tracking-[0.16em] text-red-400">01</p>
                                <h3 class="text-2xl font-black uppercase" data-i18n="goals.leaner.title">Leaner</h3>
                                <p class="mt-4 text-sm leading-relaxed text-gray-300" data-i18n="goals.leaner.text">Build a steady routine around strength, conditioning, and the goals you set with your coach.</p>
                            </div>
                        </article>

                        <article class="group">
                            <div class="relative h-64 overflow-hidden bg-[#202020]" style="clip-path: polygon(8% 0, 100% 0, 92% 100%, 0 100%)">
                                <img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=700&q=85" alt="" data-i18n-alt="goals.wellbeing.alt" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/55 to-transparent"></div>
                            </div>
                            <div class="relative -mt-12 mx-3 min-h-52 border-t-2 border-red-500 bg-[#202020] p-6 shadow-xl" style="clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%)">
                                <p class="mb-3 text-xs font-bold tracking-[0.16em] text-red-400">02</p>
                                <h3 class="text-2xl font-black uppercase" data-i18n="goals.wellbeing.title">Well-being</h3>
                                <p class="mt-4 text-sm leading-relaxed text-gray-300" data-i18n="goals.wellbeing.text">Make movement a regular part of your week with sessions for energy, balance, and confidence.</p>
                            </div>
                        </article>

                        <article class="group">
                            <div class="relative h-64 overflow-hidden bg-[#202020]" style="clip-path: polygon(8% 0, 100% 0, 92% 100%, 0 100%)">
                                <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=700&q=85" alt="" data-i18n-alt="goals.athletic.alt" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/55 to-transparent"></div>
                            </div>
                            <div class="relative -mt-12 mx-3 min-h-52 border-t-2 border-red-500 bg-[#202020] p-6 shadow-xl" style="clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%)">
                                <p class="mb-3 text-xs font-bold tracking-[0.16em] text-red-400">03</p>
                                <h3 class="text-2xl font-black uppercase" data-i18n="goals.athletic.title">Athletic</h3>
                                <p class="mt-4 text-sm leading-relaxed text-gray-300" data-i18n="goals.athletic.text">Work on stamina, coordination, and movement with focused training and challenging classes.</p>
                            </div>
                        </article>

                        <article class="group">
                            <div class="relative h-64 overflow-hidden bg-[#202020]" style="clip-path: polygon(8% 0, 100% 0, 92% 100%, 0 100%)">
                                <img src="https://images.unsplash.com/photo-1534367610401-9f5ed68180aa?auto=format&fit=crop&w=700&q=85" alt="" data-i18n-alt="goals.stronger.alt" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/55 to-transparent"></div>
                            </div>
                            <div class="relative -mt-12 mx-3 min-h-52 border-t-2 border-red-500 bg-[#202020] p-6 shadow-xl" style="clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%)">
                                <p class="mb-3 text-xs font-bold tracking-[0.16em] text-red-400">04</p>
                                <h3 class="text-2xl font-black uppercase" data-i18n="goals.stronger.title">Stronger</h3>
                                <p class="mt-4 text-sm leading-relaxed text-gray-300" data-i18n="goals.stronger.text">Build strength with guidance on form, resistance, and a pace that works for you.</p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section id="classes" class="max-w-7xl mx-auto px-6 py-20">
                <div class="text-center mb-12">
                    <p class="text-red-500 uppercase tracking-[0.25em] text-sm font-bold mb-3" data-i18n="section.offer.label">What We Offer</p>
                    <h2 class="text-4xl md:text-5xl font-black" data-i18n="section.offer.title">Train Smarter. Perform Better.</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-[#1a1a1a] border border-white/10 rounded-3xl p-7 hover:border-red-500 transition">
                        <div class="w-14 h-14 rounded-full bg-red-600/15 flex items-center justify-center mb-5 text-2xl">🏋️</div>
                        <h3 class="text-2xl font-bold mb-3" data-i18n="card.group.title">Group Classes</h3>
                        <p class="text-gray-300 leading-relaxed" data-i18n="card.group.text">BodyCombat, Yoga, HYROX, and high-energy sessions designed to keep you moving and motivated.</p>
                    </div>

                    <div class="bg-[#1a1a1a] border border-white/10 rounded-3xl p-7 hover:border-red-500 transition">
                        <div class="w-14 h-14 rounded-full bg-red-600/15 flex items-center justify-center mb-5 text-2xl">💪</div>
                        <h3 class="text-2xl font-bold mb-3" data-i18n="card.personal.title">Personal Training</h3>
                        <p class="text-gray-300 leading-relaxed" data-i18n="card.personal.text">1-on-1 expert coaching to help you lift stronger, move smarter, and reach your fitness goals faster.</p>
                    </div>

                    <div class="bg-[#1a1a1a] border border-white/10 rounded-3xl p-7 hover:border-red-500 transition">
                        <div class="w-14 h-14 rounded-full bg-red-600/15 flex items-center justify-center mb-5 text-2xl">🎯</div>
                        <h3 class="text-2xl font-bold mb-3" data-i18n="card.fitpass.title">FITPASS</h3>
                        <p class="text-gray-300 leading-relaxed" data-i18n="card.fitpass.text">Pay-as-you-go access that gives you flexibility without long-term contracts or rigid commitments.</p>
                    </div>
                </div>
            </section>

            <section id="fitness-first" class="scroll-mt-24 bg-[#141414] py-20 md:py-24">
                <div class="mx-auto max-w-7xl px-6">
                    <div class="mb-12 grid gap-6 border-b border-white/10 pb-8 md:grid-cols-[0.85fr_1.15fr] md:items-end">
                        <div>
                            <p class="mb-3 text-xs font-bold uppercase tracking-[0.25em] text-red-500" data-i18n="why.label">Why Infliction</p>
                            <h2 class="max-w-lg text-4xl font-black leading-tight sm:text-5xl" data-i18n="why.title">A training space built for your next step.</h2>
                        </div>
                        <p class="max-w-2xl text-base leading-relaxed text-gray-300 md:justify-self-end" data-i18n="why.description">Build a routine with the right mix of equipment, coaching, classes, and flexible ways to train. Come as you are, then keep finding new reasons to move forward.</p>
                    </div>

                    <div class="grid gap-x-10 md:grid-cols-2">
                        <article class="grid grid-cols-[56px_1fr] gap-4 border-b border-white/10 py-7">
                            <span class="text-sm font-bold tracking-[0.12em] text-red-400">01</span>
                            <div>
                                <h3 class="text-xl font-bold" data-i18n="why.feature1.title">Room to build strength</h3>
                                <p class="mt-2 max-w-lg leading-relaxed text-gray-400" data-i18n="why.feature1.text">Train across a range of equipment and make each visit your own.</p>
                            </div>
                        </article>
                        <article class="grid grid-cols-[56px_1fr] gap-4 border-b border-white/10 py-7">
                            <span class="text-sm font-bold tracking-[0.12em] text-red-400">02</span>
                            <div>
                                <h3 class="text-xl font-bold" data-i18n="why.feature2.title">Coaching when you need it</h3>
                                <p class="mt-2 max-w-lg leading-relaxed text-gray-400" data-i18n="why.feature2.text">Get individual guidance to work on form, consistency, and your next goal.</p>
                            </div>
                        </article>
                        <article class="grid grid-cols-[56px_1fr] gap-4 border-b border-white/10 py-7">
                            <span class="text-sm font-bold tracking-[0.12em] text-red-400">03</span>
                            <div>
                                <h3 class="text-xl font-bold" data-i18n="why.feature3.title">Classes with energy</h3>
                                <p class="mt-2 max-w-lg leading-relaxed text-gray-400" data-i18n="why.feature3.text">Switch up solo training with group sessions, yoga, and high-intensity workouts.</p>
                            </div>
                        </article>
                        <article class="grid grid-cols-[56px_1fr] gap-4 border-b border-white/10 py-7">
                            <span class="text-sm font-bold tracking-[0.12em] text-red-400">04</span>
                            <div>
                                <h3 class="text-xl font-bold" data-i18n="why.feature4.title">Ways to train that fit</h3>
                                <p class="mt-2 max-w-lg leading-relaxed text-gray-400" data-i18n="why.feature4.text">Choose a monthly membership or FITPASS for pay-as-you-go flexibility.</p>
                            </div>
                        </article>
                    </div>

                    <div class="mt-10 flex flex-col gap-5 border-l-2 border-red-500 pl-5 sm:flex-row sm:items-center sm:justify-between sm:pl-7">
                        <p class="text-xl font-bold sm:text-2xl" data-i18n="why.closing">Your next chapter starts with one visit.</p>
                        <a href="#memberships" class="inline-flex w-fit items-center gap-3 text-sm font-bold uppercase tracking-[0.12em] text-red-400 transition hover:text-white" data-i18n="why.cta">Explore memberships <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </section>

            <section id="memberships" class="bg-[#171717] border-y border-white/10 py-20">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="mx-auto mb-12 max-w-3xl text-center">
                        <p class="text-red-500 uppercase tracking-[0.25em] text-sm font-bold mb-3" data-i18n="section.membership.label">Memberships</p>
                        <h2 class="text-4xl md:text-5xl font-black" data-i18n="section.membership.title">Choose the plan that fits your routine.</h2>
                        <p class="mt-4 text-gray-400" data-i18n="section.membership.subtitle">Compare membership options and find the right fit for your training.</p>
                    </div>

                    <div class="mx-auto grid max-w-6xl items-stretch gap-6 pt-4 md:grid-cols-3">
                        <article class="flex h-full flex-col rounded-2xl border border-white/10 bg-[#1a1a1a] p-7 sm:p-8">
                            <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-gray-400" data-i18n="plan.base.label">Establish your base</p>
                            <h3 class="mb-5 text-2xl font-black" data-i18n="plan.base.name">Base Membership</h3>
                            <div class="mb-2 flex flex-wrap items-baseline gap-2 border-b border-white/15 pb-5">
                                <span class="text-3xl font-black sm:text-4xl" data-membership-price="2650">PHP 2,650</span>
                                <span class="text-gray-400" data-i18n="plan.base.period">/month</span>
                            </div>
                            <p class="mb-5 text-sm text-gray-400" data-i18n="plan.base.fee">Zero joining fee</p>
                            <ul class="mb-8 flex-1 space-y-3 text-sm leading-relaxed text-gray-300">
                                <li data-i18n="plan.base.item1">Access to all equipment</li>
                                <li data-i18n="plan.base.item2">Locker room access</li>
                                <li data-i18n="plan.base.item3">Flexible gym hours</li>
                            </ul>
                            @guest
                                <button onclick="triggerChat('Hi, I want to get started with the Base Membership.')" class="w-full rounded-md bg-red-600 px-5 py-3.5 text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:bg-red-500" data-i18n="plan.base.cta">Get Started</button>
                            @endguest
                            @auth
                                <a href="{{ route('dashboard') }}#membership" class="block w-full rounded-md bg-red-600 px-5 py-3.5 text-center text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:bg-red-500" data-i18n="plan.base.cta">Select Plan</a>
                            @endauth
                        </article>

                        <article class="relative flex h-full flex-col rounded-2xl border-2 border-red-500 bg-[#1f1f1f] p-7 shadow-[0_0_30px_rgba(239,68,68,0.18)] sm:p-8">
                            <span class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 whitespace-nowrap rounded-full bg-red-600 px-5 py-2 text-xs font-black uppercase tracking-[0.1em] text-white" data-i18n="plan.popular">Most Popular</span>
                            <p class="mb-3 mt-2 text-xs font-bold uppercase tracking-[0.2em] text-red-400" data-i18n="plan.premium.label">Train without boundaries</p>
                            <h3 class="mb-5 text-2xl font-black" data-i18n="plan.premium.name">Premium Membership</h3>
                            <div class="mb-2 flex flex-wrap items-baseline gap-2 border-b border-white/15 pb-5">
                                <span class="text-3xl font-black sm:text-4xl" data-membership-price="2800">PHP 2,800</span>
                                <span class="text-gray-400" data-i18n="plan.base.period">/month</span>
                            </div>
                            <p class="mb-5 text-sm text-gray-400" data-i18n="plan.base.fee">Zero joining fee</p>
                            <ul class="mb-8 flex-1 space-y-3 text-sm leading-relaxed text-gray-300">
                                <li data-i18n="plan.premium.item1">Premium equipment access</li>
                                <li data-i18n="plan.premium.item2">Priority coaching</li>
                                <li data-i18n="plan.premium.item3">Member perks</li>
                            </ul>
                            @guest
                                <button onclick="triggerChat('Hi, I want to learn more about Premium Membership.')" class="w-full rounded-md bg-red-600 px-5 py-3.5 text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:bg-red-500" data-i18n="plan.premium.cta">Choose Premium</button>
                            @endguest
                            @auth
                                <a href="{{ route('dashboard') }}#membership" class="block w-full rounded-md bg-red-600 px-5 py-3.5 text-center text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:bg-red-500" data-i18n="plan.premium.cta">Choose Premium</a>
                            @endauth
                        </article>

                        <article class="flex h-full flex-col rounded-2xl border border-white/10 bg-[#1a1a1a] p-7 sm:p-8">
                            <p class="mb-3 text-xs font-bold uppercase tracking-[0.2em] text-gray-400" data-i18n="plan.flexible">Pay as you go</p>
                            <h3 class="mb-5 text-2xl font-black" data-i18n="plan.fitpass.name">FITPASS</h3>
                            <div class="mb-2 flex flex-wrap items-baseline gap-2 border-b border-white/15 pb-5">
                                <span class="text-3xl font-black sm:text-4xl" data-membership-price="483">PHP 483</span>
                                <span class="text-gray-400" data-i18n="plan.fitpass.period">/credit</span>
                            </div>
                            <p class="mb-5 text-sm text-gray-400" data-i18n="plan.base.fee">Zero joining fee</p>
                            <ul class="mb-8 flex-1 space-y-3 text-sm leading-relaxed text-gray-300">
                                <li data-i18n="plan.fitpass.item1">No contracts</li>
                                <li data-i18n="plan.fitpass.item2">Pay-as-you-go</li>
                                <li data-i18n="plan.fitpass.item3">Flexible workout access</li>
                            </ul>
                            @guest
                                <button onclick="triggerChat('Hi, I want to learn more about how FITPASS works.')" class="w-full rounded-md border border-white/30 px-5 py-3.5 text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:border-red-500 hover:text-red-400" data-i18n="plan.fitpass.cta">Learn More</button>
                            @endguest
                            @auth
                                <a href="{{ route('dashboard') }}#membership" class="block w-full rounded-md border border-white/30 px-5 py-3.5 text-center text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:border-red-500 hover:text-red-400" data-i18n="plan.fitpass.cta">Select Plan</a>
                            @endauth
                        </article>
                    </div>
                </div>
            </section>

            <section id="highlights" class="scroll-mt-24 border-y border-white/10 bg-[#0d0d0d] py-20 md:py-24">
                <div class="mx-auto max-w-7xl px-6">
                    <div class="mb-10 flex flex-col gap-4 border-b border-white/10 pb-7 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="mb-3 text-xs font-bold uppercase tracking-[0.24em] text-red-500" data-i18n="highlights.label">Inside Infliction</p>
                            <h2 class="text-4xl font-black sm:text-5xl" data-i18n="highlights.title">Gym highlights</h2>
                        </div>
                        <p class="max-w-xl text-sm leading-relaxed text-gray-400" data-i18n="highlights.subtitle">Take a closer look at the training, coaching, and membership options waiting for you.</p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <a href="#fitness-first" class="group overflow-hidden border border-white/10 bg-[#171717] transition hover:border-red-500/60">
                            <div class="aspect-[1.6] overflow-hidden"><img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=900&q=85" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                            <div class="flex items-center justify-between gap-4 p-4"><h3 class="font-bold text-white" data-i18n="highlights.item1">The training floor</h3><span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold uppercase tracking-[0.1em] text-red-400"><span data-i18n="highlights.readMore">Explore</span><span aria-hidden="true">→</span></span></div>
                        </a>
                        <a href="#personal-training" class="group overflow-hidden border border-white/10 bg-[#171717] transition hover:border-red-500/60">
                            <div class="aspect-[1.6] overflow-hidden"><img src="https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=900&q=85" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                            <div class="flex items-center justify-between gap-4 p-4"><h3 class="font-bold text-white" data-i18n="highlights.item2">One-to-one coaching</h3><span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold uppercase tracking-[0.1em] text-red-400"><span data-i18n="highlights.readMore">Explore</span><span aria-hidden="true">→</span></span></div>
                        </a>
                        <a href="#classes" class="group overflow-hidden border border-white/10 bg-[#171717] transition hover:border-red-500/60">
                            <div class="aspect-[1.6] overflow-hidden"><img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=900&q=85" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                            <div class="flex items-center justify-between gap-4 p-4"><h3 class="font-bold text-white" data-i18n="highlights.item3">Group sessions</h3><span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold uppercase tracking-[0.1em] text-red-400"><span data-i18n="highlights.readMore">Explore</span><span aria-hidden="true">→</span></span></div>
                        </a>
                        <a href="#training-goals" class="group overflow-hidden border border-white/10 bg-[#171717] transition hover:border-red-500/60">
                            <div class="aspect-[1.6] overflow-hidden"><img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=85" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                            <div class="flex items-center justify-between gap-4 p-4"><h3 class="font-bold text-white" data-i18n="highlights.item4">Strength and progress</h3><span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold uppercase tracking-[0.1em] text-red-400"><span data-i18n="highlights.readMore">Explore</span><span aria-hidden="true">→</span></span></div>
                        </a>
                        <a href="#memberships" class="group overflow-hidden border border-white/10 bg-[#171717] transition hover:border-red-500/60">
                            <div class="aspect-[1.6] overflow-hidden"><img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=900&q=85" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                            <div class="flex items-center justify-between gap-4 p-4"><h3 class="font-bold text-white" data-i18n="highlights.item5">Membership options</h3><span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold uppercase tracking-[0.1em] text-red-400"><span data-i18n="highlights.readMore">Explore</span><span aria-hidden="true">→</span></span></div>
                        </a>
                        <a href="#fitness-first" class="group overflow-hidden border border-white/10 bg-[#171717] transition hover:border-red-500/60">
                            <div class="aspect-[1.6] overflow-hidden"><img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?auto=format&fit=crop&w=900&q=85" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                            <div class="flex items-center justify-between gap-4 p-4"><h3 class="font-bold text-white" data-i18n="highlights.item6">Equipment for every session</h3><span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold uppercase tracking-[0.1em] text-red-400"><span data-i18n="highlights.readMore">Explore</span><span aria-hidden="true">→</span></span></div>
                        </a>
                        <a href="#classes" class="group overflow-hidden border border-white/10 bg-[#171717] transition hover:border-red-500/60">
                            <div class="aspect-[1.6] overflow-hidden"><img src="https://images.unsplash.com/photo-1517963879433-6ad2b056d712?auto=format&fit=crop&w=900&q=85" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                            <div class="flex items-center justify-between gap-4 p-4"><h3 class="font-bold text-white" data-i18n="highlights.item7">Move with the community</h3><span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold uppercase tracking-[0.1em] text-red-400"><span data-i18n="highlights.readMore">Explore</span><span aria-hidden="true">→</span></span></div>
                        </a>
                        <a href="#personal-training" class="group overflow-hidden border border-white/10 bg-[#171717] transition hover:border-red-500/60">
                            <div class="aspect-[1.6] overflow-hidden"><img src="https://images.unsplash.com/photo-1579758629938-03607ccdbaba?auto=format&fit=crop&w=900&q=85" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                            <div class="flex items-center justify-between gap-4 p-4"><h3 class="font-bold text-white" data-i18n="highlights.item8">Coaching that meets you here</h3><span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold uppercase tracking-[0.1em] text-red-400"><span data-i18n="highlights.readMore">Explore</span><span aria-hidden="true">→</span></span></div>
                        </a>
                        <a href="#memberships" class="group overflow-hidden border border-white/10 bg-[#171717] transition hover:border-red-500/60">
                            <div class="aspect-[1.6] overflow-hidden"><img src="https://images.unsplash.com/photo-1534258936925-c58bed479fcb?auto=format&fit=crop&w=900&q=85" alt="" loading="lazy" decoding="async" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                            <div class="flex items-center justify-between gap-4 p-4"><h3 class="font-bold text-white" data-i18n="highlights.item9">Flexible ways to train</h3><span class="inline-flex shrink-0 items-center gap-1 text-xs font-bold uppercase tracking-[0.1em] text-red-400"><span data-i18n="highlights.readMore">Explore</span><span aria-hidden="true">→</span></span></div>
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <footer id="contact" class="bg-[#0d0d0d] border-t border-white/10 py-12">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
                <div>
                    <h3 class="text-2xl font-black uppercase tracking-[0.12em] text-white" data-i18n="footer.brand">Infliction Gym</h3>
                    <p class="text-gray-400 mt-2" data-i18n="footer.location">Magalang, Pampanga</p>
                </div>
                <div class="text-gray-300">
                    <p><span data-i18n="footer.emailLabel">Email:</span> <a href="mailto:InflictionGym@gmail.com" class="text-red-400 hover:text-red-300">InflictionGym@gmail.com</a></p>
                    <div class="mt-3 flex justify-center gap-5 text-sm md:justify-start">
                        <a href="{{ route('legal.terms') }}" class="text-gray-400 underline-offset-4 hover:text-white hover:underline">Terms of Service</a>
                        <a href="{{ route('legal.privacy') }}" class="text-gray-400 underline-offset-4 hover:text-white hover:underline">Privacy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <a id="back-to-top" href="#top" class="fixed bottom-6 left-6 z-[9998] hidden border border-white/20 bg-[#1d1d1d] px-4 py-3 text-sm font-bold text-white shadow-lg transition hover:border-red-500 hover:text-red-400" aria-label="Back to top">Back to top ↑</a>
    <button id="chat-launcher" onclick="toggleChat()" data-i18n="chat.launcher">Chat with Us</button>

    <div id="chat-modal">
        <div class="chat-header" data-i18n="chat.support">Infliction Gym Support</div>
        <div class="chat-body" id="chat-body" role="log" aria-live="polite">
            <div class="msg assistant" data-i18n="chat.initial">Hi! Welcome to Infliction Gym Magalang. How can I help you today?</div>
        </div>
        <div class="chat-footer">
            <input type="text" id="chat-input" data-i18n-placeholder="chat.placeholder" placeholder="Type your message...">
            <button id="chat-send-btn" data-i18n="chat.send">Send</button>
        </div>
    </div>

    <script>
        let sessionId = localStorage.getItem('infliction_session') || 'sess_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('infliction_session', sessionId);
        let isSending = false;

        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const backToTopButton = document.getElementById('back-to-top');

        if (backToTopButton) {
            window.addEventListener('scroll', function () {
                backToTopButton.classList.toggle('hidden', window.scrollY < 500);
            }, { passive: true });
        }

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });
        }

        function toggleChat() {
            const modal = document.getElementById('chat-modal');
            if (modal) {
                modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
            }
        }

        function triggerChat(message) {
            const modal = document.getElementById('chat-modal');
            const input = document.getElementById('chat-input');

            if (modal) {
                modal.style.display = 'flex';
            }

            if (input) {
                input.value = message;
                sendMsg();
            }
        }

        async function sendMsg() {
            const input = document.getElementById('chat-input');
            const body = document.getElementById('chat-body');
            const text = input ? input.value.trim() : '';

            if (!text || !body || isSending) return;

            isSending = true;
            const sendBtn = document.getElementById('chat-send-btn');
            const originalSendLabel = sendBtn ? sendBtn.textContent : '';
            const activeLanguage = document.getElementById('language-switcher')?.value || 'en';
            if (sendBtn) {
                sendBtn.disabled = true;
                sendBtn.textContent = translations[activeLanguage]['chat.sending'];
                sendBtn.style.opacity = '0.65';
                sendBtn.style.cursor = 'wait';
            }
            body.innerHTML += `<div class="msg user">${escapeHtml(text)}</div>`;
            input.value = '';
            body.scrollTop = body.scrollHeight;

            try {
                const response = await fetch('/api/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ session_id: sessionId, message: text })
                });

                const data = await response.json();
                body.innerHTML += `<div class="msg assistant">${escapeHtml(data.reply || 'Sorry, I could not answer that right now. Please email InflictionGym@gmail.com.')}</div>`;
                body.scrollTop = body.scrollHeight;
            } catch (err) {
                body.innerHTML += `<div class="msg assistant">Sorry, something went wrong. Please email InflictionGym@gmail.com</div>`;
            } finally {
                isSending = false;
                if (sendBtn) {
                    sendBtn.disabled = false;
                    sendBtn.textContent = originalSendLabel;
                    sendBtn.style.opacity = '';
                    sendBtn.style.cursor = '';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('chat-input');
            const sendBtn = document.getElementById('chat-send-btn');

            if (input) {
                input.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        sendMsg();
                    }
                });
            }

            if (sendBtn) {
                sendBtn.addEventListener('click', function () {
                    sendMsg();
                });
            }
        });

        function escapeHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        const translations = {
            en: {
                'nav.memberships': 'Memberships',
                'nav.personal': 'Personal Training',
                'nav.fitness': 'Why Infliction',
                'nav.classes': 'Classes',
                'nav.highlights': 'Highlights',
                'nav.join': 'Join Online',
                'highlights.label': 'Inside Infliction',
                'highlights.title': 'Gym highlights',
                'highlights.subtitle': 'Take a closer look at the training, coaching, and membership options waiting for you.',
                'highlights.readMore': 'Explore',
                'highlights.item1': 'The training floor',
                'highlights.item2': 'One-to-one coaching',
                'highlights.item3': 'Group sessions',
                'highlights.item4': 'Strength and progress',
                'highlights.item5': 'Membership options',
                'highlights.item6': 'Equipment for every session',
                'highlights.item7': 'Move with the community',
                'highlights.item8': 'Coaching that meets you here',
                'highlights.item9': 'Flexible ways to train',
                'goals.label': 'Personal training',
                'goals.title': 'Reach your fitness goals.',
                'goals.subtitle': 'Different goals call for different kinds of training. Find a direction that feels right for you.',
                'goals.leaner.alt': 'Athlete training with a coach',
                'goals.leaner.title': 'Leaner',
                'goals.leaner.text': 'Build a steady routine around strength, conditioning, and the goals you set with your coach.',
                'goals.wellbeing.alt': 'Member stretching during a workout',
                'goals.wellbeing.title': 'Well-being',
                'goals.wellbeing.text': 'Make movement a regular part of your week with sessions for energy, balance, and confidence.',
                'goals.athletic.alt': 'Athlete lifting weights in the gym',
                'goals.athletic.title': 'Athletic',
                'goals.athletic.text': 'Work on stamina, coordination, and movement with focused training and challenging classes.',
                'goals.stronger.alt': 'Member performing a strength workout',
                'goals.stronger.title': 'Stronger',
                'goals.stronger.text': 'Build strength with guidance on form, resistance, and a pace that works for you.',
                'personal.imageLabel': 'Personal training',
                'personal.label': 'Personal coaching',
                'personal.title': 'A plan shaped around you.',
                'personal.description': 'Work one-on-one with a coach to improve your technique, build a routine, and stay focused on the goals that matter to you. Each session can meet you at your experience level and pace.',
                'personal.point1.title': 'Goal-led sessions',
                'personal.point1.text': 'Keep each workout connected to your next milestone.',
                'personal.point2.title': 'Confident technique',
                'personal.point2.text': 'Get practical coaching on form and movement.',
                'personal.point3.title': 'Progress you can build on',
                'personal.point3.text': 'Adjust your training as your strength grows.',
                'personal.cta': 'Talk to a coach',
                'personal.secondary': 'Compare plans',
                'why.label': 'Why Infliction',
                'why.title': 'A training space built for your next step.',
                'why.description': 'Build a routine with the right mix of equipment, coaching, classes, and flexible ways to train. Come as you are, then keep finding new reasons to move forward.',
                'why.feature1.title': 'Room to build strength',
                'why.feature1.text': 'Train across a range of equipment and make each visit your own.',
                'why.feature2.title': 'Coaching when you need it',
                'why.feature2.text': 'Get individual guidance to work on form, consistency, and your next goal.',
                'why.feature3.title': 'Classes with energy',
                'why.feature3.text': 'Switch up solo training with group sessions, yoga, and high-intensity workouts.',
                'why.feature4.title': 'Ways to train that fit',
                'why.feature4.text': 'Choose a monthly membership or FITPASS for pay-as-you-go flexibility.',
                'why.closing': 'Your next chapter starts with one visit.',
                'why.cta': 'Explore memberships',
                'hero.tag': "Magalang's Premium Fitness Destination",
                'hero.title': "Push Your Limits at",
                'hero.titleHighlight': "Magalang's",
                'hero.titleEnd': 'Premium Gym',
                'hero.subtitle': 'World-class facilities, expert coaching, and zero joining fees.',
                'hero.primary': 'Claim 1-Day Free Trial',
                'hero.secondary': 'View Memberships',
                'section.offer.label': 'What We Offer',
                'section.offer.title': 'Train Smarter. Perform Better.',
                'card.group.title': 'Group Classes',
                'card.group.text': 'BodyCombat, Yoga, HYROX, and high-energy sessions designed to keep you moving and motivated.',
                'card.personal.title': 'Personal Training',
                'card.personal.text': '1-on-1 expert coaching to help you lift stronger, move smarter, and reach your fitness goals faster.',
                'card.fitpass.title': 'FITPASS',
                'card.fitpass.text': 'Pay-as-you-go access that gives you flexibility without long-term contracts or rigid commitments.',
                'section.membership.label': 'Memberships',
                'section.membership.title': 'Choose the plan that fits your routine.',
                'section.membership.subtitle': 'Compare membership options and find the right fit for your training.',
                'plan.popular': 'Most Popular',
                'plan.base.label': 'Establish your base',
                'plan.base.name': 'Base Membership',
                'plan.base.period': '/month',
                'plan.base.fee': 'Zero joining fee',
                'plan.base.item1': 'Access to all equipment',
                'plan.base.item2': 'Locker room access',
                'plan.base.item3': 'Flexible gym hours',
                'plan.base.item4': '• Flexible gym hours',
                'plan.base.cta': 'Get Started',
                'plan.premium.label': 'Train without boundaries',
                'plan.premium.name': 'Premium Membership',
                'plan.premium.item1': 'Premium equipment access',
                'plan.premium.item2': 'Priority coaching',
                'plan.premium.item3': 'Member perks',
                'plan.premium.cta': 'Choose Premium',
                'plan.flexible': 'Pay as you go',
                'plan.fitpass.name': 'FITPASS',
                'plan.fitpass.period': '/credit',
                'plan.fitpass.item1': '• No contracts',
                'plan.fitpass.item2': '• Pay-as-you-go',
                'plan.fitpass.item3': '• Great for flexible schedules',
                'plan.fitpass.item4': '• Quick and easy access',
                'plan.fitpass.cta': 'Learn More',
                'chat.launcher': 'Chat with Us',
                'chat.support': 'Infliction Gym Support',
                'chat.initial': 'Hi! Welcome to Infliction Gym Magalang. How can I help you today?',
                'chat.placeholder': 'Type your message...',
                'chat.send': 'Send',
                'chat.sending': 'Sending...',
                'footer.brand': 'Infliction Gym',
                'footer.location': 'Magalang, Pampanga',
                'footer.emailLabel': 'Email:'
            },
            id: {
                'nav.memberships': 'Keanggotaan',
                'nav.personal': 'Latihan Pribadi',
                'nav.fitness': 'Mengapa Infliction',
                'nav.classes': 'Kelas',
                'nav.highlights': 'Sorotan',
                'nav.join': 'Gabung Online',
                'highlights.label': 'Kenali Infliction',
                'highlights.title': 'Sorotan gym',
                'highlights.subtitle': 'Lihat lebih dekat pilihan latihan, coaching, dan keanggotaan yang tersedia.',
                'highlights.readMore': 'Jelajahi',
                'highlights.item1': 'Area latihan',
                'highlights.item2': 'Coaching satu lawan satu',
                'highlights.item3': 'Kelas grup',
                'highlights.item4': 'Kekuatan dan kemajuan',
                'highlights.item5': 'Pilihan keanggotaan',
                'highlights.item6': 'Peralatan untuk setiap sesi',
                'highlights.item7': 'Berlatih bersama komunitas',
                'highlights.item8': 'Coaching sesuai kemampuan Anda',
                'highlights.item9': 'Pilihan latihan fleksibel',
                'goals.label': 'Latihan pribadi',
                'goals.title': 'Raih tujuan kebugaran Anda.',
                'goals.subtitle': 'Setiap tujuan membutuhkan jenis latihan yang berbeda. Temukan pendekatan yang cocok untuk Anda.',
                'goals.leaner.alt': 'Anggota berlatih bersama pelatih',
                'goals.leaner.title': 'Lebih ramping',
                'goals.leaner.text': 'Bangun rutinitas konsisten dengan latihan kekuatan, kebugaran, dan target bersama pelatih.',
                'goals.wellbeing.alt': 'Anggota melakukan peregangan saat berlatih',
                'goals.wellbeing.title': 'Kesejahteraan',
                'goals.wellbeing.text': 'Jadikan gerak bagian rutin minggu Anda untuk mendukung energi, keseimbangan, dan percaya diri.',
                'goals.athletic.alt': 'Anggota mengangkat beban di gym',
                'goals.athletic.title': 'Atletis',
                'goals.athletic.text': 'Latih stamina, koordinasi, dan gerak melalui latihan terarah serta kelas yang menantang.',
                'goals.stronger.alt': 'Anggota menjalani latihan kekuatan',
                'goals.stronger.title': 'Lebih kuat',
                'goals.stronger.text': 'Bangun kekuatan dengan panduan teknik, beban, dan tempo yang sesuai untuk Anda.',
                'personal.imageLabel': 'Latihan pribadi',
                'personal.label': 'Pelatihan pribadi',
                'personal.title': 'Program latihan yang dirancang untuk Anda.',
                'personal.description': 'Berlatih satu lawan satu dengan pelatih untuk memperbaiki teknik, membangun rutinitas, dan menjaga fokus pada tujuan Anda. Setiap sesi disesuaikan dengan pengalaman dan tempo latihan Anda.',
                'personal.point1.title': 'Sesi berorientasi tujuan',
                'personal.point1.text': 'Hubungkan setiap latihan dengan target berikutnya.',
                'personal.point2.title': 'Teknik yang lebih mantap',
                'personal.point2.text': 'Dapatkan panduan praktis tentang gerakan dan postur.',
                'personal.point3.title': 'Kemajuan bertahap',
                'personal.point3.text': 'Sesuaikan latihan seiring bertambahnya kekuatan Anda.',
                'personal.cta': 'Tanya pelatih',
                'personal.secondary': 'Bandingkan paket',
                'why.label': 'Mengapa Infliction',
                'why.title': 'Ruang latihan untuk langkah Anda berikutnya.',
                'why.description': 'Bangun rutinitas dengan perpaduan peralatan, bimbingan, kelas, dan pilihan latihan yang fleksibel. Mulai dari kemampuan Anda sekarang, lalu terus temukan alasan baru untuk maju.',
                'why.feature1.title': 'Ruang untuk membangun kekuatan',
                'why.feature1.text': 'Gunakan beragam peralatan dan jadikan setiap kunjungan sesuai gaya latihan Anda.',
                'why.feature2.title': 'Bimbingan saat diperlukan',
                'why.feature2.text': 'Dapatkan arahan pribadi untuk teknik, konsistensi, dan target berikutnya.',
                'why.feature3.title': 'Kelas yang penuh energi',
                'why.feature3.text': 'Variasikan latihan mandiri dengan kelas grup, yoga, dan latihan intensitas tinggi.',
                'why.feature4.title': 'Pilihan latihan yang fleksibel',
                'why.feature4.text': 'Pilih keanggotaan bulanan atau FITPASS dengan pembayaran sesuai pemakaian.',
                'why.closing': 'Perjalanan baru Anda dimulai dari satu kunjungan.',
                'why.cta': 'Lihat keanggotaan',
                'hero.tag': 'Destinasi Kebugaran Premium di Magalang',
                'hero.title': 'Dorong Batasmu di',
                'hero.titleHighlight': 'Magalang\'s',
                'hero.titleEnd': 'Gym Premium',
                'hero.subtitle': 'Fasilitas kelas dunia, pelatih ahli, dan tanpa biaya pendaftaran.',
                'hero.primary': 'Dapatkan Uji Coba 1 Hari',
                'hero.secondary': 'Lihat Paket',
                'section.offer.label': 'Yang Kami Tawarkan',
                'section.offer.title': 'Latih Lebih Cerdas. Tampil Lebih Baik.',
                'card.group.title': 'Kelas Grup',
                'card.group.text': 'BodyCombat, Yoga, HYROX, dan sesi berenergi tinggi yang membuatmu terus bergerak dan termotivasi.',
                'card.personal.title': 'Latihan Pribadi',
                'card.personal.text': 'Pelatihan ahli 1-on-1 untuk membantu Anda mengangkat beban lebih kuat, bergerak lebih cerdas, dan mencapai tujuan kebugaran lebih cepat.',
                'card.fitpass.title': 'FITPASS',
                'card.fitpass.text': 'Akses bayar per penggunaan yang memberi fleksibilitas tanpa kontrak jangka panjang atau komitmen yang kaku.',
                'section.membership.label': 'Keanggotaan',
                'section.membership.title': 'Pilih paket yang cocok dengan rutinitas Anda.',
                'section.membership.subtitle': 'Bandingkan pilihan keanggotaan dan temukan yang sesuai dengan latihan Anda.',
                'plan.popular': 'Paling Populer',
                'plan.base.label': 'Mulai dari dasar',
                'plan.base.name': 'Keanggotaan Dasar',
                'plan.base.period': '/bulan',
                'plan.base.fee': 'Tanpa biaya pendaftaran',
                'plan.base.item1': 'Akses semua peralatan',
                'plan.base.item2': 'Akses ruang loker',
                'plan.base.item3': 'Jam gym fleksibel',
                'plan.base.item4': '• Jam gym yang fleksibel',
                'plan.base.cta': 'Mulai',
                'plan.premium.label': 'Latihan tanpa batas',
                'plan.premium.name': 'Keanggotaan Premium',
                'plan.premium.item1': 'Akses peralatan premium',
                'plan.premium.item2': 'Prioritas pelatihan',
                'plan.premium.item3': 'Keuntungan khusus anggota',
                'plan.premium.cta': 'Pilih Premium',
                'plan.flexible': 'Bayar sesuai pemakaian',
                'plan.fitpass.name': 'FITPASS',
                'plan.fitpass.period': '/kredit',
                'plan.fitpass.item1': '• Tanpa kontrak',
                'plan.fitpass.item2': '• Bayar sesuai pemakaian',
                'plan.fitpass.item3': '• Cocok untuk jadwal yang fleksibel',
                'plan.fitpass.item4': '• Mudah diakses',
                'plan.fitpass.cta': 'Pelajari',
                'chat.launcher': 'Chat dengan Kami',
                'chat.support': 'Dukungan Infliction Gym',
                'chat.initial': 'Halo! Selamat datang di Infliction Gym Magalang. Ada yang bisa saya bantu?',
                'chat.placeholder': 'Ketik pesan Anda...',
                'chat.send': 'Kirim',
                'chat.sending': 'Mengirim...',
                'footer.brand': 'Infliction Gym',
                'footer.location': 'Magalang, Pampanga',
                'footer.emailLabel': 'Email:'
            },
            th: {
                'nav.memberships': 'สิทธิสมาชิก',
                'nav.personal': 'ฝึกส่วนตัว',
                'nav.fitness': 'ทำไมต้อง Infliction',
                'nav.classes': 'คลาส',
                'nav.highlights': 'ไฮไลท์',
                'nav.join': 'เข้าร่วมออนไลน์',
                'highlights.label': 'รู้จัก Infliction',
                'highlights.title': 'ไฮไลท์ของยิม',
                'highlights.subtitle': 'ชมพื้นที่ฝึก โค้ช และตัวเลือกสมาชิกที่พร้อมให้คุณเริ่มต้น',
                'highlights.readMore': 'ดูเพิ่มเติม',
                'highlights.item1': 'พื้นที่ฝึกซ้อม',
                'highlights.item2': 'ฝึกตัวต่อตัวกับโค้ช',
                'highlights.item3': 'คลาสกลุ่ม',
                'highlights.item4': 'ความแข็งแรงและพัฒนาการ',
                'highlights.item5': 'แพ็กเกจสมาชิก',
                'highlights.item6': 'อุปกรณ์พร้อมสำหรับทุกการฝึก',
                'highlights.item7': 'ออกกำลังกายไปด้วยกัน',
                'highlights.item8': 'โค้ชที่เข้าใจระดับของคุณ',
                'highlights.item9': 'รูปแบบการฝึกที่ยืดหยุ่น',
                'goals.label': 'ฝึกส่วนตัว',
                'goals.title': 'ไปให้ถึงเป้าหมายฟิตเนสของคุณ',
                'goals.subtitle': 'แต่ละเป้าหมายเหมาะกับการฝึกที่ต่างกัน เลือกแนวทางที่ใช่สำหรับคุณ',
                'goals.leaner.alt': 'สมาชิกฝึกซ้อมกับโค้ช',
                'goals.leaner.title': 'หุ่นกระชับ',
                'goals.leaner.text': 'สร้างกิจวัตรที่สม่ำเสมอด้วยการฝึกความแข็งแรงและความฟิตตามเป้าหมายของคุณ',
                'goals.wellbeing.alt': 'สมาชิกยืดเหยียดระหว่างออกกำลังกาย',
                'goals.wellbeing.title': 'สุขภาวะที่ดี',
                'goals.wellbeing.text': 'เติมการเคลื่อนไหวให้เป็นส่วนหนึ่งของสัปดาห์ เพื่อพลัง ความสมดุล และความมั่นใจ',
                'goals.athletic.alt': 'สมาชิกยกน้ำหนักในยิม',
                'goals.athletic.title': 'สมรรถภาพนักกีฬา',
                'goals.athletic.text': 'พัฒนาความอึด การประสานงาน และการเคลื่อนไหวด้วยการฝึกที่มีเป้าหมายและคลาสที่ท้าทาย',
                'goals.stronger.alt': 'สมาชิกฝึกความแข็งแรง',
                'goals.stronger.title': 'แข็งแรงขึ้น',
                'goals.stronger.text': 'เพิ่มความแข็งแรงด้วยคำแนะนำเรื่องท่าทาง แรงต้าน และจังหวะที่เหมาะกับคุณ',
                'personal.imageLabel': 'ฝึกส่วนตัว',
                'personal.label': 'การฝึกแบบตัวต่อตัว',
                'personal.title': 'แผนฝึกที่ออกแบบเพื่อคุณ',
                'personal.description': 'ฝึกแบบตัวต่อตัวกับโค้ชเพื่อพัฒนาเทคนิค สร้างกิจวัตร และมุ่งสู่เป้าหมายของคุณ แต่ละเซสชันปรับให้เหมาะกับประสบการณ์และจังหวะการฝึกของคุณ',
                'personal.point1.title': 'ฝึกตามเป้าหมาย',
                'personal.point1.text': 'เชื่อมโยงการฝึกแต่ละครั้งกับเป้าหมายถัดไป',
                'personal.point2.title': 'เทคนิคที่มั่นใจ',
                'personal.point2.text': 'รับคำแนะนำที่นำไปใช้ได้จริงเรื่องท่าทางและการเคลื่อนไหว',
                'personal.point3.title': 'พัฒนาต่อเนื่อง',
                'personal.point3.text': 'ปรับการฝึกไปพร้อมกับความแข็งแรงที่เพิ่มขึ้น',
                'personal.cta': 'พูดคุยกับโค้ช',
                'personal.secondary': 'เปรียบเทียบแพ็กเกจ',
                'why.label': 'ทำไมต้อง Infliction',
                'why.title': 'พื้นที่ฝึกเพื่อก้าวต่อไปของคุณ',
                'why.description': 'สร้างกิจวัตรด้วยอุปกรณ์ที่เหมาะสม การดูแลจากโค้ช คลาสออกกำลังกาย และรูปแบบการฝึกที่ยืดหยุ่น เริ่มจากจุดที่คุณอยู่ แล้วค้นหาแรงผลักดันใหม่เพื่อพัฒนาต่อไป',
                'why.feature1.title': 'พื้นที่สร้างความแข็งแรง',
                'why.feature1.text': 'เลือกใช้อุปกรณ์หลากหลายและออกแบบการฝึกในแต่ละครั้งได้ตามต้องการ',
                'why.feature2.title': 'มีโค้ชคอยแนะนำ',
                'why.feature2.text': 'รับคำแนะนำเฉพาะบุคคลเรื่องท่าทาง ความสม่ำเสมอ และเป้าหมายถัดไป',
                'why.feature3.title': 'คลาสที่เต็มไปด้วยพลัง',
                'why.feature3.text': 'เพิ่มสีสันให้การฝึกด้วยคลาสกลุ่ม โยคะ และการออกกำลังกายความเข้มข้นสูง',
                'why.feature4.title': 'รูปแบบการฝึกที่ยืดหยุ่น',
                'why.feature4.text': 'เลือกสมาชิกแบบรายเดือนหรือ FITPASS ที่จ่ายตามการใช้งาน',
                'why.closing': 'ก้าวต่อไปของคุณเริ่มจากการมาออกกำลังกายสักครั้ง',
                'why.cta': 'ดูแพ็กเกจสมาชิก',
                'hero.tag': 'สถานที่ออกกำลังกายระดับพรีเมียมที่ Magalang',
                'hero.title': 'ก้าวไปอีกระดับที่',
                'hero.titleHighlight': 'Magalang\'s',
                'hero.titleEnd': 'Premium Gym',
                'hero.subtitle': 'สิ่งอำนวยความสะดวกระดับโลก การฝึกซ้อมโดยผู้เชี่ยวชาญ และไม่มีค่าลงทะเบียน',
                'hero.primary': 'รับทดลองใช้ฟรี 1 วัน',
                'hero.secondary': 'ดูแผนสมาชิก',
                'section.offer.label': 'สิ่งที่เรานำเสนอ',
                'section.offer.title': 'ฝึกให้ฉลาดกว่า. ทำผลงานได้ดีขึ้น.',
                'card.group.title': 'คลาสกลุ่ม',
                'card.group.text': 'BodyCombat, Yoga, HYROX และเซสชันสุดเร้าใจที่ทำให้คุณเคลื่อนไหวและมีแรงบันดาลใจต่อเนื่อง',
                'card.personal.title': 'ฝึกส่วนตัว',
                'card.personal.text': 'การฝึกสอนแบบ 1 ต่อ 1 จากผู้เชี่ยวชาญเพื่อช่วยให้คุณยกของหนักได้ดีขึ้น เคลื่อนไหวได้ดีขึ้น และบรรลุเป้าหมายฟิตเนสได้เร็วขึ้น',
                'card.fitpass.title': 'FITPASS',
                'card.fitpass.text': 'การเข้าถึงแบบจ่ายตามการใช้งานที่ให้ความยืดหยุ่นโดยไม่ต้องผูกมัดระยะยาว',
                'section.membership.label': 'สิทธิสมาชิก',
                'section.membership.title': 'เลือกแผนที่เหมาะกับกิจวัตรของคุณ',
                'section.membership.subtitle': 'เปรียบเทียบแพ็กเกจสมาชิกและเลือกแบบที่เหมาะกับการฝึกของคุณ',
                'plan.popular': 'ยอดนิยม',
                'plan.base.label': 'เริ่มต้นอย่างมั่นคง',
                'plan.base.name': 'สิทธิสมาชิกพื้นฐาน',
                'plan.base.period': '/เดือน',
                'plan.base.fee': 'ไม่มีค่าลงทะเบียน',
                'plan.base.item1': 'ใช้อุปกรณ์ได้ทุกชนิด',
                'plan.base.item2': 'ใช้ห้องล็อกเกอร์ได้',
                'plan.base.item3': 'เวลาเข้าใช้ยืดหยุ่น',
                'plan.base.item4': '• ชั่วโมงการใช้งานยืดหยุ่น',
                'plan.base.cta': 'เริ่มต้น',
                'plan.premium.label': 'ฝึกได้เต็มขีดจำกัด',
                'plan.premium.name': 'สมาชิกพรีเมียม',
                'plan.premium.item1': 'ใช้อุปกรณ์ระดับพรีเมียม',
                'plan.premium.item2': 'รับคำแนะนำจากโค้ชก่อน',
                'plan.premium.item3': 'สิทธิพิเศษสำหรับสมาชิก',
                'plan.premium.cta': 'เลือกพรีเมียม',
                'plan.flexible': 'จ่ายตามการใช้งาน',
                'plan.fitpass.name': 'FITPASS',
                'plan.fitpass.period': '/เครดิต',
                'plan.fitpass.item1': '• ไม่มีสัญญา',
                'plan.fitpass.item2': '• จ่ายตามการใช้งาน',
                'plan.fitpass.item3': '• เหมาะกับตารางที่ยืดหยุ่น',
                'plan.fitpass.item4': '• เข้าถึงง่ายและรวดเร็ว',
                'plan.fitpass.cta': 'เรียนรู้เพิ่มเติม',
                'chat.launcher': 'แชตกับเรา',
                'chat.support': 'ฝ่ายสนับสนุน Infliction Gym',
                'chat.initial': 'สวัสดี! ยินดีต้อนรับสู่ Infliction Gym Magalang คุณต้องการความช่วยเหลือด้านใด?',
                'chat.placeholder': 'พิมพ์ข้อความของคุณ...',
                'chat.send': 'ส่ง',
                'chat.sending': 'กำลังส่ง...',
                'footer.brand': 'Infliction Gym',
                'footer.location': 'Magalang, Pampanga',
                'footer.emailLabel': 'อีเมล:'
            }
        };

        const languageSwitcher = document.getElementById('language-switcher');
        const currenciesByLanguage = {
            en: { locale: 'en-PH', currency: 'PHP', phpRate: 1 },
            id: { locale: 'id-ID', currency: 'IDR', phpRate: 280 },
            th: { locale: 'th-TH', currency: 'THB', phpRate: 0.62 }
        };
        const applyTranslations = (lang) => {
            const selected = translations[lang] || translations.en;
            document.documentElement.lang = lang;

            document.querySelectorAll('[data-membership-price]').forEach((element) => {
                const priceInPhp = Number(element.getAttribute('data-membership-price'));
                const currency = currenciesByLanguage[lang] || currenciesByLanguage.en;
                const convertedPrice = Math.round(priceInPhp * currency.phpRate);
                element.textContent = new Intl.NumberFormat(currency.locale, {
                    style: 'currency',
                    currency: currency.currency,
                    maximumFractionDigits: 0
                }).format(convertedPrice);
            });

            document.querySelectorAll('[data-i18n]').forEach((element) => {
                const key = element.getAttribute('data-i18n');
                if (selected[key]) {
                    element.textContent = selected[key];
                }
            });

            document.querySelectorAll('[data-i18n-placeholder]').forEach((element) => {
                const key = element.getAttribute('data-i18n-placeholder');
                if (selected[key]) {
                    element.setAttribute('placeholder', selected[key]);
                }
            });

            document.querySelectorAll('[data-i18n-alt]').forEach((element) => {
                const key = element.getAttribute('data-i18n-alt');
                if (selected[key]) {
                    element.setAttribute('alt', selected[key]);
                }
            });

            if (languageSwitcher) {
                const languageLabels = {
                    en: { en: 'English', id: 'Bahasa Indonesia', th: 'ภาษาไทย' },
                    id: { en: 'English', id: 'Bahasa Indonesia', th: 'ภาษาไทย' },
                    th: { en: 'English', id: 'Bahasa Indonesia', th: 'ภาษาไทย' }
                };

                languageSwitcher.querySelectorAll('option').forEach((option) => {
                    const value = option.value;
                    option.textContent = languageLabels[lang]?.[value] || value.toUpperCase();
                });
                languageSwitcher.value = lang;
            }

            const heroTag = document.querySelector('[data-hero-tag]');
            const heroTitle = document.querySelector('[data-hero-title]');
            const heroHighlight = document.querySelector('[data-hero-highlight]');
            const heroTitleEnd = document.querySelector('[data-hero-end]');
            const heroSubtitle = document.querySelector('[data-hero-subtitle]');
            const heroPrimary = document.querySelector('[data-hero-primary]');
            const heroSecondary = document.querySelector('[data-hero-secondary]');

            if (heroTag) heroTag.textContent = selected['hero.tag'];
            if (heroTitle) heroTitle.textContent = selected['hero.title'];
            if (heroHighlight) heroHighlight.textContent = selected['hero.titleHighlight'];
            if (heroTitleEnd) heroTitleEnd.textContent = selected['hero.titleEnd'];
            if (heroSubtitle) heroSubtitle.textContent = selected['hero.subtitle'];
            if (heroPrimary) heroPrimary.textContent = selected['hero.primary'];
            if (heroSecondary) heroSecondary.textContent = selected['hero.secondary'];
        };

        if (languageSwitcher) {
            languageSwitcher.addEventListener('change', (event) => {
                const lang = event.target.value;
                localStorage.setItem('infliction_lang', lang);
                applyTranslations(lang);
            });

            const savedLang = localStorage.getItem('infliction_lang') || 'en';
            languageSwitcher.value = savedLang;
            applyTranslations(savedLang);
        }
    </script>
</body>
</html>