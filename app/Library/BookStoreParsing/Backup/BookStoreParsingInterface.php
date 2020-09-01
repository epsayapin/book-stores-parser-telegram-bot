<?php

namespace App\Library\BookStoreParsing;

interface BookStoreParsingInterface{
	public static function getBookCard($id): BookCard;
	public static function getSearchResultPage($query, $pageNunber): SearchResultPage;
}