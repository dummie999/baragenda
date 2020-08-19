<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        setlocale(LC_TIME, "nl_NL");
        Carbon::setLocale(config('app.locale')); // nl

        // Register our own @admin / @superadmin blade if to simplify things
        Blade::if('notadmin', function () {
            return !auth()->check() || auth()->user()->info->admin==0;
        });
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->info->admin>0;
        });
        Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->info->admin==1;
        });

    }
}
