<?php

namespace Tests\Feature;

require_once 'app/Library/ChcnnParsing.php';

use App\Library\ChcnnParsing as ChcnnParsing;

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

        public function testChcnnSearchUrlExists()
        {
            $this->assertTrue(!(ChcnnParsing::$search_url == ""));
        }

        public function testChcnnParsingClassShouldExists()
    {
        
        $this->assertTrue(class_exists('\App\Library\ChcnnParsing'));

    }

    public function testGetBookListMustReturnBookList()
    {

        $searchQuerys = ['Ведьмак', 'Портнягин', 'Сорокин', 'Гарри Поттер',
                    'Стив Джобс', 'Пушкин', 'Достоевский', 'Алексей Иванов', 'Пелевин', 'Воннегут'];

        $searchQuery = $searchQuerys[rand(0, count($searchQuerys) - 1)];

        $result = \App\Library\ChcnnParsing::getBookList($searchQuery);
        $bookList = $result[0]["bookList"];


       //$jsonString = $response->getBody();
      //  $bodyAsArray = json_decode($jsonString, true);
        $this->assertTrue(isset($result[0]{"bookList"}));
        $this->assertTrue(isset($result[1]['currentPage']));
        $this->assertTrue(isset($result["pagesCount"]));

        $this->assertEquals(24, count($result[0]["bookList"]));
        $this->assertGreaterThan(0, $result["pagesCount"]);

        foreach($bookList as $book)
        {
            $this->assertTrue(isset($book["title"]));
            $this->assertTrue(isset($book["code"]));
        }

    }

    public function xxtestgetBookCardShouldReturnBookCard()
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

        $bookcard = \App\Library\ChcnnParsing::getBookCard($code);

        echo "Used code $code";

        $propertyList = ['author', 'price', 'title', 'code', 'coverFormat', 'pages' ];

        foreach($propertyList as $property)
        {
            $this->assertTrue(isset($bookcard[$property]), "\n -- $property don't exists");
            if (!($property == 'author'))
            {
            $this->assertNotEquals("", $bookcard[$property], "\n -- $property empty");
            }
        }

        $this->assertGreaterThan(0, count($bookcard['author']));
    
    }

    public function testSinglePageResultsShouldHandleCorrect()
    {
        $query = 'Cobain';
        $result = ChcnnParsing::getBookList($query);
        $this->assertGreaterThan(0, $result["pagesCount"]);

    }

}
