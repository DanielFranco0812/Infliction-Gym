<x-app-layout>
    <div class="min-h-[calc(100vh-76px)] bg-[#111] text-white">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-5 border-b border-white/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="mb-2 text-xs font-bold uppercase tracking-[0.24em] text-red-500">Move with purpose</p>
                    <h1 class="text-3xl font-black sm:text-4xl">Classes & coaching</h1>
                    <p class="mt-2 max-w-2xl text-gray-400">Find a session that fits your week, led by the Infliction Gym coaching team.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-300 transition hover:text-red-400">← Member dashboard</a>
            </div>

            @if($schedules->isNotEmpty())
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($schedules as $schedule)
                        <article class="flex min-h-56 flex-col border border-white/10 bg-[#171717] p-6 transition hover:border-red-500/50">
                            <div class="mb-5 flex items-start justify-between gap-4">
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-red-400">{{ $schedule->day }}</p>
                                <span class="border border-white/10 px-2.5 py-1 text-xs text-gray-300">{{ $schedule->time_from }}–{{ $schedule->time_to }}</span>
                            </div>
                            <h2 class="text-2xl font-black">{{ $schedule->class_name }}</h2>
                            <div class="mt-auto border-t border-white/10 pt-5">
                                <p class="font-semibold text-white">{{ $schedule->coach->name }}</p>
                                <p class="mt-1 text-sm text-gray-400">{{ $schedule->coach->specialty }}</p>
                                @if($schedule->location)
                                    <p class="mt-3 text-sm text-gray-400">{{ $schedule->location }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <section class="border border-white/10 bg-[#171717] px-6 py-16 text-center sm:px-10">
                    <p class="mb-3 text-xs font-bold uppercase tracking-[0.22em] text-red-400">Schedule updating</p>
                    <h2 class="text-2xl font-black">New sessions are on the way.</h2>
                    <p class="mx-auto mt-3 max-w-lg text-sm leading-relaxed text-gray-400">Our coaching team is updating the timetable. Contact the gym for current class availability.</p>
                    <a href="mailto:InflictionGym@gmail.com" class="mt-6 inline-flex items-center justify-center bg-red-600 px-5 py-3 text-sm font-bold uppercase tracking-[0.1em] text-white transition hover:bg-red-500">Contact the gym</a>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>