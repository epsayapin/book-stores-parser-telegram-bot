<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use App\Library;
use App\Library\StartCommand;
use App\Library\ChcnnParsing;
use App\Library\TelegramBookDataMessage;

use \App\Entity;



class TelegramBotMessagesController extends Controller
{
    //

	public function handle()
	{
		$message = session()->get('message');
		
		if (isset($message["callback_query"]))
		{
			return redirect()->route('callback')->with(['message' => $message]);
		}

		if (isset($message["message"]["text"]) && ! isset($lastMessage["message"]["entities"][0]['type']))
		{
			return redirect()->route('message')->with(['message' => $message]);
		}


	}

	public function message()
	{
		$message = session()->get('message');
		$chatId = $message['message']['chat']['id'];
		$query = $message["message"]["text"];	
		$searchResult = ChcnnParsing::getBookList($query);
		TelegramBookDataMessage::showSearchResult($chatId, $searchResult);
	}

	public function callback()
	{
		$message = session()->get('message');
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
