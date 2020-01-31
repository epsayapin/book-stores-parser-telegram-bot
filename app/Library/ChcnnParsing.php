<?php 
namespace App\Library;

use Symfony\Component\DomCrawler\Crawler;

class ChcnnParsing
{
	public static $search_url = 'https://chaconne.ru/search/?q=';
	public static $bookcard_url = 'https://chaconne.ru/product/';

	public static function getBookList(String $query, int $currentPage = 0): array
	{

		$requestURL = self::$search_url . urlencode($query); 
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