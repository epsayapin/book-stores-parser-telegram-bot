<?php
namespace App\Library;
use Telegram;

class TelegramBotMessages
{
	public static function showSearchResult($chatId, $searchResult)
	{


		$keyboard = [];

		foreach($searchResult[0]["bookList"] as $book)
		{
			$keyboard[][] = ['text' => $book['title'], 'callback_data' => $book['code']];

		}

		$replyMarkup = Telegram::replyKeyboardMarkup([
			'inline_keyboard' => $keyboard
		]);

		$response = Telegram::sendMessage([
			'chat_id' => $chatId,
			'text' => 'Hello, world!',
			'reply_markup' => $replyMarkup
		]);

		$getMessageId = $response->getMessageId();
		
	}

}