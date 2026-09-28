<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-white">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if(session('status'))
                <div class="rounded-xl border border-green-500/30 bg-green-500/10 px-4 py-3 text-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-6">
                    <h3 class="text-2xl font-black text-white mb-4">Add Coach</h3>
                    <form method="POST" action="{{ route('admin.coaches.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Coach Name</label>
                            <input type="text" name="name" required class="w-full rounded-lg border border-[#3a3a3a] bg-[#111111] text-white px-3 py-2 focus:border-red-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Specialty</label>
                            <input type="text" name="specialty" required class="w-full rounded-lg border border-[#3a3a3a] bg-[#111111] text-white px-3 py-2 focus:border-red-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Bio</label>
                            <textarea name="bio" rows="3" class="w-full rounded-lg border border-[#3a3a3a] bg-[#111111] text-white px-3 py-2 focus:border-red-500 focus:outline-none"></textarea>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-600 bg-[#111111] text-red-500">
                            <label class="text-sm text-gray-300">Active</label>
                        </div>
                        <button type="submit" class="bg-red-600 hover:bg-red-500 text-white font-bold px-5 py-2.5 rounded-full uppercase tracking-wide transition">
                            Save Coach
                        </button>
                    </form>
                </div>

                <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-6">
                    <h3 class="text-2xl font-black text-white mb-4">Add Schedule</h3>
                    <form method="POST" action="{{ route('admin.schedules.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Coach</label>
                            <select name="coach_id" required class="w-full rounded-lg border border-[#3a3a3a] bg-[#111111] text-white px-3 py-2 focus:border-red-500 focus:outline-none">
                                <option value="">Select coach</option>
                                @foreach($coaches as $coach)
                                    <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Day</label>
                            <input type="text" name="day" placeholder="Monday" required class="w-full rounded-lg border border-[#3a3a3a] bg-[#111111] text-white px-3 py-2 focus:border-red-500 focus:outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Time From</label>
                                <input type="text" name="time_from" placeholder="08:00" required class="w-full rounded-lg border border-[#3a3a3a] bg-[#111111] text-white px-3 py-2 focus:border-red-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-300 mb-1">Time To</label>
                                <input type="text" name="time_to" placeholder="09:30" required class="w-full rounded-lg border border-[#3a3a3a] bg-[#111111] text-white px-3 py-2 focus:border-red-500 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Class Name</label>
                            <input type="text" name="class_name" placeholder="HIIT Burn" required class="w-full rounded-lg border border-[#3a3a3a] bg-[#111111] text-white px-3 py-2 focus:border-red-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-300 mb-1">Location</label>
                            <input type="text" name="location" placeholder="Main Floor" class="w-full rounded-lg border border-[#3a3a3a] bg-[#111111] text-white px-3 py-2 focus:border-red-500 focus:outline-none">
                        </div>
                        <button type="submit" class="bg-red-600 hover:bg-red-500 text-white font-bold px-5 py-2.5 rounded-full uppercase tracking-wide transition">
                            Save Schedule
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-6">
                <h3 class="text-2xl font-black text-white mb-4">Current Coaches</h3>
                <div class="space-y-4">
                    @forelse($coaches as $coach)
                        <div class="border border-[#2a2a2a] rounded-xl p-4 bg-[#121212]">
                            <div class="flex items-center justify-between gap-4 flex-wrap">
                                <div>
                                    <h4 class="text-xl font-bold text-white">{{ $coach->name }}</h4>
                                    <p class="text-red-400">{{ $coach->specialty }}</p>
                                </div>
                                <span class="rounded-full px-2 py-1 text-xs uppercase tracking-wide {{ $coach->is_active ? 'bg-green-500/20 text-green-300' : 'bg-gray-500/20 text-gray-300' }}">
                                    {{ $coach->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            @if($coach->bio)
                                <p class="mt-3 text-gray-300">{{ $coach->bio }}</p>
                            @endif

                            @if($coach->schedules->count())
                                <div class="mt-4 space-y-2">
                                    @foreach($coach->schedules as $schedule)
                                        <div class="rounded-lg border border-[#2a2a2a] bg-[#181818] px-3 py-2 text-sm text-gray-200">
                                            <span class="font-semibold text-white">{{ $schedule->day }}</span> — {{ $schedule->time_from }} to {{ $schedule->time_to }}
                                            <span class="text-red-400">{{ $schedule->class_name }}</span>
                                            @if($schedule->location)
                                                <span class="text-gray-400">• {{ $schedule->location }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-300">No coaches yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
