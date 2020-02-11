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

	public function __construct(Array $bookList, 
							int $currentPage, 
							int $totalPages, 
							int $currentPart, 
							int $totalParts, 
							string $query)
	{

		$this->bookList = $bookList;
		$this->currentPage = $currentPage;
		$this->totalPages = $totalPages;
		$this->currentPart = $currentPart;
		$this->totalParts = $totalParts;
		$this->query = $query;
	}
}