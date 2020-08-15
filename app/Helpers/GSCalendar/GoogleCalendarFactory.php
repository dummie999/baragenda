<?php

namespace App\Helpers\GSCalendar;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Directory;

class GoogleCalendarFactory
{
    public static function createForCalendarId(string $calendarId): GoogleCalendar
    {
        $config = config('google-calendar');

        $client = self::createAuthenticatedGoogleClient($config);

        $service = new Google_Service_Calendar($client);

        return self::createCalendarClient($service, $calendarId);
	}
	
    public static function createForResources(): Resource
    {
        $config = config('google-calendar');

        $client = self::createAuthenticatedGoogleClient($config);

        $service = new Google_Service_Directory($client);

        return self::createResourceClient($service);
    }
    public static function createAuthenticatedGoogleClient(array $config): Google_Client
    {
        $client = new Google_Client;

        $client->setScopes([
            Google_Service_Calendar::CALENDAR,
            Google_Service_Directory::ADMIN_DIRECTORY_RESOURCE_CALENDAR_READONLY
        ]);
        $client->setApplicationName(env('APP_NAME'));
        $client->setSubject(env('IMPERSONATE'));
        $client->setAuthConfig($config['service_account_credentials_json']);

        return $client;
    }

    protected static function createCalendarClient(Google_Service_Calendar $service, string $calendarId): GoogleCalendar
    {
        return new GoogleCalendar($service, $calendarId);
    }

    protected static function createResourceClient(Google_Service_Directory $service): Resource
    {
        return new Resource($service);
    }
}