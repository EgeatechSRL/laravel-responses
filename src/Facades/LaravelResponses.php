<?php

namespace EgeaTech\LaravelResponses\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelResponses extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-responses';
    }
}
