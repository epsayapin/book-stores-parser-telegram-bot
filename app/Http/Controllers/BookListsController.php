<?php

namespace App\Http\Controllers;

//require_once "app/Parsing/Chcnn.php";

use Illuminate\Http\Request;
use App\Library\ChcnnParsing;



//require 'app/Parsing/Chcnn.php';

class BookListsController extends Controller
{
    //

    public function create(Request $request)
    {

    	$query = $request["query"];
    	$searchResult = ChcnnParsing::getBookList($query);

    	return view("booklists.create")->with('searchResult', $searchResult);

    }
}
