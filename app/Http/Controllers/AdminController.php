<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\CoachSchedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $coaches = Coach::with('schedules')->orderBy('name')->get();
        $schedules = CoachSchedule::with('coach')->orderBy('day')->orderBy('time_from')->get();

        return view('admin.dashboard', compact('coaches', 'schedules'));
    }

    public function storeCoach(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialty' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Coach::create($validated);

        return redirect()->route('admin.dashboard')->with('status', 'Coach added successfully.');
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'coach_id' => ['required', 'exists:coaches,id'],
            'day' => ['required', 'string', 'max:50'],
            'time_from' => ['required', 'string', 'max:20'],
            'time_to' => ['required', 'string', 'max:20'],
            'class_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        CoachSchedule::create($validated);

        return redirect()->route('admin.dashboard')->with('status', 'Schedule added successfully.');
    }
}
