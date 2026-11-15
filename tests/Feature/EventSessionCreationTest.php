<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventSessionCreationTest extends TestCase
{
    use RefreshDatabase;

    private function createOfficer(): User
    {
        $role = Role::firstOrCreate(
            ['name' => 'Officer']
        );

        return User::factory()->create([
            'role_id' => $role->id,
        ]);
    }

    private function createScheduledSession(): EventSession
    {
        $officer = $this->createOfficer();

        $event = Event::create([
            'title' => 'Scheduled Availability Test',
            'description' => 'Testing scheduled availability.',
            'venue' => 'SPAMAST Campus',
            'event_date' => '2026-11-20',
            'day_type' => 'whole_day',
            'availability' => 'scheduled',
            'fine_per_miss' => 50.00,
            'created_by' => $officer->id,
        ]);

        return $event->sessions()->create([
            'period' => 'morning',
            'check_in_start' => '08:00',
            'check_in_end' => '09:00',
            'check_out_start' => '11:00',
            'check_out_end' => '12:00',
        ]);
    }

    public function test_whole_day_event_creates_morning_and_afternoon_sessions(): void
    {
        $officer = $this->createOfficer();

        $response = $this->actingAs($officer)->post(
            route('officer.events.store'),
            [
                'title' => 'Test Whole Day Event',
                'description' => 'Testing whole day session creation.',
                'venue' => 'SPAMAST Campus',
                'event_date' => '2026-11-20',
                'day_type' => 'whole_day',
                'availability' => 'scheduled',
                'fine_per_miss' => 50.00,
                'sessions' => [
                    'morning' => [
                        'check_in_start' => '07:00',
                        'check_in_end' => '08:00',
                        'check_out_start' => '11:00',
                        'check_out_end' => '12:00',
                    ],
                    'afternoon' => [
                        'check_in_start' => '12:30',
                        'check_in_end' => '13:30',
                        'check_out_start' => '16:00',
                        'check_out_end' => '17:00',
                    ],
                ],
            ]
        );

        $event = Event::where('title', 'Test Whole Day Event')->first();

        $response->assertRedirect(route('officer.events.index'));
        $this->assertNotNull($event);
        $this->assertDatabaseCount('event_sessions', 2);

        $this->assertDatabaseHas('event_sessions', [
            'event_id' => $event->id,
            'period' => 'morning',
            'check_in_start' => '07:00',
            'check_in_end' => '08:00',
            'check_out_start' => '11:00',
            'check_out_end' => '12:00',
        ]);

        $this->assertDatabaseHas('event_sessions', [
            'event_id' => $event->id,
            'period' => 'afternoon',
            'check_in_start' => '12:30',
            'check_in_end' => '13:30',
            'check_out_start' => '16:00',
            'check_out_end' => '17:00',
        ]);
    }

    public function test_half_day_event_creates_one_half_day_session(): void
    {
        $officer = $this->createOfficer();

        $response = $this->actingAs($officer)->post(
            route('officer.events.store'),
            [
                'title' => 'Test Half Day Event',
                'description' => 'Testing half day session creation.',
                'venue' => 'SPAMAST Campus',
                'event_date' => '2026-11-21',
                'day_type' => 'half_day',
                'availability' => 'scheduled',
                'fine_per_miss' => 25.00,
                'sessions' => [
                    'half_day' => [
                        'check_in_start' => '07:00',
                        'check_in_end' => '08:00',
                        'check_out_start' => '11:00',
                        'check_out_end' => '12:00',
                    ],
                ],
            ]
        );

        $event = Event::where('title', 'Test Half Day Event')->first();

        $response->assertRedirect(route('officer.events.index'));
        $this->assertNotNull($event);
        $this->assertDatabaseCount('event_sessions', 1);

        $this->assertDatabaseHas('event_sessions', [
            'event_id' => $event->id,
            'period' => 'half_day',
            'check_in_start' => '07:00',
            'check_in_end' => '08:00',
            'check_out_start' => '11:00',
            'check_out_end' => '12:00',
        ]);
    }

    public function test_scheduled_check_in_opens_during_its_time_window(): void
    {
        $session = $this->createScheduledSession();

        $this->travelTo(now()->parse('2026-11-20 08:30:00'));

        $this->assertTrue($session->fresh()->isCheckInOpen());
    }

    public function test_scheduled_check_in_is_closed_before_its_time_window(): void
    {
        $session = $this->createScheduledSession();

        $this->travelTo(now()->parse('2026-11-20 07:30:00'));

        $this->assertFalse($session->fresh()->isCheckInOpen());
    }

    public function test_scheduled_check_out_opens_during_its_time_window(): void
    {
        $session = $this->createScheduledSession();

        $this->travelTo(now()->parse('2026-11-20 11:30:00'));

        $this->assertTrue($session->fresh()->isCheckOutOpen());
    }

    public function test_scheduled_check_out_is_closed_after_its_time_window(): void
    {
        $session = $this->createScheduledSession();

        $this->travelTo(now()->parse('2026-11-20 12:30:00'));

        $this->assertFalse($session->fresh()->isCheckOutOpen());
    }
}
