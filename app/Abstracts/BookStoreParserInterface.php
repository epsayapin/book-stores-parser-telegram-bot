<?php

namespace App\Abstracts;

use App\BookCard;

interface BookStoreParserInterface{
    public function getSearchResults(String $searchQuery, int $pageNunber = 1): array;
    public function getBookCard($code): BookCard;

    public function getBooksOnSearchPageCount() : int;
}