<?php

namespace App\Library\BookStoreParsing;

use App\Library\BookStoreParsing\SearchResultPage;
use App\Library\BookStoreParsing\BookCard;

interface BookStoreParsingInterface{
	public static function getSearchResultPage(String $query, int $pageNunber = 1): SearchResultPage;
	public static function getBookCard(int $bookCode): BookCard;
}