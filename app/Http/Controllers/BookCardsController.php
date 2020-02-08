<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\ChcnnParsing;

class BookCardsController extends Controller
{
    //
    public function create(Request $request)
    {
    	$bookCard = ChcnnParsing::getBookCard($request["bookcode"]);
    	return view('bookcards.create')->with('bookCard', $bookCard);
    }
}
