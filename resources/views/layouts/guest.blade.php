<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-white selection:bg-red-500/30">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#090909] px-4 py-8 sm:px-6">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1800&q=85')] bg-cover bg-center opacity-20"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-black/90 via-[#101010]/95 to-red-950/35"></div>
            <div class="relative z-10 grid w-full max-w-[1040px] overflow-hidden border border-white/10 bg-[#111]/95 shadow-[0_28px_90px_rgba(0,0,0,0.6)] md:min-h-[640px] md:grid-cols-2">
                <section class="relative hidden min-h-[640px] flex-col justify-between overflow-hidden p-10 md:flex lg:p-12">
                    <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1100&q=85" alt="Athlete training at the gym" class="absolute inset-0 h-full w-full object-cover opacity-45">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/30 to-black/90"></div>
                    <a href="{{ url('/') }}" class="relative inline-flex items-center gap-3 text-sm font-black tracking-[0.28em] text-white">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full border border-red-500/60 bg-black/30 text-red-500">I</span>
                        INFLICTION
                    </a>
                    <div class="relative max-w-md">
                        <p class="mb-4 text-xs font-bold uppercase tracking-[0.24em] text-red-400">Magalang, Pampanga</p>
                        <h2 class="text-4xl font-black leading-tight text-white lg:text-5xl">Show up.<br><span class="text-red-500">Get stronger.</span></h2>
                        <p class="mt-5 max-w-sm text-base leading-relaxed text-gray-200">Your next training session starts here.</p>
                    </div>
                    <p class="relative text-xs font-semibold uppercase tracking-[0.18em] text-white/70">Strength built together</p>
                </section>
                <main class="flex items-center justify-center px-6 py-10 sm:px-10 md:px-12">
                    <div class="w-full max-w-sm">{{ $slot }}</div>
                </main>
            </div>
        </div>
    </body>
</html>
