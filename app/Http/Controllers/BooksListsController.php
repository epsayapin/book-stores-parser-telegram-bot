<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class BooksListsController extends Controller
{
    //

	public function getBooksList()
	{
		$bookslist = ['Cobain', 'Jobs', 'Vorum'];
		
		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$filepath = __DIR__ . '/../../../resources/test_data/search_results.html';
		$doc->loadHTMLFile(__DIR__ . '/../../../resources/test_data/search_results.html');
		$str = $doc->saveHTML();
		libxml_use_internal_errors(false);
		
		$crawler = new Crawler($str);
		$array = $crawler->filter("div.product");
/*
		foreach($array as $product)
		{
			$bookslist[] =  $product->text();
		}
*/

		return view("bookslists.index")->with('bookslist', $bookslist)->with('crawler', $array);

	}

}
