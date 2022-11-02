<?php

namespace SajadHasanzadeh\LaravelViewCounter\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelViewCounter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-view-counter';
    }
}
