<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class BooksListsController extends Controller
{
    //

	public function getBooksList(Request $request): array
	{
		$querys = ['Ведьмак', 'Портнягин', 'Сорокин', 'Гарри Поттер',
					'Стив Джобс', 'Пушкин', 'Достоевский', 'Алексей Иванов', 'Пелевин', 'Воннегут'];
		$i = rand(0, 9);

		$query = $request["query"];

		$requestURL = "https://chaconne.ru/search/?q=" . urlencode($query); 

		$bookslist = [];
		
		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$filepath = __DIR__ . '/../../../resources/test_data/search_results.html';
		$doc->loadHTMLFile($requestURL);
		$str = $doc->saveHTML();
		libxml_use_internal_errors(false);
		
		$crawler = new Crawler($str);
		$array = $crawler->filter(".products .row .product .title");
		$count = count($array);
		for($i=0; $i<$count; $i++)
		{
			$bookslist[] = $array->eq($i)->text();
		}
/*	
		foreach($array as $product)W
		{
			$bookslist[] =  $product->text();
		}
*/

		return $bookslist;

	}

}
