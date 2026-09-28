<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title') | Infliction Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#121212] text-white antialiased">
    <header class="border-b border-white/10 bg-[#0d0d0d]">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-6 py-5">
            <a href="{{ route('home') }}" class="font-black uppercase tracking-[0.16em]">Infliction Gym</a>
            <nav class="flex gap-4 text-sm text-gray-300" aria-label="Legal pages">
                <a href="{{ route('legal.terms') }}" class="hover:text-white">Terms</a>
                <a href="{{ route('legal.privacy') }}" class="hover:text-white">Privacy</a>
            </nav>
        </div>
    </header>
    <main class="mx-auto max-w-5xl px-6 py-10 sm:py-14">
        @yield('content')
    </main>
    <footer class="border-t border-white/10 px-6 py-6 text-center text-sm text-gray-400">
        <a href="{{ route('home') }}" class="hover:text-white">Infliction Gym</a>
        <span class="px-2">|</span>
        <a href="mailto:InflictionGym@gmail.com" class="hover:text-white">InflictionGym@gmail.com</a>
    </footer>
</body>
</html>