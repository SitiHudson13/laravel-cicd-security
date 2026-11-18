<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /**
     * Policies in app/Policies are auto-discovered by Laravel (Project -> ProjectPolicy).
     * Note: a Policy only protects a resource where it is actually *called*.
     */
    public function boot(): void
    {
        //
    }
}
