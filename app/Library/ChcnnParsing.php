<?php 
namespace App\Library;

use Symfony\Component\DomCrawler\Crawler;

class ChcnnParsing
{
	public static function getBookList(String $query, int $currentPage = 0): array
	{

		$requestURL = "https://chaconne.ru/search/?q=" . urlencode($query); 
		//$requestURL = __DIR__ . "/../../tests/SearchPageExample/Example.html";

		$result = [];
		$booklist = [];
		
		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$filepath = __DIR__ . '/../../../resources/test_data/search_results.html';
		$doc->loadHTMLFile($requestURL);
		$str = $doc->saveHTML();
		libxml_use_internal_errors(false);
		
		$crawler = new Crawler($str);
		$productsArray = $crawler->filter(".products .row .product .title");
		$productsCount = count($productsArray);
		$pagesCount = $crawler->filter(".paginator .links a")->last()->html();




		for($i=0; $i<$productsCount; $i++)
		{
			$booklist[] = $productsArray->eq($i)->text();
		}

		$result[] = ["bookList" => $booklist]; 
		$result[] = ["currentPage" => $currentPage];
		$result[] = ["pagesCount" => $pagesCount];
		$result[] = $requestURL;
		return $result;

	}

}