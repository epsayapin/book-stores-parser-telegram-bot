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
		$attempsLimit = 1;

		while ($status === "PENDING" && $attemps <= $attempsLimit)
		{
			sleep(2);

			$updates = Telegram::getUpdates();

			//return $updates;
			$chatId = $updates[0]['message']['chat']['id'];
			$lastMessage = $updates[count($updates) - 1];
			$query = $lastMessage["message"]["text"];
//			$update = Telegram::commandsHandler(false, ['timeout' => 30]);			

			$searchResult =[
				"bookList" => [
					['title' => $query, 'code' => '111'],
					['title' => 'Tolstoy', 'code' => '222']
				]
			];

			$searchResult = ChcnnParsing::getBookList($query);
			TelegramBotMessages::showSearchResult($chatId, $searchResult);

			$status = $entity->refresh()->status;
			$attemps++;
		}

		return $lastMessage;
	}
}
