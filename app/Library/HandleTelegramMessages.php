<?php
trait HandleTelemgramMessages
{
	public function handle($message)
	{
		
		
		if (isset($message["callback_query"]))
		{
			return redirect()->route('callback')->with(['message' => $message]);
		}

		if (isset($message["message"]["text"]) && ! isset($lastMessage["message"]["entities"][0]['type']))
		{
			return redirect()->route('message')->with(['message' => $message]);
		}


	}

	private function message()
	{
		$message = session()->get('message');
		$chatId = $message['message']['chat']['id'];
		$query = $message["message"]["text"];	
		$searchResult = ChcnnParsing::getBookList($query);
		TelegramBookDataMessage::showSearchResult($chatId, $searchResult);
	}

	private function callback()
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