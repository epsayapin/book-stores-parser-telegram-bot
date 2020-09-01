<?php
namespace App\Library\BookStoreParsing;

class SearchResultPage
{
	public $bookList;
	public $currentPage;
	public $totalPages;
	public $currentPart;
	public $totalParts;
	public $query;
	public $source;

	public function __construct(Array $bookList, 
							int $currentPage, 
							int $totalPages, 
							int $currentPart, 
							int $totalParts, 
							string $query,
							string $source = "site")
	{

		$this->bookList = $bookList;
		$this->currentPage = $currentPage;
		$this->totalPages = $totalPages;
		$this->currentPart = $currentPart;
		$this->totalParts = $totalParts;
		$this->query = $query;
		$this->source = $source;
	}
}