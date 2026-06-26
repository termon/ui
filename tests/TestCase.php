<?php

namespace Termon\Ui\Tests;

use Illuminate\Support\ViewErrorBag;
use Orchestra\Testbench\TestCase as Orchestra;
use Termon\Ui\TermonUiServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        view()->share('errors', new ViewErrorBag);
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            TermonUiServiceProvider::class,
        ];
    }
}
