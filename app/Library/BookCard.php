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
			string 	$title,
			array 	$author,
			int 	$price,
			string 	$code,
			string 	$coverFormat,
			string 	$countPages 
	)
	{
		$this->title = $title;
		$this->author = $author;
		$this->price = $price;
		$this->coverFormat = $coverFormat;
		$this->code = $code;
		$this->countPages = $countPages;

	}

}