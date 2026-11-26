<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'tab' => ['nullable', 'in:upcoming,past'],
            'date' => ['nullable', 'date'],
        ]);

        $tab = $validated['tab'] ?? 'upcoming';
        $date = $validated['date'] ?? null;

        $eventsQuery = Event::with('sessions');

        if ($tab === 'upcoming') {
            $eventsQuery->whereDate('event_date', '>=', today());
        } else {
            $eventsQuery->whereDate('event_date', '<', today());
        }

        if ($date) {
            $eventsQuery->whereDate('event_date', $date);
        }

        $events = $eventsQuery
            ->orderBy($tab === 'upcoming' ? 'event_date' : 'event_date', $tab === 'upcoming' ? 'asc' : 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('student.events.index', compact('events', 'tab', 'date'));
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
