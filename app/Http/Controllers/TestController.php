<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;
use App\Services\BookStoreParsers\ChaconneBookStoreParser;

class TestController extends Controller
{
    public function getSearchResults(){
        $parser = SettingsService::getParser();
    	dump($parser->getSearchResults("Математика"));
    }

    public function getBookCard(){
    	dump(SettingsService::getParser()->getBookCard("3822666"));
    }
}
