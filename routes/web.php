<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ProfileController;
use App\Models\CoachSchedule;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/terms', 'legal.terms')->name('legal.terms');
Route::view('/privacy', 'legal.privacy')->name('legal.privacy');

Route::post('/api/chat', [ChatController::class, 'sendMessage'])->middleware('throttle:10,1')->name('api.chat');

Route::get('/dashboard', function () {
    $schedules = CoachSchedule::with('coach')
        ->whereHas('coach', fn ($query) => $query->where('is_active', true))
        ->orderBy('day')
        ->orderBy('time_from')
        ->limit(3)
        ->get();

    return view('dashboard', compact('schedules'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/classes', function () {
        $schedules = CoachSchedule::with('coach')
            ->whereHas('coach', fn ($query) => $query->where('is_active', true))
            ->orderBy('day')
            ->orderBy('time_from')
            ->get();

        return view('classes.index', compact('schedules'));
    })->name('classes.index');

    Route::post('/membership/upgrade', [MembershipController::class, 'upgrade'])->name('membership.upgrade');
    Route::post('/membership/fitpass', [MembershipController::class, 'buyFitpass'])->name('membership.fitpass');
    Route::get('/membership/payment/return', [MembershipController::class, 'paymentReturn'])->name('membership.payment.return');
    Route::get('/member-profile', [ProfileController::class, 'memberProfile'])->name('member.profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->middleware('admin')->name('admin.dashboard');
    Route::post('/admin/coaches', [App\Http\Controllers\AdminController::class, 'storeCoach'])->middleware('admin')->name('admin.coaches.store');
    Route::post('/admin/schedules', [App\Http\Controllers\AdminController::class, 'storeSchedule'])->middleware('admin')->name('admin.schedules.store');
});

Route::post('/webhooks/paymongo', [MembershipController::class, 'webhook'])->name('webhooks.paymongo');

require __DIR__.'/auth.php';
