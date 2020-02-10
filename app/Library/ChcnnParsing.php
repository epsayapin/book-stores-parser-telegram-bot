<?php 
namespace App\Library;

use Symfony\Component\DomCrawler\Crawler;
use App\Library\SearchResult;
use App\Library\BookCard;
class ChcnnParsing
{
	public static $search_url = 'https://chaconne.ru/search/?q=';
	public static $bookcard_url = 'https://chaconne.ru/product/';
	public static $partsOnPage = 4;
	public static $partSize = 6;

	public static function getBookList(String $query, int $currentPage = 1, int $currentPart = 1): SearchResult
	{

		if($currentPart > self::$partsOnPage)
		{
			$currentPart = 1;
			$currentPage += 1;
		}

		if($currentPart == 0)
		{
			$currentPart = self::$partsOnPage;
			$currentPage -= 1;

		}


		$requestURL = self::$search_url . urlencode($query); 
		if ($currentPage > 1)
		{
			$requestURL .= "&p=$currentPage";
		}
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


		$startPosition = [
					1 => 0,
					2 => 6,
					3 => 12,
					4 => 18
				];
		$totalParts = $productsCount % self::$partSize;

		$i = $startPosition[$currentPart];
		$k = $i + self::$partSize - 1;
		if($productsCount < $k)
		{
			$k = $productsCount - 1;
		}		

		for($i; $i<=$k; $i++)
		{

			if($productsArray->eq($i))
			{
			$book = [];
			$book["title"] = $productsArray->eq($i)->filter('.title')->text();
			$link = $productsArray->eq($i)->filter(".title")->attr('href');
			preg_match('/\d*.$/', $link, $code);
			$book['code'] = str_replace("/", '', $code[0]); 

			$bookList[] = $book;
			}
		}


		if(count($crawler->filter(".paginator .links a")) > 0)
		{
			$totalPages = $crawler->filter(".paginator .links a")->last()->html();
			
		}else{
			$totalPages = 1;
		}
		
		$searchResult = new SearchResult(
					$bookList,
					$currentPage,
					$totalPages,
					$currentPart,
					$totalParts,
					$query
						);
		return $searchResult;

	}

	public static function getBookCard(String $bookCode): BookCard
	{
		
		$author = [
					0 => 'н/д'
				];
		$pages = 'н/д';
		$coverFormat = 'н/д';

		$requestURL = self::$bookcard_url . $bookCode . '/';
		//$requestURL = __DIR__ . '/../../tests/SearchPageExample/BookCardWitcher.html';

		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTMLFile($requestURL);
		$str = $doc->saveHTML();

		$crawler = new Crawler($str);
		$title = $crawler->filter('.product_text h1')->text();
		if($author[] = $crawler->filter('a.author')) 
			{
				$author[0] = $crawler->filter('a.author')->text();
			}
		$price = $crawler->filter('.price strong ')->text();
		$code =	$crawler->filter('.product_text table tr')->first()->filter('td')->last()->text();

		$productTable = $crawler->filter('.product_text table tr td');
		$countRows = count($productTable);

		for($i = 0; $i < $countRows; $i++)
		{
			switch ($productTable->eq($i)->text()) {
				case 'Кол-во страниц':
					# code...
					$pages = $productTable->eq($i+1)->text();
					break;
				case 'Оформление':
					$coverFormat = $productTable->eq($i+1)->text();
				default:
					# code...
					break;
			}

		}
		$bookCard = new BookCard($title, 
								$author, 
								(int)$price, 
								$code, 
								$coverFormat, 
								$pages);
		return $bookCard;
	}

}