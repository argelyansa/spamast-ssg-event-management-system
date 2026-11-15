<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::with('sessions')
            ->whereDate('event_date', '>=', today())
            ->orderBy('event_date')
            ->paginate(10);

        return view('student.events.index', compact('events'));
    }

    public function show(Request $request, Event $event): View
    {
        $student = $request->user()->student;

        $event->load([
            'sessions' => function ($query) use ($student) {
                $query->with([
                    'attendances' => function ($attendanceQuery) use ($student) {
                        if ($student) {
                            $attendanceQuery->where('student_id', $student->id);
                        } else {
                            $attendanceQuery->whereRaw('1 = 0');
                        }
                    },
                ]);
            },
        ]);

        return view('student.events.show', compact('event', 'student'));
    }
}
