<?php

namespace SebRave\Snapshot\Facades;

use Illuminate\Support\Facades\Facade;

class Snapshot extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'snapshot';
    }
}