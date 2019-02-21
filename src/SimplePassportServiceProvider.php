<?php

namespace Heloufir\SimplePassport;

use Heloufir\SimplePassport\Http\Controllers\PasswordController;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class SimplePassportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register PasswordController
        $this->app->make(PasswordController::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Register laravel/passport routes
        Passport::routes();

        // Register package routes
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        // Register package migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Register package views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'simple-passport');

        // Register package translations
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'simple-passport');

        // Publish package sources
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/simple-passport'),
            __DIR__.'/config/simple-passport.php' => config_path('simple-passport.php'),
            __DIR__.'/resources/lang' => resource_path('lang/vendor/simple-passport')
        ]);
    }
}
