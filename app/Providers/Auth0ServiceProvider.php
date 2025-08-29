<?php

namespace App\Providers;

use App\Auth\Auth0UserRepository;
use Illuminate\Support\ServiceProvider;

class Auth0ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Override the default Auth0 user repository with our Eloquent-backed implementation
        $this->app->singleton('auth0.repository', function () {
            return new Auth0UserRepository();
        });
    }
}

