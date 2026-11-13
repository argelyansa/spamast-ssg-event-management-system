<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('manage-ssg', function (User $user) {
            return $user->hasRole('Officer');
        });

        Gate::define('view-reports', function (User $user) {
            return $user->hasRole('Officer', 'Adviser');
        });

        Gate::define('use-attendance', function (User $user) {
            return $user->hasRole('Student');
        });
    }
}
