
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InstituteController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Officer\StudentController;
use App\Http\Controllers\Officer\EventController;
use App\Http\Controllers\Officer\AttendanceManagementController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\EventController as StudentEventController;
use App\Http\Controllers\Adviser\AttendanceController as AdviserAttendanceController;

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

    // Officer routes
    Route::prefix('officer')->name('officer.')->middleware('role:Officer')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'officer'])
            ->name('dashboard');

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

        // Officer Event Management
        Route::resource('events', EventController::class);
    });

    // Adviser routes
    Route::prefix('adviser')->name('adviser.')->middleware('role:Adviser')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adviser'])
            ->name('dashboard');

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
