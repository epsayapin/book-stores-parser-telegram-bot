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
	public static $storesInStockUrl = "https://chaconne.ru/block/nalich.php?id=";

	public static function getSearchResult(String $query, int $currentPage = 1, int $currentPart = 1): SearchResult
	{

		//На основе запрошенной части выдачи $currentPart уточнняем нужно ли перейти на следующую страницу поисковой выдачи  или же вернуться назад 

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

		//Рассчитыаем URL для парсинга

		$requestURL = self::$search_url . urlencode($query);
		if ($currentPage > 1)
			{
				$requestURL .= "&p=$currentPage";
			}

		//Парсим страницу и генерируем массив с книгами

		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTMLFile($requestURL);
		$str = $doc->saveHTML();
		libxml_use_internal_errors(false);
		
		$crawler = new Crawler($str);
		$productsArray = $crawler->filter(".products .row .product");
		$productsCount = count($productsArray);

		$bookList = [];
		if($productsCount > 0)
			{
			//Рассчитываем начальную и последнюю позицию в массиве книг для формирования итогового списка

			$partNumberAndStartPosition = [
						1 => 0,
						2 => 6,
						3 => 12,
						4 => 18
					];
			$startPosition = $partNumberAndStartPosition[$currentPart]; 				
			$finalPosition = $startPosition + self::$partSize - 1; 

			//На  случай если выбранная часть поисковой выдачи меньше стандартного размера, то есть результатов выдачи меньше 

			if($productsCount < $finalPosition)
			{
				$finalPosition = $productsCount - 1;
			}		

			//Парсим список книг

			for($i = $startPosition; $i<=$finalPosition; $i++)
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
		}
		//Собираем общую инфморацию о поисковой выдаче

		$totalParts = $productsCount % self::$partSize;
		$paginatorCount = count($crawler->filter(".paginator .links a")); 
		if( $paginatorCount > 0)
		{

			$totalPages = $crawler->filter(".paginator .links a")->last()->html();
			if($totalPages == 0)
			{
				$totalPages = $crawler->filter(".paginator .links a")->eq($paginatorCount - 2)->html();
			}
			
		}else{
			$totalPages = 1;
		}

		//Формируем ответ
		
		$searchResult = new SearchResult(
										$bookList,
										(int)$currentPage,
										(int)$totalPages,
										(int)$currentPart,
										(int)$totalParts,
										$query
									);
		return $searchResult;

	}

	public static function getBookCard(String $bookCode): BookCard
	{
		
		$author = [0 => 'н/д'];
		$pages = 'н/д';
		$coverFormat = 'н/д';

		$requestURL = self::$bookcard_url . $bookCode . '/';

		$doc = new \DOMDocument();

		libxml_use_internal_errors(true);
		$doc->loadHTMLFile($requestURL);
		libxml_use_internal_errors(false);
		try{
			$str = $doc->saveHTML();
			$crawler = new Crawler($str);
			$title = $crawler->filter('.product_text h1')->text();
			if($crawler->filter('a.author')) 
				{
					$author[0] = $crawler->filter('a.author')->text();
				}
			$internetPrice = $crawler->filter('.price strong ')->text();
			$localPrice = $crawler->filter(".rozn .price strong")->text();
			$code =	$crawler->filter('.product_text table tr')->first()->filter('td')->last()->text();

			$productInfoTable = $crawler->filter('.product_text table tr td');
			$countRows = count($productInfoTable);

			for($i = 0; $i < $countRows; $i++)
			{
				switch ($productInfoTable->eq($i)->text()) {
					case 'Кол-во страниц':
						# code...
						$pages = $productInfoTable->eq($i+1)->text();
						break;
					case 'Оформление':
						$coverFormat = $productInfoTable->eq($i+1)->text();
					default:
						# code...
						break;
				}

			}
		}
		catch(InvalidArgumentException $e)
		{
			echo ("Ошибка - Не удалось собрать информацию о книге \n");
			return new BookCard();

		}

		$bookCard = new BookCard($title, 
								$author, 
								$internetPrice,
								$localPrice,
								$code, 
								$coverFormat, 
								$pages);
		return $bookCard;

	}

	public static function getStoresListInStock($code)
	{
		$storesInStockUrl = "/storesInStock.html";

		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTMLFile($storesInStockUrl);
		libxml_use_internal_errors(false);

		$str = $doc->saveHTML();
/*
		$crawler = new Crawler($str);
		$storesInStockCrawler = $crawler->filter('.page_content .shops row .filials-city1 .shop');

		$storesList = [];

		$storesInStockCrawlerCount = count($crawler->filter('.page_content'));
		for($i=1; $i <= $storesInStockCrawlerCount; $i++)
		{
			$storesList[] = $storesInStockCrawler->eq($i)->text();
		}
*/
		return $str;
	}

}