<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSession;
use App\Support\DashboardStatsCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'tab' => ['nullable', 'in:upcoming,past'],
            'event_date' => ['nullable', 'date'],
        ]);

        $activeTab = $validated['tab'] ?? 'upcoming';

        $query = Event::with('creator');

        if ($activeTab === 'upcoming') {
            $query->whereDate('event_date', '>=', today());
        } else {
            $query->whereDate('event_date', '<', today());
        }

        if (!empty($validated['event_date'])) {
            $query->whereDate('event_date', $validated['event_date']);
        }

        $events = $query
            ->orderBy('event_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        $searchDate = $validated['event_date'] ?? '';

        return view('officer.events.index', compact(
            'events',
            'activeTab',
            'searchDate'
        ));
    }

    public function create(): View
    {
        return view('officer.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $activePeriods = match ($request->input('day_type')) {
            'whole_day' => ['morning', 'afternoon'],
            'half_day' => ['half_day'],
            default => [],
        };

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'venue' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'day_type' => ['required', 'in:whole_day,half_day'],
            'availability' => ['required', 'in:scheduled,manual'],
            'fine_per_miss' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
            'show_on_homepage' => ['nullable', 'boolean'],
            'sessions' => ['required', 'array'],
        ];

        $timeFields = [
            'check_in_start',
            'check_in_end',
            'check_out_start',
            'check_out_end',
        ];

        foreach ($activePeriods as $period) {
            $rules["sessions.$period"] = ['required', 'array'];

            foreach ($timeFields as $field) {
                $fieldRules = [
                    'required_if:availability,scheduled',
                    'nullable',
                    'date_format:H:i',
                ];

                if (in_array($field, ['check_in_end', 'check_out_end'])) {
                    $startField = $field === 'check_in_end'
                        ? 'check_in_start'
                        : 'check_out_start';

                    $fieldRules[] = "after:sessions.$period.$startField";
                }

                $rules["sessions.$period.$field"] = $fieldRules;
            }
        }

        $validated = $request->validate($rules);
        $validated['show_on_homepage'] = $request->boolean('show_on_homepage');

        $event = DB::transaction(function () use (
            $validated,
            $request,
            $activePeriods
        ) {
            $eventData = collect($validated)
                ->except('sessions')
                ->all();

            $eventData['created_by'] = $request->user()->id;

            $event = Event::create($eventData);

            foreach ($activePeriods as $period) {
                $sessionData = $validated['sessions'][$period] ?? [];

                $event->sessions()->create([
                    'period' => $period,
                    'check_in_start' => $sessionData['check_in_start'] ?? null,
                    'check_in_end' => $sessionData['check_in_end'] ?? null,
                    'check_out_start' => $sessionData['check_out_start'] ?? null,
                    'check_out_end' => $sessionData['check_out_end'] ?? null,
                ]);
            }

            return $event;
        });

        DashboardStatsCache::forgetEventStats();

        return redirect()
            ->route('officer.events.index')
            ->with('success', 'Event and sessions created successfully.');
    }

    public function show(Event $event): View
    {
        $event->load([
            'creator',
            'sessions.attendances.student.user',
        ]);

        return view('officer.events.show', compact('event'));
    }

    public function toggleSession(Request $request, EventSession $session): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:check_in,check_out'],
        ]);

        $event = $session->event;

        abort_unless(
            $event && $event->availability === 'manual',
            403,
            'Only manual availability sessions can be opened or closed manually.'
        );

        $column = $validated['type'] . '_open';

        $newStatus = !(bool) $session->{$column};

        $session->update([
            $column => $newStatus,
        ]);

        $activity = $newStatus ? 'opened' : 'closed';
        $label = $validated['type'] === 'check_in'
            ? 'Check-in'
            : 'Check-out';

        return redirect()
            ->route('officer.events.show', $event)
            ->with('success', "{$label} has been {$activity} successfully.");
    }

    public function edit(Event $event): View
    {
        Gate::authorize('update', $event);

        $event->load('sessions');

        return view('officer.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        Gate::authorize('update', $event);

        $activePeriods = match ($request->input('day_type')) {
            'whole_day' => ['morning', 'afternoon'],
            'half_day' => ['half_day'],
            default => [],
        };

        $timeFields = [
            'check_in_start',
            'check_in_end',
            'check_out_start',
            'check_out_end',
        ];

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'venue' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'day_type' => ['required', 'in:whole_day,half_day'],
            'availability' => ['required', 'in:scheduled,manual'],
            'fine_per_miss' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
            'show_on_homepage' => ['nullable', 'boolean'],
            'sessions' => ['required', 'array'],
        ];

        foreach ($activePeriods as $period) {
            $rules["sessions.$period"] = ['required', 'array'];

            foreach ($timeFields as $field) {
                $fieldRules = [
                    'required_if:availability,scheduled',
                    'nullable',
                    'date_format:H:i',
                ];

                if (in_array($field, ['check_in_end', 'check_out_end'])) {
                    $startField = $field === 'check_in_end'
                        ? 'check_in_start'
                        : 'check_out_start';

                    $fieldRules[] = "after:sessions.$period.$startField";
                }

                $rules["sessions.$period.$field"] = $fieldRules;
            }
        }

        $validated = $request->validate($rules);
        $validated['show_on_homepage'] = $request->boolean('show_on_homepage');

        DB::transaction(function () use (
            $validated,
            $event,
            $activePeriods,
            $timeFields
        ) {
            $lockedEvent = Event::query()
                ->whereKey($event->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $dayTypeChanged = $lockedEvent->day_type !== $validated['day_type'];

            if ($dayTypeChanged) {
                $hasAttendance = $lockedEvent->sessions()
                    ->whereHas('attendances')
                    ->exists();

                if ($hasAttendance) {
                    throw ValidationException::withMessages([
                        'day_type' => 'The day type cannot be changed because attendance records already exist for this event. Existing attendance must be protected.',
                    ]);
                }

                // Safe to rebuild sessions because no attendance exists.
                $lockedEvent->sessions()->delete();
            }

            $eventData = collect($validated)
                ->except('sessions')
                ->all();

            $lockedEvent->update($eventData);

            foreach ($activePeriods as $period) {
                $submittedSession = $validated['sessions'][$period] ?? [];

                $sessionData = [
                    'period' => $period,
                ];

                foreach ($timeFields as $field) {
                    $value = $submittedSession[$field] ?? null;

                    $sessionData[$field] = blank($value)
                        ? null
                        : $value;
                }

                // updateOrCreate preserves existing session IDs and
                // manual open/close flags when the day type is unchanged.
                $lockedEvent->sessions()->updateOrCreate(
                    ['period' => $period],
                    $sessionData
                );
            }
        });

        DashboardStatsCache::forgetEventStats();

        return redirect()
            ->route('officer.events.index')
            ->with('success', 'Event and session details updated successfully.');
    }

    public function penaltiesEvents(Request $request): View
    {
        $validated = $request->validate([
            'tab' => ['nullable', 'in:upcoming,past'],
            'event_date' => ['nullable', 'date'],
        ]);

        $activeTab = $validated['tab'] ?? 'upcoming';

        $query = Event::with('creator');

        if ($activeTab === 'upcoming') {
            $query->whereDate('event_date', '>=', today());
        } else {
            $query->whereDate('event_date', '<', today());
        }

        if (!empty($validated['event_date'])) {
            $query->whereDate('event_date', $validated['event_date']);
        }

        $events = $query
            ->orderBy('event_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        $searchDate = $validated['event_date'] ?? '';

        return view('officer.penalties.events', compact(
            'events',
            'activeTab',
            'searchDate'
        ));
    }
    public function destroy(Event $event): RedirectResponse
    {
        Gate::authorize('delete', $event);

        $event->delete();

        DashboardStatsCache::forgetEventStats();

        return redirect()
            ->route('officer.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
