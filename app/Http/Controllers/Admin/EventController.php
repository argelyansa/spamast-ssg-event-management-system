<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_events' => Event::count(),
            'upcoming_events' => Event::whereDate('event_date', '>=', today())->count(),
            'past_events' => Event::whereDate('event_date', '<', today())->count(),
            'attendance_records' => Attendance::count(),
        ];

        $events = Event::with(['creator', 'sessions'])
            ->withCount(['attendances', 'penalties'])
            ->orderBy('event_date', 'desc')
            ->paginate(10);

        return view('admin.events.index', compact('events', 'stats'));
    }
}
