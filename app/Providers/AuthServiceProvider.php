<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates for different admin roles
        Gate::define('view-financial-reports', function ($admin) {
            return $admin->hasRole('admin') || $admin->hasRole('auditor');
        });

        Gate::define('manage-users', function ($admin) {
            return $admin->hasRole('admin');
        });

        Gate::define('manage-plans', function ($admin) {
            return $admin->hasRole('admin');
        });

        Gate::define('manage-sales', function ($admin) {
            return $admin->hasRole('admin') || $admin->hasRole('sales');
        });

        Gate::define('manage-courses', function ($admin) {
            return $admin->hasRole('admin') || $admin->hasRole('content');
        });

        Gate::define('manage-financial', function ($admin) {
            return $admin->hasRole('admin');
        });

        Gate::define('manage-settings', function ($admin) {
            return $admin->hasRole('admin');
        });
    }
}

