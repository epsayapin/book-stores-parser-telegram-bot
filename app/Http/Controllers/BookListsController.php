<?php

namespace App\Http\Controllers;

require_once '/app/Parsing/Chcnn.php';

use Illuminate\Http\Request;
use App\Parsing\ChcnnParsing;

//require 'app/Parsing/Chcnn.php';

class BookListsController extends Controller
{
    //

    public function create(Request $request)
    {

    	$query = $request["query"];
    	$bookList = ChcnnParsing::getBookList($query);

    	return $bookList;

    }
}
