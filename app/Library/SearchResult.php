<?php
namespace App\Library

class SearchResult
{
	public function __construct(Array $bookList, int $currentPage, int $totalPages)
	{

		$this->bookList = $bookList;
		$this->currentPage = $currentPage;
		$this->totalPages $totalPages;

	}
}