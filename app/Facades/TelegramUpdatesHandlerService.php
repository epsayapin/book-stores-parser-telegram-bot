<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TelegramUpdatesHandlerService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "TelegramUpdatesHandlerService";
    }
}