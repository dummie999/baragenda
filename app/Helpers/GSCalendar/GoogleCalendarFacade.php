<?php

namespace App\Helpers\GSCalendar\GoogleCalendar;;

use Illuminate\Support\Facades\Facade;

class GoogleCalendarFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GSCalendar';
    }
}


