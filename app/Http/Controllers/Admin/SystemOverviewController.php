<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Institute;
use App\Models\Program;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Attendance;
use App\Models\Penalty;
use App\Models\Announcement;
use Illuminate\View\View;

class SystemOverviewController extends Controller
{
    public function index(): View
    {
        $roleStats = Role::withCount('users')
            ->orderBy('name')
            ->get();

        $stats = [
            // Accounts and academic records
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_institutes' => Institute::count(),
            'total_programs' => Program::count(),

            // Events and attendance
            'total_events' => Event::count(),
            'upcoming_events' => Event::whereDate('event_date', '>=', today())->count(),
            'past_events' => Event::whereDate('event_date', '<', today())->count(),
            'total_sessions' => EventSession::count(),
            'total_attendance' => Attendance::count(),
            'checked_in' => Attendance::whereNotNull('check_in_at')->count(),
            'checked_out' => Attendance::whereNotNull('check_out_at')->count(),

            // Fines and sanctions
            'total_penalties' => Penalty::count(),
            'unpaid_penalties' => Penalty::where('status', 'unpaid')->count(),
            'paid_penalties' => Penalty::where('status', 'paid')->count(),
            'served_penalties' => Penalty::where('status', 'served')->count(),
            'unpaid_amount' => Penalty::where('status', 'unpaid')->sum('amount'),

            // Announcements
            'total_announcements' => Announcement::count(),
            'announcements_with_attachments' => Announcement::whereNotNull('attachment')
                ->where('attachment', '!=', '')
                ->count(),
        ];

        return view('admin.system-overview.index', compact('stats', 'roleStats'));
    }
}
