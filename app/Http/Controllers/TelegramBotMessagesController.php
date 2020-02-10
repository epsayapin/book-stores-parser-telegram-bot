<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use App\Library;
use App\Library\StartCommand;
use App\Library\ChcnnParsing;
use App\Library\TelegramBookDataMessage;

use \App\Entity;
use \App\Update;


class TelegramBotMessagesController extends Controller
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
			sleep(2);
			$updates = Telegram::getUpdates();

			if (count($updates) > 0)
			{
				foreach($updates as $update)
				{
					if(Update::where('update_id',$update["update_id"])->count() == 0) 
					{
					Update::create(["update_id" => $update["update_id"]]);
					self::handle($update);
					}	
				}
				

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

	public function handle($message)
	{
		//$message = session()->get('message');
		
		if (isset($message["callback_query"]))
		{
			self::callback($message);
		}

		if (isset($message["message"]["text"]) && ! isset($lastMessage["message"]["entities"][0]['type']))
		{
			self::message($message);
		}


	}

	public function message($message)
	{
		//$message = session()->get('message');
		$chatId = $message['message']['chat']['id'];
		$query = $message["message"]["text"];	
		$searchResult = ChcnnParsing::getBookList($query);
		TelegramBookDataMessage::showSearchResult($chatId, $searchResult);
	}

	public function callback($message)
	{
		//$message = session()->get('message');
		$data = explode(',', $message['callback_query']["data"]);
		$query = $data[0];
		$page = $data[1];
		$chatId = $message["callback_query"]['message']['chat']['id'];
		$messageId = $message["callback_query"]['message']['message_id'];
		$searchResult = ChcnnParsing::getBookList($query, $page);
		$replyMarkup = TelegramBookDataMessage::createReplyMarkup($searchResult);
		$response = Telegram::editMessageText([
									"chat_id" => $chatId, 
									"message_id" => $messageId,
									"text" => 'New content',
									"reply_markup" => $replyMarkup	
									]);
	}




}
