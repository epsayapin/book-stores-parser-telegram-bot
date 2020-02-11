<?php
namespace App\Library;

class BookCard
{
	public $title;
	public $author;
	public $price;
	public $coverFormat;
	public $code;
	public $countPages;

	public function __construct(
			string 	$title = 'н/д',
			array 	$author = ['н/д'],
			string 	$internetPrice = 'н/д',
			string	$localPrice = 'н/д',
			string 	$code = 'н/д',
			string 	$coverFormat = 'н/д',
			string 	$countPages = 'н/д' 
	)
	{
		$this->title = $title;
		$this->author = $author;
		$this->internetPrice = $internetPrice;
		$this->localPrice = $localPrice;
		$this->code = $code;
		$this->coverFormat = $coverFormat;
		$this->countPages = $countPages;

	}

}