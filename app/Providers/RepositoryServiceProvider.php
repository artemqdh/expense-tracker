<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(
            \App\Repositories\Interfaces\ExpenseRepositoryInterface::class,
            \App\Repositories\ExpenseRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );

        // Bind services
        $this->app->bind(
            \App\Services\Interfaces\ExpenseServiceInterface::class,
            \App\Services\ExpenseService::class
        );
        
        $this->app->bind(
            \App\Services\Interfaces\AuthServiceInterface::class,
            \App\Services\AuthService::class
        );
    }

    public function boot(): void
    {
        //
    }
}