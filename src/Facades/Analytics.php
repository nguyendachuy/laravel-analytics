<?php

namespace NguyenHuy\Analytics\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Spatie\Analytics\Analytics
 */
class Analytics extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-analytics';
    }
}
