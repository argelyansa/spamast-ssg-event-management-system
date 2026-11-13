
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;

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
    });

    // Officer routes
    Route::prefix('officer')->name('officer.')->middleware('role:Officer')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'officer'])
            ->name('dashboard');
    });

    // Adviser routes
    Route::prefix('adviser')->name('adviser.')->middleware('role:Adviser')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adviser'])
            ->name('dashboard');
    });

    // Student routes
    Route::prefix('student')->name('student.')->middleware('role:Student')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'student'])
            ->name('dashboard');
    });
});
