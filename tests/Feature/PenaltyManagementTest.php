<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Institute;
use App\Models\Penalty;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenaltyManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createPenaltyFixture(): array
    {
        $officerRole = Role::firstOrCreate(['name' => 'Officer']);
        $adviserRole = Role::firstOrCreate(['name' => 'Adviser']);
        $studentRole = Role::firstOrCreate(['name' => 'Student']);

        $officer = User::factory()->create([
            'role_id' => $officerRole->id,
        ]);

        $adviser = User::factory()->create([
            'role_id' => $adviserRole->id,
        ]);

        $studentUser = User::factory()->create([
            'role_id' => $studentRole->id,
        ]);

        $institute = Institute::create([
            'code' => 'PEN-TEST',
            'name' => 'Penalty Test Institute',
        ]);

        $program = Program::create([
            'institute_id' => $institute->id,
            'code' => 'PEN-PROG',
            'name' => 'Penalty Test Program',
        ]);

        $student = Student::create([
            'user_id' => $studentUser->id,
            'program_id' => $program->id,
            'student_no' => 'PEN-TEST-001',
            'sex' => 'Male',
            'year_level' => 1,
        ]);

        $event = Event::create([
            'title' => 'Penalty Test Event',
            'description' => 'Testing penalty generation.',
            'venue' => 'SPAMAST Campus',
            'event_date' => today(),
            'day_type' => 'whole_day',
            'availability' => 'manual',
            'fine_per_miss' => 50.00,
            'created_by' => $officer->id,
        ]);

        $morning = $event->sessions()->create([
            'period' => 'morning',
        ]);

        $afternoon = $event->sessions()->create([
            'period' => 'afternoon',
        ]);

        return compact(
            'officer',
            'adviser',
            'student',
            'program',
            'event',
            'morning',
            'afternoon'
        );
    }

    private function createAdditionalStudent(Program $program): Student
    {
        $role = Role::firstOrCreate(['name' => 'Student']);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        return Student::create([
            'user_id' => $user->id,
            'program_id' => $program->id,
            'student_no' => 'PEN-TEST-002',
            'sex' => 'Female',
            'year_level' => 2,
        ]);
    }

    public function test_officer_generates_correct_penalties_and_preserves_existing_sanction_and_status(): void
    {
        $data = $this->createPenaltyFixture();

        $student = $data['student'];
        $event = $data['event'];

        $completeStudent = $this->createAdditionalStudent($data['program']);

        // First student completes both scans in the morning session only.
        Attendance::create([
            'event_session_id' => $data['morning']->id,
            'student_id' => $student->id,
            'check_in_at' => now(),
            'check_out_at' => now(),
        ]);

        // Second student completes all four required scans.
        foreach ([$data['morning'], $data['afternoon']] as $session) {
            Attendance::create([
                'event_session_id' => $session->id,
                'student_id' => $completeStudent->id,
                'check_in_at' => now(),
                'check_out_at' => now(),
            ]);
        }

        Penalty::create([
            'event_id' => $event->id,
            'student_id' => $student->id,
            'missed_scans' => 1,
            'amount' => 50.00,
            'sanction' => 'Original sanction',
            'status' => 'paid',
        ]);

        Penalty::create([
            'event_id' => $event->id,
            'student_id' => $completeStudent->id,
            'missed_scans' => 1,
            'amount' => 50.00,
            'sanction' => 'Old record',
            'status' => 'unpaid',
        ]);

        $response = $this->actingAs($data['officer'])
            ->post(route('officer.penalties.generate', $event));

        $response->assertRedirect(route('penalties.index', $event));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('penalties', [
            'event_id' => $event->id,
            'student_id' => $student->id,
            'missed_scans' => 2,
            'amount' => 100.00,
            'sanction' => 'Original sanction',
            'status' => 'paid',
        ]);

        // A student with no missed scans should have no penalty record.
        $this->assertDatabaseMissing('penalties', [
            'event_id' => $event->id,
            'student_id' => $completeStudent->id,
        ]);

        $this->assertDatabaseCount('penalties', 1);
    }

    public function test_adviser_can_view_penalties_but_cannot_generate_edit_or_delete_them(): void
    {
        $data = $this->createPenaltyFixture();

        $penalty = Penalty::create([
            'event_id' => $data['event']->id,
            'student_id' => $data['student']->id,
            'missed_scans' => 2,
            'amount' => 100.00,
            'sanction' => 'Read-only test',
            'status' => 'unpaid',
        ]);

        $this->actingAs($data['adviser'])
            ->get(route('penalties.index', $data['event']))
            ->assertOk();

        $this->actingAs($data['adviser'])
            ->post(route('officer.penalties.generate', $data['event']))
            ->assertForbidden();

        $this->actingAs($data['adviser'])
            ->put(route('officer.penalties.update', [$data['event'], $penalty]), [
                'amount' => 200,
                'sanction' => 'Unauthorized change',
                'status' => 'paid',
            ])
            ->assertForbidden();

        $this->actingAs($data['adviser'])
            ->delete(route('officer.penalties.destroy', [$data['event'], $penalty]))
            ->assertForbidden();

        $this->assertDatabaseHas('penalties', [
            'id' => $penalty->id,
            'amount' => 100.00,
            'sanction' => 'Read-only test',
            'status' => 'unpaid',
        ]);
    }

    public function test_officer_can_update_and_delete_a_penalty(): void
    {
        $data = $this->createPenaltyFixture();

        $penalty = Penalty::create([
            'event_id' => $data['event']->id,
            'student_id' => $data['student']->id,
            'missed_scans' => 2,
            'amount' => 100.00,
            'sanction' => null,
            'status' => 'unpaid',
        ]);

        $this->actingAs($data['officer'])
            ->put(route('officer.penalties.update', [$data['event'], $penalty]), [
                'amount' => 75.00,
                'sanction' => 'Approved alternative sanction',
                'status' => 'served',
            ])
            ->assertRedirect(route('penalties.index', $data['event']));

        $this->assertDatabaseHas('penalties', [
            'id' => $penalty->id,
            'amount' => 75.00,
            'sanction' => 'Approved alternative sanction',
            'status' => 'served',
        ]);

        $this->actingAs($data['officer'])
            ->delete(route('officer.penalties.destroy', [$data['event'], $penalty]))
            ->assertRedirect(route('penalties.index', $data['event']));

        $this->assertDatabaseMissing('penalties', [
            'id' => $penalty->id,
        ]);
    }
}
