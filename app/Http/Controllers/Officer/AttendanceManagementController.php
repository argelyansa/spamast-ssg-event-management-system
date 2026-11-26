<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceManagementController extends Controller
{
    public function index(
        Request $request,
        Event $event,
        EventSession $session
    ): View {
        $this->ensureSessionBelongsToEvent($event, $session);

        $students = Student::query()
            ->with([
                'user',
                'program.institute',
                'attendances' => function ($query) use ($session) {
                    $query->where('event_session_id', $session->id);
                },
            ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($studentQuery) use ($search) {
                    $studentQuery
                        ->where('student_no', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('institute_id'), function ($query) use ($request) {
                $query->whereHas('program', function ($programQuery) use ($request) {
                    $programQuery->where(
                        'institute_id',
                        $request->input('institute_id')
                    );
                });
            })
            ->when($request->filled('program_id'), function ($query) use ($request) {
                $query->where('program_id', $request->input('program_id'));
            })
            ->orderBy('student_no')
            ->paginate(15)
            ->withQueryString();

        $institutes = Institute::orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        $totalStudents = Student::count();

        $checkedInCount = Attendance::query()
            ->where('event_session_id', $session->id)
            ->whereNotNull('check_in_at')
            ->count();

        $checkedOutCount = Attendance::query()
            ->where('event_session_id', $session->id)
            ->whereNotNull('check_out_at')
            ->count();

        $notCheckedInCount = max(0, $totalStudents - $checkedInCount);

        return view('officer.attendance.index', compact(
            'event',
            'session',
            'students',
            'institutes',
            'programs',
            'totalStudents',
            'checkedInCount',
            'checkedOutCount',
            'notCheckedInCount'
        ));
    }

    public function store(
        Request $request,
        Event $event,
        EventSession $session
    ): RedirectResponse {
        $this->ensureSessionBelongsToEvent($event, $session);

        $data = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
                Rule::unique('attendances', 'student_id')
                    ->where(fn ($query) => $query->where(
                        'event_session_id',
                        $session->id
                    )),
            ],
            'check_in_at' => [
                'nullable',
                'date',
                'required_with:check_out_at',
            ],
            'check_out_at' => [
                'nullable',
                'date',
                'after:check_in_at',
            ],
        ]);

        $session->attendances()->create([
            'student_id' => $data['student_id'],
            'check_in_at' => $data['check_in_at'] ?? null,
            'check_out_at' => $data['check_out_at'] ?? null,
        ]);

        return redirect()
            ->route('officer.attendance.index', [$event, $session])
            ->with('success', 'Attendance record added successfully.');
    }

    public function update(
        Request $request,
        Event $event,
        EventSession $session,
        Attendance $attendance
    ): RedirectResponse {
        $this->ensureSessionBelongsToEvent($event, $session);

        $attendance = $session->attendances()
            ->findOrFail($attendance->id);

        $data = $request->validate([
            'check_in_at' => [
                'nullable',
                'date',
                'required_with:check_out_at',
            ],
            'check_out_at' => [
                'nullable',
                'date',
                'after:check_in_at',
            ],
        ]);

        $attendance->update([
            'check_in_at' => $data['check_in_at'] ?? null,
            'check_out_at' => $data['check_out_at'] ?? null,
        ]);

        return redirect()
            ->route('officer.attendance.index', [$event, $session])
            ->with('success', 'Attendance record updated successfully.');
    }

    public function destroy(
        Event $event,
        EventSession $session,
        Attendance $attendance
    ): RedirectResponse {
        $this->ensureSessionBelongsToEvent($event, $session);

        $attendance = $session->attendances()
            ->findOrFail($attendance->id);

        $attendance->delete();

        return redirect()
            ->route('officer.attendance.index', [$event, $session])
            ->with('success', 'Attendance record deleted successfully.');
    }

    private function ensureSessionBelongsToEvent(
        Event $event,
        EventSession $session
    ): void {
        abort_unless(
            (int) $session->event_id === (int) $event->id,
            404
        );
    }
}
