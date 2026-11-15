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

class StudentAttendanceTest extends TestCase
{
    use RefreshDatabase;

    private function createStudentWithSession(): array
    {
        $studentRole = Role::firstOrCreate([
            'name' => 'Student',
        ]);

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

        $event = Event::create([
            'title' => 'Attendance Test Event',
            'description' => 'Testing student attendance.',
            'venue' => 'SPAMAST Campus',
            'event_date' => today(),
            'day_type' => 'half_day',
            'availability' => 'manual',
            'fine_per_miss' => 50.00,
            'created_by' => null,
        ]);

        $session = $event->sessions()->create([
            'period' => 'half_day',
            'check_in_open' => true,
            'check_out_open' => true,
        ]);

        return [$studentUser, $student, $session];
    }

    public function test_student_can_check_in_when_session_is_open(): void
    {
        [$studentUser, $student, $session] = $this->createStudentWithSession();

        $response = $this->actingAs($studentUser)
            ->from('/student/events')
            ->post(route('student.attendance.check-in', $session));

        $response->assertRedirect('/student/events');
        $response->assertSessionHas('success', 'Check-in recorded successfully.');

        $this->assertDatabaseHas('attendances', [
            'event_session_id' => $session->id,
            'student_id' => $student->id,
        ]);

        $attendance = Attendance::where('event_session_id', $session->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $this->assertNotNull($attendance->check_in_at);
    }

    public function test_student_cannot_create_duplicate_check_in_records(): void
    {
        [$studentUser, $student, $session] = $this->createStudentWithSession();

        $this->actingAs($studentUser)
            ->post(route('student.attendance.check-in', $session));

        $response = $this->actingAs($studentUser)
            ->from('/student/events')
            ->post(route('student.attendance.check-in', $session));

        $response->assertRedirect('/student/events');
        $response->assertSessionHas('info', 'You have already checked in for this session.');

        $this->assertDatabaseCount('attendances', 1);

        $this->assertDatabaseHas('attendances', [
            'event_session_id' => $session->id,
            'student_id' => $student->id,
        ]);
    }

    public function test_student_cannot_check_out_before_checking_in(): void
    {
        [$studentUser, , $session] = $this->createStudentWithSession();

        $response = $this->actingAs($studentUser)
            ->post(route('student.attendance.check-out', $session));

        $response->assertNotFound();

        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_student_can_check_out_after_checking_in(): void
    {
        [$studentUser, $student, $session] = $this->createStudentWithSession();

        $this->actingAs($studentUser)
            ->post(route('student.attendance.check-in', $session));

        $response = $this->actingAs($studentUser)
            ->from('/student/events')
            ->post(route('student.attendance.check-out', $session));

        $response->assertRedirect('/student/events');
        $response->assertSessionHas('success', 'Check-out recorded successfully.');

        $attendance = Attendance::where('event_session_id', $session->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $this->assertNotNull($attendance->check_in_at);
        $this->assertNotNull($attendance->check_out_at);

        $this->assertDatabaseCount('attendances', 1);
    }
}
