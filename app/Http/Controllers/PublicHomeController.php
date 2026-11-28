<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;

class PublicHomeController extends Controller
{
    public function index()
    {
        $events = Event::query()
            ->where('show_on_homepage', true)
            ->whereDate('event_date', '>=', today())
            ->orderBy('event_date')
            ->limit(6)
            ->get([
                'id',
                'title',
                'description',
                'venue',
                'event_date',
            ]);

        $announcements = Announcement::query()
            ->where('show_on_homepage', true)
            ->latest()
            ->limit(4)
            ->get([
                'id',
                'title',
                'body',
                'created_at',
            ]);

        return view('public.home', compact('events', 'announcements'));
    }
}