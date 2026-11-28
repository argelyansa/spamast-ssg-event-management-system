<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::with('sessions')
            ->latest('event_date')
            ->paginate(10);

        return view('adviser.events.index', compact('events'));
    }

    public function penaltiesEvents(Request $request): View
    {
        $validated = $request->validate([
            'tab' => ['nullable', 'in:upcoming,past'],
            'event_date' => ['nullable', 'date'],
        ]);

        $activeTab = $validated['tab'] ?? 'upcoming';

        $query = Event::query();

        if ($activeTab === 'upcoming') {
            $query->whereDate('event_date', '>=', today());
        } else {
            $query->whereDate('event_date', '<', today());
        }

        if (!empty($validated['event_date'])) {
            $query->whereDate('event_date', $validated['event_date']);
        }

        $events = $query
            ->latest('event_date')
            ->paginate(10)
            ->withQueryString();

        $searchDate = $validated['event_date'] ?? '';

        return view('adviser.penalties.events', compact(
            'events',
            'activeTab',
            'searchDate'
        ));
    }
}
