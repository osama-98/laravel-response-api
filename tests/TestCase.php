<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            // Add your service providers here, if any
        ];
    }

    protected function defineEnvironment($app): void
    {
        // Configure your environment here, if needed
    }
}
