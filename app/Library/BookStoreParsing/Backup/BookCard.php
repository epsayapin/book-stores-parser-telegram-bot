<?php
namespace App\Library\BookStoreParsing;

class BookCard
{
	public $title;
	public $author;
	public $localPrice;
	public $internetPrice;
	public $coverFormat;
	public $code;
	public $countPages;
	public $source;

	public function __construct(
			string 	$title = 'н/д',
			string 	$author = 'н/д',
			string 	$internetPrice = 'н/д',
			string	$localPrice = 'н/д',
			string 	$code = 'н/д',
			string 	$coverFormat = 'н/д',
			string 	$countPages = 'н/д', 
			string 	$source = 'site'
	)
	{
		$this->title = $title;
		$this->author = $author;
		$this->internetPrice = $internetPrice;
		$this->localPrice = $localPrice;
		$this->code = $code;
		$this->coverFormat = $coverFormat;
		$this->countPages = $countPages;
		$this->source = $source;
	}

	public function getLocalPrice()
	{
		if($this->localPrice == 'н/д')
		{
			return $this->localPrice;
		}else{
			return $this->localPrice . 'р.';
		}

	}

	public function getInternetPrice()
	{
		if($this->internetPrice == 'н/д')
		{
			return $this->internetPrice;
		}else{
			return $this->internetPrice . 'р.';
		}

	}


	public function getCountPages()
	{
		if($this->countPages == 'н/д')
		{
			return $this->countPages;
		}else{
			return $this->countPages . 'с.';
		}


	}

}