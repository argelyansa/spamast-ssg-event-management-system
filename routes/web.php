
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InstituteController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Officer\StudentController;
use App\Http\Controllers\Officer\EventController;
use App\Http\Controllers\Officer\AttendanceManagementController;
use App\Http\Controllers\Officer\PenaltyController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\EventController as StudentEventController;
use App\Http\Controllers\Adviser\AttendanceController as AdviserAttendanceController;
use App\Http\Controllers\Adviser\EventController as AdviserEventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AnnouncementController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->dashboardRoute());
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Theme Customization
    Route::put('/theme', [ThemeController::class, 'update'])
        ->name('theme.update');

    // Shared Penalty Viewing (Officer and Adviser)
    Route::get('/events/{event}/penalties', [PenaltyController::class, 'index'])
        ->middleware('role:Officer,Adviser')
        ->name('penalties.index');
    // Event Reports (Officer and Adviser)
    Route::middleware('role:Officer,Adviser')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{event}', [ReportController::class, 'show'])->name('reports.show');
    });

    // Admin routes
    Route::prefix('admin')->name('admin.')->middleware('role:Admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])
            ->name('dashboard');

        // Admin User Management
        Route::resource('users', UserController::class);

        // Admin Institute Management
        Route::resource('institutes', InstituteController::class)
            ->except(['show']);

        // Admin Program Management
        Route::resource('programs', ProgramController::class)
            ->except(['show']);
    });


// Shared Announcement Viewing
Route::middleware('role:Officer,Adviser,Student')->group(function () {
    Route::get('/announcements', [AnnouncementController::class, 'index'])
        ->name('announcements.index');

    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
        ->name('announcements.show');
});
    // Officer routes
    Route::prefix('officer')->name('officer.')->middleware('role:Officer')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'officer'])
            ->name('dashboard');


        // Officer Announcement Management
        Route::get('announcements/create', [AnnouncementController::class, 'create'])
            ->name('announcements.create');

        Route::post('announcements', [AnnouncementController::class, 'store'])
            ->name('announcements.store');

        Route::get('announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])
            ->name('announcements.edit');

        Route::put('announcements/{announcement}', [AnnouncementController::class, 'update'])
            ->name('announcements.update');

        Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])
            ->name('announcements.destroy');
        // Officer Penalty Management
        Route::post('events/{event}/penalties/generate', [PenaltyController::class, 'generate'])
            ->name('penalties.generate');

        Route::put('events/{event}/penalties/{penalty}', [PenaltyController::class, 'update'])
            ->name('penalties.update');

        Route::delete('events/{event}/penalties/{penalty}', [PenaltyController::class, 'destroy'])
            ->name('penalties.destroy');
        // Officer Student Management
        Route::resource('students', StudentController::class)
            ->except(['show']);

        // Officer Session Availability
        Route::patch('sessions/{session}/toggle', [EventController::class, 'toggleSession'])
            ->name('sessions.toggle');

        // Officer Attendance Management
        Route::get(
            'events/{event}/sessions/{session}/attendance',
            [AttendanceManagementController::class, 'index']
        )->name('attendance.index');

        Route::post(
            'events/{event}/sessions/{session}/attendance',
            [AttendanceManagementController::class, 'store']
        )->name('attendance.store');

        Route::put(
            'events/{event}/sessions/{session}/attendance/{attendance}',
            [AttendanceManagementController::class, 'update']
        )->name('attendance.update');

        Route::delete(
            'events/{event}/sessions/{session}/attendance/{attendance}',
            [AttendanceManagementController::class, 'destroy']
        )->name('attendance.destroy');

        // Officer Event Management
        Route::resource('events', EventController::class);
    });

    // Adviser routes
    Route::prefix('adviser')->name('adviser.')->middleware('role:Adviser')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adviser'])
            ->name('dashboard');

        // Adviser Read-Only Event Browsing
        Route::get('/events', [AdviserEventController::class, 'index'])
            ->name('events.index');

        // Adviser Read-Only Fines and Sanctions
        Route::get('/penalties/events', [AdviserEventController::class, 'penaltiesEvents'])
            ->name('penalties.events');

        // Adviser Read-Only Attendance Records
        Route::get('/attendance', [AdviserAttendanceController::class, 'events'])
            ->name('attendance.events');

        Route::get(
            '/events/{event}/sessions/{session}/attendance',
            [AdviserAttendanceController::class, 'index']
        )->name('attendance.index');
    });

    // Student routes
    Route::prefix('student')->name('student.')->middleware('role:Student')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'student'])
            ->name('dashboard');

        // Student Event Viewing
        Route::get('/events', [StudentEventController::class, 'index'])
            ->name('events.index');

        Route::get('/events/{event}', [StudentEventController::class, 'show'])
            ->name('events.show');

        // Student Attendance
        Route::post('sessions/{session}/check-in', [AttendanceController::class, 'checkIn'])
            ->name('attendance.check-in');

        Route::post('sessions/{session}/check-out', [AttendanceController::class, 'checkOut'])
            ->name('attendance.check-out');
    });
});
