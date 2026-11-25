<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Services\WeatherService;

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

        View::composer('dashboards.*', function ($view) {
            $view->with(
                'weather',
                app(WeatherService::class)->current()
            );
        });
    }
}
