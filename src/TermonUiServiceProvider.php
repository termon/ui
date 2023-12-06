<?php

namespace Termon\Ui;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TermonUiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->loadViewsFrom(__DIR__.'/resources/views', 'ui');
            
    }
}
