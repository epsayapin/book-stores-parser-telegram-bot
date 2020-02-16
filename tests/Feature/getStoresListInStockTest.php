<?php

namespace Tests\Feature;

//require_once 'app/Library/ChcnnParsing.php';

use App\Library\ChcnnParsing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class getStoresListInStockTest extends TestCase
{
    public function testGetStoresListInStockShouldReturnArray()
    {
	    $bookCodes = [
	                //'2617830',
	                '4052565',
	                '4052340',
	                '4052821',
	                '4000529',
	                '3978197',
	                '4000527',
	                '2113226',
	                '3791720',
	                //'3869778'
	        ];

	    $code = $bookCodes[rand(0, count($bookCodes) - 1)];
	    echo "\nUsed code - " . $code . "\n";

      $storesInStock = ChcnnParsing::getStoresListInStock($code);

      $this->assertTrue(is_array($storesInStock));
      $this->assertGreaterThan(0, count($storesInStock));

    }

 }