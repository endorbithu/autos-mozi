<?php

namespace App\Providers;

use App\Contracts\RESTServiceInterface;
use App\Services\RESTService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RESTServiceInterface::class, RESTService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
