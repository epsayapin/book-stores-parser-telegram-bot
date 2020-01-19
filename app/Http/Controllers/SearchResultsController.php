<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchResultsController extends Controller
{
    //

    public function getResults(Request $request)
    {
    	return ["result" => ["kek", "kek"]];
    }
}
