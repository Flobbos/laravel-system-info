<?php

namespace Flobbos\LaravelSystemInfo;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaravelSystemInfoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/laravel-system-info.php' => config_path('laravel-system-info.php'),
        ], 'laravel-system-info-config');

        $this->registerRoutes();

        $this->commands([
            \Flobbos\LaravelSystemInfo\Commands\InstallCommand::class,
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/laravel-system-info.php', 'laravel-system-info');
        $this->app->register(MiddlewareServiceProvider::class);
    }

    protected function registerRoutes()
    {
        Route::group([
            'middleware' => 'laravel-system-info.token',
        ], function () {
            Route::get('/api/system-info', [SystemInfoController::class, 'index']);
        });
    }
}
