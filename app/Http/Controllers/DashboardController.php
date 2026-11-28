<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\Institute;
use App\Models\Penalty;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function admin()
    {
        $stats = Cache::remember('admin.stats', now()->addMinutes(10), function () {
            return [
                'users_by_role' => Role::withCount('users')
                    ->get()
                    ->mapWithKeys(fn ($role) => [$role->name => $role->users_count])
                    ->all(),
                'institutes' => Institute::count(),
                'programs' => Program::count(),
            ];
        });

        return view('dashboards.admin', compact('stats'));
    }

    public function officer()
    {
        $stats = Cache::remember('officer.stats', now()->addMinutes(10), function () {
            return [
                'students' => Student::count(),
                'upcoming_events' => Event::whereDate('event_date', '>=', today())->count(),
                'unpaid_fines' => Penalty::where('status', 'unpaid')->count(),
                'announcements' => Announcement::count(),
            ];
        });

        $latestAnnouncements = Announcement::with('user')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboards.officer', compact('stats', 'latestAnnouncements'));
    }

    public function adviser()
    {
        $stats = Cache::remember('adviser.stats', now()->addMinutes(10), function () {
            return [
                'upcoming_events' => Event::whereDate('event_date', '>=', today())->count(),
                'attendance_records' => \App\Models\Attendance::count(),
                'unpaid_fines' => Penalty::where('status', 'unpaid')->count(),
            ];
        });

        $latestAnnouncements = Announcement::with('user')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboards.adviser', compact('stats', 'latestAnnouncements'));
    }

    public function student()
    {
        $user = auth()->user();

        $stats = Cache::remember("student.stats.{$user->id}", now()->addMinutes(10), function () use ($user) {
            $student = Student::where('user_id', $user->id)->first();

            return [
                'upcoming_events' => Event::whereDate('event_date', '>=', today())->count(),
                'attendance_records' => $student ? $student->attendances()->count() : 0,
            ];
        });

        $latestAnnouncements = Announcement::with('user')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboards.student', compact('stats', 'latestAnnouncements'));
    }
}
