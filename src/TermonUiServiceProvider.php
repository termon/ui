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

    public function boot(): void
    {
        //
        $this->publishes([
            __DIR__.'/resources/views/components' => resource_path('views/components/ui'),
        ], 'termon/ui');
        
        $this->loadViewsFrom(__DIR__.'/resources/views', 'ui');
            
    }
}