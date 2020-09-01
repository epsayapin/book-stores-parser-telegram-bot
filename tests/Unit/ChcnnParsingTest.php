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


    public function xtestGetBookCardShouldReturnBookCard()
    {

        //$code = $bookCodes[rand(0, count($bookCodes) - 1)];
        $code = self::getCode();
        echo "\nUsed code - " . $code . "\n";

        $bookcard = ChcnnParsing::getBookCard($code);

        $propertyListString = ['author', 'title', 'code', 'coverFormat', 'countPages' ];
        $propertyListInt = ['localPrice', "internetPrice"];
        foreach($propertyListString as $property)
        {
            $this->assertTrue(isset($bookcard->$property), "\n -- $property don't exists");
            if (!($property == 'author'))
            {
            $this->assertNotEquals("", $bookcard->$property[0], "\n -- $property empty");
            }
        }

        foreach($propertyListInt as $property)
        {
            $this->assertTrue(isset($bookcard->$property), "\n -- $property not set");
            $this->assertNotEquals(0, $bookcard->$property, "\n -- $property equal zero");

        }
    
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
