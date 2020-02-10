<?php
namespace App\Library;

class SearchResult
{
	public $bookList;
	public $currentPage;
	public $totalPages;
	public $currentPart;
	public $totalParts;
	public $query;

	public function __construct(Array $bookList, $currentPage, int $totalPages, int $currentPart, $totalParts, string $query)
	{

		$this->bookList = $bookList;
		$this->currentPage = $currentPage;
		$this->totalPages = $totalPages;
		$this->currentPart = $currentPart;
		$this->totalParts = $totalParts;
		$this->query = $query;
	}
}