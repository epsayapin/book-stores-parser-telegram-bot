<?php 
namespace App\Services\BookStoreParsers;

use App\Traits\BookStoreParserTrait;
use App\Abstracts\BookStoreParserInterface;
use Symfony\Component\DomCrawler\Crawler;
use App\BookCard;

class ChaconneBookStoreParser implements BookStoreParserInterface
{
    use BookStoreParserTrait;

	const SEARCH_URL = 'https://chaconne.ru/search/?q=';
	const BOOK_CARD_URL = 'https://chaconne.ru/product/';

    const SEARCH_PAGE_NUMBER_GET_PARAM = "&p=";
	const SEARCH_RESULT_CSS_SELECTORS = [
	    "products" => ".products .row .product",
        "title" => ".title",
    ];

	const PAGINATOR_LINK_CSS_SELECTOR = ".paginator .links a";

	const BOOK_CARD_CSS_SELECTORS = [
        "main" => [
            'author' => 'a.author',
            'title' => '.product_text h1',
            'internetPrice' => '.price strong',
            'localPrice' => '.rozn .price strong',
        ],
        "details_table" => '.product_text table tr td',
    ];

	const BOOK_CARD_DETAILS_TABLE_TITLES = [
	    "code" =>  "Код товара",
        "countPages" => "Кол-во страниц",
        "coverFormat" =>  "Оформление"
    ];

	const BOOKS_ON_SEARCH_PAGE = 24;

	public function getSearchResults(String $searchQuery, int $pageNumber = 1): array
    {
        $pageCrawler = $this->createCrawlerByUrl($this->createSearchPageUrl($searchQuery, $pageNumber));

        $pagesCount = $this->calculateCountSearchResultsPages($pageCrawler);

		$resultCrawlers = $pageCrawler->filter(self::SEARCH_RESULT_CSS_SELECTORS["products"]);

		$results = [];
        foreach ($resultCrawlers as $index => $node) {
            $results[] = $this->extractSearchResultFromCrawler(new Crawler($node));
        }

		return [
		    "books" => $results,
            "pageNumber" => $pageNumber,
            "pagesCount" => $pagesCount,
            "searchQuery" => $searchQuery
        ];
	}

    public function getBookCard($code): BookCard
	{
		$bookCard = [];

        $crawler = self::createCrawlerByUrl(self::BOOK_CARD_URL . $code . '/');

        foreach (self::BOOK_CARD_CSS_SELECTORS["main"] as $bookProperty => $cssSelector) {

            if (count($crawler->filter($cssSelector)) > 0) {
                $bookCard[$bookProperty] = $crawler->filter($cssSelector)->text();
            }
        }

        $detailsTableCralwer = $crawler->filter(self::BOOK_CARD_CSS_SELECTORS["details_table"]);

        for($i = 0; $i < count($detailsTableCralwer); $i++)
        {
            switch ($detailsTableCralwer->eq($i)->text()) {
                case self::BOOK_CARD_DETAILS_TABLE_TITLES["code"];
                    $bookCard["code"] = $detailsTableCralwer->eq($i+1)->text();
                    break;
                case self::BOOK_CARD_DETAILS_TABLE_TITLES["countPages"]:
                    $bookCard["countPages"] = $detailsTableCralwer->eq($i+1)->text();
                    break;
                case self::BOOK_CARD_DETAILS_TABLE_TITLES["coverFormat"]:
                    $bookCard["coverFormat"] = $detailsTableCralwer->eq($i+1)->text();
                    break;
            }

        }

		return new BookCard($bookCard);
	}

	public function getBooksOnSearchPageCount() : int
    {
        return self::BOOKS_ON_SEARCH_PAGE;
    }

    private function calculateCountSearchResultsPages($pageCrawler) : int
    {
        $pagesCount = 1;

        if ($pageCrawler->filter(self::PAGINATOR_LINK_CSS_SELECTOR)->count() > 1) {
            if (intval($pageCrawler->filter(self::PAGINATOR_LINK_CSS_SELECTOR)->last()->text()) != 0) {
                $pagesCount = $pageCrawler->filter(self::PAGINATOR_LINK_CSS_SELECTOR)->last()->text();
            } else {
                $paginationCount = $pageCrawler->filter(self::PAGINATOR_LINK_CSS_SELECTOR)->count();
                $pagesCount = $pageCrawler->filter(self::PAGINATOR_LINK_CSS_SELECTOR)->eq($paginationCount - 2)->text();
            }
        }

        return $pagesCount;
    }

    private function createSearchPageUrl($query, $pageNumber) : string
    {
        $url = self::SEARCH_URL . urlencode($query);
        $url .= $pageNumber > 1 ? self::SEARCH_PAGE_NUMBER_GET_PARAM . $pageNumber : "";

        return $url;
    }

    private function extractSearchResultFromCrawler($crawler) : array
    {
        $book = [];

        $book["title"] = $crawler->filter(self::SEARCH_RESULT_CSS_SELECTORS["title"])->text();

        $link = $crawler->filter(self::SEARCH_RESULT_CSS_SELECTORS["title"])->attr('href');
        preg_match('/\d*.$/', $link, $code);
        $book['code'] = str_replace("/", '', $code[0]);

        return $book;
    }
}