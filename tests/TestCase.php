<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    #[\Override]
    protected function getPackageProviders($app): array
    {
        return [
            \Osama\ApiResponse\ApiResponseServiceProvider::class,
        ];
    }

    #[\Override]
    protected function defineEnvironment($app): void
    {
        // Configure your environment here, if needed
    }
}
