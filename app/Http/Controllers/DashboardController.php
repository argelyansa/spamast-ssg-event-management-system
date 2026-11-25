<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboards.admin');
    }

    public function officer()
    {
        $latestAnnouncements = Announcement::with('user')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboards.officer', compact('latestAnnouncements'));
    }

    public function adviser()
    {
        $latestAnnouncements = Announcement::with('user')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboards.adviser', compact('latestAnnouncements'));
    }

    public function student()
    {
        $latestAnnouncements = Announcement::with('user')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboards.student', compact('latestAnnouncements'));
    }
}
