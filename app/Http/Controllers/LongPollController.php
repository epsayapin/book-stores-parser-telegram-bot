<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use App\Library;
use App\Library\StartCommand;
use App\Library\ChcnnParsing;
use App\Library\TelegramBookDataMessage;

use \App\Entity;


class LongPollController extends Controller
{
    //

	public function longpoll()
	{


	$entity = Entity::findOrFail(2);
		$status = $entity->status;
		$attemps = 1;
		$attempsLimit = 5;

		while ($status === "PENDING" && $attemps <= $attempsLimit)
		{
			sleep(1);

			$updates = Telegram::getUpdates();

			if (count($updates) >0)
			{
				$update = Telegram::commandsHandler(false, ['timeout' => 30]);			
				
				$lastMessage = $updates[count($updates) - 1];
				
				if (isset($lastMessage["callback_query"]))
				{
					$callbackData = $lastMessage["callback_query"]["data"];
					TelegramBookDataMessage::showBookCard($chatId, $callbackData);
				}

				if (isset($lastMessage["message"]["text"]) && ! isset($lastMessage["message"]["entities"][0]['type']))
				{
				$chatId = $updates[0]['message']['chat']['id'];
				$query = $lastMessage["message"]["text"];	
				$searchResult = ChcnnParsing::getBookList($query);
				TelegramBookDataMessage::showSearchResult($chatId, $searchResult);
				}

				$status = $entity->refresh()->status;
			}
			$attemps++;
		}

		Telegram::sendMessage([
				"chat_id" => $chatId,
				"text" => 'Long Poll is dead'
			]);

		return $updates;
	}
}
