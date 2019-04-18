<?php

namespace Whchi\LaravelApplicationInsights\Facades;

use Illuminate\Support\Facades\Facade;

class AppInsights extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AppInsights';
    }
}
