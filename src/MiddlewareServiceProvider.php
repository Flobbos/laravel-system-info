<?php

namespace Flobbos\LaravelSystemInfo;

use Illuminate\Support\ServiceProvider;
use Flobbos\LaravelSystemInfo\Middleware\CheckToken;

class MiddlewareServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app['router']->aliasMiddleware('laravel-system-info.token', CheckToken::class);
    }
}
