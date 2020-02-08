<?php 
namespace App\Library;

use Symfony\Component\DomCrawler\Crawler;
use App\Library\SearchResult;

class ChcnnParsing
{
	public static $search_url = 'https://chaconne.ru/search/?q=';
	public static $bookcard_url = 'https://chaconne.ru/product/';

	public static function getBookList(String $query, int $currentPage = 1): SearchResult
	{


		$requestURL = self::$search_url . urlencode($query); 
		//$requestURL = __DIR__ . "/../../tests/SearchPageExample/Example.html";
		//$requestURL = __DIR__ . "/../../tests/SearchPageExample/SinglePageResult.html";


		$bookList = [];
		$currentPage;
		$countPages;
		
		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$filepath = __DIR__ . '/../../../resources/test_data/search_results.html';
		$doc->loadHTMLFile($requestURL);
		$str = $doc->saveHTML();
		libxml_use_internal_errors(false);
		
		$crawler = new Crawler($str);
		$productsArray = $crawler->filter(".products .row .product");
		$productsCount = count($productsArray);


		for($i=0; $i<$productsCount; $i++)
		{
			$book = [];
			$book["title"] = $productsArray->eq($i)->filter('.title')->text();
			$link = $productsArray->eq($i)->filter(".title")->attr('href');
			preg_match('/\d*.$/', $link, $code);
			$book['code'] = str_replace("/", '', $code[0]); 

			$bookList[] = $book;
		}


		if(count($crawler->filter(".paginator .links a")) > 0)
		{
			$countPages = $crawler->filter(".paginator .links a")->last()->html();
			
		}else{
			$countPages = 1;
		}
		
		$searchResult = new SearchResult(
					$bookList,
					$currentPage,
					$countPages
						);
		return $searchResult;

	}

	public static function getBookCard(String $bookCode): array
	{
		$bookCard = [];
		$bookCard["author"] = [];

		$requestURL = self::$bookcard_url . $bookCode . '/';
		//$requestURL = __DIR__ . '/../../tests/SearchPageExample/BookCardWitcher.html';

		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTMLFile($requestURL);
		$str = $doc->saveHTML();

		$crawler = new Crawler($str);


		$bookCard["title"] = $crawler->filter('.product_text h1')->text();
		$bookCard["author"][] = $crawler->filter('a.author')->text();
		$bookCard["price"] = $crawler->filter('.price strong ')->text();
		$bookCard["code"] =	$crawler->filter('.product_text table tr')->first()->filter('td')->last()->text();

		$productTable = $crawler->filter('.product_text table tr td');
		$countRows = count($productTable);

		for($i = 0; $i < $countRows; $i++)
		{
			switch ($productTable->eq($i)->text()) {
				case 'Кол-во страниц':
					# code...
				$bookCard["pages"] = $productTable->eq($i+1)->text();
					break;
				case 'Оформление':
					$bookCard["coverFormat"] = $productTable->eq($i+1)->text();
				default:
					# code...
					break;
			}

		}

		return $bookCard;
	}

}