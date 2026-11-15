<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventUpdateTest extends TestCase
{
    use RefreshDatabase;

    private function createOfficer(): User
    {
        $role = Role::firstOrCreate(['name' => 'Officer']);

        return User::factory()->create([
            'role_id' => $role->id,
        ]);
    }

    private function createEventWithSessions(User $officer): Event
    {
        $event = Event::create([
            'title' => 'Event Update Test',
            'description' => 'Testing event updates.',
            'venue' => 'SPAMAST Campus',
            'event_date' => '2026-11-20',
            'day_type' => 'whole_day',
            'availability' => 'scheduled',
            'fine_per_miss' => 50.00,
            'created_by' => $officer->id,
        ]);

        $event->sessions()->createMany([
            [
                'period' => 'morning',
                'check_in_start' => '08:00',
                'check_in_end' => '09:00',
                'check_out_start' => '11:00',
                'check_out_end' => '12:00',
            ],
            [
                'period' => 'afternoon',
                'check_in_start' => '13:00',
                'check_in_end' => '14:00',
                'check_out_start' => '16:00',
                'check_out_end' => '17:00',
            ],
        ]);

        return $event;
    }

    private function halfDayUpdateData(): array
    {
        return [
            'title' => 'Event Update Test',
            'description' => 'Testing event updates.',
            'venue' => 'SPAMAST Campus',
            'event_date' => '2026-11-20',
            'day_type' => 'half_day',
            'availability' => 'scheduled',
            'fine_per_miss' => 50.00,
            'sessions' => [
                'half_day' => [
                    'check_in_start' => '08:00',
                    'check_in_end' => '09:00',
                    'check_out_start' => '11:00',
                    'check_out_end' => '12:00',
                ],
            ],
        ];
    }

    public function test_event_day_type_cannot_change_when_attendance_exists(): void
    {
        $officer = $this->createOfficer();
        $event = $this->createEventWithSessions($officer);

        $studentRole = Role::firstOrCreate(['name' => 'Student']);

        $studentUser = User::factory()->create([
            'role_id' => $studentRole->id,
        ]);

        $institute = Institute::create([
            'code' => 'TEST',
            'name' => 'Test Institute',
        ]);

        $program = Program::create([
            'institute_id' => $institute->id,
            'code' => 'TEST-PROG',
            'name' => 'Test Program',
        ]);

        $student = Student::create([
            'user_id' => $studentUser->id,
            'program_id' => $program->id,
            'student_no' => 'TEST-2026-001',
            'sex' => 'Male',
            'year_level' => 1,
        ]);

        $morningSession = $event->sessions()
            ->where('period', 'morning')
            ->firstOrFail();

        Attendance::create([
            'event_session_id' => $morningSession->id,
            'student_id' => $student->id,
            'check_in_at' => now(),
        ]);

        $response = $this->actingAs($officer)->put(
            route('officer.events.update', $event),
            $this->halfDayUpdateData()
        );

        $response->assertSessionHasErrors('day_type');

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'day_type' => 'whole_day',
        ]);

        $this->assertDatabaseCount('event_sessions', 2);

        $this->assertDatabaseHas('attendances', [
            'event_session_id' => $morningSession->id,
            'student_id' => $student->id,
        ]);
    }

    public function test_event_day_type_can_change_when_no_attendance_exists(): void
    {
        $officer = $this->createOfficer();
        $event = $this->createEventWithSessions($officer);

        $response = $this->actingAs($officer)->put(
            route('officer.events.update', $event),
            $this->halfDayUpdateData()
        );

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'day_type' => 'half_day',
        ]);

        $this->assertDatabaseCount('event_sessions', 1);

        $this->assertDatabaseHas('event_sessions', [
            'event_id' => $event->id,
            'period' => 'half_day',
            'check_in_start' => '08:00',
            'check_in_end' => '09:00',
            'check_out_start' => '11:00',
            'check_out_end' => '12:00',
        ]);
    }
}
