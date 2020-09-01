<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\BookStoreParsing\ChaconneParsing;

class ChaconneParsingController extends Controller
{
    //
    public function getStoresListInStock(){
    	$code = '111';
    	$storestList = ChcnnParsing::getStoresListInStock($code);

    	return $storestList;
    }

    public function getSearchResultPage(){

    	dump(ChaconneParsing::getSearchResultPage("Сорокин"));
    }

}
