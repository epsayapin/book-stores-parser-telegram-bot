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

    public function testGetSearchResultMustReturnSearchResult()
    {

        $searchQuerys = ['Ведьмак', 'Сорокин', 'Гарри Поттер',
                    'Пушкин', 'Достоевский', 'Иванов', 'Пелевин', 'Воннегут'];

        $searchQuery = $searchQuerys[rand(0, count($searchQuerys) - 1)];
        echo "\nUsed query - " . $searchQuery . "\n";

        $searchResult =ChcnnParsing::getSearchResult($searchQuery);
        $bookList = $searchResult->bookList;

        $this->assertTrue(isset($searchResult->bookList));
        $this->assertTrue(isset($searchResult->currentPage));
        $this->assertTrue(isset($searchResult->totalPages));

        $this->assertEquals(ChcnnParsing::$partSize, count($searchResult->bookList));
        $this->assertGreaterThan(0, $searchResult->totalPages);

        foreach($bookList as $book)
        {
            $this->assertTrue(isset($book["title"]));
            $this->assertTrue(isset($book["code"]));
        }

    }

    public function testGetBookCardShouldReturnBookCard()
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

        $bookcard = ChcnnParsing::getBookCard($code);

        $propertyListString = ['author', 'title', 'code', 'coverFormat', 'countPages' ];
        $propertyListInt = ['price' ];
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
            $this->assertTrue(isset($bookcard->$property));
            $this->assertNotEquals(0, $bookcard->$property, "\n -- $property equal zero");

        }



        $this->assertGreaterThan(0, count($bookcard->author));
    
    }

    public function testSinglePageResultsShouldHandleCorrect()
    {
        $query = 'Cobain';
        $searchResult = ChcnnParsing::getSearchResult($query);
        $this->assertGreaterThan(0, $searchResult->totalPages);
    }

    public function testGetStoresListInStockShouldReturnArray()
    {
        
    }

}
