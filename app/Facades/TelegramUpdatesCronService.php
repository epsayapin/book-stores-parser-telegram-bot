<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TelegramUpdatesCronService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "TelegramUpdatesCronService";
    }
}