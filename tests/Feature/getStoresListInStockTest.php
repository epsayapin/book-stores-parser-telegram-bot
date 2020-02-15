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
      $code = '111';

      $storesInStock = ChcnnParsing::getStoresListInStock($code);

      $this->assertTrue(is_array($storesInStock));
      $this->assertGreatThan(0, count($storesInStock));

    }

 }