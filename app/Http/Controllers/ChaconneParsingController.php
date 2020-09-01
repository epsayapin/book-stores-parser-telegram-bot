<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\BookStoreParsing\ChaconneParsing;

class ChaconneParsingController extends Controller
{
    //
    public function getSearchResultPage(){

    	dump(ChaconneParsing::getSearchResultPage("Сорокин"));
    }

    public function getBookCard(){

    	dump(ChaconneParsing::getBookCard("3822666"));
    }

    public function getStoresListInStock(){
    	$code = '111';
    	$storestList = ChcnnParsing::getStoresListInStock($code);

    	return $storestList;
    }

}
