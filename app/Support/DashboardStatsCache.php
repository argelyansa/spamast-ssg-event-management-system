<?php

namespace App\Support;

use App\Models\Student;
use Illuminate\Support\Facades\Cache;

class DashboardStatsCache
{
    public static function forgetAdmin(): void
    {
        Cache::forget('admin.stats');
    }

    public static function forgetOfficer(): void
    {
        Cache::forget('officer.stats');
    }

    public static function forgetAdviser(): void
    {
        Cache::forget('adviser.stats');
    }

    public static function forgetStudent(int $userId): void
    {
        Cache::forget("student.stats.{$userId}");
    }

    public static function forgetAllStudentStats(): void
    {
        Student::query()
            ->select('user_id')
            ->chunk(100, function ($students) {
                foreach ($students as $student) {
                    self::forgetStudent((int) $student->user_id);
                }
            });
    }

    public static function forgetEventStats(): void
    {
        self::forgetOfficer();
        self::forgetAdviser();
        self::forgetAllStudentStats();
    }

    public static function forgetAttendanceStats(int $userId): void
    {
        self::forgetAdviser();
        self::forgetStudent($userId);
    }

    public static function forgetPenaltyStats(): void
    {
        self::forgetOfficer();
        self::forgetAdviser();
    }
}
