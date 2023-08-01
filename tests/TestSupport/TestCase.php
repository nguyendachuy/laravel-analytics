<?php

namespace NguyenHuy\Analytics\Tests\TestSupport;

use Orchestra\Testbench\TestCase as Orchestra;
use NguyenHuy\Analytics\AnalyticsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            AnalyticsServiceProvider::class,
        ];
    }
}
