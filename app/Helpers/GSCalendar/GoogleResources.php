<?php

namespace App\Helpers\GSCalendar\GoogleCalendar;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTime;
use Google_Service_Calendar;


class GoogleResource {


/** @var \Google_Service_Directory */
protected $adminService;

/** @var array */
protected $resources;


public function __construct(Google_Service_Directory $adminService)
{
    $this->adminService = $adminService;
    $this->resources = [];
}


public static function get($adminService): Array
{


    $googleResources = $adminService->resources_calendars->listResourcesCalendars('my_customer'); //the whole resource object

    $googleResourcesList = $googleResources->getItems();

    while ($googleResources->getNextPageToken()) {
        $queryParameters['pageToken'] = $googleResources->getNextPageToken();

        $googleResources = $adminService->resources_calendars->listResourcesCalendars('my_customer');

        $googleResourcesList = array_merge($googleResourcesList, $googleResources->getItems());
    }

    $resources=$googleResourcesList;

    return $resources;
}




/**
 * Returns all resources from either cache of web
 *
 * @return array
 */
function formatResources($adminService, $resources) : array
{



    foreach($resources->getItems() as $key => $resource) {
        $resourceList[] = array(
            'name' => $resource->getResourceName() ,
            'id' => $resource->getResourceId() ,
            'generatedname' => $resource->getGeneratedResourceName() ,
            'capacity' => $resource->getCapacity() ,
            'features'=> $resource->getResourcefeatures(),
            'email' => $resource->getResourceEmail()
        );
    }
    return $resourceList;
}

}