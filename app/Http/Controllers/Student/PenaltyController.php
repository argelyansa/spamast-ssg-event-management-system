<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Penalty;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class PenaltyController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user()->student;

        abort_unless($student, 403, 'Your account is not linked to a student record.');

        $penalties = Penalty::query()
            ->with('event')
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(15);

        $totalFines = Penalty::where('student_id', $student->id)->sum('amount');

        return view('student.penalties.index', compact('penalties', 'totalFines'));
    }
}
