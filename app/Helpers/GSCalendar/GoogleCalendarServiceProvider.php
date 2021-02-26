<?php

namespace App\Helpers\GSCalendar;

use Illuminate\Support\ServiceProvider;
use App\Helpers\GSCalendar\Exceptions\InvalidConfiguration;

class GoogleCalendarServiceProvider extends ServiceProvider
{
    public function boot()
    {
   #    $this->publishes([
    #        __DIR__.'/../config/google-calendar.php' => config_path('google-calendar.php'),
  #      ], 'config');
    }

    public function register()
    {
        #$this->mergeConfigFrom(__DIR__.'/../../../config/google-calendar.php', 'google-calendar');

        $this->app->bind(GoogleCalendar::class, function () {
            $config = config('google-calendar');

            $this->guardAgainstInvalidConfiguration($config);

            return GoogleCalendarFactory::createForCalendarId($config['calendar_id']);
        });

        $this->app->bind(Resource::class, function () {

            return GoogleCalendarFactory::createForResources();
        });
        
        $this->app->alias(GoogleCalendar::class, 'GSCalendar');
    }

    protected function guardAgainstInvalidConfiguration(array $config = null)
    {
        if (empty($config['calendar_id'])) {
            throw InvalidConfiguration::calendarIdNotSpecified();
        }

        $credentials = $config['service_account_credentials_json'];

        if (! is_array($credentials) && ! is_string($credentials)) {
            throw InvalidConfiguration::credentialsTypeWrong($credentials);
        }

        if (is_string($credentials) && ! file_exists($credentials)) {
            throw InvalidConfiguration::credentialsJsonDoesNotExist($credentials);
        }
    }
}
