<?php

namespace Tests\Feature;

//require_once 'app/Library/ChcnnParsing.php';

use App\Library\ChcnnParsing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class ChcnnParsingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

        public function testChcnnSearchUrlShouldBeExists()
        {
            $this->assertTrue(!(ChcnnParsing::$search_url == ""));
        }

        public function xxtestChcnnParsingClassShouldExists()
    {
        $this->assertTrue(class_exists('ChcnnParsing'));
        echo "\nClass ChcnnParsing Exists\n";

    }




    public function xtestSinglePageResultsShouldHandleCorrect()
    {
        $query = 'Cobain';
        $searchResult = ChcnnParsing::getSearchResult($query);
        $this->assertGreaterThan(0, $searchResult->totalPages);
    }

    public function testGetStoresListInStockShouldReturnArray()
    {

      $storesInStock = ChcnnParsing::getStoresListInStock(self::getCode());

      $this->assertTrue(is_array($storesInStock));
      $this->assertGreaterThan(0, count($storesInStock));

    }



}
