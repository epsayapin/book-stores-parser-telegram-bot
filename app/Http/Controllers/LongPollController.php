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
		$attempsLimit = 100;

		while ($status === "PENDING" && $attemps <= $attempsLimit)
		{
			sleep(1);

			$updates = Telegram::getUpdates();

			if (count($updates) >0)
			{
				
				$lastMessage = $updates[count($updates) - 1];
				return redirect()->route('handle')->with(['message' => $lastMessage]);
				
				$status = $entity->refresh()->status;	
				$attemps++;
				if ($attemps > $attempsLimit)
				{
					Telegram::sendMessage([
						"chat_id" => $chatId,
						"text" => 'Long Poll is dead'
						]);
				}
			}
		}



			return $updates;
	}
}
