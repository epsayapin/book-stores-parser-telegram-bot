<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use App\Library;

use App\Library\ChcnnParsing;

class TelegramBotMessagesController extends Controller
{
    //

	public function test()
	{
		$response = Telegram::getMe();

		$botId = $response->getId();
		$firstName = $response->getFirstName();
		$username = $response->getUsername();

		$updates = Telegram::getUpdates();

		$lastMessage = $updates[count($updates) - 1];

		//$update = Telegram::commandsHandler(false, ['timeout' => 30]);

/*
		$searchResult = ChcnnParsing::getBookList($lastMessage["message"]["text"]);

		$str = "";

		foreach($searchResult[0]["bookList"] as $title)
		{
			$str .= $title . "\n";
		}

		$response = Telegram::sendMessage([
		'chat_id' => '117157138', 
		'text' => $str 
		]);

		$messageId = $response->getMessageId();
*/
		return $updates[count($updates) - 1];

		
	}
}
