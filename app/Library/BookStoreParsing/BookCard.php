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

	public function __construct()
	{

		$this->author = [0 => 'н/д'];
		$this->pages = 'н/д';
		$this->coverFormat = 'н/д';

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