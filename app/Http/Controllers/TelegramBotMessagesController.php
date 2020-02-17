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
		$attempsLimit = 10;
		$updates = Telegram::getUpdates();

		while ($status === "PENDING" && $attemps <= $attempsLimit)
		{
			sleep(2);
			$updates = Telegram::getUpdates();
			//return $updates;

			if (count($updates) > 0)
			{
				foreach($updates as $update)
				{
					if(Update::where('update_id',$update["update_id"])->count() == 0) 
					{
					self::handle($update);
					Update::create(["update_id" => $update["update_id"]]);
					}	
				}
				

				$status = $entity->refresh()->status;	
				$attemps++;
				/*
				if ($attemps > $attempsLimit)
				{
					Telegram::sendMessage([
						"chat_id" => $chatId,
						"text" => 'Long Poll is dead'
						]);
				}
				*/
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
		session(['query' => $query]);
		$searchResult = ChcnnParsing::getSearchResult($query);
		TelegramBookDataMessage::showSearchResult($chatId, $searchResult);
	}

	public function callback($message)
	{
		//$message = session()->get('message');
		$callback_data = explode(',', $message['callback_query']["data"]);
		$chatId = $message["callback_query"]['message']['chat']['id'];
		$messageId = $message["callback_query"]['message']['message_id'];
			
		switch ($callback_data[0]) {
			case 'empty':
				break;
			case 'searchResult':
				$query = session('query');
				$page = $callback_data[1];
				$part = $callback_data[2];
				$searchResult = ChcnnParsing::getSearchResult($query, $page, $part);
				$replyMarkup = TelegramBookDataMessage::createReplyMarkup($searchResult);
				$response = Telegram::editMessageText([
									"chat_id" => $chatId, 
									"message_id" => $messageId,
									"text" => 'Вот что удалось найти',
									"reply_markup" => $replyMarkup	
									]);
				break;
			case 'bookCard':
				$code = $callback_data[1];
				$bookCard = ChcnnParsing::getBookCard($code);
				TelegramBookDataMessage::showBookCard($chatId, $bookCard);
				break;
			case 'storesListInStock':
				$code = $callback_data[1];
				//$storesList = ChcnnParsing::getStoresListInStock($code);
				TelegramBookDataMessage::addStoresListInStock($chatId, $messageId, $code);
				break;
			default:
				# code...
				break;
		}

	}




}
