<?php

namespace App\Providers;

use App\Services\TelegramUpdatesCronService;
use App\Services\TelegramUpdatesHandlerService;
use Illuminate\Support\ServiceProvider;

class BookStoresParserProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton("TelegramUpdatesHandlerService", function(){

            return new TelegramUpdatesHandlerService();
        });

        $this->app->singleton("TelegramUpdatesCronService", function (){

            return new TelegramUpdatesCronService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
