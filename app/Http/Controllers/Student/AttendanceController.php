<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\EventSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(Request $request, EventSession $session): RedirectResponse
    {
        $student = $request->user()->student;

        abort_unless($student, 403, 'Your account is not linked to a student record.');
        abort_unless($session->isCheckInOpen(), 403, 'Check-in is currently closed.');

        $attendance = Attendance::firstOrCreate(
            [
                'event_session_id' => $session->id,
                'student_id' => $student->id,
            ],
            [
                'check_in_at' => now(),
            ]
        );

        if (!$attendance->wasRecentlyCreated) {
            if ($attendance->check_in_at) {
                return back()->with('info', 'You have already checked in for this session.');
            }

            $attendance->update([
                'check_in_at' => now(),
            ]);
        }

        return back()->with('success', 'Check-in recorded successfully.');
    }

    public function checkOut(Request $request, EventSession $session): RedirectResponse
    {
        $student = $request->user()->student;

        abort_unless($student, 403, 'Your account is not linked to a student record.');
        abort_unless($session->isCheckOutOpen(), 403, 'Check-out is currently closed.');

        $attendance = Attendance::query()
            ->where('event_session_id', $session->id)
            ->where('student_id', $student->id)
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->firstOrFail();

        $attendance->update([
            'check_out_at' => now(),
        ]);

        return back()->with('success', 'Check-out recorded successfully.');
    }
}
