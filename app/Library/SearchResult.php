<?php
namespace App\Library;

class SearchResult
{
	public $bookList;
	public $currentPage;
	public $countPages;
	public $resultPart;
	public $query;

	public function __construct(Array $bookList, $currentPage, int $countPages, int $resultPart, string $query)
	{

		$this->bookList = $bookList;
		$this->currentPage = $currentPage;
		$this->countPages = $countPages;
		$this->resultPart = $resultPart;
		$this->query = $query;
	}
}