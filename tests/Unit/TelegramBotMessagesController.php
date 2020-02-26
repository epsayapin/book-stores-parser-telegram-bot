<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class TelegramBotMessagesController extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

 	public function testStartCommandShouldReturnTextMessage()
 	{
 		$textResponse = "Hello World";
 		$chatId = "117157138";
 		
 		$startMessage = {
 			"message" => [
 				"chat" => [
 					"id" => $chatId
 				],

 				"text" => "/start",

 				"entity" => [
 					0 => [
 						"type" => "bot_command"
 					]
 				]

 			]

 		};

 	}
}
