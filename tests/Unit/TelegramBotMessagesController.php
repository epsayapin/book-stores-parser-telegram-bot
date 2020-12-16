<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\TelegramBotMessageController as Controller;

class TelegramBotMessageController extends TestCase
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
 		
 		$startMessage = [
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

 		];

 	$controller = new Controller();

 	$response = $controller->handle($startMessage);

 	echo $response["message"];
 	$this->assertTrue(isset($response["message"]));

 	}
}
