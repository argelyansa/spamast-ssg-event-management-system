<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function index()
    {
        Gate::authorize('view-reports');

        $events = Event::withCount('sessions')
            ->orderByDesc('event_date')
            ->get();

        return view('reports.index', compact('events'));
    }

    public function show(Event $event)
    {
        Gate::authorize('view-reports');

        $attendanceQuery = DB::table('attendances as a')
            ->join('event_sessions as es', 'a.event_session_id', '=', 'es.id')
            ->join('students as s', 'a.student_id', '=', 's.id')
            ->join('users as u', 's.user_id', '=', 'u.id')
            ->join('programs as p', 's.program_id', '=', 'p.id')
            ->join('institutes as i', 'p.institute_id', '=', 'i.id')
            ->where('es.event_id', $event->id)
            ->whereNotNull('a.check_in_at');

        $programSummaries = (clone $attendanceQuery)
            ->select(
                'i.id as institute_id',
                'i.code as institute_code',
                'i.name as institute_name',
                'p.id as program_id',
                'p.code as program_code',
                'p.name as program_name'
            )
            ->selectRaw('COUNT(DISTINCT a.student_id) as total_students')
            ->selectRaw("COUNT(DISTINCT CASE WHEN s.sex = 'Male' THEN a.student_id END) as male_count")
            ->selectRaw("COUNT(DISTINCT CASE WHEN s.sex = 'Female' THEN a.student_id END) as female_count")
            ->groupBy(
                'i.id',
                'i.code',
                'i.name',
                'p.id',
                'p.code',
                'p.name'
            )
            ->orderBy('i.code')
            ->orderBy('p.code')
            ->get();

        $grandTotals = (clone $attendanceQuery)
            ->selectRaw('COUNT(DISTINCT a.student_id) as total_students')
            ->selectRaw("COUNT(DISTINCT CASE WHEN s.sex = 'Male' THEN a.student_id END) as male_count")
            ->selectRaw("COUNT(DISTINCT CASE WHEN s.sex = 'Female' THEN a.student_id END) as female_count")
            ->first();

        // Include all registered students, even those without attendance records.
        $studentRecords = DB::table('students as s')
            ->join('users as u', 's.user_id', '=', 'u.id')
            ->join('programs as p', 's.program_id', '=', 'p.id')
            ->join('institutes as i', 'p.institute_id', '=', 'i.id')
            ->leftJoin('event_sessions as es', function ($join) use ($event) {
                $join->where('es.event_id', '=', $event->id);
            })
            ->leftJoin('attendances as a', function ($join) {
                $join->on('a.student_id', '=', 's.id')
                    ->on('a.event_session_id', '=', 'es.id');
            })
            ->leftJoin('penalties as pen', function ($join) use ($event) {
                $join->on('pen.student_id', '=', 's.id')
                    ->where('pen.event_id', '=', $event->id);
            })
            ->select(
                's.id as student_id',
                's.student_no',
                'u.name as student_name',
                's.sex',
                'p.code as program_code',
                'p.name as program_name',
                'i.code as institute_code',
                'pen.missed_scans',
                'pen.amount',
                'pen.sanction',
                'pen.status'
            )
            ->selectRaw(
                'COUNT(DISTINCT CASE WHEN a.check_in_at IS NOT NULL THEN es.id END) as attended_sessions'
            )
            ->groupBy(
                's.id',
                's.student_no',
                'u.name',
                's.sex',
                'p.code',
                'p.name',
                'i.code',
                'pen.missed_scans',
                'pen.amount',
                'pen.sanction',
                'pen.status'
            )
            ->orderBy('i.code')
            ->orderBy('p.code')
            ->orderBy('s.student_no')
            ->get();

        return view('reports.show', compact(
            'event',
            'programSummaries',
            'grandTotals',
            'studentRecords'
        ));
    }
}
