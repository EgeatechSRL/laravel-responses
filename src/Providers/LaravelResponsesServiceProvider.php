<?php

namespace EgeaTech\LaravelResponses\Providers;

use Illuminate\Support\ServiceProvider;
use Egeatech\LaravelResponses\LaravelResponses;

class LaravelResponsesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the service the package provides.
        $this->app->singleton('laravel-responses', function ($app) {
            return new LaravelResponses;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['laravel-responses'];
    }
}
