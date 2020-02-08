<?php
namespace App\Library;
use Telegram;

class TelegramBotMessages
{
	public static function showSearchResult($chatId, $searchResult)
	{


		$keyboard = [];
		$i = 1;
		foreach($searchResult[0]["bookList"] as $book)
		{
			$keyboard[][] = ['text' => "$i. " . $book['title'], 'callback_data' => $book['code']];
			$i++;
		}

		$fillStr = "................................................................................";

		$currentPage = $searchResult[1]['currentPage'];
		$countPages = $searchResult['pagesCount'];
		$buttonPages = [
				["text" => "Total $countPages", "callback_data" => 'empty'],
				["text" => "Current $currentPage", "callback_data" => 'empty']
		];

		$keyboard[][] = $buttonPages;

		$keyboard[][] = ["text" => $fillStr, 'callback_data' => "epmty"];

		$replyMarkup = Telegram::replyKeyboardMarkup([
			'inline_keyboard' => $keyboard
		]);

		$response = Telegram::sendMessage([
			'chat_id' => $chatId,
			'text' => 'Вот что удалось найти',
			'reply_markup' => $replyMarkup
		]);

		$getMessageId = $response->getMessageId();
		
	}

}