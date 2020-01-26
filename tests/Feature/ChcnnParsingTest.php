<?php

namespace Tests\Feature;

require_once 'app/Library/ChcnnParsing.php';

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

        public function testChcnnParsingClassShouldExists()
    {
        
        $this->assertTrue(class_exists('\App\Library\ChcnnParsing'));

    }

    public function testGetBookListMustReturnBookList()
    {

        $searchQuerys = ['Ведьмак', 'Портнягин', 'Сорокин', 'Гарри Поттер',
                    'Стив Джобс', 'Пушкин', 'Достоевский', 'Алексей Иванов', 'Пелевин', 'Воннегут'];

        $searchQuery = $searchQuerys[rand(0, count($searchQuerys) - 1)];

        $bookList = \App\Library\ChcnnParsing::getBookList($searchQuery);

       //$jsonString = $response->getBody();
      //  $bodyAsArray = json_decode($jsonString, true);
        $this->assertEquals(24, count($bookList));


    }
}
