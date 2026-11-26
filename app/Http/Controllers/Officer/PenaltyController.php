<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Institute;
use App\Models\Penalty;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PenaltyController extends Controller
{
    public function index(Request $request, Event $event): View
    {
        $penalties = Penalty::query()
            ->with([
                'student.user',
                'student.program.institute',
            ])
            ->where('event_id', $event->id)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery
                        ->where('student_no', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('institute_id'), function ($query) use ($request) {
                $query->whereHas('student.program', function ($programQuery) use ($request) {
                    $programQuery->where(
                        'institute_id',
                        $request->input('institute_id')
                    );
                });
            })
            ->when($request->filled('program_id'), function ($query) use ($request) {
                $query->whereHas('student', function ($studentQuery) use ($request) {
                    $studentQuery->where(
                        'program_id',
                        $request->input('program_id')
                    );
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->orderByDesc('missed_scans')
            ->orderBy('student_id')
            ->paginate(15)
            ->withQueryString();

        $institutes = Institute::orderBy('name')->get();
        $programs = Program::orderBy('name')->get();

        $totalPenalties = Penalty::where('event_id', $event->id)->count();

        $totalAmount = Penalty::where('event_id', $event->id)
            ->sum('amount');
        $programSummary = Penalty::with('student.program')
            ->where('event_id', $event->id)
            ->get()
            ->groupBy(function ($penalty) {
                return $penalty->student?->program_id ?? 'unassigned';
            })
            ->map(function (Collection $programPenalties) {
                $program = $programPenalties->first()?->student?->program;

                return [
                    'program_name' => $program?->name ?? 'Unassigned',
                    'student_count' => $programPenalties
                        ->pluck('student_id')
                        ->unique()
                        ->count(),
                    'total_amount' => $programPenalties->sum(
                        fn ($penalty) => (float) $penalty->amount
                    ),
                ];
            })
            ->sortByDesc('total_amount')
            ->values();

        return view('penalties.index', compact(
            'event',
            'penalties',
            'institutes',
            'programs',
            'totalPenalties',
            'totalAmount',
            'programSummary'
        ));
    }

    public function generate(Event $event): RedirectResponse
    {
        $sessionIds = $event->sessions()->pluck('id');

        if ($sessionIds->isEmpty()) {
            return back()->with(
                'error',
                'Cannot generate penalties because this event has no sessions.'
            );
        }

        $requiredScans = $sessionIds->count() * 2;

        DB::transaction(function () use ($event, $sessionIds, $requiredScans) {
            Student::query()
                ->with([
                    'attendances' => function ($query) use ($sessionIds) {
                        $query->whereIn('event_session_id', $sessionIds);
                    },
                ])
                ->chunkById(200, function ($students) use ($event, $requiredScans) {
                    foreach ($students as $student) {
                        $completedScans = $student->attendances->sum(
                            function ($attendance) {
                                return (int) ($attendance->check_in_at !== null)
                                    + (int) ($attendance->check_out_at !== null);
                            }
                        );

                        $missedScans = max(
                            0,
                            $requiredScans - $completedScans
                        );

                        if ($missedScans === 0) {
                            Penalty::where('event_id', $event->id)
                                ->where('student_id', $student->id)
                                ->delete();

                            continue;
                        }

                        Penalty::updateOrCreate(
                            [
                                'event_id' => $event->id,
                                'student_id' => $student->id,
                            ],
                            [
                                'missed_scans' => $missedScans,
                                'amount' => $missedScans * (float) $event->fine_per_miss,
                            ]
                        );
                    }
                });
        });

        return redirect()
            ->route('penalties.index', $event)
            ->with('success', 'Penalties generated successfully.');
    }

    public function update(
        Request $request,
        Event $event,
        Penalty $penalty
    ): RedirectResponse {
        abort_unless((int) $penalty->event_id === (int) $event->id, 404);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'sanction' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:unpaid,paid,served'],
        ]);

        $penalty->update($validated);

        return redirect()
            ->route('penalties.index', $event)
            ->with('success', 'Penalty updated successfully.');
    }

    public function destroy(Event $event, Penalty $penalty): RedirectResponse
    {
        abort_unless((int) $penalty->event_id === (int) $event->id, 404);

        $penalty->delete();

        return redirect()
            ->route('penalties.index', $event)
            ->with('success', 'Penalty deleted successfully.');
    }
}
