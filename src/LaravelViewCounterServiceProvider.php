<?php

namespace SajadHasanzadeh\LaravelViewCounter;

use Illuminate\Support\ServiceProvider;

class LaravelViewCounterServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'sajad-hasanzadeh');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'sajad-hasanzadeh');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-view-counter.php', 'laravel-view-counter');

        // Register the service the package provides.
        $this->app->singleton('laravel-view-counter', function ($app) {
            return new LaravelViewCounter;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-view-counter'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravel-view-counter.php' => config_path('laravel-view-counter.php'),
        ], 'laravel-view-counter.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/sajad-hasanzadeh'),
        ], 'laravel-view-counter.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/sajad-hasanzadeh'),
        ], 'laravel-view-counter.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/sajad-hasanzadeh'),
        ], 'laravel-view-counter.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
