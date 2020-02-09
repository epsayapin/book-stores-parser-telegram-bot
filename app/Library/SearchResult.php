<?php
namespace App\Library;

class SearchResult
{
	public $bookList;
	public $currentPage;
	public $countPages;
	public $query;

	public function __construct(Array $bookList, int $currentPage, int $countPages, string $query)
	{

		$this->bookList = $bookList;
		$this->currentPage = $currentPage;
		$this->countPages = $countPages;
		$this->query = $query;
	}
}