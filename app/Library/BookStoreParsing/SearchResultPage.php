<?php
namespace App\Library\BookStoreParsing;

class SearchResultPage
{
	public $bookList;
	public $currentPage;
	public $query;

	public function __construct(Array $bookList, 
							string $query,
							int $currentPage 
						)
	{

		$this->bookList = $bookList;
		$this->currentPage = $currentPage;
		$this->query = $query;
	}
}