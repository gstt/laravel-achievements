<?php

namespace Gstt\Achievements\Facades;

use Illuminate\Support\Facades\Facade;

class Achievement extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'gstt.achievements.achievement';
    }
}