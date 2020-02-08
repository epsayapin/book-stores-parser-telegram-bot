<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use App\Library;
use App\Library\StartCommand;
use App\Library\ChcnnParsing;
use App\Library\TelegramBotMessages;

use \App\Entity;


class LongPollController extends Controller
{
    //

	public function longpoll()
	{


	$entity = Entity::findOrFail(2);
		$status = $entity->status;
		$attemps = 1;
		$attempsLimit = 100;

		while ($status === "PENDING" && $attemps <= $attempsLimit)
		{
			sleep(1);

			$updates = Telegram::getUpdates();

			//return $updates;
			if (count($updates) >0)
			{
				$chatId = $updates[0]['message']['chat']['id'];
				$lastMessage = $updates[count($updates) - 1];

				$update = Telegram::commandsHandler(false, ['timeout' => 30]);			


				if (isset($lastMessage["message"]["text"]) && ! isset($lastMessage["message"]["entities"][0]['type']))
				{
				$query = $lastMessage["message"]["text"];	
				//$searchResult = ChcnnParsing::getBookList($query);
				

				TelegramBotMessages::showSearchResult($chatId, $searchResult);
				}
				$status = $entity->refresh()->status;
			}
			$attemps++;
		}

		Telegram::sendMessage([
				"chat_id" => $chatId,
				"text" => 'Long Poll is dead'
			]);

		return $lastMessage;
	}
}
