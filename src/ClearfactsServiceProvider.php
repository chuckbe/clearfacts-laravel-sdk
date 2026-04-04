<?php

namespace Clearfacts;

use Illuminate\Support\ServiceProvider;

class ClearfactsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/clearfacts.php',
            'clearfacts'
        );

        $this->app->singleton(ClearfactsManager::class, function () {
            return new ClearfactsManager();
        });

        $this->app->alias(ClearfactsManager::class, 'clearfacts');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/clearfacts.php' => config_path('clearfacts.php'),
            ], 'clearfacts-config');
        }
    }
}
