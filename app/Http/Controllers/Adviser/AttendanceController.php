<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function events(): View
    {
        $events = Event::with('sessions')
            ->latest('event_date')
            ->paginate(10);

        return view('adviser.attendance.events', compact('events'));
    }

    public function index(
        Request $request,
        Event $event,
        EventSession $session
    ): View {
        abort_unless(
            (int) $session->event_id === (int) $event->id,
            404
        );

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

        $notCheckedInCount = max(
            0,
            $totalStudents - $checkedInCount
        );

        return view('adviser.attendance.index', compact(
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
}
