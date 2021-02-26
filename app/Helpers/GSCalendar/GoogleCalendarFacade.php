<?php

namespace App\Helpers\GSCalendar;

use Illuminate\Support\Facades\Facade;

class GoogleCalendarFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GSCalendar';
    }
}


