<?php


namespace App\Services;


use App\Services\BookStoreParsers\ChaconneBookStoreParser;

class SettingsService
{
    public static function getParser()
    {
        return new ChaconneBookStoreParser();
    }
}