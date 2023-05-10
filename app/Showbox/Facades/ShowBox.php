<?php

namespace App\ShowBox\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\ShowBox\ShowBox
 */
class ShowBox extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\ShowBox\ShowBox::class;
    }
}
