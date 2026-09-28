<?php

namespace Tests\Feature;

use App\Models\Coach;
use App\Models\CoachSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_class_schedules(): void
    {
        $this->get(route('classes.index'))
            ->assertRedirect(route('login'));
    }

    public function test_members_can_view_active_coach_class_schedules(): void
    {
        $coach = Coach::create([
            'name' => 'Ava Coach',
            'specialty' => 'Strength training',
            'bio' => 'Certified strength coach.',
            'is_active' => true,
        ]);

        CoachSchedule::create([
            'coach_id' => $coach->id,
            'day' => 'Monday',
            'time_from' => '9:00 AM',
            'time_to' => '10:00 AM',
            'class_name' => 'Strength Foundations',
            'location' => 'Main floor',
        ]);

        $member = User::factory()->create();

        $this->actingAs($member)
            ->get(route('classes.index'))
            ->assertOk()
            ->assertSee('Strength Foundations')
            ->assertSee('Ava Coach')
            ->assertSee('Monday');
    }
}