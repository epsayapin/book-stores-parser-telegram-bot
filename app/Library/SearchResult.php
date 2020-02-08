<?php
namespace App\Library;

class SearchResult
{
	public $bookList;
	public $currentPage;
	public $countPages;

	public function __construct(Array $bookList, int $currentPage, int $countPages)
	{

		$this->bookList = $bookList;
		$this->currentPage = $currentPage;
		$this->countPages = $countPages;

	}
}