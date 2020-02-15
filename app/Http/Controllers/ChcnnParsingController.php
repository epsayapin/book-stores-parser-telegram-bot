<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\ChcnnParsing;

class ChcnnParsingController extends Controller
{
    //
    public function getStoresListInStock()
    {
    	$code = '111';
    	$storestList = ChcnnParsing::getStoresListInStock($code);

    	return $storestList;
    }
}
