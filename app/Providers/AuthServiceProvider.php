<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    // In AuthServiceProvider.php
    Gate::define('manage-books', function ($user) {
    return true; // Temporarily allows all authenticated users
    });

    Gate::define('manage-dashboard', function ($user) {
        return $user->role === 'admin';
    });

    Gate::define('manage-users', function ($user) {
        return $user->role === 'admin';
    });

    Gate::define('borrow-books', function ($user) {
        return $user->role === 'user';
    });
    }
}
