<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchResultsPagesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testSearchRequestResponde()
    {
        $searchQuerys = [ "Ведьмак", 
                        "Гарри поттер", 
                        "Властелин колец", 
                        "Достоевский"];
        $searchQuery = $searchQuerys[rand(0, count($searchQuerys) - 1)];

        $response = $this->json('GET', 'searchresults', ['query' => $searchQuery]);
        $this->assertEquals($response, $searchQuery . "1990");

    }
}
