<?php

namespace Osama\ApiResponse;

use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'api-response');

        $this->publishes([
            __DIR__.'/../resources/lang' => $this->app->langPath('vendor/api-response'),
        ], 'api-response-lang');
    }
}
