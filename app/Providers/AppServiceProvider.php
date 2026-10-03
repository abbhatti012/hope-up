<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Services\QueueService::class, function ($app) {
            return new \App\Services\QueueService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Format all model dates when serialized to JSON
        Carbon::serializeUsing(function ($date) {
            return $date->format('d-M-Y h:i A'); // e.g. 16-Jun-2025 02:05 AM
        });
    }
}
