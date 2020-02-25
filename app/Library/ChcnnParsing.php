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

		$partNumberAndStartPosition = [
					1 => 0,
					2 => 6,
					3 => 12,
					4 => 18
				];
		$startPosition = $partNumberAndStartPosition[$currentPart]; 				
		$finalPosition = $startPosition + self::$partSize - 1; 


		if( session("searchResult") && session("searchResult")->query == $query && session("searchResult")->currentPage == $currentPage )
		{
			$searchResult = session("searchResult");

			$allBookList = $searchResult->bookList;
			$partBookList = [];

			for($i = $startPosition; $i<=$finalPosition; $i++)
			{
				if($allBookList[$i])
				{
				$partBookList[] = $allBookList[$i]; 
				}
			}

			$searchResult->bookList = $partBookList;

		}else{
			//Рассчитыаем URL для парсинга

			$requestURL = self::$search_url . urlencode($query);
			if ($currentPage > 1)
				{
					$requestURL .= "&p=$currentPage";
				}
			//$requestURL = __DIR__ . "/../../tests/SearchPageExample/Example.html";
			//Парсим страницу и генерируем массив с книгами

			$doc = new \DOMDocument();
			libxml_use_internal_errors(true);
			$doc->loadHTMLFile($requestURL);
			$str = $doc->saveHTML();
			libxml_use_internal_errors(false);
			
			$crawler = new Crawler($str);
			$productsArray = $crawler->filter(".products .row .product");
			$productsCount = count($productsArray);

			$partBookList = [];
			$allBookList = [];

			if($productsCount > 0)
				{
				//Рассчитываем начальную и последнюю позицию в массиве книг для формирования итогового списка



				//На  случай если выбранная часть поисковой выдачи меньше стандартного размера, то есть результатов выдачи меньше 

				if($productsCount < $finalPosition)
				{
					$finalPosition = $productsCount - 1;
				}		

				//Парсим весь список книг

				for($i = 0; $i < $productsCount; $i++)
				{
					if($productsArray->eq($i))
						{
							$book = [];

							$book["title"] = $productsArray->eq($i)->filter('.title')->text();
							$link = $productsArray->eq($i)->filter(".title")->attr('href');
							preg_match('/\d*.$/', $link, $code);
							$book['code'] = str_replace("/", '', $code[0]); 

							$allBookList[] = $book;
						}

				}

				//Создаём массив с необходимой частью выдачи 

				for($i = $startPosition; $i<=$finalPosition; $i++)
				{
					if($allBookList[$i])
					{
					$partBookList[] = $allBookList[$i]; 
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
											$partBookList,
											(int)$currentPage,
											(int)$totalPages,
											(int)$currentPart,
											(int)$totalParts,
											$query
										);
			$cachedSearchResult = new SearchResult(
									$allBookList,
									$currentPage,
									$totalPages,
									$currentPart,
									$totalParts,
									$query,
									"cache"
									);

			session(['sessionResult' => $cachedSearchResult]);
		}

		return $searchResult;

	}

	public static function getBookCard(String $bookCode): BookCard
	{
		
		$author = [0 => 'н/д'];
		$pages = 'н/д';
		$coverFormat = 'н/д';

		$requestURL = self::$bookcard_url . $bookCode . '/';
	//	$requestURL = __DIR__ . "/../../tests/SearchPageExample/BookCardWitcher.html";
		$doc = new \DOMDocument();

		libxml_use_internal_errors(true);
		$doc->loadHTMLFile($requestURL);
		libxml_use_internal_errors(false);

		$filters = [
			'author' => 'a.author',
			'title' => '.product_text h1',
			'internetPrice' => '.price strong',
			'localPrice' => '.rozn .price strong',
		];

		$bookCard = new BookCard();

		try{
			$str = $doc->saveHTML();
			$crawler = new Crawler($str);

			foreach ($filters as $property => $filter) {

				if(count($crawler->filter($filter))>0)
				{
					$bookCard->$property = $crawler->filter($filter)->text(); 
				}
			}

			$productInfoTable = $crawler->filter('.product_text table tr td');
			$countRows = count($productInfoTable);

			for($i = 0; $i < $countRows; $i++)
			{
				switch ($productInfoTable->eq($i)->text()) {
					case 'Код товара';
						$bookCard->code = $productInfoTable->eq($i+1)->text();
						break;
					case 'Кол-во страниц':
						# code...
						$bookCard->pages = $productInfoTable->eq($i+1)->text();
						break;
					case 'Оформление':
						$bookCard->coverFormat = $productInfoTable->eq($i+1)->text();
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
/*
		$bookCard = new BookCard($title, 
								$author, 
								$internetPrice,
								$localPrice,
								$code, 
								$coverFormat, 
								$pages);
*/
		return $bookCard;

	}

	public static function getStoresListInStock($code): array
	{
		//Если в наличии - data-nalich="1" иначе - 3


		$storesInStockUrl = self::$storesInStockUrl . $code;
		
		//$code = "4052579";
		//$storesInStockUrl = __DIR__ . "/../../tests/SearchPageExample/storesInStock.html";

		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTMLFile($storesInStockUrl);
		libxml_use_internal_errors(false);

		$str = $doc->saveHTML();

		$crawler = new Crawler($str);
		$storesInStockCrawler = $crawler->filter('.filials-city1 .shop');

		$storesList = [];

		$storesInStockCrawlerCount = count($crawler->filter('.filials-city1 .shop'));

		for($i=0; $i <= $storesInStockCrawlerCount - 1; $i++)
		{
			if ($storesInStockCrawler->eq($i)->attr('data-nalich') == "1")
			{ 
				$storeTitle = $storesInStockCrawler->eq($i)->filter('.title')->text();
				$storePhone = $storesInStockCrawler->eq($i)->filter('a.unstyled')->text(); 
				$storesList[] = ["title" => $storeTitle, "phone" => $storePhone];
			}
		}

		return $storesList;
	}

}